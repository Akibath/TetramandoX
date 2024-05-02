<?php
	require 'core.inc.php';
	if(isset($_POST['regUsername']) && isset($_POST['regPassword']) && isset($_POST['regPasswordAgain']) && isset($_POST['regFirstname']) && isset($_POST['regLastname']) && isset($_POST['regeMail'])){
		$regUsername = $_POST['regUsername'];
		$regPassword = $_POST['regPassword'];
		$regPasswordAgain = $_POST['regPasswordAgain'];
		$regFirstname = $_POST['regFirstname'];
		$regLastname = $_POST['regLastname'];
		$regeMail = $_POST['regeMail'];
		if (!empty($regUsername)&&!empty($regPassword)&&!empty($regPasswordAgain)&&!empty($regFirstname)&&!empty($regLastname)&&!empty($regeMail)) {
			signUp($regUsername, $regPassword, $regPasswordAgain, $regFirstname, $regLastname, $regeMail);
		}
	}
?>
<!DOCTYPE html>
