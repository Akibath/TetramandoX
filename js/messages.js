var sendToID;
var messageCardSearchBox;
var messageCardPersonID;
//var token = getCookie('token');
//setInterval('callAjax(\'ajaxChatbar=1\')', 10000);
function selectMessageCard(personID){
	loadAjax('chatroomConversation', 'chatroomShowConversation='+ personID);
	var oldMessageCard = document.getElementById("crMessageCard_"+messageCardPersonID);
	if(oldMessageCard)
		oldMessageCard.className = 'crMessageCard';
	
	// var prevMessageCard = document.querySelector('#crMessageCard_'+personID).previousElementSibling.className;
	// if(prevMessageCard=='crMessageCard')
	// 	document.querySelector('#crMessageCard_'+personID).previousElementSibling.className = 'messageCardSelectedDown';

	messageCardPersonID = personID;

	document.getElementById("crMessageCard_"+personID).className = 'messageCardSelected';
	//document.getElementById("MeachFriend_"+personID).setAttribute('class', '');
	var personName = document.getElementById("messageCardPersonName_"+personID).innerHTML;
	document.getElementById("chatroomTitle_Name").innerHTML = personName;
	document.getElementById('msgTo').value = personID;
	sendToID = personID;
	chatDiv = document.getElementById('chatroomConversation');
	setTimeout("chatDiv.scrollTop = chatDiv.scrollHeight;", 100);
	//document.getElementById('MeachFriend_'+personID).className = 'MeachFriend messageCardSelected';
}
function chatroomSendMessage(){
	//var personID = document.getElementById('msgTo').value;
	var msg = document.getElementById('chatroomTextarea').value;
	var profilePicURL = document.getElementById('topmPanelUlLiLinkProP').getAttribute('src');
	var msgDiv = "<div id=chatroomEachMessage class='chatroomEachMessage sent'><img src='"+profilePicURL+"'><div class=chatroomEachMessagetext><div class=speechBubbleCorner></div><div id=speechBubbleDiv><div class=speechBubbleBox>"+msg+"</div></div></div></div>";
	document.getElementById('chatroomTextarea').value = null;
	document.getElementById('chatroomNavMsg_'+sendToID).innerHTML = msg;
	document.getElementById('chatroomConversation').innerHTML += msgDiv;
	addAjax('chatroomConversation', 'sendMessageTo='+sendToID+'&&sendMessageContent='+msg);
	chatDiv = document.getElementById('chatroomConversation');
	chatDiv.scrollTop = chatDiv.scrollHeight;
	//chatDiv.scrollTop = '1500';
	//addAjax('chatroomConversation', 'chatroomSendMessage='+msg+'&&chatroomSendMessageTo='+sendToID);


}
// var divObject;
// window.onload = function()
// {
//     divObject = document.getElementById('chatroomConversation');
// //    alert(divObject.scrollHeight)
// //    alert(divObject.scrollTop)
//     divObject.scrollTop = divObject.scrollHeight;
//     setTimeout("divObject.scrollTop = divObject.scrollHeight;",100);
// }  

function messageCardSearchBox(){
	messageCardSearchBox = document.getElementById('chatroomNavSearchBox').value;
	alert(messageCardSearchBox);
}
function chatroomNavSearch(){
	//var chatroomNavBox = document.getElementById('chatroomNavFriends').innerHTML;
	var searchString = document.getElementById('chatroomNavSearchBox').value;
	if(searchString!='')
		loadAjax('chatroomNavFriends', 'chatroomNavSearchString='+searchString);
	else if(searchString==''){
		//document.getElementById('chatroomConversation').innerHTML = chatroomNavBox;
		document.getElementById('chatroomNavFriends').innerHTML = '<div id="chatroomNavLoading"><img src="http://localhost/TetramandoX/img/loading1.gif"></div>';
		loadAjax('chatroomNavFriends', 'chatroomNavListLastMessage');
		//document.getElementById('chatroomNavFriends').innerHTML = searchString;
	}
}
