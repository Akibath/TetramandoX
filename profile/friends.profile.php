<?php
	if(!defined('friends_profile')){
		require 'pageNotFound.inc.php';
		exit();
	}
?>
<!DOCTYPE html>
<head>
	<title>Friends of <?php echo personName($personID, 'firstName'); ?></title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/friends.myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfileCard.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html" charset="UTF-8">
</head>
<div class="profileBody">
	<?php define('profileCard_inc', true); require 'profileCard.inc.php'; ?>
	<div class="profileContent">
		<div class="mainTitle">
			<span>Friends</span>
		</div>	
		<div class="allFriends">
			<div>
				<ul class="eachFriendBox">
		<?php
			$friendsArr = friendsList($personID);
			$allFriends = null;
			for($i=0; $i<count($friendsArr); $i++){
				$eachFriend = $friendsArr[$i];
				$personProfession = !empty(personProfession($eachFriend, true))? '<div>'.personProfession($eachFriend, true).'</div><br>': null;
				$personLocation = !empty(personLocation($eachFriend, true))? '<div>'.personLocation($eachFriend, true).'</div><br>': null;
				
				$totalMutualFriends = totalMutualFriends($eachFriend);
				if($totalMutualFriends > 1)
					$totalMutualFriends = '<div>'.$totalMutualFriends.' Mutual friends</div>';
				else if($totalMutualFriends > 0)
					$totalMutualFriends = '<div>'.$totalMutualFriends.' Mutual friend</div>';
				
				$allFriends .=
					'<li>
						<a href="'.profileURL($eachFriend).'">
							<div class="profilePic">
								<img src="'.profilePicURL($eachFriend, 128).'">	
							</div>
						<div class="personDetail">
							<div class="personName">'.personName($eachFriend, 'entireName', 41).'</div>
						</a><br>	
							<div class="personDesc">
								'.$personProfession.$personLocation.$totalMutualFriends.'
							</div>
						</div>
					</li>';
			}
			
			echo count($friendsArr) == 0 || !isViewPermission($personID, 'friendListPermission')? '<div class="noActivity">No activity to show.</div>': $allFriends;	
		?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php require './topMenu.inc.php'; ?>