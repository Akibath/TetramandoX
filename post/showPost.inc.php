<?php
	if(!defined('showPost_inc')){
		require '../pageNotFound.inc.php';
		exit();
	}

	define('topMenu_inc', true);

	$postID = $_GET['postID'];
	$postPropt = postPropt($postID);
	$title = !empty($postPropt['text'])? substr($postPropt['text'], 0, 16): personName(userID, 'fullName', 30);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?> | TetramandoX</title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css" >
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/post.inc.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body>
	<?php require 'topMenu.inc.php'; ?>
	<div class="mainContent">
		<?php echo echoPost($postID, false, true); ?>
	</div>
</body>
<style>
body{
	height:100%;
	background:#EDEFF4;
}
.mainContent{
	width:850px;
	margin:100px auto;
}
</style>
</html>