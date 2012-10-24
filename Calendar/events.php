<?php
session_start();
require "database.php";

if(!isset($_SESSION['identifier'])) {
	exit;
}

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT name, datetime FROM events WHERE owner=?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

// Bind the parameter
$user = $_SESSION['identifier'];
$stmt->bind_param('s', $user);
$stmt->execute();


$stmt->bind_result($name, $datetime);

while($stmt->fetch()) {
	echo "{\n";
	echo "title: " . $name . ",\n";
	$php_date_str = substr($datetime, 0, 4). ", " . substr($datetime,5,7) . ", " . substr($datetime, 8, 10) . ", " . substr($datetime, 11, 13) . ", " . substr($datetime, 14, 16);
	echo "start: new Date(" . $php_date_str . "),\n";
	echo "}\n";

	echo  "|";

}

exit;
$stmt->close;
?>