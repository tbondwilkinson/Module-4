<?php

	session_start();

	$usid = @$_SESSION['identifier'] ?: null;
	unset($_SESSION['identifier']);

	$_SESSION['identifier'] = $_GET["openid_identity"];
	exit;
?>