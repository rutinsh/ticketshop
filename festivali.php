<?php
require("backend/db_con.php");
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biļešu Bāze - Festivāli</title>

    <!--Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="resources/CSS/index.css" rel="stylesheet">
    <script src="script.js"></script>
</head>

<body>
<?php include 'navbar.php'; ?>

<section class="events-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <div class="btn-group">
                <input type="text" class="form-control" id="datepicker" placeholder="Visi datumi">
            </div>
            <div class="d-flex">
                <select id="orderSelect" class="form-select me-2">
                    <option value="asc">Datums: Augoši</option>
                    <option value="desc">Datums: Dilstoši</option>
                </select>
                <button class="btn btn-secondary me-2" id="resetButton">Notīrīt</button>
                <button class="btn btn-success" id="applyButton">Pielietot</button>
            </div>
        </div>
        <div class="row" id="eventsContainer">
            <?php
            $sql = "SELECT Nosaukums, Datums, Laiks, Informacija, Cena, Plakats FROM festivali";
            $result = $connection->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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

            $connection->close();
            ?>
        </div>
    </div>
</section>

<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function(){
        $('#datepicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            autoclose: true
        });

        $('#applyButton').on('click', function() {
            var selectedDate = $('#datepicker').val();
            var order = $('#orderSelect').val();
            if (selectedDate || order) {
                $.ajax({
                    url: 'filter_festivali.php',
                    type: 'POST',
                    data: { date: selectedDate, order: order },
                    success: function(response) {
                        $('#eventsContainer').html(response);
                    }
                });
            }
        });

        $('#resetButton').on('click', function() {
            $('#datepicker').val('');
            $('#orderSelect').val('asc');
            $.ajax({
                url: 'filter_festivali.php',
                type: 'POST',
                data: { date: '', order: 'asc' },
                success: function(response) {
                    $('#eventsContainer').html(response);
                }
            });
        });
    });
</script>

</body>
</html>
