<?php
	define('core_inc', true);
	define('topMenu_inc', true);
	require '../core.inc.php';
	login2Continue();

?>
<!DOCTYPE html>
<html>
<head>
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/messages.js"></script>
	<link href="http://localhost/TetramandoX/css/topMenu.css" type="text/css" rel="stylesheet">
	<link href="http://localhost/TetramandoX/css/messages.css" type="text/css" rel="stylesheet">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Messages</title>
</head>
<body>
	<?php require '../topMenu.inc.php'; ?>
	<div id="MmainBody">
		<div id="MmainContent" style="display:inline-block">
			<div id="messagesFriendsList">
				<div id="chatroomNavTitle">
					<div id="chatroomNavTitleText">
						<span onclick="loadAjax('checkToken', 'checkToken=1')">Inbox(27)</span>
						<span id="checkToken">Other(4)</span>
					</div>
				</div>
				<div id="chatroomNavSearch">
					<div id="chatroomNavSearchIn">
						<input type=text name="" id="chatroomNavSearchBox" onkeyup="chatroomNavSearch()" placeholder="Search....">
					</div>
				</div>
				<div id="chatroomNavFriends">
					<?php  listMessageCards(); 
						//sendMessage(38, 'seventhX msg to padox');
					 	?>
					 	
				</div>
			</div>
			<div id="mainMessagesPanel">
				<div id="chatroomMessageTitle">
					<div id="chatroomTitle_Name"></div>
				</div>
				<div id="chatroomMsgs">
					<div id="chatroomConversation">
						<div id="chatroomEachMessage" class="chatroomEachMessage recv">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Hii akiD
									</div>
								</div>
							</div>
						</div>

						<div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div>
						<div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div><div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div><div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div><div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div><div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div><div id="chatroomEachMessage" class="chatroomEachMessage sent">
							<img src="http://localhost/TetramandoX/userFiles/profilePic/ddb922aa9c.jpg">
							<div class="chatroomEachMessagetext">
								<div class="speechBubbleCorner"></div>
								<div id="speechBubbleDiv">
									<div class="speechBubbleBox">
										Helllo Padox!
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="mainMSendingArea">
					<div id="chatroomSA">
						<div id="chatroomSABox">
							<textarea name="sendMsg" placeholder="Write a reply...." rows=3 id="chatroomTextarea" maxlength=3000></textarea>
							<input type=hidden name="msgToFriend" value="" id="msgTo" >
							<input type=button value="Reply" id="sendMessageBut" onclick="chatroomSendMessage()">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>