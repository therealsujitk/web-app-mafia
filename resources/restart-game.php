<?php
include('../conn.php');
session_start();

$townID = $_SESSION["townID"];

$query = "ALTER TABLE town_" . $townID . " RENAME TO town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "CREATE TABLE town_" . $townID . " AS SELECT user_id, name, avatar FROM town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "DROP TABLE town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET has_started = 0, game_index = 0, daily_index = 0, daily_max = 3 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

//Change time_stamp to current time

mysqli_close($conn);