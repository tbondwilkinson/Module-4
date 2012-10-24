<!DOCTYPE html>
<head>
	<title>Calendar!</title>
</head>
<body>
<form name  action="">
	<input id="start" name="start" type="hidden" value="true" />
	<fieldset>
		<legend>Sign in using OpenID</legend>
		<div id="openid_choice">
			<p>Please select your account provider:</p>
			<select name="identifier">
				<option value="https://www.google.com/accounts/o8/id">Google</option>
				<option value="http://yahoo.com/">Yahoo</option>
			</select>
		</div>
		<p>
			<input id="user_submit" type="submit" value="Sign In"/>
		</p>
	</fieldset>
</form>

<script type="text/javascript">
	function submitUser(event) {
		alert("Submitting user");
		<?php
		require_once 'OpenID/RelyingParty.php';
		require_once 'OpenID/Message.php';
		require_once 'Net/URL2.php';
		 
		session_start();
		 
		$realm = "http://ec2-54-245-10-30.us-west-2.compute.amazonaws.com/~tbondwilkinson/Module-4/Calendar/main.php";
		$returnTo = $realm;
		 
		$identifier = @$_POST['identifier'] ?: @$_SESSION['identifier'] ?: null; // note: the @ signs suppress "undefined" notices
		 
		$o = new OpenID_RelyingParty($returnTo, $realm, $identifier);
		 
		// Part 1: We are processing a login request before visiting the OpenID provider
		if(@$_POST['start']) {
			$authRequest = $o->prepare();
			$url = $authRequest->getAuthorizeURL();
		 
			header("Location: ".$url);
			exit;
		}
		 
		// Part 2: The user is returning to our site after visiting the OpenID provider's site
		else {
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
		}
		?>
	}

	var submit = document.getElementById("user_submit");
	submit.addEventListener("click", submitUser, false);
</script>
</body>
</html>