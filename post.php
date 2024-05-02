<?php
	define('core_inc', true);
	require 'core.inc.php';

	if(isset($_GET['postID']) && !empty($_GET['postID'])){
		$postID = $_GET['postID'];
		if(isLogin() && isPostPermission($postID)){
			define('showPost_inc', true); 
			require 'post/showPost.inc.php';
		} else{
			define('postNotFound_inc', true);
			require 'post/postNotFound.inc.php';
		}
	}

?>