<?php 
	define('core_inc', true);
	require '../core.inc.php';
	login2Continue();
?>
<!DOCTYPE html>
<html>
<head>
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<link href="http://localhost/TetramandoX/css/topMenu.css" type="text/css" rel="stylesheet">
	<link href="http://localhost/TetramandoX/css/messages.css" type="text/css" rel="stylesheet">
	<link href="http://localhost/TetramandoX/css/notifications.css" type="text/css" rel="stylesheet">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Notifications</title>
</head>
<body>
	<?php require '../topMenu.inc.php'; ?>
	<div id="mainContentDiv">
		<div id="notificationBox">
			<div id="notitifTitleBar">
				<div id="notificationTitle">
					Your Notifications
					<span id="notifMarkAllRead" onclick="callAjax('notifMarkRead=All')">Mark read</span>
				</div>
				<div id="notificationContents">
					<?php listAllNotification(); ?>
					
				</div>		
			</div>
		</div>
	</div>
</body>
</html>