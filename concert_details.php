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
    <title><?php echo htmlspecialchars($row["Nosaukums"]); ?></title>


    <!--Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="resources/CSS/index.css" rel="stylesheet">
    <style>
        .event-details .poster-section {
            padding: 0;
        }
        .event-details .poster-section img {
            width: 375px;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            margin-left:250px
        }
        .event-details .info-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
        }
        .event-details h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .event-details .event-meta, .event-details {
            margin-top: 20px;
        }
        .event-details .event-meta p {
            margin: 0;
            font-size: 1.1rem;
        }
        .event-details {
            font-size: 1.2rem;
            line-height: 1.6;
        }
        .event-details .btn {
            margin-top: 20px;
            font-size: 1.1rem;
            padding: 10px 20px;
        }
        .event-details .price {
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>

<body>
<?php include 'navbar.php'; ?>

<section class="event-details py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 poster-section">
                <img src="<?php echo htmlspecialchars($row["Plakats"]); ?>" class="img-fluid" alt="Event Image">
            </div>
            <div class="col-md-6 info-section">
                <h1><?php echo htmlspecialchars($row["Nosaukums"]); ?></h1>
                <div class="event-meta">
                    <p><i class="fas fa-calendar-alt"></i> <?php echo date("d F Y, l", strtotime($row["Datums"])); ?>, <span><?php echo date("H:i", strtotime($row["Laiks"])); ?></span></p>
                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row["Informacija"]); ?></p>
                </div>
                <p class="price">no <?php echo number_format($row["Cena"], 2); ?> EUR</p>
                <a href="purchase.php?id=<?php echo $eventID; ?>" class="btn-buy"><i class="fas fa-ticket-alt"></i> Nopirkt biļeti</a>
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
