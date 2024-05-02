<?php
	define('core_inc', true);
	define('welcome_inc', true);
	define('login_inc', true);
	require 'core.inc.php';
	if(isLogin())
		require 'welcome.inc.php';
	else
		require 'login.inc.php';
?>