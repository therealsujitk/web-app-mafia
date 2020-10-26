<?php

$conn = mysqli_connect("localhost","root","","mafia");

if (!$conn) {
	die("Sorry, there was an error connecting to our database. Please contact <b>BinaryStack</b> if this error persists.");
}