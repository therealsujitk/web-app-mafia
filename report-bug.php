<?php

$con = mysqli_connect('localhost', 'root', '299792458');

$report = $_POST['bug-report'];

mysqli_select_db($con, 'mafia');
$query = "INSERT INTO bug_reports (report) VALUES('$report');";
mysqli_query($con, $query);

?>
