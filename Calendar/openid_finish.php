<?php

	session_start();

	print_r($_GET);

	$usid = @$_SESSION['identifier'] ?: null;
	unset($_SESSION['identifier']);
 
	$queryString = count($_POST) ? file_get_contents('php://input') : $_SERVER['QUERY_STRING'];
 
	$message = new OpenID_Message($queryString, OpenID_Message::FORMAT_HTTP);
 
	$result = $o->verify(new Net_URL2($returnTo . '?' . $queryString), $message);
 
	if($result->success()){
		// Login Success!
 
		// Get the OpenID identifier, which is unique to every OpenID user (i.e. you can use it in your database to
		// keep track of people between logins), and save it in the session:
		$_SESSION["openid.identity"] = $message->get("openid.identity");
 
		// Now redirect to the target page for logged-in users
	}else{
		// Login Failed.  You can redirect back to the login page or whatever
	}

	exit;
?>