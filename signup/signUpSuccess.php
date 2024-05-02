<?php
	require 'core.inc.php';
	require 'connect.inc.php';
	login2Continue();
	if(isset($_GET['login'])){
		loggedIn($_SESSION['userID']);
	}
	$firstName = getField('userinfo', 'firstName');
	$lastName = getField('userinfo', 'lastName');
	$name = $firstName.' '.$lastName;
?>
<!DOCTYPE html>
<title>Login to your account - <?php echo $name; ?></title>
Hello <?php echo $firstName; ?> welcome to TetramandoX! <a href=?login>Click here to login to your account</a>