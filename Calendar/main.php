<?php
session_start();
?>

<!DOCTYPE html>
<head>
    <script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo/yahoo-min.js&2.6.0/build/event/event-min.js&2.6.0/build/connection/connection-min.js"></script> 

	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/start/jquery-ui.css" type="text/css" rel="Stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>
	<title>Calendar!</title>
</head>
<body>

<div id="ops">
	<div style="margin-bottom:5px;">
	    <a href="javascript:openYahooWindow();">Sign in with a Yahoo! ID</a>
	</div>

	<div style="margin-bottom:5px;">
	    <a href="javascript:openGoogleWindow();">Sign in with a Google Account</a>
	</div>

	<div style="margin-bottom:10px;">
	    <a href="javascript:openMySpaceWindow();">Sign in using MySpaceID</a>
	</div>

	Or, use OpenID:
	<form onsubmit="openPopupWindow(document.getElementById('openid_identifier').value)">
		<input type="text" id="openid_identifier" value="http://" /><input type="submit" value="Sign in"/>
	</form>
</div>
<div id="bucket"></div>

<script type="text/javascript">
function getCenteredCoords(width, height) {
    var xPos = null;
    var yPos = null;
    if (window.ActiveXObject) {
        xPos = window.event.screenX - (width/2) + 100;
        yPos = window.event.screenY - (height/2) - 100;
    } else {
        var parentSize = [window.outerWidth, window.outerHeight];
        var parentPos = [window.screenX, window.screenY];
        xPos = parentPos[0] +
            Math.max(0, Math.floor((parentSize[0] - width) / 2));
        yPos = parentPos[1] +
            Math.max(0, Math.floor((parentSize[1] - (height*1.25)) / 2));
    }
   return [xPos, yPos];
}

function openPopupWindow(openid) {
  document.getElementById('ops').style.display = 'none';
  document.getElementById('bucket').innerHTML = 'Signing you in <img src="/static/spinner.gif"/>';
  var w = window.open('./openid_begin.php?openid_identifier='+encodeURIComponent(openid), 'openid_popup', 'width=450,height=500,location=1,status=1,resizable=yes');

  var coords = getCenteredCoords(450,500);
  w.moveTo(coords[0],coords[1]);
}

function openYahooWindow() {
  openPopupWindow('yahoo.com');
}

function openMySpaceWindow() {
  openPopupWindow('http://www.myspace.com/');
}

function openGoogleWindow() {
  openPopupWindow('https://www.google.com/accounts/o8/id');
}

function handleOpenIDResponse(openid_args) {
  document.getElementById('ops').style.display = 'none';
  document.getElementById('bucket').innerHTML = 'Verifying OpenID response';

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

  //YAHOO.util.Connect.asyncRequest('GET', './openid_finish.php?'+openid_args,
   //   {'success': function() {
   //   		alert("success"); 
   //   		document.getElementById("bucket").innerHTML = "SUCCESSSS";
   //
   //   		$("#ops").show();
   //      }}); 
}

</script>
</script>
</body>
</html>