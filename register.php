<?php 

require_once('backend/db_con.php');
$con = $connection;

$check = false;
$checkpw = false;
$create = false;
 
 if (isset($_POST['name']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['passwordx2'])) {

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $passwordx2 = mysqli_real_escape_string($con, $_POST['passwordx2']);

    $check_query = "SELECT * FROM lietotaji WHERE Epasts = '$email'";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $check = true;
    } else {
        if($password != $passwordx2){
            $checkpw = true;
        }else{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO lietotaji (Vards, Uzvards, Epasts, Parole) VALUES ('$name','$lastname','$email','$hashedPassword')";
            $result = mysqli_query($con,$query);
            if($result){
                $create = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/CSS/login.css">
    <title>Reģistrācija</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">
            <header>Reģistrācija</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="name">Vārds</label>
                    <input type="text" name="name" id="Vards" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="lastname">Uzvārds</label>
                    <input type="text" name="lastname" id="Uzvards" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">E-pasts</label>
                    <input type="email" name="email" id="Epasts" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Parole</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="passwordx2">Atkārtojiet paroli</label>
                    <input type="password" name="passwordx2" id="password" autocomplete="off" required>
                </div>

                <div class="field">

                <?php
                    if ($check == true){
                        echo "<h1>Profils ar šo e-pastu jau ir izveidots</h1>";
                    }elseif($checkpw == true){
                        echo "<h1>Paroles nav vienādas</h1>";
                    }elseif($create == true){
                        echo "<script>alert('Profils ir veiksmīgi izveidots!'); window.location='index.php';</script>";
                    }
                ?>
                    
                    <input type="submit" class="btn" name="submit" value="Reģistrēties" required>
                </div>
                <div class="links">
                    Lietotājs jau eksistē? <a href="login.php">Ienākt</a>
                </div>
            </form>
        </div>
      </div>
</body>
</html>