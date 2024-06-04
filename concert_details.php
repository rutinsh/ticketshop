<?php
require("backend/db_con.php");

if (!isset($_GET['id'])) {
    echo "Invalid Event ID.";
    exit;
}

$eventID = intval($_GET['id']);
$sql = "SELECT Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM koncerti WHERE KoncertiID = $eventID";
$result = $connection->query($sql);

if ($result->num_rows == 0) {
    echo "Event not found.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasākuma Detalizācija</title>

    <!--Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="resources/CSS/index.css" rel="stylesheet">
</head>

<body>
<?php include 'navbar.php'; ?>

<section class="event-details py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
            <img src="<?php echo htmlspecialchars($row["Plakats"]); ?>" class="img-fluid" alt="Event Image">
            </div>
            <div class="col-md-6">
                <h1><?php echo $row["Nosaukums"]; ?></h1>
                <p><?php echo date("d F Y, H:i", strtotime($row["Datums"] . ' ' . $row["Laiks"])); ?></p>
                <p><?php echo $row["Informacija"]; ?></p>
                <p class="text-primary">no <?php echo number_format($row["Cena"], 2); ?> EUR</p>
                <button class="btn btn-success">Nopirkt biļeti</button>
            </div>
        </div>
    </div>
</section>

<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>

<?php
$connection->close();
?>
