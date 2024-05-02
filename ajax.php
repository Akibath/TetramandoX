<?php
	define('core_inc', true);
	require 'core.inc.php';
	list($JSN_errorCode_0, $JSN_errorCode_1) = array('{"errorCode": 0 }', '{"errorCode": 1 }');
	if(!$_POST)
		redirect404();
	else if(!isLogin()){
		echo '{"errorCode": 7}';
		exit();
	}
	//echo $_SERVER['HTTP_USER_AGENT'];
	//echo print_r(getallheaders());
	//$headers = apache_request_headers();

	if(isset($_GET['chatroomShowConversation']) && !empty($_GET['chatroomShowConversation'])){
		$personID = filterVar($_GET['chatroomShowConversation']);
		chatroomListConversation($personID);
	}

	if(isPost('sendMessageTo', 'sendMessageContent')){
		$personID = filterPosted('sendMessageTo');
		$msg = filterPosted('sendMessageContent');
		sendMessage($personID, $msg);
	}

	if(isset($_GET['chatroomNavSearchString']) && !empty($_GET['chatroomNavSearchString'])){
		$string = ($_GET['chatroomNavSearchString']);
		messageCardSearch($string);
		//listTheNames($string);
	}

	if(isset($_GET['chatroomNavListLastMessage'])){
		listMessageCards();
	}

	if(isPost('updateTopMenu', '!chatBoxs')){
		$timeNow = time();
		$newFriendRequests = mysql_query("SELECT reqFrom FROM friendreq WHERE reqTo = ".userID." AND time+3 >= ".time()." AND seen = 0 ORDER BY time DESC ");
		$newRequests = array();
		while($newFriendRequests_F = mysql_fetch_assoc($newFriendRequests)){
			$personID = $newFriendRequests_F['reqFrom'];
			$newRequests[] = array(
				'personID' => $personID,
				'profilePic' => profilePicURL($personID),
				'username' => getField('userlogin', 'username', $personID),
				'name' => personName($personID, 'fullName', 20),
				'desc' => personDesc($personID)
			);
		}
		$totalUnseen = queryRows("SELECT id FROM friendreq WHERE reqTo = ".userID." AND seen = 0 ");
		$friendRequests = array('newRequests' => $newRequests, 'totalRecv' => totalRecvRequests(), 'totalSent' => totalSentRequests(), 'totalUnseen' => $totalUnseen);
		
		$allMessages_Q = mysql_query("SELECT msgFrom FROM lastMessage WHERE msgTo = ".userID." AND time+3>=".time()." ORDER BY time DESC ");
		$newMessages = array();
		while($allMessages_F = mysql_fetch_assoc($allMessages_Q)){
			$personID = $allMessages_F['msgFrom'];
			$msgTable = getMsgTable($personID);
			$msg = queryResult("SELECT msg FROM $msgTable ORDER BY id DESC LIMIT 1 ");
			$newMessages[] = array(
				'personID' => $personID,
				'msg' => $msg,
				'profilePicURL' => profilePicURL($personID),
				'username' => getField('userlogin', 'username', $personID),
				'personName' => personName($personID, 'fullName', 20)
			);
		}
		$totalUnseen = queryRows("SELECT id FROM lastMessage WHERE msgTo = ".userID." AND topMenuSeen = 0 ");
		$messages = array('newMessages' => $newMessages, 'totalUnseen' => $totalUnseen);

		$newNotifs = array();
		foreach(queryFetch("SELECT * FROM notification.".userID." WHERE topMenuSeen = 0 ") as $eachNotification){
			$personID = $eachNotification['senderID'];
			$newNotifs[] = array(
				'personID' => $personID,
				
			);
		}
		$notifications = array('newNotifs' => $newNotifs, 'totalUnseen' => queryRows("SELECT id FROM notification.".userID." WHERE topMenuSeen = 0"));

		$givenChatBoxs = filterPosted('chatBoxs');
		$givenChatBox = explode('-', $givenChatBoxs);
		$openedChatboxs = array();
		for($i = 1; $i <= 4; $i++){
			$personID = (int) queryResult("SELECT chatbox$i FROM chatBoxs WHERE userID = ".userID);
			if($personID == NULL || (count($givenChatBox)>=$i && $givenChatBox[$i-1] == $personID)) continue;
			
			if(!isPersonAvail($personID)){
				mysql_query("UPDATE chatBoxs SET chatBox$i = NULL WHERE userID = ".userID);
				continue;
			}
			$openedChatboxs[] = array(
				'pID' => $personID,
				'pName' => personName($personID, true, 18),
				'pUName' => getField('userlogin', 'username', $personID),
				'pProPic' => profilePicURL($personID)
			);
		}
		
		echo json_encode(array(
			'errorCode' => 0,
			'friendRequests' => $friendRequests,
			'messages' => $messages,
			'notifications' => $notifications,
			'chatBoxs' => $openedChatboxs,
			'chatStatus' => getField('settings', 'turnOffChat') != 'A'? 1: 0,
			'chatbarPosition' => (int) getField('settings', 'chatbarPosition')
		));
		
	}

	if(isPost('listChatcards')){
		if(getField('settings', 'chatbarPosition') == 1 && getField('settings', 'chatbarPosition') != 'A'){
			$chatCards = listChatCards();
			echo json_encode(array('errorCode' => 0, 'onlineFriends' => totalOnlineFriends(), 'chatCards' => $chatCards));
		} else echo $JSN_errorCode_1;
	}

	if(isPost('chatbarPosition')){
		$chatbarPosition = filterPosted('chatbarPosition');
		if($chatbarPosition == 1){
			queryUpdate('settings', 'chatbarPosition', 1);
			if(getField('settings', 'turnOffChat') != 'A'){
				$chatCards = listChatCards();
				echo json_encode(array('errorCode' => 0, 'onlineFriends' => totalOnlineFriends(), 'chatCards' => $chatCards));
			}
		} else if($chatbarPosition == 0){
			queryUpdate('settings', 'chatbarPosition', 0);
			echo $JSN_errorCode_0;
		}
	}

	if(isPost('turnOnChat')){
		queryUpdate('settings', 'turnOffChat', 'N');
		if(getField('settings', 'chatbarPosition') == 1){
			$chatCards = listChatCards();
			echo json_encode(array('errorCode' => 0, 'onlineFriends' => totalOnlineFriends(), 'chatCards' => $chatCards));
		} else echo $JSN_errorCode_1;
	}

	if(isPost('turnOffChat')){
		queryUpdate('settings', 'turnOffChat', 'A');
	}

	if(isPost('turnOffChatFor')){

	}

	if(isPost('turnOffChatExcept')){
		
	}

	if(isPost('clearNoOfNotifs')){
		$fieldType = filterPosted('clearNoOfNotifs');
		echo $fieldType;
		if($fieldType == 0){
			mysql_query("UPDATE friendreq SET seen = 1 WHERE reqTo = ".userID." AND seen = 0 ");
		} else if($fieldType == 1){
			mysql_query("UPDATE lastMessage SET topMenuSeen = 1 WHERE msgTo = ".userID." AND topMenuSeen = 0 ");
		}
	}

	if(isPost('searchChatcards')){
		$string = filterPosted('searchChatcards');
		
		//Search Friends of friends
		$knownPersons = array();
		$knownPersons_Q = mysql_query("
								(SELECT msgFrom as personID FROM lastmessage WHERE msgTo = ".userID." AND deleted != 'R') UNION
								(SELECT msgTo as personID FROM lastmessage WHERE msgFrom = ".userID." AND deleted != 'S')
							");

		while($knownPersons_F = mysql_fetch_assoc($knownPersons_Q)){
			$personID = $knownPersons_F['personID'];
			if(!isFriend($personID) && !isMutualFriend($personID) && isPersonAvail($personID))
				array_push($knownPersons, $personID);
		}
		
		$availPersons = array_merge(friendsList(userID), $knownPersons, mutualFriends());
		$matchedPersonsArr = searchByNames($availPersons, $string);
		$matchedFriends = array();
		$matchedKnownPersons = array();

		for($i=0; $i<count($matchedPersonsArr); $i++){
			isFriend($matchedPersonsArr[$i])? $matchedFriends[] = $matchedPersonsArr[$i]: $matchedKnownPersons[] = $matchedPersonsArr[$i];
		}

		$matchedPersons = array_merge($matchedFriends, $matchedKnownPersons);
		$chatCardsArr = array();
		for($i=0; $i<15; $i++){
			if(empty($matchedPersons[$i]))
				break;

			$personID = $matchedPersons[$i];
			$lastChatbar = queryResult("SELECT lastSeen FROM ajax WHERE userID = $personID ");
			$intervalTime = time() - $lastChatbar;
			
			$lastSeen_Sec = time() - $lastChatbar;
			$lastSeen_Hour = $lastSeen_Sec/3600;
			$lastSeen = '';
				
			if($lastSeen_Sec<3600){
				$lastSeen = $lastSeen_Sec<60? '1Min': (int) ($lastSeen_Sec/60).'Min';
			} else if($lastSeen_Sec<86400){
				$lastSeenHr = (int) $lastSeen_Hour;
				$lastSeenMin = (int) (($lastSeen_Hour - $lastSeenHr)*60);
				$lastSeen = $lastSeenHr.'Hr '.$lastSeenMin.'Min';
			}
					
			$personProperties['personID'] = $personID;
			$personProperties['username'] = getField('userlogin', 'username', $personID);
			$personProperties['personName'] = personName($personID, true);
			$personProperties['profilePicURL'] = profilePicURL($personID, 32);
			$personProperties['className'] = isFriend($personID)? 0: 1;
			$personProperties['chatStatus'] = $intervalTime<=10? 1: $lastSeen;
					
			$chatCardsArr[] = $personProperties;
		}

		echo json_encode(array('errorCode' => 0, 'onlineFriends' => totalOnlineFriends(), 'chatCards' => $chatCardsArr));
	}

	if(isPost('openChatbox')){
		echo 'okay';
	}

	if(isPost('chatboxConversation', 'type')){
		$personID = filterPosted('chatboxConversation');
		$type = filterPosted('type');
		if(isPersonAvail($personID)){
			for($i = 1; $i <= 4; $i++){
				if(isChatboxOpen($personID)) break;
				if(queryRows("SELECT id FROM chatBoxs WHERE userID = ".userID." AND chatbox$i IS NULL") == 1){
					queryUpdate('chatBoxs', 'chatbox'.$i , $personID);
					break;
				}
			}
			if($type === 1 && queryRows("SELECT id FROM lastMessage WHERE msgFrom = $personID AND msgTo = ".userID." AND seenTime = 0  ") == 1)
				mysql_query("UPDATE lastMessage SET seen = 1, seenTime = ".time()." WHERE msgFrom = $personID AND msgTo = ".userID);
			$msgTable = getMsgTable($personID);
			$lastSeenMsg = queryResult("SELECT id FROM $msgTable ORDER BY id DESC LIMIT 1 ");
			mysql_query("UPDATE lastMessage SET lastSeenMsgID = $lastSeenMsg WHERE (msgFrom = ".userID." AND msgTo = $personID) OR (msgFrom = $personID AND msgTo = ".userID.") ");
			$QallMessages = mysql_query("SELECT id, senderID, msg FROM $msgTable ORDER BY id DESC LIMIT 50");

			$chatboxConversation = array();
			while($messageProperties = mysql_fetch_assoc($QallMessages)){
				if(isMsgAvail($personID, $messageProperties['id'])){
					$msg = addEmoticons(nl2br($messageProperties['msg']), 'chatboxEmoticon');
					$eachMessage['id'] = $messageProperties['id'];
					$eachMessage['type'] = $messageProperties['senderID'] == userID? 1:0;
					$eachMessage['msg'] = $msg;
					$chatboxConversation[] = $eachMessage;
				}
			}

			$chatboxConversation = array_reverse($chatboxConversation);
			echo json_encode(array('errorCode' => 0, 'messages' => $chatboxConversation, 'profilePicURL' => profilePicURL($personID, 64)));
		}
	}

	if(isPost('chatboxSendMsg', 'message', 'personID')){
		$msg = filterPosted('message');
		$personID = filterPosted('personID');
		if(isPersonAvail($personID)){
			$timeNow = time();
			$msgTable = getMsgTable($personID);
			$lastMsgID = queryResult("SELECT id FROM lastMessage WHERE (msgFrom = ".userID." AND msgTo = $personID) OR (msgFrom = $personID AND msgTo = ".userID.") ");

			if(!empty($lastMsgID))
				mysql_query("UPDATE lastMessage SET msgFrom = ".userID.", msgTo = $personID, time = ".time().", seen = 0, seenTime = 0, topMenuSeen = 0 WHERE  id = $lastMsgID ");
			else
				mysql_query("INSERT INTO lastMessage (msgFrom, msgTo, time) VALUES (".userID.", $personID, ".time().") ");

			if(!isTableExists($msgTable)){
				mysql_query("CREATE TABLE IF NOT EXISTS $msgTable (
				  id int(11) NOT NULL AUTO_INCREMENT,
				  senderID int(11) NOT NULL,
				  msg varchar(3000) NOT NULL,
				  deleted varchar(1) NOT NULL,
				  time int(11) NOT NULL,
				  PRIMARY KEY (id)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
			}
			mysql_query("INSERT INTO $msgTable (senderID, msg, time) VALUES(".userID.", '$msg', $timeNow) ");

			//echo queryResult("SELECT id FROM $msgTable ORDER BY id DESC LIMIT 1 ");
			echo json_encode(array('eC' => 0, 'mID' => queryResult("SELECT id FROM $msgTable ORDER BY id DESC LIMIT 1 ")));
		}
		
			//echo json_encode(array('errorCode' => 0, 'personName' => personName($personID, 'fullName'), 'username' => getField('userlogin', 'username', $personID), 'profilePic' => profilePicURL($personID, 64)));
	}

	if(isPost('updateChatbox')){
		$personsIDs = filterPosted('updateChatbox');
		$personIDArr = explode('-', $personsIDs);
		$JSN_response = array();
		for($i = 0; $i < 4; $i++){
			if(empty($personIDArr[$i]) || empty(explode('_', $personIDArr[$i]))) break;

			$personID = explode('_', $personIDArr[$i])[0];

			if(!isChatboxOpen($personID)){
				$JSN_response[] = array('pID' => $personID, 'status' => 0);
				continue;
			}

			$msgTable = getMsgTable($personID);
			$lastAjax = queryResult("SELECT lastSeenMsgID FROM lastMessage WHERE msgFrom = $personID AND msgTo = ".userID);
			$lastAjax = empty($lastAjax)? queryResult("SELECT id FROM $msgTable ORDER BY id DESC LIMIT 1"): $lastAjax;
			$lastAjax = explode('_', $personIDArr[$i])[1];
			$allUnseenMsgs_Q  = mysql_query("SELECT id, msg, senderID, time FROM $msgTable WHERE id > $lastAjax AND ((senderID = ".userID." AND deleted != 'S') OR (senderID = $personID AND deleted != 'R')) ");
			$unseenMsgs = array();
			while($unseenMsgs_F = mysql_fetch_assoc($allUnseenMsgs_Q)){
				$type = $unseenMsgs_F['senderID'] == userID? 1: 0;
				$unseenMsgs[] = array('type' => $type, 'id' => $unseenMsgs_F['id'], 'msg' => nl2br($unseenMsgs_F['msg']));
			}

			if(queryRows("SELECT id FROM lastMessage WHERE msgFrom = ".userID." AND msgTo = $personID AND seen = 1 ") == 1){
				//mysql_query("UPDATE lastMessage SET seen = 0 WHERE msgFrom = ".userID." AND msgTo = $personID ");
				$seenTime = queryResult("SELECT seenTime FROM lastMessage WHERE msgFrom = ".userID." AND msgTo = $personID ");
				$JSN_response[] = array('pID' => $personID, 'seen' => 1, 'time' => time2String($seenTime, false));
			} else if(count($unseenMsgs)>0)
				$JSN_response[] = array('pID' => $personID, 'proPic' => profilePicURL($personID, 32), 'msgs' => $unseenMsgs);

			$lastSeenMsg = queryResult("SELECT id FROM $msgTable ORDER BY id DESC LIMIT 1 ");
			mysql_query("UPDATE lastMessage SET lastSeenMsgID = $lastSeenMsg WHERE msgFrom = $personID AND msgTo = ".userID);
		}

		if(count($JSN_response)>0) echo json_encode(array('eC' => 0, 'newMsgs' => $JSN_response));
	}

	if(isPost('msgSeen')){
		$personID = filterPosted('msgSeen');
		mysql_query("UPDATE lastMessage SET seen = 1, topMenuSeen = 1, seenTime = ".time()." WHERE msgFrom = $personID AND msgTo = ".userID." AND seenTime = 0");
	}

	if(isPost('createPost', 'postText')){
		$postText = filterPosted('postText');
		$postImage = null;
		if(createPost($postText, $postImage, '')){
			$postID = userID.'_'.queryResult("SELECT id FROM posts.".userID." ORDER BY id DESC LIMIT 1 ");
			echo json_encode(array('errorCode' => 0, 'postID' => $postID));
		  } else 
		  	echo $JSN_errorCode_1;
	};

	if(isPost('deletePost')){
		$postID = filterPosted('deletePost');
		echo deletePost($postID) || !isPostExist($postID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('likePost')){
		$postID = filterPosted('likePost');
		echo likePost($postID) || isLiked($postID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('unlikePost')){
		$postID = filterPosted('unlikePost');
		echo unlikePost($postID) || !isLiked($postID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('hatePost')){
		$postID = filterPosted('hatePost');
		echo hatePost($postID) || isHated($postID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('unhatePost')){
		$postID = filterPosted('unhatePost');
		echo unhatePost($postID) || !isHated($postID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('postComment', 'postID', 'commentText')){
		list($postID, $commentText) = array(filterPosted('postID'), filterPosted('commentText'));
		if(createComment($postID, $commentText)){
			$commentLocalID = queryResult("SELECT id FROM postPromotion.$postID WHERE type = 'C' AND personID = ".userID." ORDER BY id DESC LIMIT 1 ");
			echo json_encode(array('errorCode' => 0, 'commentLocalID' => $commentLocalID));
		} else 
			echo $JSN_errorCode_1;
	}

	if(isPost('userDetails_JSN')){
		echo json_encode(array(
			'userID' => userID, 
			'username' => getField('userlogin', 'username'), 
			'entireName' =>  personName(userID, true), 
			'profilePicURL' => profilePicURL(userID)
		));
	}

	if(isPost('personDetails_JSN')){
		$friendsList = friendsList(userID);
		$knownPersons = array();
		$personsDetails = array();
		$allPersons = array_merge($friendsList, $knownPersons);
		for($i=0; $i<count($allPersons); $i++){
			$personID = (int) $allPersons[$i];
			$eachPerson['personID'] = $personID;
			$eachPerson['username'] = getField('userlogin', 'username', $personID);
			$eachPerson['entireName'] = personName($personID, true);
			$eachPerson['profilePicURL'] = profilePicURL($personID);
			$personsDetails[] = $eachPerson;
		}
		echo json_encode(array('personDetails' => $personsDetails));
	}

	if(isPost('deleteComment')){
		$commentID = filterPosted('deleteComment');
		echo deleteComment($commentID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('setChangeName') && isPost('firstName') && isset($_POST['middleName']) && isset($_POST['lastName']) && isset($_POST['nickName'])){
		$timeNow = time();
		$nameTypeArr = array("firstName", "middleName", "lastName", "nickName");
		for($i = 0; $i<4; $i++){
			$nameArr[$i] = $_POST[$nameTypeArr[$i]];
		}
		
		$intervalTime = $timeNow - getField('settings', 'nameChanged');
		if(isNameAvail($nameArr) && $intervalTime > 60*60*24*7){
			if(!empty($nameArr[1]) && empty($nameArr[2])){
				$nameArr[2] = $nameArr[1];
				$nameArr[1] = '';
			}
			
			for($i=0; $i<4; $i++){
				if($i != 3)
					$nameArr[$i] = ucfirst(strtolower($nameArr[$i]));

				queryUpdate('personalinfo', $nameTypeArr[$i], filterVar($nameArr[$i]));
			}
			queryUpdate('settings', 'nameChanged', time());

			echo 1;
		}
	}

	if(isPost('chkUsernameAvail')){
		$username = filterPosted('chkUsernameAvail');
		if(isUsernameAvail($username))
			echo 1;
		else
			echo 0;
	}

	if(isPost('set_isMailAvail')){
		if(isEmailAvail(filterPosted('set_isMailAvail'), true))
			echo $JSN_errorCode_0;
		else
			echo $JSN_errorCode_1;
	}

	if(isPost('setChangeUsername')){
		$username = filterPosted('setChangeUsername');
		$timeNow = time();
		$lastUpdated = getField('settings', 'usernameChanged', userID);
		$intervalTime = $timeNow - $lastUpdated;
		if(($intervalTime < 60*60*24*30*2 || $lastUpdated == 0) && isUsernameAvail($username)){
			queryUpdate('settings', 'usernameChanged', $timeNow);
			mysql_query("UPDATE userlogin SET username = '$username' WHERE id = ".userID." ");
			echo 1;
		}
	}

	if(isPost('setChangeEmail')){
		$email = filterPosted('setChangeEmail');
		if(isEmailAvail($email, true)){
			mysql_query("UPDATE userlogin SET email = '$email' WHERE id = ".userID." ");
			//new mail activation
			echo 1;
		}
	}

	if(isPost('setChangePassword', 'currentPassword', 'newPassword')){
		$currentPassword = filterPosted('currentPassword');
		$newPassword = filterPosted('newPassword');
		$currentHash = getField('userlogin', 'password', userID);
		if(isStrlen($currentPassword, 6, 64) && isStrlen($newPassword, 6, 64)){
			if(password_verify($currentPassword, $currentHash)){
				$newHash = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 12));
				queryUpdate('account_info', 'lastPassword', $currentHash);
				queryUpdate('account_info', 'lastPasswordChanged', time());
				queryUpdate('userlogin', 'password', $newHash);
				echo $JSN_errorCode_0;
			} else
				echo $JSN_errorCode_1;
		}
	}

	if(isPost('setChangeLoginNotif')){
		$newSetting = filterPosted('setChangeLoginNotif');
		if($newSetting == 'T' || $newSetting == 'F'){
			$currentSetting = getField('settings', 'loginNotif', userID);
			if($currentSetting != $newSetting){
				queryUpdate('settings', 'loginNotif', $newSetting);
				echo $JSN_errorCode_0;
			}
		}
	}

	if(isPost('set_Gender')){
		$newSetting = filterPosted('set_Gender');
		if($newSetting == 'm' || $newSetting == 'f'){
			$currentSetting = getField('personalinfo', 'gender');
			if($currentSetting != $newSetting){
				queryUpdate('personalinfo', 'gender', $newSetting);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_DOB') && isPost('date') && isPost('month') && isPost('year')){
		$date = filterPosted('date');
		$month = filterPosted('month');
		$year = filterPosted('year');
		$dobTime = isValidDOB($date, $month, $year);
		if(!empty($dobTime)){
			queryUpdate('personalinfo', 'dob', $dobTime);
			echo $JSN_errorCode_0;
		} else
			echo $JSN_errorCode_1;
	}

	if(isPost('set_isMobNumberAvail')){
		$mobileNum = filterPosted('set_isMobNumberAvail');
		if(strlen($mobileNum) == 10 && !preg_match('/[^0-9]/', $mobileNum) && queryRows('personalinfo', 'mobileNumber', $mobileNum) == 0){
			echo $JSN_errorCode_0;	
		} else
			echo $JSN_errorCode_1;
	}

	if(isPost('set_MobileNumber')){
		$mobileNum = filterPosted('set_MobileNumber');
		if(strlen($mobileNum) == 10 && !preg_match('/[^0-9]/', $mobileNum) && queryRows('personalinfo', 'mobileNumber', $mobileNum) == 0){
			queryUpdate('personalinfo', 'mobileNumber', $mobileNum);
			echo $JSN_errorCode_0;
		} else
			echo $JSN_errorCode_1;

	}

	if(isPost('set_isSecMailAvail')){
		$mailID = filterPosted('set_isSecMailAvail');
		$isAnyMail = queryResult("SELECT id FROM userlogin WHERE email = '$mailID' ");
		if(strlen($mailID)<129 && filter_var($mailID, FILTER_VALIDATE_EMAIL) && queryRows('personalinfo', 'secMail', $mailID) == 0 && (empty($isAnyMail) || $isAnyMail == userID))
			echo $JSN_errorCode_0;
		else
			echo $JSN_errorCode_1;
	}

	if(isPost('set_SecMail')){
		$mailID = filterPosted('set_SecMail');
		$isAnyMail = queryResult("SELECT id FROM userlogin WHERE email = '$mailID' ");
		if(strlen($mailID)<129 && filter_var($mailID, FILTER_VALIDATE_EMAIL) && queryRows('personalinfo', 'secMail', $mailID) == 0 && (empty($isAnyMail) || $isAnyMail == userID)){
			queryUpdate('personalinfo', 'secMail', $mailID);
			echo $JSN_errorCode_0;
		} else
			echo $JSN_errorCode_1;
	}

	if(isPost('set_currentPosition', 'city', 'state', 'country')){
		$currentPosition[0] = filterPosted('city');
		$currentPosition[1] = filterPosted('state');
		$currentPosition[2] = filterPosted('country');
		$isAvail = true;
		for($i=0; $i<3; $i++){
			if(!isStrlen($currentPosition[$i], 3, 32) || preg_match('/[^a-zA-Z\s]/', $currentPosition[$i])){
				$isAvail = false;
				break;
			}
		}

		if($isAvail == true){
			$fieldsArr = array('currentCity', 'currentState', 'currentCountry');
			for($i=0;$i<3;$i++){
				queryUpdate('personalinfo', $fieldsArr[$i], ucfirst(strtolower($currentPosition[$i])));
			}

			echo $JSN_errorCode_0;
		} else
			echo $JSN_errorCode_1;

	}

	if(isPost('set_workStatus') && isPost('position') && isPost('company')){
		$workStatus[0] = filterPosted('position');
		$workStatus[1] = filterPosted('company');
		$isAvail = true;
		for($i=0; $i<2; $i++){
			if(!isStrlen($workStatus[$i], 3, 32) || preg_match('/[^a-zA-Z\s]/', $workStatus[$i])){
				$isAvail = false;
				break;
			}
		}

		if($isAvail == true){
			$fieldsArr = array('workPost', 'workComp');
			for($i=0; $i<2; $i++){
				queryUpdate('personalinfo', $fieldsArr[$i], $workStatus[$i]);
			}

			echo $JSN_errorCode_0;
		} else
			echo $JSN_errorCode_1;
	}

	if(isPost('setBasicPrivacy')){
		$newSetting = filterPosted('setBasicPrivacy');
		$availSettings = array('o', 'f', 'm', 'e');
		if(in_array($newSetting, $availSettings)){
			$oldSetting = getField('settings', 'basicPrivacy', userID);
			if($oldSetting != $newSetting){
				queryUpdate('settings', 'basicPrivacy', $newSetting);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('setChangeTimelinePrivacy')){
		$availParams = array('e', 'o', 'f', 'm');
		$permission = filterPosted('setChangeTimelinePrivacy');
		if(in_array($permission, $availParams)){
			$oldSetting = getField('settings', 'timelinePermission', userID);
			if($permission != $oldSetting){
				queryUpdate('settings', 'timelinePermission', $permission);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_ReqPermission')){
		$availParams = array('e', 'm');
		$newSetting = filterPosted('set_ReqPermission');
		if(in_array($newSetting, $availParams)){
			$currentSetting = getField('settings', 'reqPermission', userID);
			if($newSetting != $currentSetting){
				queryUpdate('settings', 'reqPermission', $newSetting);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_ChatPermission')){
		$availParams = array('e', 'm', 'f');
		$newSetting = filterPosted('set_ChatPermission');
		if(in_array($newSetting, $availParams)){
			$currentSetting = getField('settings', 'chatPermission', userID);
			if($newSetting != $currentSetting){
				queryUpdate('settings', 'chatPermission', $newSetting);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_PromotePermission')){
		$availParams = array('e', 'm', 'f');
		$newSetting = filterPosted('set_PromotePermission');
		if(in_array($newSetting, $availParams)){
			$currentSetting = getField('settings', 'postPromotPermission', userID);
			if($newSetting != $currentSetting){
				queryUpdate('settings', 'postPromotPermission', $newSetting);
			}

			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_UndoSpam')){
		$personID = filterPosted('set_UndoSpam');
		if(undoSpam($personID)){
			echo $JSN_errorCode_0;
		}
	}

	if(isPost('set_UnblockPerson')){
		$personID = filterPosted('set_UnblockPerson');
		if(unblockPerson($personID)){
			echo $JSN_errorCode_0;
		}
	}

	if(isPost('seeMorePosts')){
		$postID = filterPosted('seeMorePosts');
		$allPostsArr = homepagePosts();
		if(isPostExist($postID)){
			for($i = 0; $i < count($allPostsArr); $i++){
				if($postID == $allPostsArr[$i]['postID']){
					$nextPostPosition = $i + 1;
					break;
				}
			}
		}

		$responseText =  null;
		if(!empty($nextPostPosition)){
			for($i = $nextPostPosition; $i < $nextPostPosition + 25; $i++){
				if(empty($allPostsArr[$i])) break;

				$postProperties = echoPost($allPostsArr[$i]['postID']);
				$commentArea_className = totalComments($allPostsArr[$i]['postID'])<=6? 'show': 'hide';
				$post_HTML = $postProperties[0].$postProperties[1].$postProperties[2].'<div class="commentArea '.$commentArea_className.'">'.$postProperties[3].'</div>';
				$responseText .= '<div id="'.$allPostsArr[$i]['postID'].'" class="post">'.$post_HTML.'</div>';
				
				$nextPostPosition_Ajax = $i + 1;
			}

			if(($nextPostPosition_Ajax) < count($allPostsArr)){
				$responseText .= '<button class="showMorePosts" onclick="showMorePosts()">See More...</button>';
			}
			
			echo $responseText;
		}

	}

	if(isPost('showMoreComments')){
		$lastCommentID = filterPosted('showMoreComments');
		if(isCommentExits($lastCommentID)){
			$commentIDArr = explode('_', $lastCommentID);
			$postID = $commentIDArr[0].'_'.$commentIDArr[1];
			$lastCommentLocalID = $commentIDArr[2];
			$remainComments_Q = mysql_query("SELECT id, personID, commentText, time FROM postPromotion.$postID WHERE id < $lastCommentLocalID AND type = 'C' ORDER BY time DESC LIMIT 25");
			$commentsArr = array();
			while($remainComments = mysql_fetch_assoc($remainComments_Q)){
				$personID = $remainComments['personID'];
				$commentsArr[] = array(
					'id' => $postID.'_'.$remainComments['id'], 
					'username' => getField('userlogin', 'username', $personID), 
					'personName' => personName($personID, true), 
					'profilePicURL' => profilePicURL($personID, 32), 
					'commentText' => $remainComments['commentText'],
					'time' => time2String($remainComments['time']), 
					'deleteComment' => userID == explode('_', $postID)[0] || userID == $personID? 1: 0
				);
			}

			$lastCommentIDAjax = explode('_', end($commentsArr)['id'])[2];
			$showMoreComment = queryRows("SELECT id FROM postPromotion.$postID WHERE id < $lastCommentIDAjax AND type = 'C' ") > 0? 1: 0;
			echo json_encode(array('errorCode' => 0, 'showMoreComments' => $showMoreComment, $commentsArr));
		}
	}

	if(isPost('searchNames', '!name', '!gender', '!ageFrom', '!ageTo', '!mobileNumber', '!emailID', '!work', '!location')){
		list($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location) = filterPosted('name', 'gender', 'ageFrom', 'ageTo', 'mobileNumber', 'emailID', 'work', 'location');
		if(!$gender) $gender = 'B';
		$matchedPersons = graphicalSearch($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location);
		
		for($i = 0; $i < 50; $i++){
			if(empty($matchedPersons[$i])) break;

			$personID = $matchedPersons[$i];
			$personProperties['personID'] = $personID;
			$personProperties['personName'] = personName($personID, true, 30);
			$personProperties['profilePicURL'] = profilePicURL($personID, 92);
			$personProperties['username'] = getField('userlogin', 'username', $personID);
			$personProperties['work'] = personProfession($personID, true);
			$personProperties['location'] = personLocation($personID, true);
			$personProperties['mutualFriends'] = $personID == userID? 0: totalMutualFriends($personID);
			$matchedPersons_JSN[] = $personProperties;
		}

		$showMoreResults = count($matchedPersons) > $i? 1: 0;
		$totalResults = count($matchedPersons);
		
		if(!empty($matchedPersons))
			echo json_encode(array('errorCode' => 0, 'showMore' => $showMoreResults, 'totalResults' => $totalResults, 'matchedPersons' => $matchedPersons_JSN));
		else
			echo $JSN_errorCode_1;
	}

	if(isPost('showMoreResults', 'lastResultPosition', '!name', '!gender', '!ageFrom', '!ageTo', '!mobileNumber', '!emailID', '!work', '!location')){
		$name = filterPosted('name');
		$gender = empty(filterPosted('gender'))? 'b': filterPosted('gender');
		$ageFrom = filterPosted('ageFrom');
		$ageTo = filterPosted('ageTo');
		$mobileNumber = filterPosted('mobileNumber');
		$emailID = filterPosted('emailID');
		$work = filterPosted('work');
		$location = filterPosted('location');

		$matchedPersons = graphicalSearch($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location);
		$lastResultPosition = filterPosted('lastResultPosition');

		for($i=$lastResultPosition; $i<$lastResultPosition+50; $i++){
			if(empty($matchedPersons[$i]))
				break;

			$personID = $matchedPersons[$i];
			$personProperties['personID'] = $personID;
			$personProperties['personName'] = personName($personID, true, 41);
			$personProperties['profilePicURL'] = profilePicURL($personID);
			$personProperties['username'] = getField('userlogin', 'username', $personID);
			$personProperties['profession'] = personProfession($personID, true);
			$personProperties['location'] = personLocation($personID, true);
			$personProperties['mutualFriends'] = totalMutualFriends($personID);
			$remainPersons[] = $personProperties;
		}

		$showMoreResults = count($matchedPersons) > $i? 1: 0;

		if(!empty($remainPersons))
			$JSN_response = array('errorCode' => 0, 'matchedPersons' => $remainPersons, 'showMore' => $showMoreResults);
		else
			$JSN_response = array('errorCode' => 1);
		
		echo json_encode($JSN_response);
	}

	if(isPost('addSuggestedFriend', 'suggestedIDs')){
		$personID = filterPosted('addSuggestedFriend');
		$suggestedIDs = explode('-', filterPosted('suggestedIDs'));
		//if(sendFriendRequest($personID)){
			
			echo $JSN_errorCode_0;

		//} else
		//	echo $JSN_errorCode_1;
	}

	if(isPost('sendRequest')){
		$personID = filterPosted('sendRequest');
		echo sendFriendRequest($personID) || isSentRequest($personID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('cancelRequest')){
		$personID = filterPosted('cancelRequest');
		echo cancelFriendRequest($personID) || !isRecvRequest($personID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('deleteRequest')){
		$personID = filterPosted('deleteRequest');
		echo ignoreFriendRequest($personID) || !isRecvRequest($personID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('acceptRequest')){
		$personID = filterPosted('acceptRequest');
		echo acceptFriendRequest($personID) || isFriend($personID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('unFriend')){
		$personID = (int) filterPosted('unFriend');
		echo unFriend($personID) || !isFriend($personID)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('markSpam')){
		$personID = filterPosted('markSpam');
		echo markSpam($personID) || isSpam($personID, true)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('undoSpam')){
		$personID = filterPosted('undoSpam');
		echo undoSpam($personID) || !isSpam($personID, true)? $JSN_errorCode_0: $JSN_errorCode_1;
	}

	if(isPost('instantSearch')){
		$searchString = filterPosted('instantSearch');
		$allMatchedPersons = graphicalSearch($searchString, null, null, null, null, null, null, null);
		shuffle($allMatchedPersons);
		$matchedPersons = array();
		for($i = 0; $i < 5; $i++){
			if(empty($allMatchedPersons[$i])) break;
			$personID = $allMatchedPersons[$i];
			$matchedPersons[] = array(
									'personID' => $personID, 
									'username' => getField('userlogin', 'username', $personID),
									'profilePicURL' => profilePicURL($personID), 
									'personName' => personName($personID, 'fullName', 32), 
									'personDesc' => personDesc($personID)
								);
		}
		echo json_encode(array('errorCode' => 0, 'matchedPersons' => $matchedPersons));
	}

	if(isPost('closeChatbox')){
		$personID = filterPosted('closeChatbox');
		for($i = 1; $i <= 4; $i++){
			if(!isChatboxOpen($personID)) break;
			if(queryRows("SELECT id FROM chatBoxs WHERE userID = ".userID." AND chatbox$i = $personID ")){
				mysql_query("UPDATE chatBoxs SET chatbox$i = NULL WHERE userID = ".userID);
				break;
			}
		}

		while($i <= 4){
			$j = $i + 1;
			if($i == 4 || queryRows("SELECT id FROM chatBoxs WHERE userID = ".userID." AND chatbox$j IS NULL ")) break;
			$nextPersonID = queryResult("SELECT chatbox$j FROM chatBoxs WHERE userID = ".userID);
			mysql_query("UPDATE chatBoxs SET chatbox$i = $nextPersonID, chatbox$j = NULL WHERE userID = ".userID);
			$i++;
		}
	}

	if(isPost('likePage')){
		$pageID = filterPosted('likePage');
		if(isPage($pageID)){
			if(!isLiked($pageID))
				queryInsert('pages.'.$pageID, array('personID'), array(userID));
			echo $JSN_errorCode_0; 
		}
	}

	if(isPost('unlikePage')){
		$pageID = filterPosted('unlikePage');
		if(isPage($pageID)){
			if(isLiked($pageID)){
				queryDelete('pages.'.$pageID, array('personID'), array(userID));
				echo $JSN_errorCode_0;
			} else
				echo $JSN_errorCode_1;
		}
	}

	if(isPost('hoverCard')){
		$personID = filterPosted('hoverCard');
		if(userID == $personID || isPersonAvail($personID)){
			$personDesc = array(personLocation($personID, true), personProfession($personID, true));
			$personDesc2 = array(personAge($personID, false), friendsList($personID, true), totalMutualFriends($personID, true));
			$personDesc3 = $personDesc2[0];
			for($i = 1; $i < count($personDesc2); $i++){
				if(!$personDesc2[$i]) continue;
				$personDesc3 .= $personDesc3? ' <span>·</span> '.$personDesc2[$i]: $personDesc2[$i];
			}


			$personDesc[] = $personDesc3;
			for($i = 0; $i < count($personDesc); $i++){
				if(!$personDesc[$i])
					continue;
				else if(!isset($aboutPerson1))
					$aboutPerson1 = $personDesc[$i];
				else if(!isset($aboutPerson2))
					$aboutPerson2 = $personDesc[$i];
				else
					$aboutPerson3 = $personDesc[$i];
			}

			echo json_encode(array(
				'errorCode' => 0,
				'hoverCardDetails' =>
					array(
						'personName' => personName($personID, 20),
						'userName' => getField('userLogin', 'userName', $personID),
						'coverPic' => coverPicURL($personID, false),
						'profilePic' => profilePicURL($personID),
						'personDesc' => $personDesc,
						'aboutPerson1' => $aboutPerson1,
						'aboutPerson2' => isset($aboutPerson2)? $aboutPerson2: '',
						'aboutPerson3' => isset($aboutPerson3)? $aboutPerson3: '',
						'button' => personRelationButton($personID, true)
					)
			));
		} else {
			echo json_encode(array('errorCode' => 1));
		}
	}

?>