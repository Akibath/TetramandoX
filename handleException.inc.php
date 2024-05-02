<?php
	//new connectDB();
	new fileStructure();
	class connectDB{
		public function __construct(){
			if(!@mysql_connect('localhost', 'root', '')){
				$this->writeFile('Could not connect to server.', null);
				define('connErr_inc', true); require 'connErr.inc.php'; exit;
			} else
				$this->searchDBs();
		}

		private function searchDBs(){
			$DBs = array('users', 'notification', 'messages', 'activities');
			for($i = 0; $i < count($DBs); $i++){
				if(!mysql_select_db($DBs[$i]))
					$this->writeFile($DBs[$i], true);
				else
					$this->searchTables($DBs[$i]);
			}
		}

		private function searchTables($DB){
			$tables = array(
				'users' => array('userLogin', 'friendReq', 'personalInfo', 'settings', 'friends', 'spam_block', 'pageInfo', 'account_info', 'recover', 'blockedIP')	
			);
			$tablesAtDB = queryFetch("SHOW TABLES FROM $DB ");
			for($i = 0; $i < count($tables[$DB]); $i++){
				for($j = 0, $flag = false; $j < count($tablesAtDB); $j++){
					if($tables[$DB] == $tablesAtDB[$j]){
						$flag = true;
					}
				}
				if(!$flag) $this->writeFile($DB.'.'.$tables[$DBLog][$i], false);
			}
		}

		private function writeFile($name, $type){ 
			$fileName = 'bin/DBLog.txt';
			$dateTime = getDate(time());
			$exceptionMessage = $type === null? $name: (($type? 'Database': 'Table')." '$name' not found.")	;
			$exceptionDate = '['.$dateTime['year'].', '.substr($dateTime['month'], 0, 3).' '.$dateTime['mday'].']';
			$Exception = $exceptionDate.' => '.$exceptionMessage."\n";
			$fileContents = file_get_contents($fileName);
			if(!is_numeric(strpos($fileContents, $exceptionDate))){
				$fileHanle = fopen($fileName, 'a');
				fwrite($fileHanle, $Exception);
				fclose($fileHanle);
			}
		}
	}
	
	class fileStructure{
		private $files = array(
			'settings' 		=> array('index.php'),
			'messages' 		=> array('index.php'), 
			'notifications' => array('index.php'),  
			'userFiles' 	=> array('0_defaultFiles' => array('thumbs'), '.htaccess'),
			'requests' 		=> array('sent' => array('index.php'), 'index.php'),
			'post' 			=> array('postNotFound.inc.php', 'showPost.inc.php'),
			'js'	   		=> array('main.js', 'messages.js', 'myProfile.js', 'search.js', 'settings.js'), 
			'recover'		=> array('forgotPassword.inc.php', 'forgotUsername.inc.php', 'resetMyPassword.inc.php'),
			'signup'   		=> array('activateMail.php', 'generateCaptcha.php', 'signupConfirm.php', 'signUpSuccess.php'),
			'profile' 		=> array(
								'about.profile.php', 'friends.profile.php', 'likes.profile.php', 'pageProfile.inc.php', 'photos.profile.php', 'profile.inc.php', 
								'profileCard.inc.php', 'profileNotExist.inc.php', 'publicProfile.inc.php', 'subProfile.php', 'userProfile.inc.php'
							), 
			'css'			=> array(
								'friendReqs.css', 'friends.myProfile.css', 'homepage.css', 'main.css', 'messages.css', 'myProfile.css','myProfileCard.css', 
								'notifications.css', 'photos.myProfile.css', 'post.inc.css', 'search.css', 'sentRequests.css', 'settings.css', 'topMenu.css'
							),
			'.htaccess', 'ajax.php', 'connErr.inc.php', 'core.inc.php', 'index.php', 'login.inc.php', 'login.php', 'logout.php', 'pageNotFound.inc.php',
			'post.php', 'profile.php', 'recover.php', 'search.php', 'settings.php', 'signup.php', 'topMenu.inc.php', 'welcome.inc.php'
		);

		function __construct(){
			echo '<pre>';
			self :: searchFiles($this->files, '');
			exit;
		}

		function searchFiles($fileName, $folderName){
			if(!is_array($fileName))
				return file_exists($folderName.$fileName);

			foreach($fileName as $key => $subFile){
				if(!is_array($subFile))
					!self :: searchFiles($subFile, $folderName) && self :: writeFile("File $folderName$subFile does not found.");
				else {
					$subFolder = !is_numeric($key)? ($folderName == ''? $key.'/': $folderName.$key.'/'): ''; 
					!is_numeric($key) && !self :: searchFiles('', $subFolder)? self :: writeFile("Folder $folderName$key does not found."): self :: searchFiles($subFile, $subFolder);
				}
			}
		}

		private function writeFile($exceptionMessage){
			echo $exceptionMessage.'<br>';return 0;
			$fileName = 'bin/DBLog.txt';
			$dateTime = getDate(time());
			$exceptionDate = '['.$dateTime['year'].', '.substr($dateTime['month'], 0, 3).' '.$dateTime['mday'].']';
			$Exception = $exceptionDate.' => '.$exceptionMessage."\n";
			$fileContents = file_get_contents($fileName);
			if(!is_numeric(strpos($fileContents, $Exception))){
				$fileHanle = fopen($fileName, 'a');
				fwrite($fileHanle, $Exception);
				fclose($fileHanle);
			}
		}
	}

?>