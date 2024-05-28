<?php
if (isset($_GET['FestivaliID'])) {
    DelFestivals();
}

function DelFestivals()
{
    include 'db_con.php';
    $sql = "DELETE FROM festivali WHERE FestivaliID='" . $_GET["FestivaliID"] . "'";
    if (mysqli_query($connection, $sql)) {
        header("Location: ../editfestivali2.php");
    } else {
        echo "Error deleting record: " . mysqli_error($connection);
    }
    mysqli_close($connection);
}