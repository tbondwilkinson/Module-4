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
	echo "title: '" . $name . "',\n";
	$month = intval(substr($datetime,5,2)) - 1;
	$php_date_str = substr($datetime, 0, 4). ", " . substr($datetime,5,2) . ", " . substr($datetime, 8, 2) . ", " . substr($datetime, 11, 2) . ", " . substr($datetime, 14, 2);
	echo "start: new Date(" . $php_date_str . ")\n";
	echo "}\n";

}

exit;
$stmt->close;
?>