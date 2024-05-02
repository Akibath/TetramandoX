<?php
	if(!defined('about_profile')){
		require 'pageNotFound.inc.php';
		exit();
	}
?>
<!DOCTYPE html>
<head>
	<title>About <?php echo personName($personID, 'firstName'); ?></title>
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/topMenu.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/about.myProfile.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/TetramandoX/css/myProfileCard.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/myProfile.js"></script>
	<meta http-equiv="content-type" content="text/html" charset="UTF-8">
</head>
<div class="profileBody">
	<?php define('profileCard_inc', true); require 'profileCard.inc.php'; ?>
	<div class="profileContent">
		<div class="mainTitle">
			<span>About</span>
		</div>
		<ul>
		<?php
			$aboutPerson[] = personGender($personID, true);
			if(isViewPermission($personID, 'basicPrivacy')){
				$aboutPerson[] = personAge($personID, true);
				$aboutPerson[] = getField('personalinfo', 'mobileNumber', $personID);
				$aboutPerson[] = getField('personalinfo', 'secMail', $personID);
				$aboutPerson[] = personProfession($personID, true);
				$aboutPerson[] = personLocation($personID, true);
			}

			$aboutPersonTitle = array('Gender', 'Date of birth', 'Mobile Number', 'Email', 'Work', 'Lives in');

			for($i=0; $i<6; $i++){
				if(empty($aboutPerson[$i]))
					break;

				echo 
					'<li>
						<div class="title">'.$aboutPersonTitle[$i].'</div>
						<div class="content">'.$aboutPerson[$i].'</div>
					</li>'
				;
			}
		?>
		</ul>
	</div>
</div>
<?php
	require './topMenu.inc.php';
?>