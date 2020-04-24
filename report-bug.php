<?php

	$conn = mysqli_connect('localhost', 'root', '299792458', 'mafia');

	$report = $_POST['report'];
	$report = str_replace("'", "\'", $report);

	$query = "INSERT INTO bug_reports (report) VALUES('$report');";
	mysqli_query($conn, $query);
	
	mysqli_close($conn);
?>
