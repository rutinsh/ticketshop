<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/CSS/login.css">
    <title>Autorizācija</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <?php 
            include("backend/db_con.php");

            if(isset($_POST['submit'])){
                $Epasts = mysqli_real_escape_string($connection, $_POST['email']);
                $Parole = mysqli_real_escape_string($connection, $_POST['password']);
              
                $query = "SELECT * FROM lietotaji WHERE Epasts = ?";
                $stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt, 's', $Epasts);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
              
                if($result && mysqli_num_rows($result) == 1){
                  $user = mysqli_fetch_assoc($result);
                  
                  // Verify the hashed password
                  if(password_verify($Parole, $user['Parole'])) {
                    $_SESSION['email'] = $Epasts;
                    $_SESSION['email'] = $user['Epasts'];
                    $_SESSION['name'] = $user['Vards'];
                    if($user['admin'] == 1) {
                      header('Location: admin.php');
                    } else {
                      header('Location: index.php');
                    }
                  exit();
                } else {
                  echo "<h1>Nepareiza parole</h1>";
                }
              } else {
                echo "<h1>Nepareizs e-pasts vai parole</h1>";
              }
            }
              
            ?>
            <header>Autorizācija</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">E-pasts</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Parole</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Pieslēgties" required>
                </div>
                <div class="links">
                    Jauns lietotājs? <a href="register.php">Reģistrēties</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
