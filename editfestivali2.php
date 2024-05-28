<?php
require('backend/db_con.php');

if (isset($_POST['Nosaukums']) && isset($_POST['Datums']) && isset($_POST['Laiks']) && isset($_POST['Informacija'])) {
    $Nosaukums = $_POST['Nosaukums'];
    $Datums = $_POST['Datums'];
    $Laiks = $_POST['Laiks'];
    $Informacija = $_POST['Informacija'];
    $imagePath = '';

    if (isset($_FILES['Plakats']) && $_FILES['Plakats']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imagePath = $targetDir . basename($_FILES['Plakats']['name']);
        if (move_uploaded_file($_FILES['Plakats']['tmp_name'], $imagePath)) {
            // File is successfully uploaded
        } else {
            echo "Error uploading the file.";
            exit;
        }
    }

    $stmt = $connection->prepare("INSERT INTO festivali (Nosaukums, Datums, Laiks, Informacija, Plakats) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $Nosaukums, $Datums, $Laiks, $Informacija, $imagePath);
    
    if ($stmt->execute()) {
        header('location: editfestivali2.php');
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <link href="resources/CSS/admin.css" rel="stylesheet">
    <style>
        .card {
            position: relative;
        }
        .card-img-top {
            width: 100%;
            height: auto;
        }
        .card-body-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.6); /* Melna caurspīdīga fona krāsa */
            color: white;
            padding: 10px;
        }
        .card-body-overlay h5,
        .card-body-overlay p {
            margin: 0;
        }
    </style>
</head>

<body>
<?php include 'navbar2.php'; ?>

<section class="hero-section">
    <div class="Fields">
        <button id="add-btn" class="btn btn-primary">Pievienot festivālu</button>
        <form action="" method="post" enctype="multipart/form-data">
            <div id="add-pop" style="display:none;">
                <input name="Nosaukums" type="varchar" class="form-control mb-2" placeholder="Nosaukums" required>
                <input name="Datums" type="date" class="form-control mb-2" placeholder="Datums" required>
                <input name="Laiks" type="time" class="form-control mb-2" placeholder="Laiks" required>
                <input name="Informacija" type="text" class="form-control mb-2" placeholder="Informacija" required>
                <input name="Plakats" type="file" class="form-control mb-2">
                <input class="btn btn-success mb-2" name="submit" type="submit" value="Pievienot">
                <button id="close-btn" class="btn btn-secondary mb-2">Atcelt</button>
            </div>
        </form>
    </div>
    <div class="list">
        <div class="row">
            <?php
                $query = "SELECT * FROM festivali";
                $result = mysqli_query($connection, $query);
                while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($row['Plakats'])): ?>
                        <img src="<?php echo $row['Plakats']; ?>" class="card-img-top" alt="Festivāla plakāts">
                    <?php endif; ?>
                    <div class="card-body-overlay">
                        <h5 class="card-title"><?php echo $row['Nosaukums']; ?></h5>
                        <p class="card-text"><strong>Datums:</strong> <?php echo $row['Datums']; ?></p>
                        <p class="card-text"><strong>Laiks:</strong> <?php echo $row['Laiks']; ?></p>
                        <p class="card-text"><strong>Informācija:</strong> <?php echo $row['Informacija']; ?></p>
                        <a href="backend/functions.php?FestivaliID=<?php echo $row['FestivaliID']; ?>" class="btn btn-danger">Dzēst</a>
                        <a href="edit-dievkalpojums.php?dievkalpojuma_id=<?php echo $row['FestivaliID']; ?>" class="btn btn-warning">Labot</a>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
</body>
</html>

