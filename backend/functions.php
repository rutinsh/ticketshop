<?php
if (isset($_GET['FestivaliID'])) {
    DelFestivals();
}

function DelFestivals()
{
    include 'db_con.php';
    $sql = "DELETE FROM festivali WHERE FestivaliID='" . $_GET["FestivaliID"] . "'";
    if (mysqli_query($connection, $sql)) {
        header("Location: ../editfestivali.php");
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}

if (isset($_GET['KoncertiID'])) {
    DelKoncerts();
}

function DelKoncerts()
{
    include 'db_con.php';
    $sql = "DELETE FROM koncerti WHERE KoncertiID='" . $_GET["KoncertiID"] . "'";
    if (mysqli_query($connection, $sql)) {
        header("Location: ../editkoncerti.php");
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}

if (isset($_GET['StandupID'])) {
    DelStandup();
}

function DelStandup()
{
    include 'db_con.php';
    $sql = "DELETE FROM standup WHERE StandupID='" . $_GET["StandupID"] . "'";
    if (mysqli_query($connection, $sql)) {
        header("Location: ../editstandup.php");
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}

if (isset($_GET['CitiID'])) {
    DelCiti();
}

function DelCiti()
{
    include 'db_con.php';
    $sql = "DELETE FROM citi WHERE CitiID='" . $_GET["CitiID"] . "'";
    if (mysqli_query($connection, $sql)) {
        header("Location: ../editciti.php");
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}

function checkAdmin() {
    session_start();
    if (!isset($_SESSION["email"]) || $_SESSION["admin"] != 1) {
        header("Location: index.php");
        exit();
    }
}
?>
