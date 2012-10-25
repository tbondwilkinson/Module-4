<?php
session_start();
require "database.php";

if (!isset($_SESSION['identifier'])) {
	exit;
}

$stmt = $mysqli->prepare("INSERT INTO events (name, datetime, owner) VALUES (?, ?, ?)");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$name = $_GET['title'];
$datetime = $_GET['datetime'];
$owner = $_SESSION['identifier'];
$stmt->bind_param('sss', $name, $datetime, $owner);
$stmt->execute();

$stmt->close();

$events[0] = array();
$events[0]['year'] = substr($datetime, 0, 4);
$events[0]['month'] = intval(substr($datetime, 5, 2)) - 1;
$events[0]['day'] = substr($datetime, 8, 2);
$events[0]['hour'] = substr($datetime, 11, 2);
$events[0]['minute'] = substr($datetime, 14, 2);
$events[0]['title'] = htmlentities($name);

echo json_encode($events);
?>