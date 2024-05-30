<?php
include 'navbar.php';
include 'backend/db_con.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$Epasts = $_SESSION['email'];
$sql = "SELECT Vards, Uzvards, Epasts FROM lietotaji WHERE Epasts = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $Epasts);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateProfile'])) {
    $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    
    $update_sql = "UPDATE lietotaji SET Vards = ?, Uzvards = ?, Epasts = ? WHERE Epasts = ?";
    $update_stmt = $connection->prepare($update_sql);
    $update_stmt->bind_param("ssss", $firstName, $lastName, $email, $Epasts);
    
    if ($update_stmt->execute()) {
        $_SESSION['email'] = $email; 
        echo "<p>Profils ir veiksmīgi atjaunināts!</p>";
    } else {
        echo "<p>Neizdevās atjaunināt profilu.</p>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changePassword'])) {
    $currentPassword = mysqli_real_escape_string($connection, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($connection, $_POST['newPassword']);
    $confirmPassword = mysqli_real_escape_string($connection, $_POST['confirmPassword']);
    
    if ($newPassword !== $confirmPassword) {
        echo "<p>Jaunās paroles nesakrīt.</p>";
    } else {
        $sql = "SELECT Parole FROM lietotaji WHERE Epasts = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $Epasts);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($currentPassword, $user['Parole'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update_sql = "UPDATE lietotaji SET Parole = ? WHERE Epasts = ?";
            $update_stmt = $connection->prepare($update_sql);
            $update_stmt->bind_param("ss", $hashedPassword, $Epasts);
            
            if ($update_stmt->execute()) {
                echo "<p>Parole veiksmīgi nomainīta!</p>";
            } else {
                echo "<p>Neizdevās nomainīt paroli.</p>";
            }
        } else {
            echo "<p>Pašreizējā parole ir nepareiza.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lietotāja profils</title>
    <link rel="stylesheet" href="resources/CSS/profile.css">
</head>
<body>
    <div class="profile-container">
        <h2>Lietotāja Profils</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="firstName">Vārds*</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['Vards'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastName">Uzvārds*</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['Uzvards'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">E-pasts*</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Epasts'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="updateProfile" class="btn btn-success btn-sm">Saglabāt</button>
        </form>
    </div>
    
    <div class="password-change-container">
        <h3>Paroles maiņa</h3>
        <form action="" method="post">
            <div class="form-group">
                <label for="currentPassword">Vecā parole*</label>
                <input type="password" id="currentPassword" name="currentPassword" required>
            </div>
            <div class="form-group">
                <label for="newPassword">Jaunā parole*</label>
                <input type="password" id="newPassword" name="newPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Jaunā parole atkārtoti*</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" name="changePassword" class="btn btn-success btn-sm">Nomainīt paroli</button>
        </form>
    </div>
</body>
</html>
