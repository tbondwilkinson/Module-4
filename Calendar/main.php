<?php
session_start();
?>

<!DOCTYPE html>
<head>
	<link rel='stylesheet' type='text/css' href='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.css' />
	<link rel='stylesheet' type='text/css' href='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.print.css' media='print' />


    <script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo/yahoo-min.js&2.6.0/build/event/event-min.js&2.6.0/build/connection/connection-min.js"></script> 

	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/start/jquery-ui.css" type="text/css" rel="Stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>


<script type='text/javascript' src='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.min.js'></script>
	<title>Calendar!</title>
<script type='text/javascript'>

	$(document).ready(function() {
	
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.open("GET", "events.php", true);
		xmlHttp.addEventListener("load", ajaxCallback, false);
		xmlHttp.send(null);
	});

	function ajaxCallback(event) {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,basicWeek,basicDay'
			},
			editable: true,
			events: [
			]
		});

		var events = event.target.responseText.split("|");

		for (var i = 0; i< events.length; i++) {
			$('#calendar').fullCalendar('addEvent', events[i]);
		}
	}

</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

	#calendar {
		width: 900px;
		margin: 0 auto;
		}

</style>
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

<div id='calendar'></div>

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
  YAHOO.util.Connect.asyncRequest('GET', './openid_finish.php?'+openid_args,
      {'success': function() {
      		alert("success"); 
      		document.getElementById("bucket").innerHTML = "SUCCESSSS";

      		$("#ops").show();
         }}); 
}

</script>
</script>
</body>
</html>