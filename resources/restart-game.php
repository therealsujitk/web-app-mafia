<?php

include('../conn.php');
session_start();

$townID = $_SESSION["townID"];
$check = $_POST["check"];

$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
if($check != 'true')
    die('Sorry, something went terribly wrong');
else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] == '0')
    die('success');

$query = "ALTER TABLE town_" . $townID . " RENAME TO town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "CREATE TABLE town_" . $townID . " AS SELECT name, avatar FROM town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD user_id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY;";
mysqli_query($conn, $query);

$query = "DROP TABLE town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET has_started = 0, game_index = 0, daily_index = 0, daily_max = 3 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET time_stamp = NOW() WHERE town_id = '$townID';";
mysqli_query($conn, $query);

mysqli_close($conn);
echo 'success';