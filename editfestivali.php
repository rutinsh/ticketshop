<?php
require('backend/db_con.php');

if (isset($_POST['Nosaukums']) && isset($_POST['Datums']) && isset($_POST['Laiks']) && isset($_POST['Informacija'])) {
    $Nosaukums = $_POST['Nosaukums'];
    $Datums = $_POST['Datums'];
    $Laiks = $_POST['Laiks'];
    $Informacija = $_POST['Informacija'];


    $stmt = $connection->prepare("INSERT INTO festivali (Nosaukums, Datums, Laiks, Informacija) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $Nosaukums, $Datums, $Laiks, $Informacija);
    
    if ($stmt->execute()) {
        header('location: editfestivali.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biļešu Bāze</title>

    <!--Bootsrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="resources/CSS/admin.css" rel="stylesheet">
    <script src="script.js"></script>

</head>

<body>
<?php include 'navbar2.php'; ?>

      <section class="hero-section">
            <div class="Fields">
            <button id="add-btn">Pievienot festivālu</button>
            <form action="" method="post">
                <div id="add-pop">
                    <input name="Nosaukums" type="varchar" class="input" placeholder="Nosaukums" required>
                    <input name="Datums" type="date" class="input" placeholder="Datums" required>
                    <input name="Laiks" type="time" class="input" placeholder="Laiks" required>
                    <input name="Informacija" type="text" class="input" placeholder="Informacija" required>
                    <input class="btn" name="submit" type="submit" value="Pievienot">
                    <button id="close-btn">Atcelt</button>
                </div>
            </form>
        </div>
        <div class="list">
            <div class="tabulaBox">
                <table class="table-sortable" id="trow">
                    <thead>
                        <th>ID</th>
                        <th>Nosaukums</th>
                        <th>Datums</th>
                        <th>Laiks</th>
                        <th>Informācija</th>
                    </thead>
                    <?php
                        $query = "SELECT * FROM festivali";
                        $result = mysqli_query($connection, $query);
                        while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr class="table">
                        <td><?php echo $row["FestivaliID"]; ?></td>
                        <td><?php echo $row['Nosaukums']; ?></td>
                        <td><?php echo $row['Datums']; ?></td>
                        <td><?php echo $row['Laiks']; ?></td>
                        <td><?php echo $row['Informacija']; ?></td>
                        <td><a href="backend/functions.php?PasakumaID=<?php echo $row["FestivaliID"];?>"><button class="dzest1" id='dzest'>Dzēst</button></a><br><a href="edit-dievkalpojums.php?dievkalpojuma_id=<?php echo $row["FestivaliID"]; ?>"><button class="labot1" id='labot'>Labot</button></a></td>
                    </tr>
                    <?php
                     }
                     ?>
                    </table>
            </div>
        <script>
    const addBtn = document.getElementById('add-btn');
    const addPop = document.getElementById('add-pop');
    const closeBtn = document.getElementById('close-btn');

    addBtn.addEventListener('click', () => {
        addPop.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        addPop.style.display = 'none';
    });
</script>
            </div>
      </section>

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>




