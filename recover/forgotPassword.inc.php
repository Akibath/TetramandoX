<?php
	if(isset($_POST['rcUsername'])&&!empty($_POST['rcUsername'])){
		$rcUsername = $_POST['rcUsername'];
		forgotPassword($rcUsername);
	} else { 
		?><form method=POST>Username: <input type=text name=rcUsername><input type=submit value=recover></form><?php
	}
?>