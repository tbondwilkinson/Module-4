<?php
	
	require "database.php";
	session_start();

	$usid = @$_SESSION['identifier'] ?: null;
	unset($_SESSION['identifier']);

	$_SESSION['identifier'] = $_GET["openid_identity"];

	// Use a prepared statement
	$stmt = $mysqli->prepare("SELECT identifier FROM users WHERE identifier=?");

	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	// Bind the parameter
	$user = $_GET['openid_identity'];
	$stmt->bind_param('s', $user);
	$stmt->execute();
	 
	// Bind the results
	$stmt->bind_result($user);
	if($stmt->fetch()) {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO users (identifier) VALUES (?)");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}

		$user = $_GET['openid_identity'];
		$stmt->bind_param('s', $user);
		$stmt->execute();

		$stmt->close();
	}

	exit;
?>