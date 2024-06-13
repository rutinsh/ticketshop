<?php
session_start();
require('backend/db_con.php');

if (!isset($_SESSION['userID'])) {
    echo "You must be logged in to view this page.";
    exit;
}

$userID = $_SESSION['userID'];

// Fetch the latest purchase for the user
$sql = "SELECT p.*, t.EventType, t.EventID, e.Nosaukums 
        FROM purchases p 
        JOIN tickets t ON p.PurchaseID = t.PurchaseID 
        JOIN koncerti e ON t.EventID = e.KoncertiID 
        WHERE p.UserID = ? 
        ORDER BY p.PurchaseDate DESC 
        LIMIT 1";
$stmt = $connection->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No purchase found.";
    exit;
}

$purchases = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <div class="alert alert-success mt-5">
        <h4 class="alert-heading">Paldies par pirkumu!</h4>
        <p>Jūsu biļetes uz pasākumu "<?php echo htmlspecialchars($purchases[0]['Nosaukums']); ?>" ir veiksmīgi iegādātas.</p>
        <hr>
        <?php foreach ($purchases as $purchase): ?>
            <p>Biļete ID: <?php echo $purchase['TicketID']; ?>, Cena: <?php echo number_format($purchase['Price'], 2); ?> EUR</p>
        <?php endforeach; ?>
        <p class="mb-0">Kopējā summa: <?php echo number_format($purchases[0]['TotalPrice'], 2); ?> EUR</p>
    </div>
</div>

</body>
</html>

<?php
$connection->close();
?>
