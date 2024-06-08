<?php
require("backend/db_con.php");

if (isset($_POST['date']) && isset($_POST['order'])) {
    $date = $_POST['date'];
    $order = $_POST['order'] == 'desc' ? 'DESC' : 'ASC';
    $events = [];

    if ($date) {
        $sql = "SELECT FestivaliID, Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM festivali WHERE Datums = '$date' ORDER BY Datums $order";
    } else {
        $sql = "SELECT FestivaliID, Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM festivali ORDER BY Datums $order";
    }
    
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }

    if (count($events) > 0) {
        foreach ($events as $event) {
            echo '<div class="col-md-4 mb-4 event-card" data-date="' . $event["Datums"] . '">';
            echo '  <div class="card h-100">';
            echo '    <a href="festival_details.php?id=' . htmlspecialchars($event["FestivaliID"]) . '">';
            echo '      <img src="' . htmlspecialchars($event["Plakats"]) . '" class="card-img-top" alt="Event Image">';
            echo '      <div class="card-body">';
            echo '        <h5 class="card-title">' . htmlspecialchars($event["Nosaukums"]) . '</h5>';
            echo '        <p class="card-text">' . date("d.m.y", strtotime($event["Datums"])) . ' ' . htmlspecialchars($event["Laiks"]) . '<br>' . htmlspecialchars($event["Informacija"]) . '</p>';
            echo '        <p class="card-text text-primary">no ' . number_format($event["Cena"], 2) . ' EUR</p>';
            echo '      </div>';
            echo '    </a>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No events found.</p>';
    }

    $connection->close();
}
?>
