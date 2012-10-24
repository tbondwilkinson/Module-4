<!DOCTYPE html>
<head>
    <script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo/yahoo-min.js&2.6.0/build/event/event-min.js&2.6.0/build/connection/connection-min.js"></script> 
	<title>Calendar!</title>
</head>
<body>

<div id="ops">
	<div style="margin-bottom:5px;">
	    <a href="javascript:openYahooWindow();">
		<img src="http://www.yahoo.com/favicon.ico" style="border:none;"/> Sign in with a Yahoo! ID</a>
	</div>

	<div style="margin-bottom:5px;">
	    <a href="javascript:openGoogleWindow();">
		<img src="http://www.google.com/favicon.ico" style="border:none;"/> Sign in with a Google Account</a>
	</div>

	<div style="margin-bottom:10px;">
	    <a href="javascript:openMySpaceWindow();">
		<img src="http://www.myspace.com/favicon.ico" style="border:none;"/> Sign in using MySpaceID</a>
	</div>

	Or, use OpenID:
	<form onsubmit="openPopupWindow(document.getElementById('openid_identifier').value)">
		<input type="text" id="openid_identifier" value="http://" /><input type="submit" value="Sign in"/>
	</form>
</div>
<div id="bucket"></div>

<script type="text/javascript">
function openPopupWindow(openid) {
  document.getElementById('ops').style.display = 'none';
  document.getElementById('bucket').innerHTML = 'Signing you in <img src="/static/spinner.gif"/>';
  var w = window.open('/openid_begin?openid_identifier='+encodeURIComponent(openid), 'openid_popup', 'width=450,height=500,location=1,status=1,resizable=yes');

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
  YAHOO.util.Connect.asyncRequest('GET', '/openid_finish?'+openid_args,
      {'success': function(r) {
              document.getElementById('bucket').innerHTML = r.responseText; 
         }}); 
}

</script>
</script>
</body>
</html>