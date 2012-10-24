<?php
session_start();
?>
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
	}

	var submit = document.getElementById("user_submit");
	submit.addEventListener("click", submitUser, false);
</script>
</body>
</html>