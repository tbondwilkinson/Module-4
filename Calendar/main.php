<?php
session_start();
?>

<!DOCTYPE html>
<head>
	<link rel='stylesheet' type='text/css' href='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.css' />
	<link rel='stylesheet' type='text/css' href='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.print.css' media='print' />


	<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo/yahoo-min.js&amp;2.6.0/build/event/event-min.js&amp;2.6.0/build/connection/connection-min.js"></script> 

	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/start/jquery-ui.css" type="text/css" rel="Stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>


<script type='text/javascript' src='http://arshaw.com/js/fullcalendar-1.5.4/fullcalendar/fullcalendar.min.js'></script>
	<title>Calendar!</title>
<script type='text/javascript'>
/*jslint browser: true*/
/*global $, document, jQuery, YAHOO, confirm */

function getEventsCallback(event) {
	"use strict";

	if (event.target.responseText === "") {
		return;
	}

	var json = JSON.parse(event.target.responseText);

	jQuery.each(json, function () {
		$('#calendar').fullCalendar("renderEvent", {
			id: this.year + this.month + this.day + this.hour + this.minute + this.second + this.title,
			title:  this.title,
			start: new Date(this.year, this.month, this.day, this.hour, this.minute, this.second),
			color: this.color
		}, true);
	});
}

function getEvents() {
	"use strict";
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open("GET", "events.php", true);
	xmlHttp.addEventListener("load", getEventsCallback, false);
	xmlHttp.send(null);
}


function ready() {
	"use strict";
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		editable: true,
		events: [],
		eventClick: function (calEvent) {
			$("#dialog-delete-event").data("calEvent", calEvent);
			$("#dialog-delete-event").dialog('option', 'title', calEvent.title);
			$("#time").text("Time: " + calEvent.start);

			$("#dialog-delete-event").dialog('open');
		},
		eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
			var date, oldDate, xmlHttp, oldDatetime, newDatetime;

			date = event.start;
			oldDatetime = date.getFullYear() + '-' +
						('00' + (date.getMonth() + 1)).slice(-2) + '-' +
						date.getDate() + ' ' +
						('00' + date.getHours()).slice(-2) + ':' +
						('00' + date.getMinutes()).slice(-2) + ':' +
						('00' + date.getSeconds()).slice(-2);

			oldDate = new Date();
			oldDate.setDate(event.start.getDate() + dayDelta);

			newDatetime = oldDate.getFullYear() + '-' +
						('00' + (oldDate.getMonth() + 1)).slice(-2) + '-' +
						oldDate.getDate() + ' ' +
						('00' + oldDate.getHours()).slice(-2) + ':' +
						('00' + oldDate.getMinutes()).slice(-2) + ':' +
						('00' + oldDate.getSeconds()).slice(-2);

			alert(oldDatetime);
			alert(newDatetime);

			xmlHttp = new XMLHttpRequest();
			xmlHttp.open("GET", "moveevent.php?title=" + event.title + "&datetime=" + oldDatetime + "&newdatetime=" + newDatetime, true);
			xmlHttp.addEventListener("load", function () {}, false);
			xmlHttp.send(null);
    	}
	});

	getEvents();

	var title = $("#title"),
		datetime = $("#datetime"),
		token = $("#token"),
		color = $("#color"),
		allFields = $([]).add(title).add(datetime);

	function checkRegexp(o, regexp) {
		if (!(regexp.test(o.val()))) {
			o.addClass("ui-state-error");
			return false;
		}
		return true;
	}

	$("#dialog-form-event").dialog({
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true,
		buttons: {
			"Create an event": function () {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );
				
				bValid = bValid && checkRegexp(title, /^([0-9a-zA-Z_ ])+$/);
				bValid = bValid && checkRegexp(datetime, /^\d{4}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])\s([01][1-9]|2[0123])[:]([0-5][0-9])[:]([0-5][0-9])$/);
				
				if ( bValid ) {
					var xmlHttp = new XMLHttpRequest();

					xmlHttp.open("GET",  "newevent.php?title=" + title.val() + "&datetime=" + datetime.val() + "&token=" + token.val() + "&color=" + color.val());
					xmlHttp.addEventListener("load", getEventsCallback, false);
					xmlHttp.send(null);
				    $( this ).dialog( "close" );
				}
			},
			Cancel: function () {
				$(this).dialog("close");
			}
		}
	});

	$("#dialog-delete-event").dialog({
		autoOpen: false,
		height: 250,
		width: 300,
		modal: true,
		buttons: {
			"Delete this event": function (e) {
				var r, date, datetime, xmlHttp, calEvent;
				calEvent = $("#dialog-delete-event").data("calEvent");

				$("#calendar").fullCalendar('removeEvents', calEvent.id);
				date = calEvent.start;
				datetime = date.getFullYear() + '-' +
					('00' + (date.getMonth() + 1)).slice(-2) + '-' +
					date.getDate() + ' ' +
					('00' + date.getHours()).slice(-2) + ':' +
					('00' + date.getMinutes()).slice(-2) + ':' +
					('00' + date.getSeconds()).slice(-2);
				xmlHttp = new XMLHttpRequest();
				xmlHttp.open("GET", "removeevent.php?title=" + calEvent.title + "&datetime=" + datetime, true);
				xmlHttp.addEventListener("load", function () {}, false);
				xmlHttp.send(null);
				$(this).dialog("close");
			},
			Cancel: function () {
				$(this).dialog("close");
			}
		},
		close: function () {
			allFields.val("").removeClass("ui-state-error");
		}
	});

	$("#addevent")
		.button()
		.click(function () {
			$("#dialog-form-event").dialog("open");
		});
	$("#logout")
		.button()
		.click(function () {
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open("GET", "logout.php");
			xmlHttp.addEventListener("load", function () {
				$("#calendar").fullCalendar('removeEvents');
				$("#calendar").fullCalendar('refetchEvents');
				$("#ops").show();
			},
				false);
			xmlHttp.send(null);
		});
}

