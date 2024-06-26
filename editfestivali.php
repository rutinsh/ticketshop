<?php
require('backend/db_con.php');
include 'backend/functions.php';
checkAdmin();

// Jauna Festivāla izveide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_new'])) {
    $Nosaukums = htmlspecialchars($_POST['Nosaukums']);
    $Datums = htmlspecialchars($_POST['Datums']);
    $Laiks = htmlspecialchars($_POST['Laiks']);
    $Informacija = htmlspecialchars($_POST['Informacija']);
    $Cena = htmlspecialchars($_POST['Cena']);
    $BilesuSkaits = htmlspecialchars($_POST['BilesuSkaits']);
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

    $stmt = $connection->prepare("INSERT INTO festivali (Nosaukums, Datums, Laiks, Informacija, Cena, BilesuSkaits, Plakats) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssdis", $Nosaukums, $Datums, $Laiks, $Informacija, $Cena, $BilesuSkaits, $imagePath);

    if ($stmt->execute()) {
        header('Location: editfestivali.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Esošā festivāla labošana
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_edit'])) {
    $FestivaliID = htmlspecialchars($_POST['FestivaliID']);
    $Nosaukums = htmlspecialchars($_POST['Nosaukums']);
    $Datums = htmlspecialchars($_POST['Datums']);
    $Laiks = htmlspecialchars($_POST['Laiks']);
    $Informacija = htmlspecialchars($_POST['Informacija']);
    $Cena = htmlspecialchars($_POST['Cena']);
    $BilesuSkaits = htmlspecialchars($_POST['BilesuSkaits']);
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

    $stmt = $connection->prepare("UPDATE festivali SET Nosaukums = ?, Datums = ?, Laiks = ?, Informacija = ?, Cena = ?, BilesuSkaits = ?, Plakats = ? WHERE FestivaliID = ?");
    $stmt->bind_param("ssssdisi", $Nosaukums, $Datums, $Laiks, $Informacija, $Cena, $BilesuSkaits, $imagePath, $FestivaliID);

    if ($stmt->execute()) {
        header('Location: editfestivali.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Festivālu datu ieguve tālākai apstrādāšanai
$editFestival = null;
if (isset($_GET['EditFestivaliID'])) {
    $FestivaliID = intval($_GET['EditFestivaliID']);
    $result = mysqli_query($connection, "SELECT * FROM festivali WHERE FestivaliID = $FestivaliID");
    if ($result) {
        $editFestival = mysqli_fetch_assoc($result);
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
        <button id="add-btn" class="btn btn-primary">Pievienot festivālu</button>
        <form action="" method="post" enctype="multipart/form-data">
            <div id="add-pop" style="display:<?php echo isset($editFestival) ? 'block' : 'none'; ?>;">
                <input name="Nosaukums" type="varchar" class="form-control mb-2" placeholder="Nosaukums" required value="<?php echo isset($editFestival) ? $editFestival['Nosaukums'] : ''; ?>">
                <input name="Datums" type="date" class="form-control mb-2" placeholder="Datums" required value="<?php echo isset($editFestival) ? $editFestival['Datums'] : ''; ?>">
                <input name="Laiks" type="time" class="form-control mb-2" placeholder="Laiks" required value="<?php echo isset($editFestival) ? $editFestival['Laiks'] : ''; ?>">
                <input name="Informacija" type="text" class="form-control mb-2" placeholder="Informācija" required value="<?php echo isset($editFestival) ? $editFestival['Informacija'] : ''; ?>">
                <input name="Cena" type="number" step="0.01" class="form-control mb-2" placeholder="Cena" required value="<?php echo isset($editFestival) ? $editFestival['Cena'] : ''; ?>">
                <input name="BilesuSkaits" type="number" class="form-control mb-2" placeholder="Biļešu skaits" required value="<?php echo isset($editFestival) ? $editFestival['BilesuSkaits'] : ''; ?>">
                <input name="Plakats" type="file" class="form-control mb-2">
                <?php if (isset($editFestival)): ?>
                    <input type="hidden" name="FestivaliID" value="<?php echo $editFestival['FestivaliID']; ?>">
                    <input type="hidden" name="existingPlakats" value="<?php echo $editFestival['Plakats']; ?>">
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
                        <p class="card-text"><strong>Cena:</strong> <?php echo number_format($row['Cena'], 2); ?> EUR</p>
                        <p class="card-text"><strong>Biļešu skaits:</strong> <?php echo $row['BilesuSkaits']; ?></p>
                        <a href="editfestivali.php?EditFestivaliID=<?php echo $row['FestivaliID']; ?>" class="btn btn-warning">Labot</a>
                        <a href="backend/functions.php?FestivaliID=<?php echo $row['FestivaliID']; ?>" class="btn btn-danger">Dzēst</a>
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

    <?php if (isset($editFestival)): ?>
        document.getElementById('add-pop').style.display = 'block';
    <?php endif; ?>
</script>
</body>
</html>
