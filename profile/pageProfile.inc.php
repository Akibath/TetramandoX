<?php
	define('profileCard_inc', true);
	if(isset($_POST['postText']) && isset($_FILES['postImage'])){
		$uploadedImgName = uploadImage('postImage');
		$postText = $_POST['postText'];
		if(!empty($uploadedImgName)){
			createPost($postText, $uploadedImgName, 'I');
			header('Location: http://localhost/TetramandoX/'.getField('userLogin', 'userName', $profileID));
		}
	}

	if(isset($_FILES['profilePic'])){
		$profilePicName = uploadImage('profilePic');
		$coverPicName = uploadImage('coverPic');
		
		if(!empty($profilePicName)){
			queryUpdate('personalinfo', 'profilePic', $profilePicName);
			createPost('', $profilePicName, 'P');
			header('Location: http://localhost/TetramandoX/'.getField('userLogin', 'userName', $profileID));
		} else if(!empty($coverPicName)){
			queryUpdate('personalinfo', 'coverPic', $coverPicName);
			createPost('', $coverPicName, 'C');
			header('Location: http://localhost/TetramandoX/'.getField('userLogin', 'userName', $profileID));
		}
	}

	$userLocation = personLocation($profileID, true);
	
	$category = '<li>'.getField('pageInfo', 'category', $personID).'</li>';
	$currentLocation_LI = !empty($userLocation)? '<li>Lives at <span style="font:bold 14px helvetica;">'.$userLocation.'</span></li>': null;
	$birthDate_LI = '<li>Created on '.personAge($profileID, true).'</li>';
	$about_LI = '<li>'.getField('pageInfo', 'about', $personID).'</li>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>1<?php echo personName($profileID, true, 30); ?> | TetramandoX</title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfileCard.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/post.inc.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
	<?php require 'topMenu.inc.php'; ?>
	<div class="profileBody">
		<?php require 'profileCard.inc.php'; ?>
		<div class="profileContent">
			<div class="profilePanes">
				<div class="profilePane about">
					<div class="title">
						<a href="<?php echo profileURL($profileID).'/about' ?>">About</a>
					</div>
					<ul>
						<?php echo $category.$currentLocation_LI.$birthDate_LI.$about_LI; ?>
					</ul>
				</div>
				<div class="profilePane photos">
					<div class="title">
						<a href="<?php echo profileURL($profileID).'/photos' ?>">Photos<span> (<?php echo count(photosList($profileID)); ?>)</span></a>
					</div>
					<div class="content">
						<?php
							$photosArr = photosList($profileID);
							$dirName = directoryName($profileID);
							if(count($photosArr) >0){
								for($i=0; $i<6; $i++){
									if(!empty($photosArr[$i])){
										$localPath = $dirName.'/thumbs/'.preg_replace('/\..+$/', '', $photosArr[$i]).'_92x92.'.end(explode('.', $photosArr[$i]));
										$postID = queryResult("SELECT postID FROM posts.$profileID WHERE image = '$photosArr[$i]' ");
										echo '<a href="http://localhost/TetramandoX/post.php?postID='.$postID.'"><img src="http://localhost/TetramandoX/userFiles/'.$localPath.'"></a>';
									}
								}
							} else{
								echo '<div class="noActivity">No activity to show.</div>';
							}
						 ?>

					</div>
				</div>
				<div class="profilePane friends">
					<div class="title">
						<a href="<?php echo profileURL($profileID).'/friends' ?>">Friends<span> (<?php echo count(friendsList($profileID)); ?>)</span></a>
					</div>
					<div class="content">
					<?php
						$friendsArr = friendsList($profileID);
						if(count($friendsArr) >0){
							for($i = 0; $i < 6; $i++){
								if(!empty($friendsArr[$i])){
									$profilePicURL = profilePicURL($friendsArr[$i], 256);
									echo '<a href="'.profileURL($friendsArr[$i]).'"><img src="'.$profilePicURL.'"></a>';
								}
							}
						} else{
							echo '<div class="noActivity">No activity to show.</div>';
						}

					?>

					</div>
				</div>
			</div>
			<div class="profileTimeline">
				<?php
					$postBox = 
						'<div class="createPost">
							<form method="POST" name="postStatus" enctype="multipart/form-data" onsubmit="return createPost(this);">
								<textarea placeholder="Write something...." name="postText" rows="1"></textarea>
								<label>
									<input type="file" name="postImage" class="postImgUploadImg" onchange="uploadImg(this.parentNode.parentNode)" style="display:none">
									<img class="cameraPicture" src="http://localhost/TetramandoX/img/profile/camera.png">
								</label>
								<button class="removeImg" onclick="removeImage(this.parentNode);">Remove Image</button>
								<span class="pictureName"></span>
								<button class="timelinePostButton" type="submit" value="Post">Post</button>
							</form>
						</div>';
					if($profileID == userID) echo $postBox;
				?>
				<div class="posts">
					<?php 
						$allPostsArr = timelinePosts($profileID);
						for($i = 0; $i < 25; $i++){
							if(empty($allPostsArr[$i])) break;
							echo echoPost($allPostsArr[$i]);
						}
						if(count($allPostsArr) > $i)
							echo '<button class="showMorePosts" onclick="showMorePosts()">See More...</button>';
						else if(!count($allPostsArr))
							echo '<div class="noActivity">No activity to show.</div>';
					?>
				</div>
			</div>
		</div>
	</div>

<script>
	function logOut(){
		window.location.href='logout.php';
	}
	// var newProfilePic = document.getElementById('uploadProfilePicFile');
 //    var submitNewprofilePic = document.getElementById('uploadprofilePicSubmit');
 //    //submitNewprofilePic.parentNode.removeChild(submitNewprofilePic);
 //    newProfilePic.onchange = function(){
 //        if (this.value!=='')
 //            this.form.submit();
 //    };
</script>