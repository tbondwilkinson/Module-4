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

$name = $_POST['title'];
$datetime = $_POST['datetime'];
$owner = $_SESSION['identifier'];
$stmt->bind_param('sss', $name, $datetime, $owner);
$stmt->execute();

$stmt->close();

?>