function getCenteredCoords(width, height) {
	"use strict";
	var xPos, yPos, parentSize, parentPos;
	xPos = null;
	yPos = null;
	if (window.ActiveXObject) {
		xPos = window.event.screenX - (width / 2) + 100;
		yPos = window.event.screenY - (height / 2) - 100;
	} else {
		parentSize = [window.outerWidth, window.outerHeight];
		parentPos = [window.screenX, window.screenY];
		xPos = parentPos[0] +
			Math.max(0, Math.floor((parentSize[0] - width) / 2));
		yPos = parentPos[1] +
			Math.max(0, Math.floor((parentSize[1] - (height * 1.25)) / 2));
	}
	return [xPos, yPos];
}

function openPopupWindow(openid) {
	"use strict";
	document.getElementById('ops').style.display = 'none';
	var w, coords;
	w = window.open('./openid_begin.php?openid_identifier=' + encodeURIComponent(openid), 'openid_popup', 'width=450,height=500,location=1,status=1,resizable=yes');

	coords = getCenteredCoords(450, 500);
	w.moveTo(coords[0], coords[1]);
}

function openYahooWindow() {
	"use strict";
	openPopupWindow('yahoo.com');
}

function openMySpaceWindow() {
	"use strict";
	openPopupWindow('http://www.myspace.com/');
}

function openGoogleWindow() {
	"use strict";
	openPopupWindow('https://www.google.com/accounts/o8/id');
}

function handleOpenIDResponse(openid_args) {
	"use strict";
	document.getElementById('ops').style.display = 'none';
	YAHOO.util.Connect.asyncRequest('GET', './openid_finish.php?' + openid_args,
		{'success': function () {
			getEvents();
		}});
}

$(document).ready(ready);
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

<?php 
if (isset($_SESSION['identifier'])) {
	echo "<script language ='javascript'>$('#ops').hide();";
	echo "</script>";
}
?>

<div id="addevent_div">
	<button id="addevent" type="button">Add event</button>
</div>
<div id="logout_div">
	<button id="logout" type="button">Logout</button>
</div>
<div id="calendar"></div>

<div id="dialog-form-event" title="Create new event">
	<p class="validateTips">All form fields are required.</p>
 
	<form>
	<fieldset>
		<label for="title">Title</label>
		<input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" /><br>
		<label for="datetime">Date and time (YYYY-MM-DD HH:MM:SS)</label>
		<input type="text" name="datetime" id="datetime" value="" class="text ui-widget-content ui-corner-all" />
		<select name="color" id="color">
		<option value="red" selected="selected">Red</option>
		<option value="orange">Orange</option>
		<option value="yellow">Yellow</option>
		<option value="green">Green</option>
		<option value="blue">Blue</option>
		<option value="purple">Purple</option>
		</select>
		<input type="hidden" name="token" id="token" value="<?=$_SESSION['token'];?>" />
	</fieldset>
	</form>
</div>

<div id="dialog-delete-event" title="HELLO">
	<div id="time"></div>
</div>
</body>
</html>