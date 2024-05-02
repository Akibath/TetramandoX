<?php
	define('core_inc', true);
	define('topMenu_inc', true);
	require '../../core.inc.php';
	login2Continue();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sent requests</title>
	<meta charset="UTF-8"></meta>
	<script type="text/javascript" src="http://localhost/TetramandoX/js/friendReqs.js"></script>
	<script type="text/javascript" src="http://localhost/TetramandoX/js/main.js"></script>
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/friendReqs.css">
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/topMenu.css">
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/sentRequests.css">
</head>
<body>
	<?php  require '../../topMenu.inc.php'; ?>
<div id="mainContentDiv">
	<div id="mainContent">
		<div id="SDVreqList">
			<div id="SDVtitles">
				<div id="SDVmainTitle">
					Sent requests
				</div>
			</div>
			<div id="SDVeachUserPane">
				<?php  echo listSentRequests(); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>