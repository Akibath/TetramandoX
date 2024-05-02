<?php
	define('core_inc', true);
	define('topMenu_inc', true);
	require '../core.inc.php'; 
	$usernameUpdateInterval = time() - getField('settings', 'usernameChanged', userID);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Settings - TetramandoX</title>
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/topMenu.css">
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/settings.css">
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/settings.js"></script>
	<meta http-equiv="content-type" content="text/html" charset="UTF-8">
</head>
<body>
	<?php require '../topMenu.inc.php'; ?>
	<div class="settingsMainPane">
		<div class="settingsTitle">General Account Settings</div>
		<ul>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Name</div>
					<div class="eachSettingContent"><?php echo personName(userID, 'entireName', 30); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
				<?php
					$nameUpdateInterval = time() - getField('settings', 'nameChanged', userID);
					if($nameUpdateInterval > 60*60*24*7){
						$nameListArr = array("firstName", "middleName", "lastName", "nickName");
						$nameTitleArr = array("First", "Middle", "Last", "Nick");
						
						for($i = 0; $i<4; $i++){
							$nameMaxLength = $i == 3? 8: 32;
							echo '
								<div class="eachChangeBox">
									<div style="width:100px">'.$nameTitleArr[$i].' name:</div>
									<input type="text" onkeyup="isSettingAvail(this, \'Name\')" id="'.$nameListArr[$i].'" placeholder="'.personName(userID, $nameListArr[$i], null).'" maxlength='.$nameMaxLength.'>
									<span class="availStatus"></span>
								</div>
							';
						}
						echo '<div class="optionPaneLog">Note: You should wait for 7 days to change your name next time</div>';
					} else{
						echo '
							<div class="optionPaneLog alone">Note: You should wait for 7 days to change your name</div>
						';
					}

				?>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Username</div>
					<div class="eachSettingContent"><?php echo profileURL(userID); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
				<?php
					$usernameLastUpdated = getField('settings', 'usernameChanged', userID);
					$usernameUpdateInterval = time() - $usernameLastUpdated;
					if($usernameUpdateInterval < 60*60*24*30*2 || $usernameLastUpdated == 0){
						echo '
							<div class="eachChangeBox">
								<div style="text-align:right;padding-right:7px;line-height:19px;">Username:</div>
								<input type="text" onkeyup="isSettingAvail(this, \'Username\')" id="username" placeholder="'.getField('userlogin', 'username', userID).' "  maxlength="32">
								<span class="availStatus"></span>
							</div>
							<div class="optionPaneLog">Note: You should wait for 60 days to change your name next time</div>
						';
					} else
						echo '<div class="optionPaneLog alone">Note: You cant change your username at the moment</div>';
				?>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Email</div>
					<div class="eachSettingContent"><?php echo getField('userlogin', 'email'); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="eachChangeBox">
						<div style="text-align:right;padding-right:7px;line-height:19px;">Email ID:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'Email')" id="email" placeholder="<?php echo getField('userlogin', 'email'); ?>"  maxlength="128">
						<span class="availStatus"></span>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Password</div>
					<div class="eachSettingContent">
					<?php
						$passwordChangedTime = getField('account_info', 'passwordChanged');
						echo 'Last Updated - '.time2String($passwordChangedTime);

					?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="eachChangeBox">
						<div style="width:150px;text-align:left;padding-right:7px;line-height:19px;">Current Password:</div>
						<input type="password" onkeyup="isSettingAvail(this, 'password')" id="currentPassword" placeholder="●●●●●●●●●●●●●" maxlength="64">
						<span class="availStatus"></span>
					</div>
					<div class="eachChangeBox">
						<div style="width:150px;text-align:left;padding-right:7px;line-height:19px;">New Password:</div>
						<input type="password" onkeyup="isSettingAvail(this, 'password')" id="newPassword" maxlength="64">
						<span class="availStatus"></span>
					</div>
					<div class="eachChangeBox">
						<div style="width:150px;text-align:left;padding-right:7px;line-height:19px;">New Password again:</div>
						<input type="password" onkeyup="isSettingAvail(this, 'password')" id="newPasswordAgain" maxlength="64">
						<span class="availStatus"></span>
					</div>
					<div class="optionPaneLog"></div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Login Notification</div>
					<div class="eachSettingContent">Get notification when someone logs into your account</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<?php
						$loginNotif = getField('settings', 'loginNotif');
						if($loginNotif == 't'){
							$loginNotif_On_isChecked = 'checked="checked"';
							$loginNotif_On_onChange_ID = "'notFocus'";
							
							$loginNotif_Off_isChecked = '';
							$loginNotif_Off_onChange_ID = "''";
						
						} else if($loginNotif == 'f'){
							$loginNotif_Off_isChecked = 'checked="checked"';
							$loginNotif_Off_onChange_ID = "'notFocus'";
							
							$loginNotif_On_isChecked = '';
							$loginNotif_On_onChange_ID = "''";
						}

						$loginNotif_onChange = 'onChange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id = ';
						$loginNotif_onChange_END = '" ';
						$loginNotif_On_onChange = $loginNotif_onChange.$loginNotif_On_onChange_ID.$loginNotif_onChange_END;
						$loginNotif_Off_onChange = $loginNotif_onChange.$loginNotif_Off_onChange_ID.$loginNotif_onChange_END;


					?>
					<div class="radios">
						<input type="radio" class="radio loginNotificationRadio" id="loginNotificationOn" name="loginNotificationRadio" value="1" <?php echo $loginNotif_On_onChange.$loginNotif_On_isChecked; ?> >
							<label for="loginNotificationOn">Turn On</label>
						<input type="radio" class="radio loginNotificationRadio" id="loginNotificationOff" name="loginNotificationRadio" value="0" <?php echo $loginNotif_Off_onChange.$loginNotif_Off_isChecked?> >
							<label for="loginNotificationOff">Turn Off</label>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
		</ul>
		<div class="settingsTitle" style="margin-top:30px;">Basic Information Settings</div>
		<ul>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Gender</div>
					<div class="eachSettingContent"><?php echo personGender(userID)? 'Male': 'Female'; ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<?php
						$checkedMale = null;
						$checkedFemale = null;
						personGender(userID)? $checkedMale = 'checked': $checkedFemale = 'checked';
					?>
					<div class="radios">
						<input type="radio" class="radio genderRadio" id="genderRadioM" name="genderRadio" value="m" onchange="this.parentNode.parentNode.parentNode.querySelector('.saveChangeBut').id= ''" <?php echo $checkedMale; ?> >
							<label for="genderRadioM">Male</label>
						<input type="radio" class="radio genderRadio" id="genderRadioF" name="genderRadio" value="f" onchange="this.parentNode.parentNode.parentNode.querySelector('.saveChangeBut').id= ''" <?php echo $checkedFemale; ?> >
							<label for="genderRadioF">Female</label>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="saveChangeBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Date of Birth</div>
					<div class="eachSettingContent"><?php echo personAge(userID, true); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					Date: <input type="text" placeholder="<?php echo personAge(userID, false)[0] ?>" maxlength=2 style="width:25px;">
					<span style="padding-left:10px">Month:</span>
						<select class="DOBMonth">
							<option value="0">Select</option>
							<option value="1">January</option>
							<option value="2">February</option>
							<option value="3">March</option>
							<option value="4">April</option>
							<option value="5">May</option>
							<option value="6">June</option>
							<option value="7">July</option>
							<option value="8">August</option>
							<option value="9">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					<span style="padding-left:15px">Year:</span>
						<input type="text" placeholder="<?php echo personAge(userID, false)[2] ?>" maxlength=4 style="width:35px">
					<span class="availStatus"></span>
					<div class="buttons">
						<button class="saveChangeBut" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Mobile Number</div>
					<div class="eachSettingContent"><?php echo getField('personalinfo', 'mobileNumber'); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
						Mobile Number: <input type="text" onkeyup="isSettingAvail(this, 'mobileNum')" placeholder="<?php echo getField('personalinfo', 'mobileNumber'); ?>" maxlength=10>
						<span class="availStatus"></span>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Secondary Email</div>
					<div class="eachSettingContent"><?php echo getField('personalinfo', 'secMail'); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					Email ID: <input type="text" onkeyup="isSettingAvail(this, 'secondaryMail')" id="" placeholder="<?php echo getField('userlogin', 'email'); ?>" maxlength=128>
					<span class="availStatus"></span>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Current Position</div>
					<div class="eachSettingContent"><?php echo personLocation(userID, true); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="eachChangeBox">
						<div style="width:60px;text-align:left;padding-right:7px;line-height:19px;">City:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'currentPosition')" placeholder="<?php echo personLocation()[0]; ?>" maxlength="32">
						<span class="availStatus"></span>
					</div>
					<div class="eachChangeBox">
						<div style="width:60px;text-align:left;padding-right:7px;line-height:19px;">State:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'currentPosition')" placeholder="<?php echo personLocation()[1]; ?>" maxlength="32">
						<span class="availStatus"></span>
					</div>
					<div class="eachChangeBox">
						<div style="width:60px;text-align:left;padding-right:7px;line-height:19px;">Country:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'currentPosition')" placeholder="<?php echo personLocation()[2]; ?>" maxlength="32">
						<span class="availStatus"></span>
					</div>
					<div class="optionPaneLog"></div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Work</div>
					<div class="eachSettingContent"><?php echo personProfession(userID, true); ?></div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="eachChangeBox">
						<div style="width:80px;text-align:left;padding-right:7px;line-height:19px;">Position:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'workStatus')" id="currentPassword" placeholder="<?php echo personProfession()[0]; ?>" maxlength="32">
						<span class="availStatus"></span>
					</div>
					<div class="eachChangeBox">
						<div style="width:80px;text-align:left;padding-right:7px;line-height:19px;">Company:</div>
						<input type="text" onkeyup="isSettingAvail(this, 'workStatus')" id="newPassword" placeholder="<?php echo personProfession()[1]; ?>" maxlength="32">
						<span class="availStatus"></span>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
		</ul>
		<div class="settingsTitle" style="margin-top:30px;">Account Privacy Settings</div>
		<ul>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Basic Privacy</div>
					<div class="eachSettingContent">Who can see basic informations about me (Eg: Mobile number, City)</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="radios">
					<?php
						$timelinePermission = getField('settings', 'timelinePermission', userID);
						$permissionTypeArr = array('Only Me', 'Friends', 'Friends and Mutual Friends', 'Everyone');
						$permissionInitArr = array('o', 'f', 'm', 'e');
						for($i=0; $i<4;$i++){
							$id = 'basicPermission'.strtoupper($permissionInitArr[$i]);
							$value = $permissionInitArr[$i];
							$checked = $timelinePermission == $permissionInitArr[$i]? 'checked ': '';
							
							echo '
								<input type="radio" class="radio basicPrivacyRadio" id="'.$id.'" name="timelinePermission" value="'.$value.'" '.$checked.' onchange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id= \'\'">
									<label for="'.$id.'">'.$permissionTypeArr[$i].'</label>
							';
						}


					?>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Timeline Privacy</div>
					<div class="eachSettingContent">Who can see posts on my timeline</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="radios">
					<?php
						$timelinePermission = getField('settings', 'timelinePermission', userID);
						$permissionTypeArr = array('Everyone', 'Only Me', 'Friends', 'Friends and Mutual Friends');
						$permissionInitArr = array('e', 'o', 'f', 'm');
						for($i=0; $i<4;$i++){
							$id = 'timelinePer'.strtoupper($permissionInitArr[$i]);
							$value = $permissionInitArr[$i];
							$checked = $timelinePermission == $permissionInitArr[$i]? 'checked ': '';
							
							echo '
								<input type="radio" class="radio timelinePermissionRadio" id="'.$id.'" name="timelinePermission" value="'.$value.'" '.$checked.' onchange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id= \'\'">
									<label for="'.$id.'">'.$permissionTypeArr[$i].'</label>
							';
						}


					?>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Request Permission</div>
					<div class="eachSettingContent">Who can send me friend request</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="radios">
					<?php
						$currentSetting = getField('settings', 'reqPermission', userID);
						$reqPermissionTypes = array('Everyone', 'Friends of friends');
						$reqPermissionInit = array('e', 'm');
						for($i=0;$i<count($reqPermissionTypes); $i++){
							$ID = 'requestPermission'.strtoupper($reqPermissionInit[$i]);
							$checked = $currentSetting == $reqPermissionInit[$i]? 'checked': '';
							echo '
								<input type="radio" class="radio requestPermissionRadio" id="'.$ID.'" name="requestPermissionRadio" value="'.$reqPermissionInit[$i].'" onchange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id= \'\'" '.$checked.'>
								<label for="'.$ID.'">'.$reqPermissionTypes[$i].'</label>
							';
						}
					?>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Chat Privacy</div>
					<div class="eachSettingContent">Who can send me messages</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="radios">
					<?php
						$currentSetting = getField('settings', 'chatPermission', userID);
						$chatPermissionTypes = array('Everyone', 'Friends and Mutual Friends', 'Friends');
						$chatPermissionInit = array('e', 'm', 'f');
						for($i=0;$i<count($chatPermissionTypes); $i++){
							$ID = 'chatPermission'.strtoupper($chatPermissionInit[$i]);
							$checked = $currentSetting == $chatPermissionInit[$i]? 'checked': '';

							echo '
								<input type="radio" class="radio chatPermissionRadio" id="'.$ID.'" name="chatPermissionRadio" value="'.$chatPermissionInit[$i].'" onchange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id= \'\'" '.$checked.'>
								<label for="'.$ID.'">'.$chatPermissionTypes[$i].'</label>
							';
						}
					?>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Post Promotion</div>
					<div class="eachSettingContent">Who like, hate, comment, share my posts</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<div class="radios">
					<?php
						$currentSetting = getField('settings', 'postPromotPermission', userID);
						$chatPermissionTypes = array('Everyone', 'Friends and Mutual Friends', 'Friends');
						$chatPermissionInit = array('e', 'm', 'f');
						for($i=0;$i<count($chatPermissionTypes); $i++){
							$ID = 'promotePermission'.strtoupper($chatPermissionInit[$i]);
							$checked = $currentSetting == $chatPermissionInit[$i]? 'checked': '';

							echo '
								<input type="radio" class="radio promotePermissionRadio" id="'.$ID.'" name="promotePermissionRadio" value="'.$chatPermissionInit[$i].'" onchange="this.parentNode.parentNode.parentNode.querySelector(\'.saveChangeBut\').id= \'\'" '.$checked.'>
								<label for="'.$ID.'">'.$chatPermissionTypes[$i].'</label>
							';
						}
					?>
					</div>
					<div class="buttons">
						<button class="saveChangeBut" id="notFocus" onclick="saveChange(this)">Save</button>
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
		</ul>
	
		<div class="settingsTitle" style="margin-top:30px;">Restricted Users</div>
		<ul>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Spammed Users</div>
					<div class="eachSettingContent">These users cant send messages, friend request to you</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
					<?php
						$spamUsersQ = mysql_query("SELECT id, personID FROM spam_block WHERE userID = ".userID." AND type = 's' ORDER BY id DESC");
						if(mysql_num_rows($spamUsersQ) > 0){
							while($spamUser = mysql_fetch_assoc($spamUsersQ)){
								$personID = $spamUser['personID'];
								$personName = personName($personID, 'entireName', 50);
								$profilePicURL = profilePicURL($personID, 32);
								echo '
									<div class="spammedUsers" id="spamPerson_'.$personID.'">
										<div class="profilePic"><img src="'.$profilePicURL.'"></div>
										<div class="personName"><a href="'.profileURL($personID).'">'.$personName.'</a></div>
										<div class="unSpam" onclick="undoSpam('.$personID.');">Remove</div>
									</div>
								';


							}
						} else{
							echo '
								<div class="spammedUsers noSpam">
									<div class="personName">No spam users</div>
								</div>
							';
						}
					?>

					<div class="buttons">
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
			<li id="hover">
				<div class="eachSettingBar" onclick="showOptionPane(this.parentNode)">
					<div class="eachSettingTitle">Blocked Users</div>
					<div class="eachSettingContent">Who can see me</div>
					<div class="eachSettingStatus">Edit</div>
				</div>
				<div class="eachSettingOptionPane hide">
				<?php
					$blockedUsersQ = mysql_query("SELECT id, personID FROM spam_block WHERE userID = ".userID." AND type = 'b' ORDER BY id DESC");
					if(mysql_num_rows($blockedUsersQ) > 0){
						while($spamUser = mysql_fetch_assoc($blockedUsersQ)){
							$personID = $spamUser['personID'];
							$time = $spamUser['time'];
							$personName = personName($personID, 'entireName', 40);
							$profilePicURL = profilePicURL($personID, 32);
							echo '
								<div class="blockedUsers" id="blockPerson_'.$personID.'">
									<div class="profilePic"><img src="'.$profilePicURL.'"></div>
									<div class="personName">'.$personName.'</div>
									<div class="unBlock" onclick="unblockPerson('.$personID.');">Unblock</div>
								</div>
							';
						}
					} else {
						echo '
								<div class="spammedUsers noSpam">
									<div class="personName">No blocked users</div>
								</div>
							';
					}
				?>
					<div class="buttons">
						<button class="closeOptionPaneBut" onclick="hideOptionPane(this.parentNode.parentNode.parentNode)">Cancel</button>
					</div>
				</div>
			</li>
		</ul>
	</div>
</body>
</html>
