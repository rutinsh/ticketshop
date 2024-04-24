<?php
require('backend/db_con.php');


if(isset($_POST['submit'])){
  $Epasts = $_POST['email'];
  $Parole = $_POST['password'];

  $query = "SELECT * FROM lietotaji WHERE Epasts = '$Epasts' AND Parole = '$Parole' ";
  $result = mysqli_query($connection, $query);

  if(mysqli_num_rows($result) == 1){
    $user = mysqli_fetch_assoc($result);
    session_start();
    $_SESSION['email'] = $Epasts;
    if($user['Admins'] == 1) {
      header('Location: admin.php');
    } else {
      header('Location: index.php');
    }
    exit();
  } 
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources\CSS\login.css" />
    <link rel="icon" type="image/png" href="resources/Images/favicon.png">
    <title>Autorizācija</title>
  </head>
  <body>
    <div class="login-card">
      <h2>Autorizācija</h2>
      <h3>Ievadiet e-pastu un paroli</h3>
      <form class="login-form" method="post">
        <input type="text" name="email" placeholder="E-pasts" required />
        <input type="password" name="password" placeholder="Parole"required />
		<div class="form-links">
			<a href="#">Aizmirsi paroli?</a>
  			<a href="registration.php">Reģistrēties</a>
   		</div>
        <button type="submit" name="submit">Autorizācija</button>
      </form>
    </div>
  </body>
</html>