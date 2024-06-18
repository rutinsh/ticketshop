<?php
session_start();
require('fpdf/fpdf.php');
require('backend/db_con.php');
require('phpqrcode/qrlib.php'); // Include the QR code library

if (!isset($_GET['ticketID'])) {
    die("Invalid ticket ID.");
}

$ticketID = intval($_GET['ticketID']);

$sql = "SELECT t.TicketID, e.EventType, e.ReferenceID, p.PurchaseDate, t.Price,
       CASE
           WHEN e.EventType = 'koncerti' THEN k.Nosaukums
           WHEN e.EventType = 'festivali' THEN f.Nosaukums
           WHEN e.EventType = 'standup' THEN s.Nosaukums
           WHEN e.EventType = 'citi' THEN c.Nosaukums
           ELSE 'Unknown Event'
       END AS EventName,
       CASE
           WHEN e.EventType = 'koncerti' THEN k.Datums
           WHEN e.EventType = 'festivali' THEN f.Datums
           WHEN e.EventType = 'standup' THEN s.Datums
           WHEN e.EventType = 'citi' THEN c.Datums
           ELSE NULL
       END AS EventDate,
       CASE
           WHEN e.EventType = 'koncerti' THEN k.Informacija
           WHEN e.EventType = 'festivali' THEN f.Informacija
           WHEN e.EventType = 'standup' THEN s.Informacija
           WHEN e.EventType = 'citi' THEN c.Informacija
           ELSE NULL
       END AS EventInfo
       FROM tickets t
       JOIN purchases p ON t.PurchaseID = p.PurchaseID
       JOIN events e ON t.EventID = e.EventID
       LEFT JOIN koncerti k ON e.ReferenceID = k.KoncertiID AND e.EventType = 'koncerti'
       LEFT JOIN festivali f ON e.ReferenceID = f.FestivaliID AND e.EventType = 'festivali'
       LEFT JOIN standup s ON e.ReferenceID = s.StandupID AND e.EventType = 'standup'
       LEFT JOIN citi c ON e.ReferenceID = c.CitiID AND e.EventType = 'citi'
       WHERE t.TicketID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $ticketID);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    die("Ticket not found.");
}

// Generate QR Code
$qrText = "TicketID: " . $ticket['TicketID'] . "\nEvent: " . $ticket['EventName'] . "\nDate: " . $ticket['EventDate'] . "\nPrice: " . number_format($ticket['Price'], 2) . " EUR";
$qrFile = 'qrcodes/' . $ticket['TicketID'] . '.png';
QRcode::png($qrText, $qrFile, QR_ECLEVEL_L, 3);

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 20);
        $this->Cell(0, 20, 'Event Ticket', 0, 1, 'C');
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Ticket Header
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(0, 10, 'Ticket Details', 0, 1, 'L', true);
$pdf->Ln(5);

// Event Details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Pasakums:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, $ticket['EventName'], 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Datums:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, date("d F Y", strtotime($ticket['EventDate'])), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Vieta:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->MultiCell(0, 10, $ticket['EventInfo'], 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Biletes cena:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, number_format($ticket['Price'], 2) . ' EUR', 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 10, 'Pirkuma datums:', 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, date("d F Y H:i:s", strtotime($ticket['PurchaseDate'])), 0, 1);

// QR Code
$pdf->Ln(10);
$pdf->Cell(0, 0, '', 'T');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Scan QR Code for Ticket Details', 0, 1, 'C');
$pdf->Image($qrFile, $pdf->GetX() + 80, $pdf->GetY(), 50, 50); // Add the QR code to the PDF

// Output the PDF
$pdf->Output();
?>
