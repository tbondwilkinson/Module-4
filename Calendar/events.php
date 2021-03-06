<?php
session_start();
require "database.php";

if(!isset($_SESSION['identifier'])) {
	exit;
}

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT name, datetime, color FROM events WHERE owner=?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

// Bind the parameter
$user = $_SESSION['identifier'];
$stmt->bind_param('s', $user);
$stmt->execute();


$stmt->bind_result($name, $datetime, $color);

$events = array();

$i = 0;
while($stmt->fetch()) {
	$events[$i] = array();
	$events[$i]['year'] = substr($datetime, 0, 4);
	$events[$i]['month'] = intval(substr($datetime, 5, 2)) - 1;
	$events[$i]['day'] = substr($datetime, 8, 2);
	$events[$i]['hour'] = substr($datetime, 11, 2);
	$events[$i]['minute'] = substr($datetime, 14, 2);
	$events[$i]['second'] = substr($datetime, 17, 2);
	$events[$i]['title'] = htmlentities($name);
	$events[$i]['color'] = $color;

	$i++;
}
echo json_encode($events);

exit;
$stmt->close;
?>