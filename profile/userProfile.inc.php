<?php
	//require 'getProfileInfo.inc.php';
	//require 'userDetail.inc.php';;
	if(isset($_POST['postText']) && isset($_FILES['postImage'])){
		$uploadedImgName = uploadImage('postImage');
		$postText = $_POST['postText'];
		if(!empty($uploadedImgName)){
			createPost($postText, $uploadedImgName, 'i');
		}
	}

	if(isset($_FILES['profilePic'])){
		$profilePicName = uploadImage('profilePic');
		$coverPicName = uploadImage('coverPic');
		
		if(!empty($profilePicName)){
			queryUpdate('personalinfo', 'profilePic', $profilePicName);
			createPost('', $profilePicName, 'p');
		}
		else if(!empty($coverPicName)){
			queryUpdate('personalinfo', 'coverPic', $coverPicName);
			createPost('', $coverPicName, 'c');
		}
	}

	$profilePane_about = array();
	
	if(isViewPermission($personID, 'basicPrivacy')){
		$userLocation = personLocation($personID, true);
		$userProfession = personProfession($personID);

		if(!empty($userProfession))
			$profilePane_about[] = $userProfession[0].' at <span style="font:bold 14px helvetica;">'.$userProfession[1].'</span>';
		
		if(!empty($userLocation))
			$profilePane_about[] = 'Lives at <span style="font:bold 14px helvetica;">'.$userLocation.'</span>';
		
		$profilePane_about[] = personAge($personID).' years old';
		$profilePane_about[] = personGender($personID, true);
	}
	
	if($personID != userID && isViewPermission($personID, 'friendListPermission') && totalMutualFriends($personID)>0)
		$profilePane_about[] = totalMutualFriends($personID).(totalMutualFriends($personID) == 1? ' mutual friend': ' mutual friends');

	if($personID != userID && isFriend($personID)){
		$friendsSince = queryResult("SELECT time FROM friends WHERE (userOne = ".userID." AND userTwo = $personID) OR (userOne = $personID AND userTwo = ".userID.") ");
		$profilePane_about[] = 'Friends since '.time2String($friendsSince, true);
	}

	
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo personName($personID, 'fullName', 30); ?> | TetramandoX</title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfileCard.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/post.inc.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<script>
		function acceptFriendRequest(){
			
		}
	</script>
</head>
	
	<div class="profileBody">
		<?php require 'myProfileCard.inc.php'; ?>
		<div class="profileContent">
			<div class="profilePanes">
				<div class="profilePane about">
					<div class="title">
						<a href="<?php echo profileURL($personID).'/about' ?>">About</a>
					</div>
					<ul>
						<?php
							// for($i=0; $i<10; $i++){
							// 	if(empty($profilePane_about[$i]))
							// 		break;

							// 		echo '<li>'.$profilePane_about[$i].'</li>';
							// }
						
							// echo count($profilePane_about) == 0? '<div class="noActivity">No activity to show.</div>': null;
							
						?>
					</ul>
				</div>
				<div class="profilePane photos">
					<div class="title">
						<a href="<?php echo profileURL($personID).'/photos' ?>">Photos<span> (<?php echo count(photosList($personID)); ?>)</span></a>
					</div>
					<div class="content">
						<?php
							// $photosArr = photosList($personID);
							// for($i=0; $i<6; $i++){
							// 	if(empty($photosArr[$i]))
							// 		break;

							// 	$localPath = getFilesDir($personID).'/'.$photosArr[$i];
							// 	$postID = queryResult("SELECT postID FROM posts.$personID WHERE image = '$photosArr[$i]' ");
								
							// 	echo '<a href="http://localhost/TetramandoX/post.php?postID='.$postID.'"><img src="http://localhost/TetramandoX/userFiles/'.$localPath.'"></a>';
							// }
							
							// echo count($photosArr) == 0? '<div class="noActivity">No activity to show.</div>': null;
							
						 ?>
					</div>
				</div>
				<div class="profilePane friends">
					<div class="title">
						<a href="<?php echo profileURL($personID).'/friends' ?>">Friends<span> (<?php echo count(friendsList($personID)); ?>)</span></a>
					</div>
					<div class="content">
					<?php
						// $friendsArr = friendsList($personID);
						// for($i=0; $i<6; $i++){
						// 	if(empty($friendsArr[$i]))
						// 		break;

						// 	echo '<a href="'.profileURL($friendsArr[$i]).'"><img src="'.profilePicURL($friendsArr[$i], 256).'"></a>';
						// }
						
						// echo count($friendsArr) == 0? '<div class="noActivity">No activity to show.</div>': null;
					?>

					</div>
				</div>
			</div>
			<div class="profileTimeline">
				<div class="posts">
					<?php 
						// $allPostsArr = timelinePosts($personID);
						// for($i=0; $i<25; $i++){
						// 	if(empty($allPostsArr[$i]))
						// 		break;

						// 	$currentPost = $allPostsArr[$i];
						// 	$postPromotes_className = totalComments($currentPost)<=6? 'show': 'hide';
						// 	$postProperties = echoPost($currentPost);
						// 	$post_HTML = $postProperties[0].$postProperties[1].$postProperties[2].'<div class="commentArea '.$postPromotes_className.'">'.$postProperties[3].'</div>';
							
						// 	echo '<div id="'.$currentPost.'" class="post">'.$post_HTML.'</div>';
						// }

						// if(count($allPostsArr) == 0)
						// 	echo '<div class="noActivity">No activity to show.</div>';
						// else if(count($allPostsArr)>$i)
						// 	echo '<button class="showMorePosts" onclick="showMorePosts()">See More...</button>';
					?>
				</div>
			</div>
		</div>
	</div>
	<?php require 'topMenu.inc.php'; ?>

<script>
	function logOut(){
		window.location.href = 'logout.php';
	}
</script>