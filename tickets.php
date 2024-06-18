<?php
session_start();
require('backend/db_con.php');

if (!isset($_SESSION['email'])) {
    echo "You must be logged in to view this page.";
    exit;
}

$email = $_SESSION['email'];

// Get user ID
$sql = "SELECT ID FROM lietotaji WHERE Epasts = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$userID = $user['ID'];

// Fetch all tickets for the user
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
        WHERE p.UserID = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$tickets = $result->fetch_all(MYSQLI_ASSOC);

// Separate tickets into upcoming and past events
$current_date = date('Y-m-d');
$upcoming_tickets = [];
$past_tickets = [];

foreach ($tickets as $ticket) {
    if ($ticket['EventDate'] >= $current_date) {
        $upcoming_tickets[] = $ticket;
    } else {
        $past_tickets[] = $ticket;
    }
}

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manas Biļetes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2>Manas Biļetes</h2>
    <?php if (empty($upcoming_tickets)): ?>
        <p>Nav gaidāmu pasākumu.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pasākums</th>
                    <th>Datums</th>
                    <th>Informācija</th>
                    <th>Biļetes iegādes datums</th>
                    <th>Cena</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($upcoming_tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['EventName']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['EventDate']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['EventInfo']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['PurchaseDate']); ?></td>
                        <td><?php echo number_format($ticket['Price'], 2); ?> EUR</td>
                        <td><a href="generate_pdf.php?ticketID=<?php echo $ticket['TicketID']; ?>" class="btn btn-primary">PDF</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Arhīvs</h2>
    <?php if (empty($past_tickets)): ?>
        <p>Nav bijušu pasākumu.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pasākums</th>
                    <th>Datums</th>
                    <th>Informācija</th>
                    <th>Biļetes iegādes datums</th>
                    <th>Cena</th>
                    <th>PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($past_tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['EventName']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['EventDate']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['EventInfo']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['PurchaseDate']); ?></td>
                        <td><?php echo number_format($ticket['Price'], 2); ?> EUR</td>
                        <td><a href="generate_pdf.php?ticketID=<?php echo $ticket['TicketID']; ?>" class="btn btn-primary">PDF</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

