<?php
	require 'connect.inc.php';
	require 'core.inc.php';
	require 'userDetail.inc.php';
	login2Continue();
	if(isset($_POST['currentPassword'])&&isset($_POST['newPassword'])&&isset($_POST['newPasswordAgain'])){
		$currentPassword = $_POST['currentPassword'];
		$newPassword = $_POST['newPassword'];
		$newPasswordAgain = $_POST['newPasswordAgain'];
		if(!empty($currentPassword)&&!empty($newPassword)&&!empty($newPasswordAgain)){
			if($newPassword == $newPasswordAgain){
				changePassword($currentPassword, $newPassword);
			} else
				$changePasswordLog = 'Passwords doesn\'t match';
		}
	}
	if(isset($_POST['changeUsername'])&&isset($_POST['changeFirstname'])&&isset($_POST['changeLastname'])&&isset($_POST['changeEmail'])){
		$newUsername = htmlentities(strip_tags(trim($_POST['changeUsername'])));
		$newFirstname = htmlentities(strip_tags(ucfirst(strtolower(trim($_POST['changeFirstname'])))));
		$newLastname = htmlentities(strip_tags(ucfirst(strtolower(trim($_POST['changeLastname'])))));
		$newEmail = htmlentities(strip_tags(strtolower(trim($_POST['changeEmail']))));
		if(!empty($newUsername)&&!empty($newFirstname)&&!empty($newLastname)&&!empty($newEmail)){
			updateUserInfo($newUsername, $newFirstname, $newLastname, $newEmail, $username, $primaryeMail);

		}
	}
	if(isset($_POST['unblockPerson'])&&!empty($_POST['unblockPerson'])){
		$unblockPerson = mysql_real_escape_string(htmlentities($_POST['unblockPerson']));
		unblockPerson($unblockPerson);
	}
?>
<!DOCTYPE html>
<title>Account Settings</title>
<a href=index.php>Home</a>
<a href=logout.php>Log out</a>
<form method=POST id="changePassword">
	Current password: <input type=password name=currentPassword maxlength=40><br><br>
	New password: <input type=password name=newPassword maxlength=40><br><br>
	Confim new password: <input type=password name=newPasswordAgain maxlength=40><br><br>
	<input type=submit value="Change Password">
</form>
<form method=POST id="changeUserDetails">
	User name: www.iltana.com/ 	<input type=text name=changeUsername value="<?php if(!empty($newUsername)) echo $newUsername; else echo $username; ?>" maxlength=32 required><br><br>
	First name: <input type=text name=changeFirstname value="<?php if(!empty($newFirstname)) echo $newFirstname; else echo $firstName; ?>" maxlength=32 required><br><br>
	Last name: <input type=text name=changeLastname value="<?php if(!empty($newLastname)) echo $newLastname; else echo $lastName; ?>" maxlength=32><br><br>
	Primary Email: <input type=email name=changeEmail value="<?php if(!empty($newEmail)) echo $newEmail; else echo $primaryeMail; ?>" maxlength=64 required><br><br>
	<input type=submit value="Save Changes">
</form>
<div id="blockedPersons">

	<?php listBlockedPersons(); ?>
</div>
<?php
	if(!empty($updateUserInfoLog)){
		if($updateUserInfoLog===0)
			$updateUserInfoLog = 'Sorry, Something went wrong, Please try again later.';
		elseif($updateUserInfoLog===1)
			$updateUserInfoLog = 'Changes saved successfully.';
		echo $updateUserInfoLog;
	}
?>
<style>
	#changePassword{
		border:1px solid #bbb;
		width:400px;
		padding:20px;
	}
	#changeUserDetails{
		margin:-200px 450px;
		border:1px solid #bbb;
		width:400px;
		padding:20px;
	}
	#blockedPersons{
		margin-top:200px;
		padding:20px 20px 0 20px;
		border:1px solid #aaa;
		width:500px;
	}
	#blockedPersonName{
		padding-left:10px;
		margin-top:20px;
		position:absolute;
	}
	#blockedPersonsListIn img{
		border:1px solid#ddd;
		width:60px;
		height:60px;
	}
</style>