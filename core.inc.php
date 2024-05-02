<?php
	require 'handleException.inc.php';
	session_start();
	date_default_timezone_set('UTC');
	!defined('core_inc') && redirect404();
	if(!@mysql_connect('localhost', 'root', '') || !@mysql_select_db('users')){
		define('connErr_inc', true); require 'connErr.inc.php'; exit;
	}

	isLogin() && define('userID', querySelect('cookies', 'userID', 'c_userID', filterVar($_COOKIE['userID'])));

	function isLogin(){
		if(isset($_COOKIE['userID']) && !empty($_COOKIE['userID']) && isset($_COOKIE['token']) && !empty($_COOKIE['token'])){
			$cookieValue = filterVar($_COOKIE['userID']);
			$userID = queryResult("SELECT userID FROM cookies WHERE c_userID = '$cookieValue' AND expires > ".time());
			return isUserExist($userID);
		}
	}
	
	function logout(){
		session_destroy();
		setACookie('userID', true, time());
		setACookie('token', true, time());
		mysql_query("UPDATE cookies SET c_userID = '', c_token = '', expires = 0 WHERE userID = ".userID);
		redirectHome();
	}

	function login2Continue(){
		!isLogin() && redirectHome();
	}

	function logout2Continue(){
		isLogin() && redirectHome();
	}

	function redirectHome(){
		header('Location: http://localhost/TetramandoX/');
		exit;
	}

	function redirect404(){
		require 'pageNotFound.inc.php';
		exit;
	}

	function redirectProfile(){
		$personID = func_num_args() == 1? func_get_arg(0): userID;
		header('Location: http://localhost/TetramandoX/'.getField('userlogin', 'username', $personID));
		exit;
	}

	function isUserExist($personID){
		return !empty($personID) && !preg_match('/[^0-9]/', $personID) && queryRows('userlogin', 'id', $personID);
	}

	function isPage(){
		$userID = func_num_args(1)? func_get_arg(0): userID;
		return !@preg_match('/[^0-9]/', $personID) && queryRows('userLogin', array('id', 'type'), array($userID, 'P'));
	}

	function isMailAvail($mailID){
		$queryRows = func_num_args() == 2? queryRows('personalinfo', 'secMail', $mailID): queryRows('userlogin', 'email', $mailID);
		return strlen($mailID) < 129 && filter_var($mailID, FILTER_VALIDATE_EMAIL) && $queryRows == 0;
	}

	function isMailExist($mailID){
		return strlen($mailID) < 129 && filter_var($mailID, FILTER_VALIDATE_EMAIL) && queryRows("SELECT id FROM userlogin WHERE email = '$mailID' ");
	}

	function isMobilExist($mobileNum){
		return strlen($mobileNum) == 10 && !preg_match('/[^0-9]/', $mobileNum) && !queryRows("SELECT id FROM personalinfo WHERE mobileNumber = $mobileNum ");
	}

	function isUsernameExists($username){
		return queryRows("SELECT id FROM userlogin WHERE username = '$username' ");
	}
	
	function getField($tableName, $fieldName){
		$personID = func_num_args() == 3? func_get_arg(2): userID AND $conditionField = strtolower($tableName) == 'userlogin'? 'id': 'userID';
		if(!is_array($fieldName))
			return queryResult("SELECT $fieldName FROM $tableName WHERE $conditionField = $personID ");
		
		$fieldValues = array();
		foreach($fieldName as $eachField){
			array_push($fieldValues, getField($tableName, $eachField, $personID));
		}
		return $fieldValues;
	}
	
	function querySelect($tableName){
		$fieldName = func_num_args() > 1? (func_get_arg(1) === true? '*': func_get_arg(1)): '*';
		list($conditionField, $conditionValue) = func_num_args() > 2? array(func_get_arg(2), func_get_arg(3)): array(array(), array());
		!is_array($conditionField)? $conditionField = array($conditionField) AND $conditionValue = array($conditionValue): null;
		$conditionString = '';
		$fieldName = is_array($fieldName)? implode(',', $fieldName): $fieldName;
		$conditionSort = func_num_args() > 4? 'ORDER BY '.substr(func_get_arg(4), 0, strlen(func_get_arg(4)) - 1). (func_get_arg(4)[strlen(func_get_arg(4)) - 1] == '+'? ' ASC': ' DESC'): null;
		$conditionLimit = func_num_args() > 5? 'LIMIT '.func_get_arg(5): null;
		
		for($i = 0; $i < count($conditionField); $i++){
			if(preg_match('/.<$/', $conditionField[$i]))
				$conditionOperator = '<';
			else if(preg_match('/.>$/', $conditionField[$i]))
				$conditionOperator = '>';
			else if(preg_match('/.<=$/', $conditionField[$i]))
				$conditionOperator = '<=';
			else if(preg_match('/.>=$/', $conditionField[$i]))
				$conditionOperator = '>=';
			else if(preg_match('/.!=$/', $conditionField[$i]))
				$conditionOperator = '!=';
			else if(preg_match('/^%.+%$/', $conditionField[$i]))
				$conditionOperator = 'LIKE' AND $conditionValue[$i] = '%'.$conditionValue[$i].'%';
			else if(preg_match('/^%/', $conditionField[$i]))
				$conditionOperzator = 'LIKE' AND $conditionValue[$i] = '%'.$conditionValue[$i];
			else if(preg_match('/.%$/', $conditionField[$i]))
				$conditionOperator = 'LIKE' AND $conditionValue[$i] .= '%';
			else
				$conditionOperator = '=';
			
			if(is_array($conditionValue[$i])){
				for($j = 0; $j < count($conditionValue[$i]); $j++){
					!is_numeric($conditionValue[$i][$j])? $conditionValue[$i][$j] = "'".$conditionValue[$i][$j]."'": null;
				}

				$conditionValue[$i] = '('.implode(',', $conditionValue[$i]).')' AND $conditionOperator = 'IN ';
			} else !is_numeric($conditionValue[$i])? $conditionValue[$i] = "'$conditionValue[$i]'": null;
			
			$conditionPrefix = $i == 0? ' WHERE': (preg_match('/.|$/', $conditionField[$i])? ' OR': ' AND');
			$conditionField[$i] = preg_replace(array('/<$/', '/>$/', '/<=$/', '/>=$/', '/!=$/', '/^\%/', '/%$/', '/\|$/'), '', $conditionField[$i]);
			$conditionString .= "$conditionPrefix $conditionField[$i] $conditionOperator $conditionValue[$i]";
		}
		$queryResult = queryFetch("SELECT $fieldName FROM $tableName $conditionString $conditionSort $conditionLimit");
		return preg_match('/[^a-zA-Z0-9]/', $fieldName)? $queryResult: $queryResult[0];
	}
	
	function queryResult($sQuery){
		if(!queryRows($sQuery)) return 0;
		list($row, $field) = func_num_args() == 1? array(0, null): (func_num_args() == 2? array(0, func_get_arg(1)): array(func_get_arg(1), func_get_arg(2)));
		return mysql_result(mysql_query($sQuery), $row, $field);
	}
	
	function queryRows($sQuery){
		if(func_num_args() == 3){
			list($tableName, $fieldName, $value, $matchField) = array(func_get_arg(0), func_get_arg(1), func_get_arg(2), array());
			!is_array($fieldName)? list($fieldName, $value) = array(array($fieldName), array($value)): null;
			for($i = 0; $i < count($fieldName); $i++){
				!is_numeric($value[$i])? $value[$i] = "'$value[$i]'": null;
				$matchField[$i] = " $fieldName[$i] = $value[$i]";
			}
			return queryRows("SELECT id FROM $tableName WHERE ".implode(' AND', $matchField));
		} else
			return mysql_num_rows(mysql_query($sQuery));
	}
	
	function queryFetch($sQuery){
		$xQuery = mysql_query($sQuery);
		$fetchedResults = array();
		while($eachResult = func_num_args() == 1? mysql_fetch_assoc($xQuery): mysql_fetch_array($xQuery)){
			if(count(array_keys($eachResult)) == 1){
				$eachResult = $eachResult[array_keys($eachResult)[0]];
			}
			array_push($fetchedResults, $eachResult);
		}
		
		return func_num_args() > 1? $fetchedResults[0]: $fetchedResults;
	}
	
	function queryUpdate($tableName, $fieldName, $value){
		list($conditionField, $SETFields) = array(strtolower($tableName) == 'userlogin'? 'id': 'userID', array());
		!is_array($fieldName)? list($fieldName, $value) = array(array($fieldName), array($value)): null;
		for($i = 0; $i < count($fieldName); $i++){
			!is_numeric($value[$i])? $value[$i] = "'$value[$i]'": null;
			$SETFields[$i] = " $fieldName[$i] = $value[$i]";
		}
		mysql_query("UPDATE $tableName SET ".implode(',', $SETFields)." WHERE $conditionField = ".userID);
	}

	function queryInsert($tableName, $fieldName, $value){
		!is_array($fieldName)? list($fieldName, $value) = array(array($fieldName), array($value)): null;
		for($i = 0; $i < count($fieldName); $i++){
			!is_numeric($value[$i])? $value[$i] = "'$value[$i]'": null;
		}
		list($insertField, $insertValue) = array(implode(',', $fieldName), implode(',', $value));
		mysql_query("INSERT INTO $tableName ($insertField) VALUES($insertValue)");
	}
	
	function queryDelete($tableName, $fieldName, $value){
		$matchField = array();
		!is_array($fieldName)? list($fieldName, $value) = array(array($fieldName), array($value)): null;
		for($i = 0; $i < count($fieldName); $i++){
			!is_numeric($value[$i])? $value[$i] = "'$value[$i]'": null;
			$matchField[$i] = " $fieldName[$i] = $value[$i]";
		}

		mysql_query("DELETE FROM $tableName WHERE ".implode(' AND', $matchField));
	}

	function filterVar($var){
		return htmlspecialchars(mysql_real_escape_string(trim($var)));
	}

	function isEmail($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL) && queryRows("SELECT id FROM userlogin WHERE email = '$email' ");
	}

	function isNameAvail($nameArr){
		if(!empty($nameArr[0])){
			$isAvail = true;
			for($i = 0; $i < 4; $i++){
				$maxLength = $i == 3? 8: 32;
				if(!empty($nameArr[$i])){
					if(!isStrlen($nameArr[$i], 3, $maxLength) || preg_match('/[^a-zA-Z\s]/', $nameArr[$i])){
						$isAvail = false;
						break;
					}
				}
			}
			return $isAvail;
		}
	}

	function pageAdmin($pageID){
		return querySelect('pageInfo', 'adminID', 'userID', $pageID);
	}
	
	function getLogin($username, $givenPassword){
		list($userID, $userPassword) = queryFetch("SELECT id, password FROM userlogin WHERE username = '$username' OR email = '$username' ", true);
		if(password_verify($givenPassword, $userPassword))
			isPage($userID)? loggedIn(pageAdmin($userID)): loggedIn($userID);
		else
			header('Location: http://localhost/TetramandoX/login.php');
	}
	
	function loggedIn($userID){
		$_SESSION['userID'] = $userID;
		define('userID', $userID);
		$cookieExpires = time() + 604080;
		list($userIDValue, $tokenValue) = array(preg_replace('/[^a-zA-Z0-9]/', '', encrypt($userID, encrypt())), substr(preg_replace('/[^a-zA-Z0-9]/', '', encrypt()), 0, 32));
		setACookie('userID', $userIDValue, $cookieExpires);
		setACookie('token', $tokenValue, $cookieExpires);

		mysql_query("UPDATE cookies SET c_userID = '$userIDValue', c_token = '$tokenValue', expires = $cookieExpires WHERE userID = $userID ");
		mysql_query("UPDATE ajax SET loggedIn = ".time().", lastSeen = ".time()." WHERE userID = $userID ");
		mysql_query("UPDATE chatBoxs SET chatbox1 = NULL, chatbox2 = NULL, chatbox3 = NULL, chatbox4 = NULL WHERE userID = $userID ");
		
		$mailActivationCode = getField('account_info', 'mailActivationCode', $userID);
		empty($mailActivationCode)? redirectHome(): header('Location: signup/activateMail.php');
	}
	
	function setACookie($cookieName, $cookieValue, $cookieExpires){
		setCookie($cookieName, true, time(), '/', '', false, true);
		setCookie($cookieName, $cookieValue, $cookieExpires, '/', '', false, true);
	}

	function sendMailActivateionCode($username, $email, $firstName){
		$activationCode = md5($username+microtime());
		$activationURL = "http://www.iltana.com/loginCheck.php?email=$email&activationCode=$activationCode";
		$sendMailSub = "Activate your account";
		$sendMailMsg = "Hello $firstName You have not activated your mail yet. Please click the link below to activate your account\n\n $activationURL \n\n\n\n Thank you - TetramandoX";
		$sendMailFrom = 'From: info@iltana.com';
		if(mail($email, $sendMailSub, $sendMailMsg, $sendMailFrom)){
			if(mysql_query("UPDATE `accdetail` SET `mailActivate`='$activationCode' WHERE `id`='".$_SESSION['userID']."'"))
				header('Location: signUpSuccess.php');
		}
	}

	function activateMail(){
		if(isGETed('email') && isGETed('activationCode')){
			$mailID = filterGeted('email');
			$activationCode = filterGeted('activationCode');
			if(isMailExist($mailID)){
				echo $userID = queryResult("SELECT id FROM userlogin WHERE email = '$mailID' ");
			}
			// if(mysql_query("UPDATE `accdetail` SET `mailActivate`=1 WHERE `id`='$userID' AND `mailActivate`='$activationCode'")){
			// 	header('Location: index.php');
			// 	exit();
			// }
		}
	}

	function sendFriendRequest($personID){
		if(isPersonAvail($personID) && !isFriend($personID) && !isSpam($personID, false) && !isSentRequest($personID) && !isRecvRequest($personID)){
			queryInsert('friendReq', array('reqFrom', 'reqTo', 'time'), array(userID, $personID, time()));
			undoSpam($personID);
			return true;	
		}
	}

	function cancelFriendRequest($personID){
		if(isSentRequest($personID)){
			queryDelete('friendReq', array('reqFrom', 'reqTo'), array(userID, $personID));
			return true;
		}
	}

	function acceptFriendRequest($personID){
		if(isRecvRequest($personID)){
			queryInsert('friends', array('userOne', 'userTwo', 'time'), array(userID, $personID, time()));
			queryDelete('friendReq', array('reqFrom', 'reqTo'), array($personID, userID));
			generateNotification('R', $personID, true);
			return true;
		}
	}

	function ignoreFriendRequest($personID){
		if(isRecvRequest($personID)){
			queryDelete('friendReq', array('reqFrom', 'reqTo'), array($personID, userID));
			return true;
		}
	}

	function markSpam($personID){
		if(isPersonAvail($personID) && !isSpam($personID, true)){
			cancelFriendRequest($personID);
			ignoreFriendRequest($personID);
			queryDelete('spam_block', array('userID', 'personID', 'type'), array(userID, $personID, 'S'));
			return true;
		}
	}

	function undoSpam($personID){
		if(isSpam($personID, true)){
			queryDelete('spam_block', array('userID', 'personID', 'type'), array(userID, $personID, 'S'));
			return true;
		}
	}

	function isSpam($personID){
		if(func_num_args() == 2){
			$type = func_get_arg(1);
			if($type)
				return queryRows("SELECT id FROM spam_block WHERE userID = ".userID." AND personID = $personID AND type = 'S' ");
			else
				return queryRows("SELECT id FROM spam_block WHERE userID = $personID AND personID = ".userID." AND type = 'S' ");
		} else
			return queryRows("SELECT id FROM spam_block WHERE ((userID = $personID AND personID = ".userID.") OR (userID = ".userID." AND personID = $personID)) AND type = 'S' ");
	}

	function isBlocked($personID){
		if(func_num_args() == 2){
			$type = func_get_arg(1);
			if($type)
				return queryRows("SELECT id FROM spam_block WHERE userID = ".userID." AND personID = $personID AND type = 'B' ");
			else
				return queryRows("SELECT id FROM spam_block WHERE userID = $personID AND personID = ".userID." AND type = 'B' ");
		} else
			return queryRows("SELECT id FROM spam_block WHERE ((userID = ".userID." AND personID = $personID) OR (userID = $personID AND personID = ".userID.")) AND type = 'B' ");
	}

	function blockPerson($personID){
		if(!isPersonAvail($personID)) return 0;
		unFriend($personID);
		cancelFriendRequest($personID);
		ignoreFriendRequest($personID);
		undoSpam($personID);
		queryInsert('spam_block', array('userID', 'personID', 'type'), array(userID, $personID, 'B'));
		header('Location: index.php');
	}

	function unblockPerson($personID){
		if(isBlocked($personID, true)){
			queryDelete('spam_block', array('userID', 'personID', 'type'), array(userID, $personID, 'B'));
			return true;
		}
	}

	function isPersonAvail($personID){
		return isUserExist($personID) && $personID != userID && !isBlocked($personID) && !isPage($personID);
	}

	function isFriend($personID){
		return queryRows("SELECT id FROM friends WHERE (userOne = ".userID." AND userTwo = $personID) OR (userOne = $personID AND userTwo = ".userID.") ");
	}

	function isRecvRequest($personID){
		return queryRows("SELECT id FROM friendReq WHERE reqFrom = $personID AND reqTo = ".userID);
	}

	function isSentRequest($personID){
		return queryRows("SELECT id FROM friendReq WHERE reqFrom = ".userID." AND reqTo = $personID ");
	}

	function isMutualFriend($personID){
		foreach(friendsList(userID) as $friendID){
			if(queryRows("SELECT id FROM friends WHERE (userOne = $friendID AND userTwo = $personID) OR (userOne = $personID AND userTwo = $friendID) "))
				return true;
		}
	}

	function friendsOfFriends(){
		$friendsOfFriends = array();
		foreach(friendsList(userID) as $friend){
			foreach(friendsList($friend) as $friendOfFriend){
				if(isPersonAvail($friendOfFriend) && !isFriend($friendOfFriend) && !in_array($friendOfFriend, $friendsOfFriends))
					array_push($friendsOfFriends, $friendOfFriend);
			}
		}

		return $friendsOfFriends;
	}

	function unFriend($personID){
		if(isFriend($personID)){
			mysql_query("DELETE FROM friends WHERE (userOne = ".userID." AND userTwo = $personID) OR (userOne = $personID OR userTwo = ".userID.")");
			generateNotification('R', $personID, false);
			return true;
		}
	}

	function totalMutualFriends($personID){
		$totalMutualFriends = 0;
		foreach(friendsList(userID) as $friendID){
			if(queryRows("SELECT id FROM friends WHERE (userOne = $friendID OR userTwo = $personID) AND (userOne = $personID OR userTwo = $friendID)"))
				$totalMutualFriends++;
		}

		if(func_num_args() == 2)
			return $totalMutualFriends > 0? ($totalMutualFriends == 1? $totalMutualFriends.' mutual friend': $totalMutualFriends.' mutual friends'): null;
		else
			return $totalMutualFriends;
	}

	function totalSentRequests(){
		return queryRows('friendreq', 'reqFrom', userID);
	}

	function totalRecvRequests(){
		return queryRows('friendreq', 'reqTo', userID);
	}

	function personProfession(){
		$personID = func_num_args() >= 1? func_get_arg(0): userID;
		list($workPost, $workComp) = getField('personalinfo', array('workPost', 'workComp'), $personID);
		if(!empty($workPost) && isViewPermission($personID, 'basicPrivacy')){
			return func_num_args() == 2? $workPost.' at '.$workComp: array($workPost, $workComp);
		}
	}

	function personLocation(){
		$personID = func_num_args() >= 1? func_get_arg(0): userID;
		list($city, $state, $country) = getField('personalinfo', array('currentCity', 'currentState', 'currentCountry'), $personID);
		if(!empty($city) && isViewPermission($personID, 'basicPrivacy')){
			return func_num_args() == 2? $city.', '.$state.', '.$country: array($city, $state, $country);
		}
	}

	function personRelationButton($personID){
		if(isPage($personID)){
			if(isLiked($personID))
				return '<button onclick="unlikePage(this, '.$personID.')">Unlike</button>';
			else
				return '<button onclick="likePage(this, '.$personID.')">Like</button>';
		}
		if($personID != userID){
			$addFriendButton = null;
			$msgButton = func_num_args() == 1? '': '<button class="statusButton" onclick="openChatbox('.$personID.', \''.personName($personID, false, 19).'\', \''.getField('userLogin', 'userName', $personID).'\', \''.profilePicURL($personID).'\');">Message</button>';
			if(isFriend($personID))
				return $msgButton.'<button class="statusButton" onclick="unFriend(this, '.$personID.');">Unfriend</button>';
			else if(isRecvRequest($personID)){
				return $msgButton.'<button class="statusButton" id="confirm" onclick="acceptRequest(this, '.$personID.');">Confirm</button>
						<button class="statusButton" id="delete" onclick="deleteRequest(this, '.$personID.');">Delete</button>'
				;
			} else if(isSentRequest($personID))
				return $msgButton.'<button class="statusButton cancel" onclick="cancelRequest(this, '.$personID.');">Cancel Request</button>';
			else
				return $msgButton.'<button class="statusButton" onclick="sendRequest(this, '.$personID.');">Add Friend</button>';
		}
	}

	function personAge($personID){
		$dobTable = isPage($personID)? 'pageInfo': 'personalInfo';
		$dobTime = getField($dobTable, 'dob', $personID);
		if(!isViewPermission($personID, 'basicPrivacy')) return 0;
		if(func_num_args() == 2){
			$dobStr = getDate($dobTime);
			if(func_get_arg(1) == true)
				return $dobStr['mday'].' '.$dobStr['month'].', '.$dobStr['year'];
			else
				return getDate()['year'] - $dobStr['year'].' years old';
		} else {
			return getDate()['year'] - $dobStr['year'];
		}
	}

	function interestedGender($personID){
		return getField('personalinfo', 'interestedGender', $personID);
	}

	function isViewPermission($personID, $fieldName){
		$viewPermission = getField('settings', $fieldName, $personID);
		if($personID == userID) return true;
		switch($viewPermission){
			case 'E':
				return true;
			case 'F':
				return isFriend($personID);
			case 'M':
				return isFriend($personID) || isMutualFriend($personID);
		}
	}

	//Gender of the user in boolean. 1 for male || 0 for female
	function personGender($personID){
		$genderBool = getField('personalinfo', 'gender',  $personID);
		if(func_num_args() == 2)
			return $genderBool == 1? 'Male': 'Female';
		else
			return $genderBool == 1? true: false;
	}

	//Full URL for profile pic
	function profilePicURL($personID){
		$tableName = isPage($personID)? 'pageInfo': 'personalInfo';
		$imgName = getField($tableName, 'profilePic', $personID );

		if(empty($imgName))
			list($dirName, $imgName) = array('0_defaultFiles/', personGender($personID)? 'defaultMale.png': 'defaultFemale.png');
		else
			$dirName = getField('account_info', 'directoryName', $personID).'/';
		
		if(func_num_args() == 2) $imgName = 'thumbs/'.explode('.', $imgName)[0].'_'.func_get_arg(1).'x'.func_get_arg(1).'.'.explode('.', $imgName)[1];

		return 'http://localhost/TetramandoX/userFiles/'.$dirName.$imgName;
	}

	//Full URL for cover picture
	function coverPicURL($personID){
		$tableName = isPage($personID)? 'pageInfo': 'personalInfo';
		$imgName = getField($tableName, 'coverPic', $personID);
		if(empty($imgName))
			list($dirName, $imgName) = array('0_defaultFiles/', 'defaultCover.jpg');
		else
			$dirName = getField('account_info', 'directoryName', $personID).'/';

		if(func_num_args() == 2){
			if(func_get_arg(1) == true)
				$imgDimen = '_838x315';
			else
				$imgDimen = '_370x140';
			$imgName = 'thumbs/'.explode('.', $imgName)[0].$imgDimen.'.'.explode('.', $imgName)[1];

		}

		return $picURL = 'http://localhost/TetramandoX/userFiles/'.$dirName.$imgName;
	}

	//Full URL to profile
	function profileURL($personID){
		$userName = mysql_result(mysql_query("SELECT `username` FROM `users`.`userlogin` WHERE `id` = '$personID'"), 0);
		return $profileURL = 'http://localhost/TetramandoX/'.$userName;
	}

	//Send message and stores in db and stores the last message in another table.
	function sendMessage($personID, $msg){
		if(!isPersonAvail($personID)) return 0;
		$timeNow = time();
		$msgTable = getMsgTable($personID);
		$lastMsgID = queryResult("SELECT id FROM lastMessage WHERE (msgFrom = ".userID." AND msgTo = $personID) OR (msgFrom = $personID AND msgTo = ".userID.") ");

		if(!empty($lastMsgID))
			mysql_query("UPDATE lastMessage SET msgFrom = ".userID.", msgTo = $personID, time = ".time().", seen = 0, seenTime = 0, topMenuSeen = 0 WHERE  id = $lastMsgID ");
		else
			mysql_query("INSERT INTO lastMessage (msgFrom, msgTo, time) VALUES (".userID.", $personID, ".time().") ");

		if(isTableExists($msgTable))
			mysql_query("INSERT INTO $msgTable (senderID, msg, time) VALUES(".userID.", '$msg', $timeNow) ");
		else {
			mysql_query(
				"CREATE TABLE IF NOT EXISTS $msgTable (
				id int(11) NOT NULL AUTO_INCREMENT,
				senderID int(11) NOT NULL,
				msg varchar(3000) NOT NULL,
				deleted varchar(1) NOT NULL,
				time int(11) NOT NULL,
				PRIMARY KEY (id),
				FOREIGN KEY (senderID) REFERENCES users.userlogin(id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
			");
		}
		return true;
	}
	
	function getMsgTable($personID){
		$userFirst = 'messages.'.userID.'_'.$personID;
		$personFirst = 'messages.'.$personID.'_'.userID;
		return userID>$personID? $personFirst: $userFirst;
	}

	function listMessageCards(){
		//$QEachPerson = mysql_query("SELECT CONCAT(`msgFrom` as `personID`,',',`msgTo` as `personID`) FROM `lastmessage` WHERE (`msgFrom` = '".userID."' OR `msgTo` = ".userID.") ORDER BY `id` DESC ");
		$QAllMessages = mysql_query("SELECT id FROM lastmessage WHERE msgFrom = ".userID." OR msgTo = ".userID." ORDER BY id DESC ");
		while($eachMessage = mysql_fetch_assoc($QAllMessages)){
			$lastMessageId = $eachMessage['id'];
			// $QmsgToID = "SELECT msgTo FROM lastmessage WHERE msgFrom = ".userID." AND id = $lastMessageId ";
			// $QmsgFromID = "SELECT msgFrom FROM lastmessage WHERE msgTo = ".userID." AND id = $lastMessageId ";
			// if(getNumRows($QmsgToID) ==1)
			// 	$personID = getQueryResult($QmsgToID, 0, 'msgTo');
			// elseif(getNumRows($QmsgFromID) == 1)
			// 	$personID = getQueryResult($QmsgFromID, 0, 'msgFrom');

			$personRecv = queryResult("SELECT msgTo FROM lastmessage WHERE msgFrom = ".userID." AND id = $lastMessageId ");
			$personSent = queryResult("SELECT msgFrom FROM lastmessage WHERE msgTo = ".userID." AND id = $lastMessageId ");
			$personID = empty($personRecv)? $personSent: $personRecv;
			echoMessageCard($personID);
		}
	}

	function chatroomListConversation($personID){
		$msgsTableName = getMsgTable($personID);
		$QlistConversation = mysql_query("SELECT `id`, `senderID`, `msg`, `deleted` FROM $msgsTableName ORDER BY `id` ASC LIMIT 50");
		while($listConversation = mysql_fetch_array($QlistConversation)){
			$messageID = $listConversation['id'];
			$msgSenderID = $listConversation['senderID'];
			$msgDeletedBool = $listConversation['deleted'];
			$msgDeleted = $msgSenderID == userID? $msgDeletedBool == 's'?true:false: $msgDeletedBool == 'r'?true:false;
			$profilePicURL = $msgSenderID == $personID? profilePicURL($personID, 64): profilePicURL(userID, 64);
			$messageType = $msgSenderID == userID?'sent':'recv';
			$msg = $listConversation['msg'];
			if(!$msgDeleted){
				$eachMessage = '<div id="chatroomEachMessage" class="chatroomEachMessage '.$messageType.'">
									<img src="'.$profilePicURL.'">
									<div class="chatroomEachMessagetext">
										<div class="speechBubbleCorner"></div>
										<div id="speechBubbleDiv">
											<div class="speechBubbleBox">'.$msg.'</div>
										</div>
									</div>
								</div>';
				echo $eachMessage;
			}
		}
	}

	function isAnyMessage($personID){
		$QlastMessageID = "SELECT id FROM lastmessage WHERE (msgFrom = ".userID." OR msgTo = ".userID.") AND (msgFrom = $personID OR msgTo = $personID) ";
		if(queryRows($QlastMessageID) == 1){
			$lastMessageID = queryResult($QlastMessageID);
			$NlastSentMessage = queryRows("SELECT id FROM lastMessage WHERE msgTo = $personID AND id = $lastMessageID ");
			$lastMessageType = $NlastSentMessage == 1? true: false;
			$conversationDeleted = queryResult("SELECT deleted FROM lastmessage WHERE id = $lastMessageID ");
			$conversationDeleted = $lastMessageType? $conversationDeleted == 's'?true:false: $conversationDeleted == 'r'?true:false;
			if(!$conversationDeleted)
				return true;
			else
				return false;
		}
	}

	function messageCardSearch($searchString){
		$personsID = searchKnownPersons($searchString);
		foreach ($personsID as $personID){
			echoMessageCard($personID);
		}
	}

	function echoMessageCard($personID){
		$msgTable = getMsgTable($personID);
		$profilePicURL = profilePicURL($personID, 32);
		$personName = personName($personID, 'entireName', 32);
		$lastMessageID = lastMessageID($personID);
		$QlastMessage = "SELECT senderID, msg FROM $msgTable WHERE id = $lastMessageID ";
		$lastMessage = queryResults($QlastMessage, 0, 'msg');
		$lastMessageType = queryResults($QlastMessage, 0, 'senderID');
		$C_crMessageRead = 'messageReaded';
		$messageCard =	"<div id=crMessageCard_$personID class='crMessageCard $C_crMessageRead' onclick=selectMessageCard('$personID')>
								<div id=messageCardProfilePic>
									<img src=$profilePicURL>
								</div>
									<div id=messageCardPersonName_$personID class=messageCardPersonName>$personName</div>
									<div id=chatroomNavMsg_$personID class=messageCardMsg>$lastMessage</div>
								
							</div>";
		echo $messageCard;
	}

	function isMsgAvail($personID, $messageID){
		$msgTable = getMsgTable($personID);
		if(isTableExists($msgTable)){
			$msgDetail_Q = "SELECT senderID, deleted FROM $msgTable WHERE id = $messageID ";
			$senderID = queryResult($msgDetail_Q, 'senderID');
			$deletedBool = queryResult($msgDetail_Q, 'deleted');
			return $senderID == userID? $deletedBool != 'S': $deletedBool != 'R';
		}
	}

	function lastMessageID($personID){
		if(isAnyMessage($personID)){
			$msgTable = getMsgTable($personID);
			$QlastRecv = "SELECT id FROM $msgTable WHERE senderID = $personID AND deleted != 'R' ORDER BY time DESC LIMIT 1 ";
			$QlastSent = "SELECT id FROM $msgTable WHERE senderID = ".userID." AND deleted != 'S' ORDER BY time DESC LIMIT 1 ";
			$lastRecv = queryResult($QlastRecv);
			$lastSent = queryResult($QlastSent);
			return $lastMessageID = $lastRecv>$lastSent? $lastRecv: $lastSent;
		}
	}

	function encrypt(){
		if(func_num_args() == 2){
			list($data, $key) = array(func_get_arg(0), func_get_arg(1));
			return filterVar(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $data, MCRYPT_MODE_ECB, 32)));
		} else
			return preg_filter('/[^a-zA-Z0-9]/', '', encrypt(password_hash(md5(time()), PASSWORD_BCRYPT, array('cost' =>'4')), password_hash(sha1(time()), PASSWORD_BCRYPT, array('cost' =>'4'))));
	}

	function decrypt($data, $key){
		return filterVar(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($data), MCRYPT_MODE_ECB, 32));
	}

	function personName($personID, $type){
		//if(isPage($personID)) return getField('pageInfo', 'name', $personID);
		switch(isPage($personID)){
			case true:
				$personName = getField('pageInfo', 'name', $personID);
				break;
			case false:
				list($name, $fullName, $entireName) = array(getField('personalInfo', array('firstName', 'middleName', 'lastName', 'nickName'), $personID), '', '');
				$entireName = $fullName = $name[0];
				for($i = 1; $i < 4; $i++){
					if(empty($name[$i])) continue;
					$entireName = $i != 3? $fullName .= ' '.$name[$i]: $entireName.' ('.$name[$i].')';
				}
				$personName = $type? $entireName: $fullName;
		}
		
		return func_num_args() == 3 && strlen($personName) > func_get_arg(2)? substr($personName, 0, func_get_arg(2) - 4).' ...': $personName;
	}

	function personDesc($personID){
		if(isPage($personID))
			return getField('pageInfo', 'category', $personID).' <b>·</b> '.queryRows("SELECT id FROM pages.$personID ").' likes';
		$personDesc = array();
		if(personLocation($personID, true))
			$personDesc[] = personLocation($personID, true);
		if(personProfession($personID, true))
			$personDesc[] = personProfession($personID, true);
		if(totalMutualFriends($personID) > 0)
			$personDesc[] = totalMutualFriends($personID, true);
		
		if(func_num_args() == 1)
			return count($personDesc) == 0? '': $personDesc[0];
		else 
			return $personDesc;
	}

	function isStrlen($string, $minLength, $maxLength){
		$stringLength = func_num_args() == 4? $string: strlen($string);
		if($stringLength >= $minLength && $stringLength <= $maxLength)
			return true;
	}
	
	function isUsernameAvail($username){
		if(isStrlen($username, 3, 32)){
			if(!preg_match('/[^a-zA-Z0-9.]/', $username) && !preg_match('/[a-zA-Z0-9]\.(php|json|htaccess|html|htm|js|css|jpeg|jpg|png|gif|ico)/i', $username)){
				$username = filterVar($username);
				if(!is_dir($username) && !is_file($username)){
					if(queryRows("SELECT id FROM userlogin WHERE username = '$username' ")==0){
						return $username;
					}
				}
			}
		}
	}

	function isEmailAvail($email, $type){
		if(isStrlen($email, 5, 128) && filter_var($email, FILTER_VALIDATE_EMAIL)){
			return $type? !queryRows('userlogin', 'email', $email): !queryRows('personalinfo', 'secMail', $email);
		}
	}

	function isPost(){
		$isAvail = true;
		$totalArgs = func_get_arg(func_num_args() - 1) === true? func_num_args() - 1: func_num_args();
		
		for($i = 0; $i < $totalArgs; $i++){
			$currentArg = func_get_arg($i);
			if(preg_match('/\!+[a-zA-Z0-9]/', $currentArg)){
				$currentArg = preg_filter('/[!]/', '', $currentArg);
				if(!isset($_POST[$currentArg])){
					$isAvail = false;
					break;
				}
			} else {	
				if(!isset($_POST[$currentArg]) || strlen($_POST[$currentArg]) == 0){
					$isAvail = false;
					break;
				}
			}
		}

		if(isLogin() && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
			if(func_get_arg(func_num_args() - 1) !== true){
				if(getField('cookies', 'c_token') != $_COOKIE['token'])
					$isAvail = false;
			}

			return $isAvail;
		}
	}

	function isGet(){
		$isAvail = true;
		for($i = 0; $i < func_num_args(); $i++){
			$currentArg = func_get_arg($i);
			if(preg_match('/\!+[a-zA-Z0-9]/', $currentArg)){
				$currentArg = preg_filter('/[!]/', '', $currentArg);
				if(!isset($_GET[$currentArg])){
					$isAvail = false;
					break;
				}
			} else {
				if(!isset($_GET[$currentArg]) || strlen($_GET[$currentArg]) == 0){
					$isAvail = false;
					break;
				}
			}
		}

		return $isAvail;
	}

	function isAjax(){
		if(isLogin() && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
  			if(isValidToken($_COOKIE['token']))
  				return true;
	  	}
	}

	function filterPosted(){
		$vars = array();
		for($i = 0; $i < func_num_args(); $i++){
			$var = filterVar($_POST[func_get_arg($i)]);
			$vars[] = is_numeric($var)? (int) $var: strlen($var)? $var: null;
		}
		return count($vars) == 1? $vars[0]: $vars;
	}

	function filterGeted(){
		$vars = array();
		for($i = 0; $i < func_num_args(); $i++){
			$vars[] = isGet('!'.func_get_arg($i))? filterVar($_GET[func_get_arg($i)]): null;
		}
		return count($vars) == 1? $vars[0]: $vars;
	}

	function isValidToken($tokenValue){
		$cookieValue = cookieValue('token', filterVar($tokenValue));
		if(!empty($cookieValue)){
			//setAToken();
			return true;
		}
	}
	
	function friendsList($personID){
		if(!isViewPermission($personID, 'friendListPermission'))
			return array();
		$allFriends = array();
		foreach(queryFetch("(SELECT userTwo as personID FROM friends WHERE userOne = $personID ) UNION (SELECT userOne as personID FROM friends WHERE userTwo = $personID) ") as $eachFriend){
			array_push($allFriends, (int) $eachFriend);
		}

		if(func_num_args() == 1)
			return $allFriends;
		else
			return count($allFriends) > 0? (count($allFriends) == 1? count($allFriends).' friend': count($allFriends).' friends'): null;
	}

	function chatPersonsList(){
		$friendsArr = friendsList(userID, true);
		if(!empty($friendsArr)){
			$QonlineFriends = mysql_query("SELECT userID FROM ajax WHERE userID IN($friendsArr) ORDER BY lastSeen DESC ");
			$chatbarPersons = array();
			while($personsID = mysql_fetch_assoc($QonlineFriends)){
				array_push($chatbarPersons, $personsID['userID']);
			}
		} else
			$chatbarPersons = null;

		return $chatbarPersons;

	}

	function lastSeen($timeStamp){
		$seconds = time() - $timeStamp;
		$hours = $seconds / 3600;
		$lastSeen = null;
		if($seconds < 3600)
			$lastSeen = $seconds < 60? '1Min': (int) ($seconds/ 60).'Min';
		else if($seconds < 86400){
			$lastSeenHr = (int) $hours;
			$lastSeenMin = (int) (($hours - $lastSeenHr)* 60);
			$lastSeen = $lastSeenHr.'Hr '.$lastSeenMin.'Min';
		}
		return $lastSeen; 
	}

	function isIllegalAjax($field, $intervalSecond){
		$timeNow = time();
		queryResult("SELECT $field FROM ajax WHERE userID = ".userID." ");
		echo $intervalTime = time() - queryResults("SELECT $field FROM ajax WHERE userID = ".userID." ", 0, $field);
		if($intervalTime>=$intervalSecond){
			mysql_query("UPDATE ajax SET $field = $timeNow WHERE userID = ".userID." ");
			return true;
		}
	}

	function totalOnlineFriends(){
		$timeNow = time();
		$allFriendsArr = implode(',', friendsList(userID));
		return !empty($allFriendsArr)? queryRows("SELECT userID FROM ajax WHERE userID IN($allFriendsArr) AND ($timeNow - lastSeen) <= 10 "): 0;
	}

	function echoChatboxConversation($personID){
		if(isAnyMessage($personID)){
			$msgTable = getMsgTable($personID);
			$QallMessages = mysql_query("SELECT id, senderID, msg FROM $msgTable ORDER BY id ASC LIMIT 50");
			//mysql_query("UPDATE lastmessage SET seen = 1 WHERE msgFrom = $personID AND msgTo = ".userID." ");
			$allMessages = null;
			while($messageProperties = mysql_fetch_assoc($QallMessages)){
				$msgID = $messageProperties['id'];
				if(isMsgAvail($personID, $msgID)){
					$senderID = $messageProperties['senderID'];
					$msg = addEmoticons($messageProperties['msg'], 'chatboxEmoticon');
					$msgType_CLASS = $senderID == userID?'sent':'recv';
					$profilePic = $msgType_CLASS == 'recv'? '<img src="'.profilePicURL($personID, 32).'" class="chatboxProfilePic">': null;
					$allMessages .= '
								<div class="chatboxMsg '.$msgType_CLASS.'">
									'.$profilePic.'
									<div class="chatboxMsgText">'.$msg.'</div>
								</div>';
				}
			}

			return $allMessages;
		}
	}

	function messageSeen($personID){
		mysql_query("UPDATE lastMessage SET seen = 1 WHERE msgFrom = $personID AND msgTo = ".userID);
	}

	function isPersonOnline($personID){
		return (time() - getField('ajax', 'lastSeen', $personID)) <= 10? true: false;
	}

	function isChatOn(){
		list($userID, $personID) = func_num_args() == 1? array(userID, func_get_arg(0)): array(func_get_arg(0), func_get_arg(1));
		switch(getField('settings', 'turnOffChat', $userID)){
			case 'A':
				return false;
			case 'N':
				return $userID == userID? isChatOn($personID, $userID): true;
			case 'F':
				return !queryRows('chatOff', array('userID', 'personID'), array($userID, $personID)) && $userID == userID? isChatOn($personID, $userID): true;
			case 'E':
				return queryRows('chatOff', array('userID', 'personID'), array($userID, $personID)) && $userID == userID? isChatOn($personID, $userID): true;
		}
	}

	function isChatboxOpen($personID){
		return queryRows("SELECT id FROM chatBoxs WHERE userID = ".userID." AND (chatBox1 = $personID OR chatBox2 = $personID OR chatBox3 = $personID OR chatBox4 = $personID) ");
	}

	function time2String($givenTime){
		$exactOffset = getField('account_info', 'timezoneOffset');
		$offset = ($exactOffset > 0? +(int) $exactOffset: -(int) $exactOffset) *60;
		$givenTime += $offset;
		list($dateTime, $dateTimeNow, $timeDiffr) = array(getDate($givenTime), getDate(time() + $offset), (time() + $offset) - $givenTime);
		list($timeNow, $year, $month, $day, $hour, $minute, $session) = array(
			time() + $offset,
			$dateTime['year'],
			$dateTime['month'],
			$dateTime['mday'],
			$dateTime['hours'],
			$dateTime['minutes'] < 10? '0'.$dateTime['minutes']: $dateTime['minutes'],
			$dateTime['hours'] > 11? 'PM': 'AM'
		);

		$hourStr = $hour == 00? 12: ($hour > 12? $hour - 12: $hour);
		$hourStr = $hourStr < 10? '0'.$hourStr: $hourStr;
		list($timeStr, $dateStr) = array($hourStr.':'.$minute.' '.$session, $month.' '.$day.($dateTime['year'] != $dateTimeNow['year']? ', '.$year: null));

		list($yearBefore, $monthBefore, $dayBefore, $hourBefore, $minuteBefore) = array(
			(int) ($timeDiffr /60 /60 /24 /365),
			(int) (($timeDiffr /60 /60 /24 /365) *12),
			(int) ($timeDiffr /60 /60 /24),
			(int) ($timeDiffr /60 /60),
			(int) ($timeDiffr /60)
		);
		
		if($timeDiffr > (60 *60 *24 *365))
			$dateBefore = $yearBefore.($yearBefore == 1? ' yr': ' yrs');
		else if ($timeDiffr > (60 *60 *24 *31 ))
			$dateBefore = $monthBefore.($monthBefore == 1? ' month': ' months');
		else if($timeDiffr > (60 *60 *24))
			$dateBefore = $dayBefore.($dayBefore == 1? ' day': ' days');
		else if($timeDiffr > (60 *60))
			$dateBefore = $hourBefore.($hourBefore == 1? ' hr': ' hrs');
		else if($timeDiffr > 60)
			$dateBefore = $minuteBefore.($minuteBefore == 1? ' min': ' mins');
		else
			$dateBefore = 'few seconds before';

		if(func_num_args() == 2){
			$type = func_get_arg(1);
			if($type === true)
				return $dateStr;
			else if($type === false)
				return $timeStr;
			else if($type === null)
				return $dateBefore;
		} else return $dateStr.' at '.$timeStr;
	}

	function directoryName($personID){
		return getField('account_info', 'directoryName', $personID);
	}

	function createPost($postText, $postImage, $type){
		mysql_query("
			CREATE TABLE IF NOT EXISTS posts.".userID." (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  text varchar(4000) NOT NULL,
			  image varchar(64) NOT NULL,
			  type varchar(1) NOT NULL,
			  time int(11) NOT NULL,
			  PRIMARY KEY (id)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");

		mysql_query("INSERT INTO posts.".userID." (postID, text, image, type, time) VALUES('', '$postText', '$postImage', '$type', ".time().") ");
		$postLocalID = queryResult("SELECT id FROM posts.".userID." ORDER BY id DESC LIMIT 1 ");
		$postID = userID.'_'.$postLocalID;
		mysql_query("UPDATE posts.".userID." SET postID = '$postID' WHERE id = $postLocalID ");

		mysql_query("
			CREATE TABLE IF NOT EXISTS postPromotion.$postID (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  personID int(11) NOT NULL,
			  type varchar(1) NOT NULL,
			  commentText varchar(4000) NOT NULL,
			  time int(11) NOT NULL,
			  PRIMARY KEY (id),
			  FOREIGN KEY (personID) REFERENCES users.userlogin(id)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");
		return true;
	}

	function isPostExist($postID){
		if(preg_match('/^[0-9]+_[0-9]+$/', $postID)){
			list($personID, $postID_SQL) = split('_', $postID, 2);
			return isUserExist($personID) && queryRows('posts.'.$personID, 'id', $postID_SQL) && isViewPermission($personID, 'postPermission');
		}
	}

	function isPicturePost($postID){
		if(isPostExist($postID)){
			list($postOwnerID, $postLocalID) = explode('_', $postID);
			return queryRows("SELECT id FROM posts.$postOwnerID WHERE id = $postLocalID AND image != '' ");
		}
	}

	function pictureOfPost($postID){
		if(isPicturePost($postID)){
			list($postOwnerID, $postLocalID) = explode('_', $postID);
			$imageName = queryResult("SELECT image FROM posts.$postOwnerID WHERE id = $postLocalID ");
			$directoryName = directoryName(userID);
			return 'http://localhost/TetramandoX/userFiles/'.$directoryName.'/'.$imageName;
		}
	}

	function isCommentExits($commentID){
		if(preg_match('/^[0-9]+_[0-9]+_[0-9]+$/', $commentID)){
			$commentIDArr = explode('_', $commentID);
			$postID = $commentIDArr[0].'_'.$commentIDArr[1];
			$commentLocalID = $commentIDArr[2];
			return isPostExist($postID) && queryRows("SELECT id FROM postPromotion.$postID WHERE id = $commentLocalID AND type = 'C' ");
		}
	}

	function deletePost($postID){
		if(!isPostExist($postID) || explode('_', $postID)[0] != userID)
			return 0;

		$postID_SQL = explode('_', $postID)[1];
		$postType = queryResult("SELECT type FROM posts.".userID." WHERE postID = '$postID' ");
		if($postType == 'C')
			queryUpdate('personalinfo', 'coverPic', '');
		else if($postType == 'P')
			queryUpdate('personalinfo', 'profilePic', '');

		$imageName = queryResult("SELECT image FROM posts.".userID." WHERE postID = '$postID' ");
		if(!empty($imageName)){
			$directoryName = 'userFiles/'.getField('account_info', 'directoryName').'/';
			unlink($directoryName.$imageName);
			if($postType == 'C')
				$thumbRatios = array(array(60, 40), 64, 92, array(157, 167), array(370, 140));
			else if($postType = 'P')
				$thumbRatios = array(32, 64, 92, 140, array(157, 167));
			else
				$thumbRatios = array(64, 92, array(157, 167));
			
			for($i = 0; $i < count($thumbRatios); $i++){
				list($thumbWidth, $thumbHeight) = is_array($thumbRatios[$i])? $thumbRatios[$i]: array($thumbRatios[$i], $thumbRatios[$i]);
				unlink($directoryName.'thumbs/'.explode('.', $imageName)[0].'_'.$thumbWidth.'x'.$thumbHeight.'.'.explode('.', $imageName)[1]);
			}
		}
		
		$promotedPersons_Q = mysql_query("SELECT DISTINCT personID FROM postPromotion.$postID ");
		while($personID = mysql_fetch_assoc($promotedPersons_Q)){
			mysql_query("DELETE FROM activity.".$personID['personID']." WHERE postID = '$postID' ");
		}
		mysql_query("DELETE FROM posts.".userID." WHERE id = '$postID_SQL' ");
		mysql_query("DROP TABLE postPromotion.$postID ");
		
		return true;
	}

	function likePost($postID){
		if(isPostExist($postID) && !isLiked($postID)){
			queryInsert("postPromotion.$postID", array('personID', 'type', 'time'), array(userID, 'L', time()));
			unhatePost($postID);
			addActivity($postID, 'L');
			generateNotification('L', $postID, true);
			return true;
		} 
	}

	function unlikePost($postID){
		if(isLiked($postID)){
			queryDelete("postPromotion.$postID", array('personID', 'type'), array(userID, 'L'));
			addActivity($postID, 'L', false);
			generateNotification('L', $postID, false);
			return true;
		}
	}

	function hatePost($postID){
		if(isPostExist($postID) && !isHated($postID)){
			queryInsert("postPromotion.$postID", array('personID', 'type', 'time'), array(userID, 'H', time()));
			unlikePost($postID);
			addActivity($postID, 'H');
			generateNotification('H', $postID, true);
			return true;
		}
	}

	function unhatePost($postID){
		if(isHated($postID)){
			queryDelete("postPromotion.$postID", array('personID', 'type'), array(userID, 'H'));
			addActivity($postID, 'H', false);
			generateNotification('H', $postID, false);
			return true;
		}
	}

	function createComment($postID, $commentText){
		if(isPostExist($postID)){
			queryInsert("postPromotion.$postID", array('personID', 'type', 'commentText', 'time'), array(userID, 'C', $commentText, time()));
			addActivity($postID, 'C');
			generateNotification('C', $postID, true);
			return true;
		}
	}

	function deleteComment($commentID){
		if(isCommentExits($commentID)){
			list($postOwnerID, $postLocalID, $commentLocalID) = explode('_', $commentID);
			$postID = $postOwnerID.'_'.$postLocalID;
			$commentOwnerID = queryResult("SELECT personID FROM postPromotion.$postID WHERE id = $commentLocalID AND type = 'C' ");
			if($postOwnerID == userID || $commentOwnerID == userID){
				queryDelete("postPromotion.$postID", array('id', 'type'), array($commentLocalID, 'C'));
				addActivity($postID, 'C', false);
				generateNotification('C', $postID, false, $commentLocalID);
				return true;
			}
		}
	}

	function isLiked($postID){
		return queryRows(isPage($postID)? "SELECT id FROM pages.$postID WHERE personID = ".userID: "SELECT id FROM postPromotion.$postID WHERE type = 'L' AND personID = ".userID);
	}

	function isHated($postID){
		return queryRows("SELECT id FROM postPromotion.$postID WHERE type = 'H' AND personID = ".userID);
	}

	function totalLikes($postID){
		return isPostExist($postID) && queryRows("SELECT id FROM postPromotion.$postID WHERE type = 'L' ");
	}

	function totalHates($postID){
		return isPostExist($postID) && queryRows("SELECT id FROM postPromotion.$postID WHERE type = 'H' ");
	}

	function totalComments($postID){
		return isPostExist($postID) && queryRows("SELECT id FROM postPromotion.$postID WHERE type = 'C' ");
	}

	function isTableExists($tableName){
		if(preg_match("/[a-zA-Z0-9]+\.+[a-zA-Z0-9]/", $tableName)){
			list($dbName, $tableName) = explode('.', $tableName);
		}

		$tables_QR = mysql_query("SHOW TABLES FROM $dbName ");
		while($eachTable = mysql_fetch_row($tables_QR)){
			if($tableName == $eachTable[0])
				return true;
		}
	}

	function uploadImage($upload){
		$image = $_FILES[$upload];
		list($name, $tmpName, $meme, $size, $extension, $error, $availExts, $availMEMEs) = array(
			$image['name'], $image['tmp_name'], 
			$image['type'], $image['size'], 
			end(explode('.', $image['name'])), $image['error'],
			array('jpg', 'jpeg', 'png', 'gif'), array('image/gif', 'image/png', 'image/jpeg')
		);

		if(isLogin() && is_uploaded_file($tmpName) && $error === UPLOAD_ERR_OK && in_array($extension, $availExts) && in_array($meme, $availMEMEs) && @getImageSize($tmpName) && $size < 1024 * 1024 * 2.5){
			list($fileName, $dirName) = array(time().'_'.substr(preg_filter('/[^a-zA-Z]/', '', encrypt()), 0, 32), 'userFiles/'.directoryName(userID).'/');
			move_uploaded_file($tmpName, $dirName.$fileName.'.'.$extension);
			$imgPath = $dirName.$fileName.'.'.$extension;
			$img = $extension == 'jpeg' || $extension == 'jpg'? imageCreateFromJPEG($imgPath): ($extension == 'png'? imageCreateFromPNG($imgPath): imageCreateFromGIF($imgPath));
			$thumbRatios = array(64, 92, array(157, 167));
			if($upload == 'postImage')
				$thumbRatios = array(64, 92, array(157, 167));
			else if($upload == 'profilePic')
				$thumbRatios = array(32, 64, 92, 140, array(157, 167));
			else
				$thumbRatios = array(64, array(60, 40), 92, array(157, 167), array(370, 140), array(838, 315));

			list($imgWidth, $imgHeight) = getImageSize($imgPath);
			$imgRatio = $imgHeight < $imgWidth? $imgHeight: $imgWidth;
			list($imgHoriz, $imgVert) = $imgRatio == $imgHeight? array(($imgWidth - $imgHeight)/ 2, 0): array(0, ($imgHeight - $imgWidth)/ 2);
			$imgCroped = imageCreateTrueColor($imgRatio, $imgRatio);
			imageCopy($imgCroped, $img, 0, 0, $imgHoriz, $imgVert, $imgWidth, $imgHeight);

			for($i = 0; $i < count($thumbRatios); $i++){
				list($thumbWidth, $thumbHeight) = is_array($thumbRatios[$i])? $thumbRatios[$i]: array($thumbRatios[$i], $thumbRatios[$i]);
				$thumb = imageCreateTrueColor($thumbWidth, $thumbHeight);
				if(is_array($thumbRatios[$i])){
					$imgResizeHeight = floor($imgHeight* ($thumbWidth/ $imgWidth));	
					if($imgResizeHeight > $thumbHeight){
						$imgResized = imageCreateTrueColor($thumbWidth, $imgResizeHeight);
						imageCopyResampled($imgResized, $img, 0, 0, 0, 0, $thumbWidth, $imgResizeHeight, $imgWidth, $imgHeight);
						imageCopy($thumb, $imgResized, 0, 0, 0, ($imgResizeHeight - $thumbHeight)/ 2, $thumbWidth, $imgResizeHeight);
					} else {
						$imgResizeRatio = $thumbWidth > $thumbHeight? $thumbWidth: $thumbHeight;
						$imgResized = imageCreateTrueColor($imgResizeRatio, $imgResizeRatio);
						imageCopyResampled($imgResized, $imgCroped, 0, 0, 0, 0, $imgResizeRatio, $imgResizeRatio, $imgRatio, $imgRatio);
						imageCopy($thumb, $imgResized, 0, 0, 0, 0, $thumbWidth, $thumbHeight);
					}
				} else 
					imageCopyResampled($thumb, $imgCroped, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $imgRatio, $imgRatio);

				// if(!is_array($thumbRatios[$i]))
				// 	imageCopyResampled($thumb, $imgCroped, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $imgRatio, $imgRatio);
				// else {
				// 	$imgResizeHeight = floor($imgHeight* ($thumbWidth/ $imgWidth));	
				// 	$imgResized = imageCreateTrueColor($thumbWidth, $imgResizeHeight);
				// 	imageCopyResampled($imgResized, $img, 0, 0, 0, 0, $thumbWidth, $imgResizeHeight, $imgWidth, $imgHeight);
				// 	imageCopy($thumb, $imgResized, 0, 0, 0, ($imgResizeHeight - $thumbHeight)/ 2, $thumbWidth, $imgResizeHeight);
				// }
				
				imageJpeg($thumb, $dirName.'thumbs/'.$fileName.'_'.$thumbWidth.'x'.$thumbHeight.'.'.$extension, 100);
			}

			return $fileName.'.'.$extension;
		}
	}

	function createDirectory($userID){
		$dirName = $userID.'_'.substr(encrypt(), 0, 32);
		if(queryRows("SELECT id FROM account_info WHERE userID = $userID ") == 1)
			mysql_query("UPDATE account_info SET directoryName = '$dirName' WHERE userID = $userID ");
		else
			mysql_query("INSERT INTO account_info (userID, directoryName) VALUES ($userID, '$dirName') ");
		
		mkdir('userFiles/'.$dirName);
		mkdir('userFiles/'.$dirName.'/thumbs');
		$subDirs = array('32x32', '64x64', '128x128', '256x256');
		for($i = 0; $i < count($subDirs); $i++){
			mkdir('userFiles/'.$dirName.'/thumbs/'.$subDirs[$i]);
		}
	}

	function homepagePosts(){
		list($friendsArr, $allPosts) = array(isPage()? array(userID): array_merge(friendsList(userID), array(userID)), array());
		
		foreach($friendsArr as $eachFriend){
			$queryString = empty($queryString)? "(SELECT postID, time FROM posts.$eachFriend) ": $queryString . "UNION (SELECT postID, time FROM posts.$eachFriend) ";
			foreach(queryFetch("SELECT postID, type, time FROM activity.$eachFriend ") as $eachActivity){
				$eachActivity['personID'] = $eachFriend;
				array_push($allPosts, $eachActivity);
			}
		}

		foreach(queryFetch($queryString) as $eachPost){
			$eachPost['type'] = 'P';
			array_push($allPosts, $eachPost);
		}

		$sortByTimeArr = array();
		foreach($allPosts as $eachPost){
			$sortByTimeArr[] = $eachPost['time'];
		}
		array_multisort($sortByTimeArr, SORT_DESC, $allPosts);

		$homepagePosts = array();
		for($i = 0; $i < count($allPosts); $i++){
			$flag = true;
			for($j = 0; $j < count($homepagePosts); $j++){
				if($allPosts[$i]['postID'] == $homepagePosts[$j]['postID'] || !isPostExist($allPosts[$i]['postID'])){
					$flag = false;
					break;
				}
			}

			if($flag) array_push($homepagePosts, $allPosts[$i]);
		}
		return $homepagePosts;
	}
	
	function addActivity($postID, $type){
		$tableName = 'activity.'.userID;
		$timeNow = time();
		
		if(func_num_args() == 3){
			if($type == 'C'){
				if(queryRows("SELECT id FROM postPromotion.$postID WHERE type = 'C' AND personID = ".userID) > 1){
					$time = queryResult("SELECT time FROM postPromotion.$postID WHERE type = 'C' AND personID = ".userID." ORDER BY time DESC ");
					mysql_query("UPDATE $tableName SET time = $time WHERE postID = '$postID' ");
				} else
					mysql_query("DELETE FROM $tableName WHERE postID = '$postID' ");
			} else
				mysql_query("DELETE FROM $tableName WHERE postID = '$postID' ");
		} else {
			if(queryRows("SELECT id FROM $tableName WHERE postID = '$postID' "))
				mysql_query("UPDATE $tableName SET type = '$type', time = $timeNow WHERE postID = '$postID' ");
			else
				mysql_query("INSERT INTO $tableName (postID, type, time) VALUES ('$postID', '$type', $timeNow) ");
		}
	}

	function generateNotification($type){
		switch($type){
			case 'L':
			case 'H':
				list($postID, $personID) = array(func_get_arg(1), explode('_', func_get_arg(1))[0]);
				if($personID == userID) return 0;
				if(func_get_arg(2))
					mysql_query("INSERT INTO notification.$personID (senderID, type, postID, time) VALUES (".userID.", '$type', '$postID', ".time().") ");
				else
					mysql_query("DELETE FROM notification.$personID WHERE senderID = ".userID." AND type = '$type' AND postID = '$postID' ");
				break;
			case 'C':
				list($postID, $personID) = array(func_get_arg(1), explode('_', func_get_arg(1))[0]);
				if($personID == userID) return 0;
				if(func_get_arg(2)){
					$commentID = queryResult("SELECT id FROM postPromotion.$postID WHERE personID = ".userID." AND type = 'C' ORDER BY id DESC LIMIT 1");
					mysql_query("INSERT INTO notification.$personID (senderID, type, postID, commentID, time) VALUES (".userID.", '$type', '$postID', $commentID, ".time().") ");
				} else {
					$commentID = func_get_arg(3);
					mysql_query("DELETE FROM notification.$personID WHERE senderID = ".userID." AND type = 'C' AND postID = '$postID' AND commentID = $commentID ");
				}
				break;
			case 'R':
				$personID = func_get_arg(1);
				if(func_get_arg(2))
					mysql_query("INSERT INTO notification.$personID (senderID, type, time) VALUES (".userID.", 'R', ".time().") ");
				else {
					mysql_query("DELETE FROM notification.$personID WHERE senderID = ".userID." AND type = 'R' ");
					mysql_query("DELETE FROM notification.".userID." WHERE senderID = $personID AND type = 'R' ");
				}
				break;
		}
	}

	function timelinePosts($personID){
		$timelinePosts = array();
		$timelinePosts_Q = mysql_query("SELECT postID FROM posts.$personID ORDER BY id DESC ");
		while($postProptQ = mysql_fetch_assoc($timelinePosts_Q)){
			 array_push($timelinePosts, $postProptQ['postID']);
		}
		return $timelinePosts;
	}

	function echoPost($postArr){
		$postID = is_array($postArr)? $postArr['postID']: $postArr;
		if(!isPostExist($postID)) return 0;
		list($personID, $postID_SQL) = explode('_', $postID);
		$postProptQ = "SELECT id, text, image, type, time FROM posts.$personID WHERE id = '$postID_SQL' ";
		$postContentText = queryResult($postProptQ, 'text');
		$postContentImage = queryResult($postProptQ, 'image');
		
		if(func_num_args() == 2 && $postArr['personID'] != userID){
			$activityStr = array('L' => ' likes this', 'H' => ' hates this', 'C' => ' commented on this');
			$postActivity = 
				'<div class="postActivity">
					<span class="personName">
						<a href="'.profileURL($postArr['personID']).'">'.personName($postArr['personID'], true).'</a>
					</span>
					'.$activityStr[$postArr['type']].'
				</div>'
			;
			$commentArea_ClassName = $postArr['type'] == 'C' || totalComments($postID) <= 6 && totalComments($postID) != 0? 'show': 'hide';
		} else list($postActivity, $commentArea_ClassName) = array(null, func_num_args() == 3 || (totalComments($postID) <= 6 && totalComments($postID) != 0)? 'show': 'hide');

		switch(queryResult($postProptQ, 'type')){
			case 'P':
				$postDesc = $personID == userID? 'updated profile picture': (personGender($personID)? 'updated his profile picture': 'updated her profile picture'); break;
			case 'C':
				$postDesc = $personID == userID? 'updated cover picture': (personGender($personID)? 'updated his cover picture': 'updated her cover picture'); break;
			case 'S':
				$postDesc = 'shared a post'; break;
			default:
				$postDesc = '';
		}

		$profilePicURL = profilePicURL($personID, 64);
		$entireName = personName($personID, true);
		$postBodyImage = !empty($postContentImage)? '<img src="http://localhost/TetramandoX/userFiles/'.directoryName($personID).'/'.$postContentImage.'">': null;
		$postSettings = null;	

		if(userID == $personID){
			$postSettings = '<div class="settings">
								<img src="http://localhost/TetramandoX/img/settings_gear.png" class="postSettingsGear" style="display:none">
							</div>
							<div class="postSettingsMenu">
								<ul>
									<li onclick="deletePost(\''.$postID.'\')">Delete Post</li>
								</ul>
							</div>';
		}

		$allComments = null;
		foreach(queryFetch("SELECT id, personID, commentText, time FROM postPromotion.$postID WHERE type = 'C' ORDER BY time DESC LIMIT 6 ") as $comment){
			$commentID = $postID.'_'.$comment['id'];
			$commentOwnerID = $comment['personID'];
			
			$deleteComment = (userID == $commentOwnerID || userID == $personID)? '<div class="deleteComment"><img  onclick="deleteComment(\''.$commentID.'\')" src="http://localhost/TetramandoX/img/close1.png"></div>': null;

			$allComments .= 
				'<div id="'.$commentID.'" class="eachComment">
					<div class="profilePic"><img src="'.profilePicURL($commentOwnerID, 64).'"></div>
					<div class="content">
						<span class="personName" onmouseover="showHoverCard(event, this, '.$commentOwnerID.')"><a href="'.profileURL($commentOwnerID).'" id="personName_'.$commentOwnerID.'">'.personName($commentOwnerID, true).'</a></span>
						<span class="commentText">'.addEmoticons($comment['commentText'], 'commentEmoticon').'</span>
						<div class="time">'.time2String($comment['time']).'</div>
					</div>'.$deleteComment.'
				</div>'
			;
		}

		$seeMoreComments = totalComments($postID)>6? '<div class="showMoreComments" onclick="showMoreComments(\''.$postID.'\');">See more...</div>': null;
		
		return 
			'<div id="'.$postID.'" class="post">'.
				$postActivity.'<div class="postTitle">
					<div class="profilePic"><img src="'.$profilePicURL.'"></div>
					<div class="nameAndTime">
						<span class="name" onmouseover="showHoverCard(event, this, '.$personID.')"><a id="personName_'.$personID.'" href="'.profileURL($personID).'">'.$entireName.'</a></span>
						<span class="desc">'.$postDesc.'</span>
						<div class="time"><a href="http://localhost/TetramandoX/post.php?postID='.$postID.'">'.time2String(queryResult($postProptQ, 'time')).'</a></div>
					</div>'.$postSettings.'
				</div>
				<div class="postBody">
					<div class="postBodyText">'.addEmoticons($postContentText, 'postEmoticon').'</div>
					<div class="postBodyImage"><a href="http://localhost/TetramandoX/post.php?postID='.$postID.'">'.$postBodyImage.'</a></div>
				</div>
				<div class="promotePost">
					<ul>
						<li id="likeUnlike" onclick="likeHate(event, \''.$postID.'\');">'.(isLiked($postID)? 'Unlike': 'Like').'</li>
						<li id="hateUnhate" onclick="likeHate(event, \''.$postID.'\');">'.(isHated($postID)? 'Unhate': 'Hate').'</li>
						<li onclick="focusCommentArea(\''.$postID.'\')">Comment</li>
						<li>Share</li>
						<li class="postPromotionDetails" id="totalShares"><img src="http://localhost/TetramandoX/img/share.png">18</li>
						<li class="postPromotionDetails" id="totalComments"><img src="http://localhost/TetramandoX/img/comment.png">'.totalComments($postID).'</li>
						<li class="postPromotionDetails" id="totalHates"><img src="http://localhost/TetramandoX/img/hate.png">'.totalHates($postID).'</li>
						<li class="postPromotionDetails" id="totalLikes"><img src="http://localhost/TetramandoX/img/like.png">'.totalLikes($postID).'</li>
					</ul>
				</div>
				<div class="commentArea '.$commentArea_ClassName.'">
					<div class="writeComment">
						<img src="'.profilePicURL(userID, 64).'">
						<textarea onkeydown="postComment(event, \''.$postID.'\')" class="writeCommentTextarea" placeholder="Write a comment..."></textarea>
					</div>
					<div class="comments">'.$allComments.$seeMoreComments.'</div>
				</div>
			</div>'
		;
	}

	function postPropt($postID){
		if(isPostExist($postID)){
			$personID = explode('_', $postID)[0];
			$postID_SQL = explode('_', $postID)[1];
			$postProptQ = "SELECT text, image, type, time FROM posts.$personID WHERE id = $postID_SQL ";
			$postPropt['personID'] = $personID;
			$postPropt['text'] = queryResult($postProptQ, 'text');
			$postPropt['image'] = queryResult($postProptQ, 'image');
			$postPropt['type'] = queryResult($postProptQ, 'type');
			$postPropt['time'] = queryResult($postProptQ, 'time');

			return $postPropt;
		}
	}

	function addEmoticons($plainText, $className){
		$symbols = array(':-\)', ':-\(', '=D', 'B\|', ':-\\', ':o', ':\'\(', ';-\)', '\:P', '\:\<', '<3');
		$imageName = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11');
		$emoticonText = $plainText;
		for($i = 0; $i<count($symbols); $i++){
			$imagePath = '</span><span class="'.$className.'"><img class="" src="http://localhost/TetramandoX/img/emoticons/'.$imageName[$i].'.png"></span><span>';
			$emoticonText = preg_replace('/^'.htmlentities($symbols[$i]).'$/', $imagePath, $emoticonText);
			$emoticonText = preg_replace('/\s'.htmlentities($symbols[$i]).'$/', ' '.$imagePath, $emoticonText);
			$emoticonText = preg_replace('/^'.htmlentities($symbols[$i]).'\s/', $imagePath.' ', $emoticonText);
			$emoticonText = preg_replace('/\s'.htmlentities($symbols[$i]).'|\s'.htmlentities($symbols[$i]).'\b/', ' '.$imagePath, $emoticonText);
		}
		return '<span>'.$emoticonText.'</span>';
	}

	function isPostPermission($postID){
		if(isPostExist($postID)){
			$personID = explode('_', $postID)[0];
			$postID_SQL = explode('_', $postID)[1];
			$viewPermission = getField('settings', 'postPermission', $personID);
			if($personID == userID || $viewPermission == 'e')
				return true;
			else if($viewPermission == 'f' && isFriend($personID))
				return true;
			else if($viewPermission == 'm' && (isFriend($personID) || isMutualFriend($personID)))
				return true;
		}
	}

	function photosList($personID){
		$listPhotosQ = mysql_query("SELECT postID, image FROM posts.$personID WHERE image != '' ORDER BY time DESC ");
		$photosArr = array();
		while($postPropt = mysql_fetch_assoc($listPhotosQ)){
			$postID = $postPropt['postID'];
			if(isPostExist($postID)){
				array_push($photosArr, $postPropt['image']);
			}
		}

		return $photosArr;
	}

	function isReqPermission($personID){
		return queryResult("SELECT reqPermission FROM settings WHERE userID = $personID ");
	}

	function createMySQLEntry($userID){
		/*
			entry in all tables
			table: posts, activity, notification
			entry: account_info, ajax, cookies, personalinfo, settings, chatboxs, userlogin
		*/
			mysql_query("CREATE TABLE IF NOT EXISTS activity.$userID (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `postID` varchar(20) NOT NULL,
				  `type` varchar(1) NOT NULL,
				  `time` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1
		");
	}
	
	function graphicalSearch($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location){
		list($matchedPersons, $isMatch) = array(array(), true);
		if(($name && preg_match('/[^a-zA-Z ()]/', $name)) || ($gender && !in_array($gender, array(1, 0, 'B'), true)) || ($ageFrom && preg_match('/[^0-9]/', $ageFrom)) || ($ageTo && preg_match('/[^0-9]/', $ageTo)) || ($mobileNumber && preg_match('/[^0-9]/', $mobileNumber)) || ($emailID && preg_match('/[^a-zA-Z0-9._@]/', $emailID))){
			$isMatch = false;
		}

		if($name && $isMatch){
			$name = preg_replace(array('/\(/', '/\)/'), '', $name);
			$wildCard = strlen($name) < 3? "'$name%'": "'%$name%'";
			$searchQuery = "(SELECT userID FROM personalInfo WHERE 
								CONCAT(firstName, ' ', middleName, ' ', lastName, ' ', nickName) LIKE $wildCard 
								OR CONCAT(firstName, ' ', lastName, ' ', nickName) LIKE $wildCard 
								OR firstName LIKE $wildCard OR middleName LIKE $wildCard OR lastName LIKE $wildCard OR nickName LIKE $wildCard
							) UNION (SELECT userID FROM pageInfo WHERE name LIKE $wildCard) "
			;
			
			foreach(queryFetch($searchQuery) as $eachMatch){
				$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($gender && $isMatch){
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo ".($gender == 'B'? null: "WHERE gender = $gender ");
			else
				$searchQuery = "SELECT userID FROM personalInfo WHERE userID IN(".implode(',', $matchedPersons).") ".($gender == 'B'? null: "gender = $gender ");
			
			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($ageFrom && $isMatch){
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo ";
			else
				$searchQuery = "SELECT userID FROM personalInfo WHERE userID IN(".implode(',', $matchedPersons).") ";

			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(personAge($eachMatch) >= $ageFrom && isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($ageTo && $isMatch){
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo ";
			else 
				$searchQuery = "SELECT userID FROM personalInfo WHERE userID IN(".implode(',', $matchedPersons).") ";

			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(personAge($eachMatch) <= $ageTo && isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($mobileNumber && $isMatch){
			$wildCard = strlen($mobileNumber) < 5? "$mobileNumber%": "%$mobileNumber%";
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo WHERE mobileNumber LIKE $wildCard ";
			else
				$searchQuery = "SELECT userID FROM personalInfo WHERE mobileNumber LIKE $wildCard AND userID IN(".implode(',', $matchedPersons).") ";
			
			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($emailID && $isMatch){
			$wildCard = strlen($emailID) < 5? "'$emailID%'": "'%$emailID%'";
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo WHERE secMail LIKE $wildCard ";
			else
				$searchQuery = "SELECT userID FROM personalInfo WHERE secMail LIKE $wildCard AND userID IN(".implode(',', $matchedPersons).") ";

			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($work && $isMatch){
			$wildCard = strlen($work) < 3? "'$work%'": "'%$work%'";
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalInfo WHERE workComp LIKE $wildCard OR workPost LIKE $wildCard ";
			else
				$searchQuery = "SELECT userID FROM personalInfo WHERE userID IN(".implode(',', $matchedPersons).") AND workComp LIKE $wildCard OR workPost LIKE $wildCard ";

			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		if($location && $isMatch){
			$wildCard = strlen($location) < 3? "'$location%'": "'%$location%'";
			if(empty($matchedPersons))
				$searchQuery = "SELECT userID FROM personalinfo WHERE currentCity LIKE $wildCard OR currentState LIKE $wildCard OR currentCountry LIKE $wildCard ";
			else
				$searchQuery = "SELECT userID FROM personalinfo WHERE userID IN (".implode(',', $matchedPersons).") AND currentCity LIKE $wildCard OR currentState LIKE $wildCard OR currentCountry LIKE $wildCard ";

			$matchedPersons = array();
			foreach(queryFetch($searchQuery) as $eachMatch){
				if(isViewPermission($eachMatch, 'basicPrivacy'))
					$matchedPersons[] = $eachMatch;
			}

			empty($matchedPersons)? $isMatch = false: null;
		}

		return $isMatch? $matchedPersons: array();
	}

	function suggestPeople(){
		$suggestedPersons = array_diff(graphicalSearch(null, 'B', null, null, null, null, null, null), friendsList(userID), array(userID));
		shuffle($suggestedPersons);
		return $suggestedPersons;
	}

	function text2URL($text){
		$text = html_entity_decode($text);
		$text = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $text);
		$text = eregi_replace('(((f|ht){1}tps://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1">\\1</a>', $text);
		$text = eregi_replace('(www.[a-zA-Z0-9~@#%-_\:+.?&//=]+)', '<a href="http://\1">\1</a>', $text);
		return $text;
	}

	function listChatCards(){
		list($friendsArr, $onlineFriends, $offlineFriends) = array(implode(',', friendsList(userID)), array(), array());
		if(!$friendsArr)
			return array();
		
		foreach(queryFetch("SELECT userID FROM ajax WHERE userID IN($friendsArr) ORDER BY lastSeen DESC ") as $eachFriend){
			$personID = $eachFriend;
			isPersonOnline($personID) && isChatOn($personID)? $onlineFriends[] = $personID: $offlineFriends[] = $personID;
		}
		
		$chatbarPersons = array_merge($onlineFriends, $offlineFriends);
		if(getField('settings', 'turnOffChat') == 'A') shuffle($chatbarPersons);
		$chatCardsArr = array();
		for($i = 0; $i < 15; $i++){
			if(empty($chatbarPersons[$i])) break;
			$personID = $chatbarPersons[$i];
			$intervalTime = time() - (
				isChatOn($personID)? getField('ajax', 'lastSeen', $personID): queryResult("SELECT time FROM chatoff WHERE userID = ".userID." AND personID = $personID ")
			);
			list($lastSeen_Sec, $lastSeen_Hour, $lastSeen)  = array($intervalTime, $intervalTime/ 3600, '');
			if($lastSeen_Sec < 3600) 
				$lastSeen = $lastSeen_Sec < 60? '1Min': (int) ($lastSeen_Sec/60).'Min';
			else if($lastSeen_Sec < 86400){
				$lastSeenHr = (int) $lastSeen_Hour;
				$lastSeenMin = (int) (($lastSeen_Hour - $lastSeenHr)*60);
				$lastSeen = $lastSeenHr.'Hr '.$lastSeenMin.'Min';
			}

			$chatCardsArr[] = array(
				'personID' => $personID,
				'chatStatus' => $intervalTime <= 10? 1: $lastSeen,
				'username' => getField('userlogin', 'username', $personID),
				'personName' => personName($personID, true),
				'profilePicURL' => profilePicURL($personID, 32)
			);
		}
		return $chatCardsArr;
	}
?>