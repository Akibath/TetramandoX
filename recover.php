<h1>Recover your account</h1>

<?php
	require 'core.inc.php';
	require 'connect.inc.php';
	logout2Continue();
	$thisAllowed = array('username', 'password');
	if(isset($_GET['username'])&&isset($_GET['passwordResetCode'])){
		$rcsUsername = $_GET['username'];
		$rcspasswordResetCode = $_GET['passwordResetCode'];
		if(!empty($rcsUsername)&&!empty($rcspasswordResetCode)){
			resetMyPassword($rcsUsername, $rcspasswordResetCode);
		}
	}elseif(isset($_GET['success'])===true&&empty($_GET['success'])===true){
			?> Thanks, we've mailed you, Please check your email<?php
	} else {
		if(isset($_GET['this'])===true&&in_array($_GET['this'], $thisAllowed)===true){
			if($_GET['this']==='username'){
				require 'forgotUsername.inc.php';
			} elseif($_GET['this']==='password'){
				require 'forgotPassword.inc.php';
			}
		} elseif(isset($_GET['this'])===true&&in_array($_GET['this'], $thisAllowed)===false)
			redirectHome();
		else{ ?>
			<a href="?this=username">I forgot my username</a>
			<a href="?this=password">I forgot my password</a> <?php
		}
	}
?>