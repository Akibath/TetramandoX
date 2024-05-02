 <?php
	if (isset($_POST['usernameGiven'])&&isset($_POST['passwordGiven'])) {
		$usernameGiven = $_POST['usernameGiven'];
		$passwordGiven = $_POST['passwordGiven'];
		if(!empty($usernameGiven)&&!empty($passwordGiven)){
			getlogin($usernameGiven, $passwordGiven);
		} else 
			 $loginErr = 'Incorrect username or password';
	}

	if(isset($_POST['regUsername']) && isset($_POST['regPassword']) && isset($_POST['regPasswordAgain']) && isset($_POST['regFirstname']) && isset($_POST['regLastname']) && isset($_POST['regeMail'])&&isset($_POST['gender'])&&isset($_POST['getDobMonth'])&&isset($_POST['getDobDate'])&&isset($_POST['getDobYear'])&&isset($_POST['captchaInput'])&&!empty($_POST['captchaInput'])){
		$captchaInput = $_POST['captchaInput'];
		$regUsername = htmlentities(strip_tags(trim($_POST['regUsername'])));
		$regPassword = $_POST['regPassword'];
		$regPasswordAgain = $_POST['regPasswordAgain'];
		$regFirstname = htmlentities(strip_tags(trim(ucfirst(strtolower($_POST['regFirstname'])))));
		$regLastname = htmlentities(strip_tags(trim(ucfirst(strtolower($_POST['regLastname'])))));
		$regeMail = htmlentities(strip_tags(trim(strtolower($_POST['regeMail']))));
		$regGender = htmlentities(strip_tags(strtolower($_POST['gender'])));
		$getDobMonth = htmlentities(strip_tags(trim($_POST['getDobMonth'])));
		$getDobDate = htmlentities(strip_tags(trim($_POST['getDobDate'])));
		$getDobYear = htmlentities(strip_tags(trim($_POST['getDobYear'])));
		if(strlen($captchaInput)>3&&strlen($captchaInput)<6&&$captchaInput>999&&$captchaInput<100000){
			if($_SESSION['captchaRand']==$captchaInput){
				if (!empty($regUsername)&&!empty($regPassword)&&!empty($regPasswordAgain)&&!empty($regFirstname)&&!empty($regLastname)&&!empty($regeMail)&&!empty($regGender)&&!empty($getDobMonth)&&!empty($getDobDate)&&!empty($getDobYear)) {
					signUp($regUsername, $regPassword, $regPasswordAgain, $regFirstname, $regLastname, $regeMail, $regGender, $getDobMonth, $getDobDate, $getDobYear);
				} else
					$_SESSION['captchaRand'] = rand(1000, 99999);
			} else {
				$regErr = 'Incorrect captcha, Try again';
				$_SESSION['captchaRand'] = rand(1000, 99999);
			}
		} else {
			$regErr = 'Incorrect captcha, Try again';
			$_SESSION['captchaRand'] = rand(1000, 99999);
		}
	} else
		$_SESSION['captchaRand'] = rand(1000, 99999);
?>
<!DOCTYPE html>
<head>
<title>Welcome to TetramandoX</title>
<script type="text/javascript">
	function loadAjax(){
		if(window.XMLHttpRequest)
			xmlhttp = new XMLHttpRequest;
		else
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		xmlhttp.onreadystatechange = function(){
			if(xmlhttp.readyState==4&&xmlhttp.status==200)
				document.getElementById('usernameCheck').innerHTML = xmlhttp.responseText;
		}
		xmlhttp.open('GET', 'ajax/main.php?regUsername='+document.signupField.regUsername.value, true);
		xmlhttp.send();
	}
</script>
<link rel="stylesheet" type="text/css" href="main.css"/>
</head>
Login to your account<br>
<form method=POST>
	<input type=text name=usernameGiven <?php if(!empty($usernameGiven)) echo "value=$usernameGiven"; else echo 'autofocus'; ?> required maxlength=64>
	<input type=password name=passwordGiven <?php if(!empty($usernameGiven)) echo 'autofocus'; ?> required maxlength=64>
	<input type=submit value=Login>
</form>
<a href=recover.php>Can't login?</a>
<?php if(!empty($loginErr)) echo $loginErr; ?>

<form method=POST name=signupField>
	First Name: <input type=text name=regFirstname maxlength=32 required value="<?php if(!empty($regFirstname)) echo $regFirstname; ?>"><br><br>
	Last name: <input type=text name=regLastname maxlength=32 required value="<?php if(!empty($regLastname)) echo $regLastname; ?>"><br><br>
	Username: <input type=text name=regUsername maxlength=32 onkeyup=loadAjax(); required value="<?php if(!empty($regUsername)) echo $regUsername; ?>"><span id="usernameCheck"></span><br><br>
	Password: <input type=password name=regPassword maxlength=64 required><br><br>
	Confirm Password: <input type=password name=regPasswordAgain maxlength=64 required><br><br>
	Email ID: <input type=email name=regeMail maxlength=64 required value="<?php if(!empty($regeMail)) echo $regeMail; ?>"><br><br>
	Date of Birth:
	<select name="getDobMonth" required>
		<option value="0">Month</option>
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
	<input type=text name=getDobDate maxlength=2 placeholder=Date required value="<?php if(!empty($getDobDate)) echo $getDobDate; ?>">
	<input type=text name=getDobYear maxlength=4 placeholder=Year required value="<?php if(!empty($getDobYear)) echo $getDobYear; ?>"><br><br>
	<input id="genderMale" type=radio  value=m name=gender <?php if(!empty($regGender)&&$regGender=='m') echo 'checked'; ?>>
		<label for=genderMale>Male</label>
	<input id="genderFemale" type=radio value=f name=gender <?php if(!empty($regGender)&&$regGender=='f') echo 'checked'; ?>>
		<label for=genderFemale>Female</label>
	<br><img src=generateCaptcha.php><br>
	Type the number:<input type=text name=captchaInput required maxlength=5>
	<br><input type=submit value=SignUp><br>
</form>
<?php if(!empty($regErr)) echo $regErr; ?>