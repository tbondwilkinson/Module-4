<?php
session_start();
require "database.php";

if (!isset($_SESSION['identifier'])) {
	exit;
}

echo $_GET['title'];
echo $_GET['datetime'];
echo $_SESSION['identifier'];

$stmt = $mysqli->prepare("DELETE FROM events WHERE name = ? and datetime = ? and owner = ?");

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

echo "success";
exit;
?>