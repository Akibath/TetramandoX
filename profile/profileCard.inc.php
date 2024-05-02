<?php
	if(!defined('profileCard_inc')){ require 'pageNotFound.inc.php'; exit; }
	$coverPicName = getField('personalinfo', 'coverPic', $personID);
	if(!empty($coverPicName)){
		$coverPic = 
				'<a href="http://localhost/TetramandoX/post.php?postID='.
					$personID.'_'.queryResult("SELECT id FROM posts.".$personID." WHERE image = '$coverPicName' ").'">
					<img src="'.coverPicURL($personID, true).'" class="coverPic">
				</a>';
	} else
		$coverPic = '<img src="'.coverPicURL($personID, true).'" class="coverPic">';

	$profilePicName = getField('personalinfo', 'profilePic', $personID);
	if(!empty($profilePicName)){
		$profilePic = 
				'<a href="http://localhost/TetramandoX/post.php?postID='.$personID.'_'.queryResult("SELECT id FROM posts.".$personID." WHERE image = '$profilePicName' ").'">
					<img src="'.profilePicURL($personID).'" class="profilePic">
				</a>';
	} else
		$profilePic = '<img src="'.profilePicURL($personID).'" class="profilePic">';

?>
<div class="profileCard">
	<?php
		if($profileID == userID){
			echo 
				'<form method="POST" name="postStatus" enctype="multipart/form-data" onsubmit="return createPost(this);">'.$coverPic.
					'<label>
						<input type="file" name="coverPic" class="postImgUploadImg" onchange="this.parentNode.parentNode.submit();" style="display:none">
						<img src="http://localhost/TetramandoX/img/profile/camera.png" class="changeCoverPic">
					</label>
					<div class="coverPicLinear"></div>
					'.$profilePic.'<label>
						<input type="file" name="profilePic" class="postImgUploadImg" onchange="this.parentNode.parentNode.submit();" style="display:none">
						<img src="http://localhost/TetramandoX/img/profile/camera.png" class="changeProfilePic">
					</label>
					<div class="personName">'.personName($personID, true, 32).'</div>
				</form>
			';
		} else{
			$className = userID == $personID? 'personName': 'personName split';
			$button = personRelationButton($profileID);
			echo $coverPic.'<div class="coverPicLinear"></div>'.$profilePic.'
				<div class="'.$className.'">'.personName($personID, true, 32).'</div>
				<div class="profileCardButtons">'.$button.'</div>'
			;
		}
	?>
<div class="subProfiles <?php echo isPage($personID)? 'page': ''; ?>">
	<ul>
	<?php
		$currentFile = strtolower(end(explode('/', $_SERVER['REQUEST_URI'])));
		$allFiles =  isPage($personID)? array('profile', 'about', 'photos', 'invite',  'related'): array('profile', 'about', 'photos', 'friends', 'likes');
		for($i = 0; $i < count($allFiles); $i++){
			$link = $allFiles[$i] == 'profile'? profileURL($personID): profileURL($personID).'/'.$allFiles[$i];
			if($allFiles[$i] == $currentFile || $i == 0 && $currentFile == strtolower(getField('userlogin', 'username', $personID)))
				echo '<a><li class="active">'.ucfirst($allFiles[$i]).'</li></a>';
			else
				echo '<a href="'.$link.'"><li>'.ucfirst($allFiles[$i]).'</li></a>';
		}
	?>
		<li class="settingsGear"><img src="http://localhost/TetramandoX/img/settings_gear.png">
			<div class="dropdownMenu">
				<ul>
					<li onclick="reportSpam(<?php echo $personID; ?>);">Report as spam</li>
					<li onclick="blockPerson(<?php echo $personID; ?>);">Block</li>
				</ul>
			</div>
		</li>
	</ul>
</div>
</div>