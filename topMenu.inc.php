<?php
	$personIDOld = !empty($personID)? $personID: null;
	list($maxChatbarStatus, $minChatbarStatus) = getField('settings', 'chatbarPosition') == 1? array('block', 'none'): array('none', 'block');
	$chatOnOffStatus = getField('settings', 'turnOffChat') == 'A'? 'Off': 'On';
	list($chatbarContent, $chatbarPersons) = array(null, listChatCards());
	foreach($chatbarPersons as $eachPerson){
		$personID = $eachPerson['personID'];
		$chatbarContent .=
			'<div id="chatcard_'.$personID.'" class="chatCard" onclick="openChatbox(\''.$personID.'\', \''.personName($personID, true, 18).'\', \''.getField('userLogin', 'username', $personID).'\', \''.profilePicURL($personID, 64).'\')">
			    <img src="'.$eachPerson['profilePicURL'].'">
			    <div id="personName">'.$eachPerson['personName'].'</div>
			    <div id="onlineGreenLight">'.$eachPerson['chatStatus'].'</div>
			</div>'
		;
	}
?>

<?php
	echo '<div class="chatbar '.$chatOnOffStatus.'" style="display:'.$maxChatbarStatus.'" >
			<div class="chatbarContent">'.$chatbarContent.'</div>';
?>
	<div id="chatbarDown" class="chatbarSearch">
		<div class="turnOnChat" <?php if(getField('settings', 'turnOffChat') != 'A') echo 'style="display:none"'; ?> onclick="turnOnOffChat()">Turn on chat</div>
		<div class="dropdownMenu">
			<ul>
				<li onclick="hideChatbar()">Hide Sidebar</li>
				<li class="onOffChat" onclick="turnOnOffChat()"><?php echo getField('settings', 'turnOffChat') == 'A'? 'Turn On chat': 'Turn Off chat'; ?></li>
			</ul>
		</div>
		<input type="text" placeholder="Search..." onkeyup="searchChatcards();" id="chatbarSearchBox">
		<img src="http://localhost/TetramandoX/img/settings_gear.png" class="settingsIcon">
	</div>
</div>

<div class="minimizedChatbar <?php echo $chatOnOffStatus; ?>" style="display:<?php echo $minChatbarStatus; ?>" onclick="showChatbar()">
	<img src="http://localhost/TetramandoX/img/greenLight.png" id="greenLight">
	Online Friends <span style="font:normal 14px arial" class="totalOnlineFriends">(<?php echo totalOnlineFriends(); ?>)</span>
</div>
<div id="chatBoxs" class="chatBoxs">
<?php
	$chatBoxs = getField('chatBoxs', array('chatBox1', 'chatBox2', 'chatBox3', 'chatBox4'));
	for($i = 0; $i < 4 ; $i++){
		$personID = $chatBoxs[$i];
		if(!isPersonAvail($personID)) continue;
		$allMessages = '';
		$profilePicURL = profilePicURL($personID);
		mysql_query("UPDATE lastMessage SET seen = 1, seenTime = ".time()." WHERE msgFrom = $personID AND msgTo = ".userID." AND seenTime = 0 ");
		$allMessagesArr = array_reverse(queryFetch("SELECT id, senderID, msg FROM ".getMsgTable($personID)." WHERE (senderID = $personID AND deleted != 'R') OR (senderID = ".userID." AND deleted != 'S') ORDER BY id DESC LIMIT 50"));
		foreach($allMessagesArr as $eachMessage){
			$messageText = addEmoticons(nl2br($eachMessage['msg']), 'chatboxEmoticon');
			$messageID= $eachMessage['id'];
			$className = $eachMessage['senderID'] == userID? 'sent': 'recv';
			$profilePic = $className == 'recv'? '<img src="'.$profilePicURL.'" class="chatboxProfilePic">' : null;
			$allMessages .= '<div id="msgID_'.$messageID.'" class="chatboxMsg '.$className.'">'.$profilePic.'<div class="chatboxMsgText">'.$messageText.'</div></div>';
		}
		echo
			'<div id="chatbox_'.$personID.'" class="chatbox">
				<div class="chatboxTitle">
					<div class="personName">
						<a href="http://localhost/TetramandoX/'.getField('userlogin', 'username', $personID).'">'.personName($personID, 'fullName', 18).'</a>
					</div>
					<img src="http://localhost/TetramandoX/img/close_4_hover.png" onclick="closeChatbox('.$personID.')" class="closeMark">
					<img src="http://localhost/TetramandoX/img/settings_gear.png" class="settingsGear">
				</div>
				<div id="chatboxContent_'.$personID.'" class="chatboxContent">'.$allMessages.'</div>
				<div class="chatboxSettings" style="display:none">
					<ul>
						<a href=""><li>See Full Conversation</li></a>
						<li>Turn off chat of Albedo</li>
						<li onclick="document.getElementById(\'chatbox_'.$personID.'\').querySelector(\'.chatboxContent\').innerHTML = null; ">Clear window</li>
					</ul>
				</div>
				<div class="chatboxTextarea">
					<textarea class="chatboxTextareaBox" onkeydown="sendMsgChatbox(event, '.$personID.', \''.personName($personID, 'fullName', 18).'\', \''.getField('userlogin', 'username', $personID).'\', \''.profilePicURL($personID, 64).'\'); "></textarea>
				</div>
			</div>'
		;
	}
