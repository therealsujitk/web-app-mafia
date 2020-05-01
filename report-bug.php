<?php
	include('conn.php');

	$report = $_POST['report'];
	$report = str_replace("'", "\'", $report);

	$query = "INSERT INTO bug_reports (report) VALUES('$report');";
	mysqli_query($conn, $query);
	
	mysqli_close($conn);
	
	echo 'Success!';
?>
