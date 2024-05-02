<?php
	define('topMenu_inc', true);
	if(!defined('welcome_inc')){
		require 'pageNotFound.inc.php';
		exit();
	}

	if(isset($_POST['postText']) && isset($_FILES['postImage'])){
		$uploadedImgName = uploadImage('postImage');
		$postText = $_POST['postText'];
		if(!empty($uploadedImgName)){
			createPost($postText, $uploadedImgName, '');
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>TetramandoX</title>
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/homepage.css"/>
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/topMenu.css" >
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/post.inc.css" >
	<meta http-equiv="content-type" content="text/html" charset="UTF-8">
</head>
<body>
	<?php
		require 'topMenu.inc.php';
	?>
	<div class="mainContent">
		<div class="posts">
			<div class="createPost">
				<form method="POST"  name="postStatus" enctype="multipart/form-data" onsubmit="return createPost(this);">
					<textarea placeholder="Write something...." name="postText" rows="1"></textarea>
					<label>
						<input type="file" name="postImage" class="postImgUploadImg" onchange="uploadImg(this.parentNode.parentNode)" style="display:none">
						<img class="cameraPicture" src="http://localhost/TetramandoX/img/profile/camera.png">
					</label>
					<button class="removeImg" onclick="removeImage(this.parentNode);">Remove Image</button>
					<span class="pictureName"></span>
					<button class="timelinePostButton" type="submit" value="Post">Post</button>
				</form>
			</div>
			<div class="posts">
			<?php
				$allPostsArr = homepagePosts();
				for($i = 0; $i < 25; $i++){
					if(empty($allPostsArr[$i])) break;
					$currentPost = $allPostsArr[$i];
					if(in_array($currentPost['type'], array('L', 'H', 'C')))
						echo echoPost($currentPost, true);
					else
						echo echoPost($currentPost);	
				}
				if(count($allPostsArr) > $i){
					echo '<button class="showMorePosts" onclick="showMorePosts()">See More...</button>';
				}
				
			?>
			</div>
		</div>
		<?php if(!isPage(userID)) require 'suggest.inc.php'; ?>
	</div>
</body>
</html>