?>
</div>
<div id="topmPanel" class="topMenu">
	<div class="topmTitle">TetroX!</div>
	<div class="searchBox">
		<form action="http://localhost/TetramandoX/search.php" method="GET">
			<input type="text" name="name" placeholder="Search..." onkeyup="instantSearch()" autocomplete="off">
			<button class="searchButton"><img src="http://localhost/TetramandoX/img/toolbar_find.png"></button>
		</form>
		<div class="searchResult">
		<?php 
			$searchPersons = graphicalSearch(null, 'B', null, null, null, null, null, null);
			for($i = 0; $i < 5; $i++){
				if(empty($searchPersons[$i])) break;
				$personID = $searchPersons[$i];
				echo
					'<a href="'.profileURL($personID).'">
						<div class="matchedPerson">
							<img class="profilePic" src="'.profilePicURL($personID).'">
							<div class="personDetail">
								<div class="personName">'.personName($personID, 'fullName', 32).'</div>
								<div class="personDesc">'.personDesc($personID).'</div>
							</div>
						</div>
					</a>'
				;
			}
		?>
			<a href="http://localhost/TetramandoX/search.php">
				<div class="allResults">Show All</div>
			</a>
		</div>
	</div>
	<div class="topMenuLinks" >
		<a href="<?php echo profileURL(userID); ?>">
			<li>
				<img src="<?php echo profilePicURL(userID, 32); ?>">
				<div class="name"><?php $firstName = getField('personalInfo', 'firstName'); echo strlen($firstName) > 8? substr($firstName, 0, 8).' ...': $firstName; ?></div>
			</li>
		</a>
		<a href="http://localhost/TetramandoX/"><li>Home</li></a>
		<a href="http://localhost/TetramandoX/search.php"><li>Search</li></a>
	</div>
	<div class="topMenuButtons">
		<li>
			<div class="topMenuButton">
				<img src="http://localhost/TetramandoX/img/topMenuPane/shield.png" class="topMenuWrapperButton" style="margin-top:1px;height:20px;width:25px;">
				<?php 
					$unseenRequests = queryRows("SELECT id FROM friendreq WHERE reqTo = ".userID." AND seen = 0 ");
					if($unseenRequests > 0)
						echo '<div class="topMenu_noOfNotifs" style="margin-top:-15px">'.$unseenRequests.'</div>';
					else
						echo '<div class="topMenu_noOfNotifs" style="margin-top:-15px;display:none;"></div>';
				?>
			</div>
			<div id="topmWrapperDivF" class="topMenuWrapper friends" style="display:none">
				<div class="title">
					<div class="desc">Friend Requests&thinsp;<span><?php echo '('.totalRecvRequests().')'; ?></span></div>
					<div class="link">
						<a href="http://localhost/TetramandoX/requests/sent">
							Sent Requests&thinsp;<span><?php echo '('.totalSentRequests().')'; ?></span>
						</a>
					</div>
				</div>
				<div class="content">
				<?php
					$totalRecvRequests = totalRecvRequests();
					$recvRequests = array();
					$recvRequests_Q = mysql_query("SELECT reqFrom FROM friendreq WHERE reqTo = ".userID." ORDER BY time DESC LIMIT 50 ");
					while($recvRequests_F = mysql_fetch_assoc($recvRequests_Q)){
						array_push($recvRequests, $recvRequests_F['reqFrom']);
					}

					for($i=0; $i<count($recvRequests); $i++){
						$personID = $recvRequests[$i];
						$totalMutualFriends = totalMutualFriends($personID);
						$personProfession = personProfession($personID, true);
						$personLocation = personLocation($personID, true);
						if($totalMutualFriends>0)
							$personDesc = '<div onclick="showAllMutualFriends()">'.$totalMutualFriends.($totalMutualFriends>1? ' mutual friends': ' mutual friend').'</div>';
						else if(empty($personProfession))
							$personDesc = '<div>'.$personProfession.'</div>';
						else if(!empty($personLocation))
							$personDesc = '<div>'.$personLocation.'</div>';

						echo '
							<div class="friendRequest" id="friendRequest_'.$personID.'">
								<img src="'.profilePicURL($personID).'">
								<div class="desc">
									<a href="'.profileURL($personID).'">'.personName($personID, 'fullName', 20).'</a>
									'.$personDesc.'
								</div>
								<div class="status">
									<button id="delete" onclick="deleteRequest(this, '.$personID.')">Delete</button>
									<button id="confirm" onclick="acceptRequest(this, '.$personID.')">Confirm</button>
								</div>
							</div>
						';
						//echo checkdate(2, 29, 2015);
					}
				?>	
				</div>
				<a href="http://localhost/TetramandoX/requests">
					<div class="footer">See All</div>
				</a>
			</div>
		</li>
		<li>
			<div class="topMenuButton">
				<img src="http://localhost/TetramandoX/img/topMenuPane/message.png" class="topMenuWrapperButton" style="margin-top:0px;">
				<?php 
					$unseenMsgs = queryRows("SELECT id FROM lastMessage WHERE msgTo = ".userID." AND topMenuSeen = 0 ");
					if($unseenMsgs > 0)
						echo '<div class="topMenu_noOfNotifs">'.$unseenMsgs.'</div>';
					else
						echo '<div class="topMenu_noOfNotifs" style="display:none;"></div>';
				?>
			</div>
			<div id="topmWrapperDivM" class="topMenuWrapper messages" style="display:none">
				<div class="title">
					<div class="desc">Inboxs&thinsp;<span><?php echo '('.totalRecvRequests().')'; ?></span></div>
				</div>
				<div class="content">
				<?php
					$lastMessagesArr = queryFetch("
						(SELECT id, msgFrom as personID, time FROM lastMessage WHERE msgTo = ".userID.") UNION
						(SELECT id, msgTo as personID, time FROM lastMessage WHERE msgFrom = ".userID.")
					");

					foreach($lastMessagesArr as $lastMessage){
						$messagesTime[] = $lastMessage['time'];
					}
					array_multisort($messagesTime, SORT_DESC, $lastMessagesArr);
					foreach($lastMessagesArr as $lastMessage){
						$personID = $lastMessage['personID'];
						$lastMessageID = $lastMessage['id'];
						if(queryResult("SELECT seenTime FROM lastMessage WHERE msgFrom = ".userID." AND msgTo = $personID")>0)
							$messageSeen = '<img src="http://localhost/TetramandoX/img/tick.png">';
						else
							$messageSeen = null;

						$className = queryRows("SELECT id FROM lastMessage WHERE msgFrom = $personID AND msgTo = ".userID." AND topMenuSeen = 0 ") == 0? ' unseen': '';

						$lastMessageText = queryResult("SELECT msg FROM ".getMsgTable($personID)." WHERE (senderID = ".userID." AND deleted != 'S') OR (senderID = $personID AND deleted != 'R') ORDER BY time DESC LIMIT 1");
						echo 
							'<div id="eachMessage_'.$personID.'" class="eachMessage'.$className.'" onClick="openChatbox('.$personID.', \''.personName($personID, 'fullName').'\', \''.getField('userlogin', 'username', $personID).'\', \''.profilePicURL($personID, 64).'\')">
								<img src="'.profilePicURL($personID, 64).'" class="profilePic">
								<div class="messageDetails">
									<div class="personName">
										'.personName($personID, 'fullName', 32).'
									</div>
									<div class="lastMessage">
										'.$messageSeen.$lastMessageText.'
									</div>
								</div>
							</div>
						';
					}

				?>
					<!-- <div class="eachMsgBox" onClick="openChatbox(36, 'Albedo Genix', 'albedo')">
						<img src="http://localhost/TetramandoX/userFiles/38_KJHFEoUrG9l9lRf0ivUe2at5TTG8TWF9/thumbs/128x128/1423855613_xhXBvZbJjhnTHsPfSIIadqnYntCOXTYW.jpg" class="profilePic">
						<div class="messageDetails">
							<div class="personName">
								Albedo Genix
							</div>
							<div class="lastMessage">
								hello akid.... wat doin....?
							</div>
						</div>
					</div> -->
				</div>
				<a href="http://localhost/TetramandoX/messages">
					<div class="footer">See All</div>
				</a>
			</div>
		</li>
		<li>
			<div class="topMenuButton">
				<img src="http://localhost/TetramandoX/img/topMenuPane/glitter.png" class="topMenuWrapperButton">
				<?php 
					$unseenRequests = getField('ajax', 'unseenNotifs');
					if($unseenRequests > 0)
						echo '<div class="topMenu_noOfNotifs">'.$unseenRequests.'</div>';
					else
						echo '<div class="topMenu_noOfNotifs" style="display:none;"></div>';
				?>
			</div>
			<div id="topmWrapperDivN" class="topMenuWrapper notification" style="display:none">
				<div class="title">
					<div class="desc">Notifications&thinsp;<span><?php echo '('.queryRows("SELECT id FROM notification.".userID).')'; ?></span></div>
					<div class="link">Mark as Read</div>
				</div>
				<div class="content">
				<?php
					//foreach(queryFetch("SELECT * FROM postPromotion.28 ") as $a){
					//	null;
					//}
					$notificationsArr = queryFetch("SELECT * FROM notification.".userID." ORDER BY time DESC ");
					foreach($notificationsArr as $eachNotif){
						if($eachNotif['postID'] && !isPostExist($eachNotif['postID'])) continue;
						switch($eachNotif['type']){
							case 'L':
								$notifLink = 'http://localhost/TetramandoX/post.php?postID='.$eachNotif['postID'];
								$notifContent = '<b>'.personName($eachNotif['senderID'], true).'</b> liked your post.';
								break;
							case 'H':
								$notifLink = 'http://localhost/TetramandoX/post.php?postID='.$eachNotif['postID'];
								$notifContent = '<b>'.personName($eachNotif['senderID'], true).'</b> hated your post.';
								break;
							case 'C':
								$notifLink = 'http://localhost/TetramandoX/post.php?postID='.$eachNotif['postID'];
								$commentText = queryResult("SELECT commentText FROM postPromotion.".$eachNotif['postID']." WHERE id = ".$eachNotif['commentID']);
								$commentText = strlen($commentText) > 32? substr($commentText, 0, 32).' ...': $commentText;
								$contentText = explode('_', $eachNotif['postID'])[0] == userID? 'commented on your post': 'also commented on this post';
								$notifContent = '<b>'.personName($eachNotif['senderID'], true).'</b> '.$contentText.' "'.$commentText.'".';
								break;
							case 'R':
								$notifLink = 'http://localhost/TetramandoX/'.getField('userlogin', 'username', $eachNotif['senderID']);
								$notifContent = '<b>'.personName($eachNotif['senderID'], true).'</b> accepted your friend request.';
								break;
						}

						if(isPicturePost($eachNotif['postID']))
							list($postPicture, $contentStyle) = array('<img src="'.pictureOfPost($eachNotif['postID']).'" class="notifPicture">', 'float:left');
						else
							list($postPicture, $contentStyle) = array(null, 'margin-left:55px');
						
						echo
							'<a href="'.$notifLink.'">
								<div class="eachNotification">
									<img src="'.profilePicURL($eachNotif['senderID'], 64).'" class="profilePic">
									<div class="notifContent" style="'.$contentStyle.'">
										<div class="notifText">'.$notifContent.'</div>
										<div class="notifTime">'.time2String($eachNotif['time'], null).'</div>
									</div>'.$postPicture.'
								</div>
							</a>'
						;
					}

				?>
				</div>
				<a href="http://localhost/TetramandoX/notifications">
					<div class="footer">See All</div>
				</a>
			</div>
		</li>
		<li style="margin-left:50px;">
			<div class="topMenuButton">
				<img src="http://localhost/TetramandoX/img/topMenuPane/left7.png" class="topMenuWrapperButton" style="width:21px;height:21px;margin-top:2px;">
			</div>
			<div id="topmWrapperDivS" class="topMenuWrapper settings" style="display:none">
				<ul>
				<?php
					foreach(queryFetch("SELECT userID FROM pageInfo WHERE adminID = ".userID) as $pageID){
						echo '<a href="'.profileURL($pageID).'"><li>'.personName($pageID, true, 14).'</li></a>';
					}
				?>
					<a><li class="menuDivider"></li></a>
					<a><li>Create Page</li></a>
					<a href="http://localhost/TetramandoX/settings"><li>Settings</li></a>
					<a href="http://localhost/TetramandoX/logout.php"><li>Logout</li></a>
					<a><li>Report a Problem</li></a>
				</ul>
			</div>
		</li>
	</div>
</div>
<?php $personID = $personIDOld; ?>
<!-- <div class="popupOverlay">
    <div class="popupBox">
        <div class="popupTitle">Mutual Friends</div>
        <div class="popupContent">
        	<div class="eachPerson">
				<img src="http://localhost/TetramandoX/userFiles/36_YKYNnX22yfKKXPsrSO91fgHTIpU5l0eT/1423855549_tOzCOHVqqBWmNGyjtDsVfWHwIyxYUgYW.jpg">
				<div class="desc">
					<a href="http://localhost/TetramandoX/akiD">Akibath Blox Mohamed (Aki10)</a>
					<div>Chairman at Orphonix Corporation</div>
				</div>
			</div>
			<div class="eachPerson">
				<img src="http://localhost/TetramandoX/userFiles/36_YKYNnX22yfKKXPsrSO91fgHTIpU5l0eT/1423855549_tOzCOHVqqBWmNGyjtDsVfWHwIyxYUgYW.jpg">
				<div class="desc">
					<a href="http://localhost/TetramandoX/akiD">Akibath Blox Mohamed (Aki10)</a>
					<div>Chairman at Orphonix Corporation</div>
				</div>
			</div>
			<div class="eachPerson">
				<img src="http://localhost/TetramandoX/userFiles/36_YKYNnX22yfKKXPsrSO91fgHTIpU5l0eT/1423855549_tOzCOHVqqBWmNGyjtDsVfWHwIyxYUgYW.jpg">
				<div class="desc">
					<a href="http://localhost/TetramandoX/akiD">Akibath Blox Mohamed (Aki10)</a>
					<div>Chairman at Orphonix Corporation</div>
				</div>
			</div>
			<div class="eachPerson">
				<img src="http://localhost/TetramandoX/userFiles/36_YKYNnX22yfKKXPsrSO91fgHTIpU5l0eT/1423855549_tOzCOHVqqBWmNGyjtDsVfWHwIyxYUgYW.jpg">
				<div class="desc">
					<a href="http://localhost/TetramandoX/akiD">Akibath Blox Mohamed (Aki10)</a>
					<div>Chairman at Orphonix Corporation</div>
				</div>
			</div>
        </div>
        <div class="popupButtons">
        	<button>OK</button>
        	<button class="closePopupBox" onclick="this.parentNode.parentNode.parentNode.remove()">Cancel</button>
        </div>
    </div>
</div> -->