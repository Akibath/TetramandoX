<form method=POST>
	Email address: <input type=text name=rcMail>
	<input type=submit value=Recover>
</form>
<?php
	if(isset($_POST['rcMail'])===true&&empty($_POST['rcMail'])===false){
		$rcMail  = $_POST['rcMail'];
		forgotUsername($rcMail);
	}
?>