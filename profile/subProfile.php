<?php
	define('pageAccess', true);
	require 'core.inc.php';
	if(isset($_GET['spfusername'])&&isset($_GET['spfpage'])&&!empty($_GET['spfusername'])&&!empty($_GET['spfpage'])){
		$pfusername =  strtolower(mysql_real_escape_string(htmlentities($_GET['spfusername'])));
		$subProfileName = strtolower(mysql_real_escape_string(htmlentities($_GET['spfpage'])));
		$subProfiles = array('about', 'friends', 'photos');
		if(userExist($pfusername)){
			if(loginCheck() === true){
				if(!isBlocked($pfusername)){
					$username = strtolower(getField('userlogin', 'username'));
					if($username==$pfusername){
						if(in_array($subProfileName, $subProfiles))
							require 'profile/'.$subProfileName.'.mySubProfile.php';
						else
							redirectProfile();
					} elseif($username!=$pfusername){
						if(in_array($subProfileName, $subProfiles))
							require 'profile/'.$subProfileName.'.userSubProfile.php';
						else
							header('Location: http://localhost/tetramando/'.$pfusername);
					}
				} else
					require 'profileNotExist.inc.php';
			} else
				require 'publicProfile.inc.php';
		} else
			require 'profileNotExist.inc.php';
	} elseif(isset($_GET['spfusername'])&&!empty($_GET['spfusername'])&&empty($_GET['spfpage'])){
		$spfusername = filterVar($_GET['spfusername']);
		header('Location: http://localhost/TetramandoX/'.$spfusername);

	} elseif(!isset($_GET['spfusername'])||!isset($_GET['spfpage'])||empty($_GET['spfusername'])||empty($_GET['spfpage'])){
		if(loginCheck()===true)
			redirectProfile();
		elseif(loginCheck()===false)
			redirectHome();
	}
?>