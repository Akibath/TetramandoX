<?php
	require '../core.inc.php';
	login2Continue();
	require '../userDetail.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Friend Requests</title>
	<meta charset="UTF-8"></meta>
	<link href="http://localhost/TetramandoX/css/friendReqs.css" type="text/css" rel="stylesheet">
	<script src="../js/friendReqs.js" type="text/javascript"></script>
	<script src="../main.js" type="text/javascript"></script>
	<link href="http://localhost/TetramandoX/css/topMenuPanel.css" type="text/css" rel="stylesheet">
</head>
<body>
	<?php  require '../topMenu.inc.php'; ?>
<div id="mainContentDiv">
	<div id="mainContent">
		<div id="SDVreqList">
			<div id="SDVtitles">
				<div id="SDVmainTitle">
					Review Your Friend Suggestion <?php ?>
				</div>
			</div>
			<div id="SDVeachUserPane">
				<?php  echo listFriendRequests(); ?>
			</div>		
		</div>
	</div>
</div>
</body>
</html>