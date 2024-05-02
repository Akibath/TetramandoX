<?php
	if(isset($_POST['newPassword'])&&isset($_POST['confirmNewPassword'])){
		$newPassword = $_POST['newPassword'];
		$confirmNewPassword = $_POST['confirmNewPassword'];
		if(!empty($newPassword)&&!empty($confirmNewPassword)){
			if($newPassword===$confirmNewPassword){
				if(strlen($newPassword)>5){
					if(strlen($newPassword)<65){
						$newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT, array('cost'=>'12'));
						$currentPasswordHash = mysql_result(mysql_query("SELECT `password` FROM `userlogin` WHERE `id`='$rcsuserID'"), 0);
						$lastPasswordHash = mysql_result(mysql_query("SELECT `lastPassword` FROM `passwordchanged` WHERE `id`='$rcsuserID'"), 0);
						$qsetOldPasswordHash = "UPDATE `passwordchanged` SET `oldPassword`='$lastPasswordHash' WHERE `id`='$rcsuserID'";
						$qsetLastPasswordHash = "UPDATE `passwordchanged` SET `lastPassword`='$currentPasswordHash' WHERE `id`='$rcsuserID'";
						$qsetNewPasswordHash = "UPDATE `userlogin` SET `password`='$newPasswordHash' WHERE `id`='$rcsuserID'";
						$qclearPasswordResetCode = "UPDATE `passwordchanged` SET `passwordResetCode`='' WHERE `id`='$rcsuserID'";
						if(!empty($lastPasswordHash)){
							if(mysql_query($qsetOldPasswordHash)&&mysql_query($qsetLastPasswordHash)&&mysql_query($qsetNewPasswordHash)){
								mysql_query($qclearPasswordResetCode);
								getLogin($rcsUsername, $newPassword);
							}
						} elseif(empty($lastPasswordHash)){
							if(mysql_query($qsetLastPasswordHash)&&mysql_query($qsetNewPasswordHash)){
								mysql_query($qclearPasswordResetCode);
								getLogin($rcsUsername, $newPassword);
							}
						}

					} else
						echo 'Password must be lower than 65 characters.';
				} else
					echo 'Password must be 6 characters.';
			} else
				echo 'Passwords doesn\'t match';
		}
	}
?>
<form method=POST>
	New passsword: <input type=password name=newPassword><br><br>
	Confirm new password: <input type=password name=confirmNewPassword><br><br>
	<input type=submit value="Change password">
</form>