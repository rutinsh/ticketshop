<?php
require("backend/db_con.php");

if (isset($_POST['date']) && isset($_POST['order'])) {
    $date = $_POST['date'];
    $order = $_POST['order'] == 'desc' ? 'DESC' : 'ASC';
    $tables = ['koncerti', 'festivali', 'standup', 'citi'];
    $events = [];

    foreach ($tables as $table) {
        if ($date) {
            $sql = "SELECT '$table' as EventType, Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM $table WHERE Datums = '$date' ORDER BY Datums $order";
        } else {
            $sql = "SELECT '$table' as EventType, Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM $table ORDER BY Datums $order";
        }
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $events[] = $row;
            }
        }
    }

    usort($events, function($a, $b) use ($order) {
        if ($order == 'ASC') {
            return strtotime($a["Datums"]) - strtotime($b["Datums"]);
        } else {
            return strtotime($b["Datums"]) - strtotime($a["Datums"]);
        }
    });

    if (count($events) > 0) {
        foreach ($events as $event) {
            echo '<div class="col-md-4 mb-4 event-card" data-date="' . $event["Datums"] . '">';
            echo '  <div class="card h-100">';
            echo '    <img src="' . $event["Plakats"] . '" class="card-img-top" alt="Event Image">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . $event["Nosaukums"] . '</h5>';
            echo '      <p class="card-text">' . date("d.m.y", strtotime($event["Datums"])) . ' ' . $event["Laiks"] . '<br>' . $event["Informacija"] . '</p>';
            echo '      <p class="card-text text-primary">no ' . number_format($event["Cena"], 2) . ' EUR</p>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo '<p>No events found.</p>';
    }

    $connection->close();
}
?>
