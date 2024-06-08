<?php
require("backend/db_con.php");

$date = $_POST['date'] ?? '';
$order = $_POST['order'] ?? 'asc';
$order = $order === 'desc' ? 'DESC' : 'ASC';

$sql = "SELECT KoncertiID, Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM koncerti";

if ($date) {
    $sql .= " WHERE Datums = '" . $connection->real_escape_string($date) . "'";
}

$sql .= " ORDER BY Datums $order";

$result = $connection->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4 mb-4 event-card" data-date="' . htmlspecialchars($row["Datums"]) . '">';
        echo '  <div class="card h-100">';
        echo '    <a href="concert_details.php?id=' . htmlspecialchars($row["KoncertiID"]) . '">';
        echo '      <img src="' . htmlspecialchars($row["Plakats"]) . '" class="card-img-top" alt="Event Image">';
        echo '      <div class="card-body">';
        echo '        <h5 class="card-title">' . htmlspecialchars($row["Nosaukums"]) . '</h5>';
        echo '        <p class="card-text">' . date("d.m.y", strtotime($row["Datums"])) . ' ' . htmlspecialchars($row["Laiks"]) . '<br>' . htmlspecialchars($row["Informacija"]) . '</p>';
        echo '        <p class="card-text text-primary">no ' . number_format($row["Cena"], 2) . ' EUR</p>';
        echo '      </div>';
        echo '    </a>';
        echo '  </div>';
        echo '</div>';
    }
} else {
    echo '<p>No events found.</p>';
}

$connection->close();
?>
