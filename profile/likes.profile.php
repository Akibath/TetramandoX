<?php
	if(!defined('likes_profile')){
		require 'pageNotFound.inc.php';
		exit();
	}
?>
<!DOCTYPE html>
<head>
	<title>Likes of <?php echo personName($personID, 'firstName'); ?></title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/photos.myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfileCard.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html" charset="UTF-8">
</head>
<div class="profileBody">
	<?php define('profileCard_inc', true); require 'profileCard.inc.php'; ?>
	<div class="profileContent">
		<div class="mainTitle">
			<span>Likes</span>
		</div>	
		<div class="allPhotos">
		<?php
			$photosArr = photosList($personID);
			$allPhotos = null;
			for($i=0; $i<count($photosArr); $i++){
				$localPath = directoryName($personID).'/thumbs/'.preg_replace('/\..+$/', '', $photosArr[$i]).'_157x167.'.end(explode('.', $photosArr[$i]));
				$postID = queryResult("SELECT postID FROM posts.$personID WHERE image = '$photosArr[$i]' ");
				$allPhotos .= '<a href="http://localhost/TetramandoX/post.php?postID='.$postID.'"><img src="http://localhost/TetramandoX/userFiles/'.$localPath.'"></a>';
			}

			echo count($photosArr) == 0 || !isViewPermission($personID, 'postPermission')? '<div class="noActivity">No activity to show.</div>': $allPhotos;
		?>
		</div>
	</div>
</div>
<?php require './topMenu.inc.php'; ?>