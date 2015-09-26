<html>
<head>

<meta charset='UTF-8' />
<style type="text/css">
<!--
.chat_wrapper {
	width: 500px;
	 border-radius: 10px;
	margin-right: auto;
	margin-left: auto;
	background: #5555CC;
	border: 1px solid #999999;
	padding: 10px;
	font: 14px 'lucida console',monaco, monospace;
}
.chat_wrapper .message_box {
	border-radius: 10px;
	background: #FFFFFF;
	height: 150px;
	overflow: auto;
	padding: 10px;
	border: 1px solid #999999;
}
.chat_wrapper .panel input{
	border-radius: 10px; 
	padding: 2px 2px 2px 5px;
}

-->
</style>
<title>Buoy Chat</title>
</head>
<body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script language="javascript" type="text/javascript">
$(document).ready(function(){
	
<?php
	$name = $_GET['name'];
	$room = $_GET['room']; 
	echo "clientInfo = { name: '$name', room: '$room' };";
?>	
	
	conn = new WebSocket('ws://vvv.dev:8080');

	clientName = clientInfo.name;
	roomName = clientInfo.room;
	
	conn.onopen = function(e) {
		var commandChar = "&"; 
		
		var introductionJSON = {
			name: clientName,
			room: roomName
		};
		
		var msg = JSON.stringify(introductionJSON);
		 
	 	conn.send(commandChar.concat(msg)); 
  	  	console.log("Connection established!");
	};

	conn.onmessage = function(e) {
		
		var msg = JSON.parse(e.data);  
  	  	$('#message_box').append(msg.name + ": " + msg.message + "<br />"); 
  	  	
  	  	var box = $('#message_box')[0];
  	  	box.scrollTop = box.scrollHeight; 
	};
	
	$('#message').keypress(function(e) {
   		
    		if (e.keyCode == 13) { 
    		
    			var mymessage = $('#message').val(); 

			$('#message').val(''); 
	
			var msg = {
				name: clientName,
				message: mymessage,
			};
		
			conn.send(JSON.stringify(msg));
    		}
	}); 
	
});
</script>

<div class="chat_wrapper">
<div class="message_box" id="message_box"></div><br />
<div class="panel">
<input type="text" name="message" id="message" placeholder="Message" maxlength="80" style="width:100%" />
</div>
</div>

</body>
</html>
