<?php
	define('core_inc', true);
	require '../core.inc.php';
	//activateMail();
	
	if(isset($_GET['email']) && isset($_GET['activationCode']) && !empty($_GET['email']) && !empty($_GET['activationCode']) ){
			$mailID = filterVar($_GET['email']);
			$activationCode = filterVar($_GET['activationCode']);
			if(isEmail($mailID)){
				$userID = queryResult("SELECT id FROM userlogin WHERE email = '$mailID' ");
				if(queryResult("SELECT id FROM account_info WHERE mailActivationCode = '$activationCode' AND userID = $userID ") == 1){
					mysql_query("UPDATE account_info SET mailActivationCode = '' WHERE userID = $userID ");
					header('Location: http://localhost/TetramandoX/');
					exit();
				}
			}
			// if(mysql_query("UPDATE `accdetail` SET `mailActivate`=1 WHERE `id`='$userID' AND `mailActivate`='$activationCode'")){
			// 	header('Location: index.php');
			// 	exit();
			// }
	}
	
	if(isLogin()){
		if(queryResult("SELECT mailActivationCode FROM account_info WHERE userID = ".userID." ") != '')
			echo 'Hey Punk Please activate your email<a href=http://localhost/TetramandoX/>Remaind me later</a>';
		else
			header('Location: http://localhost/TetramandoX/');
	}
?>