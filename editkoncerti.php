<?php
require('backend/db_con.php');

// Jauna Koncerta izveide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_new'])) {
    $Nosaukums = htmlspecialchars($_POST['Nosaukums']);
    $Datums = htmlspecialchars($_POST['Datums']);
    $Laiks = htmlspecialchars($_POST['Laiks']);
    $Informacija = htmlspecialchars($_POST['Informacija']);
    $imagePath = '';

    if (isset($_FILES['Plakats']) && $_FILES['Plakats']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['Plakats']['type'], $allowedTypes)) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imagePath = $targetDir . basename($_FILES['Plakats']['name']);
            if (!move_uploaded_file($_FILES['Plakats']['tmp_name'], $imagePath)) {
                echo "Error uploading the file.";
                exit;
            }
        } else {
            echo "Invalid file type.";
            exit;
        }
    }

    $stmt = $connection->prepare("INSERT INTO koncerti (Nosaukums, Datums, Laiks, Informacija, Plakats) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $Nosaukums, $Datums, $Laiks, $Informacija, $imagePath);

    if ($stmt->execute()) {
        header('Location: editkoncerti.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Esošā koncerta labošana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
    $KoncertiID = htmlspecialchars($_POST['KoncertiID']);
    $Nosaukums = htmlspecialchars($_POST['Nosaukums']);
    $Datums = htmlspecialchars($_POST['Datums']);
    $Laiks = htmlspecialchars($_POST['Laiks']);
    $Informacija = htmlspecialchars($_POST['Informacija']);
    $imagePath = htmlspecialchars($_POST['existingPlakats']);

    if (isset($_FILES['Plakats']) && $_FILES['Plakats']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($_FILES['Plakats']['type'], $allowedTypes)) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $imagePath = $targetDir . basename($_FILES['Plakats']['name']);
            if (!move_uploaded_file($_FILES['Plakats']['tmp_name'], $imagePath)) {
                echo "Error uploading the file.";
                exit;
            }
        } else {
            echo "Invalid file type.";
            exit;
        }
    }

    $stmt = $connection->prepare("UPDATE koncerti SET Nosaukums = ?, Datums = ?, Laiks = ?, Informacija = ?, Plakats = ? WHERE KoncertiID = ?");
    $stmt->bind_param("sssssi", $Nosaukums, $Datums, $Laiks, $Informacija, $imagePath, $KoncertiID);

    if ($stmt->execute()) {
        header('Location: editkoncerti.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Koncertu datu ieguve tālākai apstrādāšanai
$editKoncert = null;
if (isset($_GET['EditKoncertiID'])) {
    $KoncertiID = intval($_GET['EditKoncertiID']);
    $result = mysqli_query($connection, "SELECT * FROM koncerti WHERE KoncertiID = $KoncertiID");
    if ($result) {
        $editKoncert = mysqli_fetch_assoc($result);
    }
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biļešu Bāze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background: rgba(0, 0, 0, 0.6);
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
        <button id="add-btn" class="btn btn-primary">Pievienot koncertu</button>
        <form action="" method="post" enctype="multipart/form-data">
            <div id="add-pop" style="display:<?php echo isset($editKoncert) ? 'block' : 'none'; ?>;">
                <input name="Nosaukums" type="varchar" class="form-control mb-2" placeholder="Nosaukums" required value="<?php echo isset($editKoncert) ? $editKoncert['Nosaukums'] : ''; ?>">
                <input name="Datums" type="date" class="form-control mb-2" placeholder="Datums" required value="<?php echo isset($editKoncert) ? $editKoncert['Datums'] : ''; ?>">
                <input name="Laiks" type="time" class="form-control mb-2" placeholder="Laiks" required value="<?php echo isset($editKoncert) ? $editKoncert['Laiks'] : ''; ?>">
                <input name="Informacija" type="text" class="form-control mb-2" placeholder="Informācija" required value="<?php echo isset($editKoncert) ? $editKoncert['Informacija'] : ''; ?>">
                <input name="Plakats" type="file" class="form-control mb-2">
                <?php if (isset($editKoncert)): ?>
                    <input type="hidden" name="KoncertiID" value="<?php echo $editKoncert['KoncertiID']; ?>">
                    <input type="hidden" name="existingPlakats" value="<?php echo $editKoncert['Plakats']; ?>">
                    <input class="btn btn-warning mb-2" name="submit_edit" type="submit" value="Labot">
                <?php else: ?>
                    <input class="btn btn-success mb-2" name="submit_new" type="submit" value="Pievienot">
                <?php endif; ?>
                <button id="close-btn" type="button" class="btn btn-secondary mb-2">Atcelt</button>
            </div>
        </form>
    </div>
    <div class="list">
        <div class="row">
            <?php
                $query = "SELECT * FROM koncerti";
                $result = mysqli_query($connection, $query);
                while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($row['Plakats'])): ?>
                        <img src="<?php echo $row['Plakats']; ?>" class="card-img-top" alt="Koncerta plakāts">
                    <?php endif; ?>
                    <div class="card-body-overlay">
                        <h5 class="card-title"><?php echo $row['Nosaukums']; ?></h5>
                        <p class="card-text"><strong>Datums:</strong> <?php echo $row['Datums']; ?></p>
                        <p class="card-text"><strong>Laiks:</strong> <?php echo $row['Laiks']; ?></p>
                        <p class="card-text"><strong>Informācija:</strong> <?php echo $row['Informacija']; ?></p>
                        <a href="editkoncerti.php?EditKoncertiID=<?php echo $row['KoncertiID']; ?>" class="btn btn-warning">Labot</a>
                        <a href="backend/functions.php?KoncertiID=<?php echo $row['KoncertiID']; ?>" class="btn btn-danger">Dzēst</a>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('add-btn').addEventListener('click', () => {
        document.getElementById('add-pop').style.display = 'block';
    });

    document.getElementById('close-btn').addEventListener('click', () => {
        document.getElementById('add-pop').style.display = 'none';
    });

    <?php if (isset($editKoncert)): ?>
        document.getElementById('add-pop').style.display = 'block';
    <?php endif; ?>
</script>
</body>
</html>