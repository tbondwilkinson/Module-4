<?php
$mysqli = new mysqli('localhost', 'php', 'php', 'module4');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>