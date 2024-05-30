<?php
require('backend/db_con.php');

$date = isset($_POST['date']) ? $_POST['date'] : '';

if ($date) {
    $stmt = $connection->prepare("SELECT Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM koncerti WHERE Datums = ?");
    $stmt->bind_param("s", $date);
} else {
    $stmt = $connection->prepare("SELECT Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM koncerti");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-4 event-card" data-date="' . $row["Datums"] . '">';
        echo '  <div class="card h-100">';
        echo '    <img src="' . $row["Plakats"] . '" class="card-img-top" alt="Event Image">';
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">' . $row["Nosaukums"] . '</h5>';
        echo '      <p class="card-text">' . date("d.m.y", strtotime($row["Datums"])) . ' ' . $row["Laiks"] . '<br>' . $row["Informacija"] . '</p>';
        echo '      <p class="card-text text-primary">no ' . number_format($row["Cena"], 2) . ' EUR</p>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<p>No events found.</p>';
}

$stmt->close();
$connection->close();
?>
