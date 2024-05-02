<?php
	define('core_inc', true);
	define('topMenu_inc', true);
	require 'core.inc.php';
	if(isGet('profileName')){
		$profileName = filterGeted('profileName');
		$personID = $profileID = queryResult("SELECT id FROM userLogin WHERE username = '$profileName' ");
		if(!isUserExist($personID)) redirect404();
		$pageNamePrefix = isPage($personID)? 'page': '';		
		if(isGet('pageName') && isLogin()){
			list($pageName, $pageNamesArr) = array(strtolower(filterGeted('pageName')), isPage($personID)? array('about', 'photos', 'related'): array('about', 'photos', 'friends', 'likes'));
			if(in_array($pageName, $pageNamesArr) && isLogin()){
				define($pageName.'_profile', true);
				if(isLogin() && !isBlocked($personID))
					require 'profile/'.$pageNamePrefix.$pageName.'.profile.php';
				else
					require 'profile/'.$pageNamePrefix.'publicProfile.inc.php';
			} else
				redirectProfile($personID);
			
		} else {
			if(queryRows('userlogin', 'username', $profileName))
				require isLogin() && !isBlocked($personID)? 'profile/'.$pageNamePrefix.'profile.inc.php': 'profile/'.$pageNamePrefix.'publicProfile.inc.php';
			else
				redirect404();
		}
	} else if(isLogin())
		redirectProfile();
?>