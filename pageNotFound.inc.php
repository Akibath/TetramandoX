<!DOCTYPE html>
<html>
<head>
	<title>Page not found</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
</head>
<body>
	<?php require 'topMenu.inc.php'; ?>
	<div class="mainContent" <?php echo getField('settings', 'chatbarPosition')? '': 'id="onChatbarHide"' ?>>
		<div class="problem">
			<img src="http://localhost/TetramandoX/img/problem.png">
			<div class="problemText">Page not found.</div>
		</div>
	</div>
</body>
<style>
	body{
		background:#fff;
	}
	.mainContent{
		/background:#f00;
		margin-left:0px;
		margin-top:41px;
		width:1134px;
		/height:525px;
	}
	.mainContent#onChatbarHide{
		width: 1240px;	
	}
	.problem{
		padding-top:200px;
		width:170px;
		margin:0 auto;
	}
	.problem img{
		width:170px;
	}
	.problemText{
		cursor:default;
		color:#aaa;/border:1px solid red;
		margin-left:-25px;
		font:normal 16px arial;
		font:31px corbel;
		width:480px;
	}
	.kk{
		margin
	}
</style>
</html>