<?php
session_start();
require "database.php";

if (!isset($_SESSION['identifier'])) {
	exit;
}

echo $_GET['title'];
echo $_GET['oldDatetime'];
echo $_GET['newDateTime'];
echo $_SESSION['identifier'];

$stmt = $mysqli->prepare("UPDATE events SET datetime = ? WHERE name = ? and datetime = ? and owner = ?");

if(!$stmt){
	printf("Query Prep Failed: %s\n", $mysqli->error);
	exit;
}

$newDatetime = $_GET['newDatetime'];
$name = $_GET['title'];
$oldDatetime = $_GET['oldDatetime'];
$owner = $_SESSION['identifier'];
$stmt->bind_param('ssss', $newDatetime, $name, $oldDatetime, $owner);
$stmt->execute();

$stmt->close();

echo "success";
exit;
?>