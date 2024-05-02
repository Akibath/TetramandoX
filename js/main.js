var listedChatCards, fillEmptyChatboxs = false, userDetails, xmlHttp = window.XMLHttpRequest? new XMLHttpRequest(): new ActiveXObject("Microsoft.XMLHTTP"), hoverCards = Array();
setInterval('updateChatbar()', 3000);
setInterval('updateChatbox()', 1000);
setInterval('updateTopMenu()', 3000);
setInterval('fillChatboxIfEmpty()', 2000);

ajax('userDetails_JSN=1', function(){
    userDetails = JSON.parse(xmlHttp.responseText);
});
window.onload = function(){
    listedChatCards =  document.getElementById('chatbarContent')? document.getElementById('chatbarContent').innerHTML: '';
    defaultSearchResults = document.querySelector('.topMenu').querySelector('.searchResult').innerHTML;
    documentTitle = document.title;
    var totalChatboxs = document.querySelectorAll('.chatbox');
    for(var i = 0; i<totalChatboxs.length; i++){
        totalChatboxs[i].querySelector('.chatboxContent').scrollTop = totalChatboxs[i].querySelector('.chatboxContent').scrollHeight;
    }
}

function str2Int(str){
    return parseInt(String(str).replace(/[^0-9]/g, ''));
}

function personDetails(personID){
    for(i = 0; i< personsDetails.length; i++){
        if(personsDetails[i].personID == personID){
            return personsDetails[i];
        }
    }
}

document.onscroll = function(){
    if(document.querySelector('.suggestionBox'))
        document.querySelector('.suggestionBox').className = scrollY < 50? 'suggestionBox': 'suggestionBox scroll';
}

document.onclick = function(e){//console.log(e.screenY+' => '+e.clientY);
    var target = (e && e.target) || (event && event.srcElement);
    
    var topMenuButtons = document.querySelectorAll('.topMenuWrapperButton');
    var topMenuTotalNotifs = document.querySelectorAll('.topMenu_noOfNotifs');
    var topMenuWrappers = document.querySelectorAll('.topMenuWrapper');
    for(var i = 0; i < 4; i++){
        if(target == topMenuButtons[i] || (i != 3 && target == topMenuTotalNotifs[i])){
            for(var j = 0; j < 4; j++){
               if(i != j){
                    topMenuWrappers[j].style.display = 'none';
                }
            }

            topMenuWrapper = topMenuWrappers[i];//target.parentNode.parentNode.querySelector('.topMenuWrapper');
            topMenuWrappers[i].style.display = topMenuWrappers[i].style.display == 'block'? 'none': 'block';
            if(i != 3){
                topMenuTotalNotifs[i].style.display = 'none';
                if(topMenuTotalNotifs[i].innerHTML > 0){
                    topMenuTotalNotifs[i].innerHTML = 0;
                    ajax('clearNoOfNotifs='+i, function(){
                        console.log(xmlHttp.responseText);
                    });
                }
            }
            break;
        } else if(!isParent(target, topMenuWrappers[i]) || i == 3) topMenuWrappers[i].style.display = 'none';
    }

    var chatbarMenu = document.querySelector('.chatbarSearch').querySelector('.dropdownMenu');  
    if(target == document.querySelector('.chatbarSearch').querySelector('.settingsIcon')){
        chatbarMenu.style.display = chatbarMenu.style.display == 'block'? 'none': 'block';
    } else
        chatbarMenu.style.display = 'none';


    if(document.querySelector('.subProfiles')){
        var profileCardMenu = document.querySelector('.subProfiles').querySelector('.dropdownMenu');
        if(target == document.querySelector('.subProfiles').querySelector('.settingsGear').getElementsByTagName('img')[0])
            profileCardMenu.style.display = profileCardMenu.style.display == 'block'? 'none': 'block';
        else
            profileCardMenu.style.display = 'none';
    }

   
    //All the actions about ChatBoxs.
    var chatBox = document.querySelectorAll('.chatbox');
    if(chatBox.length>0){
        for(i=0; i<chatBox.length; i++){
            //Hide the settings menu (at chatbox) when click outside
            var personID = str2Int(chatBox[i].getAttribute('id'));
            var settingsMenu = chatBox[i].querySelector('.chatboxSettings');
            var settingsButton = chatBox[i].querySelector('.settingsGear');
            if(target == settingsButton)
                settingsMenu.style.display = settingsMenu.style.display == 'none'? 'block': 'none';
            else
                settingsMenu.style.display = 'none';
            
            //Sends 'Message has been seen' sign to the sender when click indide of chatbox content or text area 
            if(isParent(target, document.querySelectorAll('.chatboxContent')[i]) || isParent(target, document.querySelectorAll('.chatboxTextarea')[i])){
                chatboxMsgSeen(personID);
            }
        }
    }

    var totalSettingsMenu = document.querySelectorAll('.postSettingsMenu'), totalSettingsGear = document.querySelectorAll('.postSettingsGear');

    if(target.className == 'postSettingsGear'){
        for(i = 0; i<totalSettingsGear.length; i++){
            if(target == totalSettingsGear[i]){
                if(totalSettingsGear[i].style.display == 'none'){
                    console.log('aa');
                    totalSettingsMenu[i].style.display = 'block';
                    totalSettingsGear[i].style.display = 'block';
                } else {
                    totalSettingsMenu[i].style.display = 'none';
                    totalSettingsGear[i].style.display = 'none';
                }
            } else {
                totalSettingsGear[i].style.display = 'none';
                totalSettingsMenu[i].style.display = 'none';
            }
        }
    } else if(target.className != 'postSettingsGear'){
        for(i = 0; i<totalSettingsMenu.length; i++){
            totalSettingsGear[i].style.display = 'none';
            totalSettingsMenu[i].style.display = 'none';
        }
    }

    var topMenuSearchDiv = document.querySelector('.topMenu').querySelector('.searchBox');
    if(target == topMenuSearchDiv.getElementsByTagName('input')[0])
        topMenuSearchDiv.querySelector('.searchResult').style.display = 'block';
    else if(!isParent(target, topMenuSearchDiv.querySelector('.searchResult')))
        topMenuSearchDiv.querySelector('.searchResult').style.display = 'none';

    if(document.querySelector('.popupOverlay')){
        if(!isParent(target, document.querySelector('.popupBox')))
            document.querySelector('.popupOverlay').remove();
    }
}

function fillChatboxIfEmpty(){
    if(fillEmptyChatboxs == true){
        var chatBoxs = document.querySelectorAll('.chatbox');
        for(var i=0; i<chatBoxs.length; i++){
            var chatboxContent = chatBoxs[i].querySelector('.chatboxContent');
            if(chatboxContent.innerHTML == ''){
                var personID = str2Int(chatboxContent.parentNode.id);
                fillChatbox(personID);
                console.log('2nd round');
            }
        }
    }
}

function updateTopMenu(){
    var chatBoxs = document.querySelectorAll('.chatbox');
    var chatBoxs_theVar = '';
    for(i = 0; i< chatBoxs.length; i++){
        chatBoxs_theVar += i == 0? str2Int(chatBoxs[i].id): '-'+str2Int(chatBoxs[i].id);
    }

    ajax('updateTopMenu=1&&chatBoxs='+chatBoxs_theVar, function(){
        //console.log((xmlHttp.responseText));
        var response_JSN = JSON.parse(xmlHttp.responseText);
        if(response_JSN.errorCode == 0){
            chatBoxs = response_JSN.chatBoxs;
            for(i = 0; i < chatBoxs.length; i++){
                openChatbox(chatBoxs[i]['pID'], chatBoxs[i]['pName'], chatBoxs[i]['pUName'], chatBoxs[i]['pProPic']);
            }

            var topMenuFriend = document.querySelectorAll('.topMenuWrapper')[0].querySelector('.content');
           
            var friendRequests = response_JSN.friendRequests;
            var newRequests = friendRequests.newRequests;
            var unseenRequests = document.querySelectorAll('.topMenuButton')[0].querySelector('.topMenu_noOfNotifs');
            if(friendRequests.totalUnseen>0){
                unseenRequests.innerHTML = friendRequests.totalUnseen;
                unseenRequests.style.display = 'block';
            } else {
                unseenRequests.innerHTML = '';
                unseenRequests.style.display = 'none';
            }

            var sentRecvRequests = document.querySelectorAll('.topMenuWrapper')[0].getElementsByTagName('span');
            sentRecvRequests[0].innerHTML = '('+friendRequests.totalRecv+')';
            sentRecvRequests[1].innerHTML = '('+friendRequests.totalSent+')';
            
            var newFriendRequests = '';
            for(var i=0; i<newRequests.length; i++){
                var personID = newRequests[i]['personID'];
                if(topMenuFriend.querySelector('#friendRequest_'+personID)){
                    topMenuFriend.querySelector('#friendRequest_'+personID).remove();
                }

                newFriendRequests += '<div class="friendRequest" id="friendRequest_'+personID+'">'+
                                        '<img src="'+newRequests[i]['profilePic']+'">'+
                                        '<div class="desc">'+
                                            '<a href="'+newRequests[i]['username']+'">'+newRequests[i]['name']+'</a>'+
                                            '<div>'+newRequests[i]['desc']+'</div>'+
                                        '</div>'+
                                        '<div class="status">'+
                                            '<button id="delete" onclick="deleteRequest(this, '+personID+')">Delete</button>'+
                                            '<button id="confirm" onclick="acceptRequest(this, '+personID+')">Confirm</button>'+
                                        '</div>'+
                                   ' </div>';
            }

            topMenuFriend.innerHTML = newFriendRequests+topMenuFriend.innerHTML;
            
            var topMenuMsg = document.querySelectorAll('.topMenuWrapper')[1].querySelector('.content');
            var messages = response_JSN.messages;
            var newMessages = messages.newMessages;

            for(i = 0; i<newMessages.length; i++){
                var messageWrapper = document.querySelectorAll('.topMenuWrapper')[1].querySelector('.content');
                if(messageCard = messageWrapper.querySelector('#eachMessage_'+newMessages[i].personID)) 
                    messageCard.remove();
                
                messageWrapper.innerHTML = 
                '<div id="eachMessage_'+newMessages[i].personID+'" class="eachMessage" onClick="openChatbox('+newMessages[i].personID+', \''+newMessages[i].personName+'\', \''+newMessages[i].username+'\', \''+newMessages[i].profilePicURL+'\')">'+
                    '<img src="'+newMessages[i].profilePicURL+'" class="profilePic">'+
                    '<div class="messageDetails">'+
                        '<div class="personName">'+newMessages[i].personName+'</div>'+
                        '<div class="lastMessage">'+newMessages[i].msg+'</div>'+
                    '</div>'+
                '</div>' + messageWrapper.innerHTML;
            }

            unseenMsgs = document.querySelectorAll('.topMenuButton')[1].querySelector('.topMenu_noOfNotifs');
            unseenMsgs.innerHTML = messages.totalUnseen;
            unseenMsgs.style.display = messages.totalUnseen>0? 'block': 'none';

            var totalAvailChatBoxs = 4 - document.querySelectorAll('.chatbox').length;
            for(i = 1; i<=totalAvailChatBoxs && i<=newMessages.length; i++){
                openChatbox(newMessages[i-1].personID, newMessages[i-1].personName, newMessages[i-1].username, newMessages[i-1].profilePicURL, true);
            }

            var chatbar = document.querySelector('.chatbar'), minChatbar = document.querySelector('.minimizedChatbar');
            //console.log(xmlHttp.responseText);
            if(response_JSN.chatStatus == 1){
                chatbar.className = 'chatbar On';
                minChatbar.className = 'minimizedChatbar On';
                chatbar.querySelector('.onOffChat').innerHTML = 'Turn off chat';
            } else if(response_JSN.chatStatus == 0) {
                chatbar.className = 'chatbar Off';
                minChatbar.className = 'minimizedChatbar Off';
                chatbar.querySelector('.onOffChat').innerHTML = 'Turn on chat';
            }

            if(response_JSN.chatbarPosition == 1){
                chatbar.style.display = 'block';
                minChatbar.style.display = 'none';
            } else if(response_JSN.chatbarPosition == 0) {
                chatbar.style.display = 'none';
                minChatbar.style.display = 'block';
            }
        }
    });
}

function isParent(childElem, parentElem){
    while(childElem.parentNode){
        if(childElem == parentElem)
            return true;
        else
            childElem = childElem.parentNode;
    }
    return false;
}

function cookieValue(cookieName){
    allCookie = document.cookie.split(';');
    for(i=0; i<allCookie.length; i++){
        if(allCookie[i].split(cookieName+'=')[1])
            return allCookie[i].split(cookieName+'=')[1];
    }
}

function updateChatbar(){
    if((document.querySelector('.chatbar').className == 'chatbar On' && document.querySelector('.chatbar').style.display == 'block')){
        ajax('listChatcards=1', function(){
            console.log(xmlHttp.responseText);
            listChatCards(JSON.parse(xmlHttp.responseText));
       });
    }
}

function listChatCards(response_JSN){
    if(response_JSN.errorCode == 0){
        var chatCards = response_JSN.chatCards;
        document.querySelector('.totalOnlineFriends').innerHTML = '('+response_JSN.onlineFriends+')';
        var allChatcards = '';
        
        for(i=0; i<chatCards.length; i++){
            var greenLight = '<img src="http://localhost/TetramandoX/img/greenLight.png">';
            var chatStatus_Content = chatCards[i].chatStatus == 1? greenLight: chatCards[i].chatStatus;
            personID = chatCards[i].personID;

            if(chatStatus_Content == greenLight)
                var personName_className = 'online';
            else if(chatStatus_Content != '')
                var personName_className = 'lastSeen';
            else
                var personName_className = '';
            onClickEvent = 'openChatbox('+chatCards[i].personID+', \''+chatCards[i].personName+'\', \''+chatCards[i].username+'\', \''+chatCards[i].profilePicURL+'\')"';
            allChatcards += 
                '<div id="chatcard_'+personID+'" class="chatCard" onclick="'+onClickEvent+'">'+
                    '<img src="'+chatCards[i].profilePicURL+'">'+
                    '<div id="personName" class="'+personName_className+'">'+chatCards[i].personName+'</div>'+
                    '<div id="onlineGreenLight">'+chatStatus_Content+'</div>'+
                '</div>';
        }

        listedChatCards = allChatcards;
        if(document.querySelector('#chatbarSearchBox').value == '') document.querySelector('.chatbarContent').innerHTML = allChatcards;
    }
}

function hideChatbar(){
    document.querySelector('.chatbar').style.display = 'none';
    document.querySelector('.minimizedChatbar').style.display = 'block';
    document.querySelector('.mainContent').id = 'onChatbarHide';
    ajax('chatbarPosition=0', function(){
        console.log(xmlHttp.responseText);
    });
}

function showChatbar(){
    document.querySelector('.chatbar').style.display = 'block';
    document.querySelector('.minimizedChatbar').style.display = 'none';
    document.querySelector('.mainContent').id = 'mainContent';
    ajax('chatbarPosition=1', function(){
        console.log(xmlHttp.responseText);
        listChatCards(JSON.parse(xmlHttp.responseText));
   });
}

function turnOnOffChat(){
    var chatbar = document.querySelector('.chatbar'), minChatbar = document.querySelector('.minimizedChatbar');
    if(chatbar.className == 'chatbar On'){
        chatbar.className = 'chatbar Off';
        chatbar.querySelector('.onOffChat').innerHTML = 'Turn on chat';
        chatbar.querySelector('.turnOnChat').style.display = 'block';
        minChatbar.className = 'minimizedChatbar Off';
        ajax('turnOffChat=1', null);
    } else {
        chatbar.className = 'chatbar On';
        chatbar.querySelector('.onOffChat').innerHTML = 'Turn off chat';
        chatbar.querySelector('.turnOnChat').style.display = 'none';
        minChatbar.className = 'minimizedChatbar On';
        ajax('turnOnChat=1', function(){
            console.log(xmlHttp.responseText);
            listChatCards(JSON.parse(xmlHttp.responseText));
       });
    }
}

function searchChatcards(){
    var searchString = document.getElementById('chatbarSearchBox').value;
    if(searchString != ''){
        ajax('searchChatcards='+searchString, function(){
            var chatbar = document.querySelector('.chatbarContent');
            //chatbar.innerHTML = '';
            var xmlHttp_JSN = JSON.parse(xmlHttp.responseText);
            if(xmlHttp_JSN.errorCode == 0){
                var chatCards = xmlHttp_JSN.chatCards;

                for(i=0; i<chatCards.length; i++){
                    className = chatCards[i].className == 0? '': 'notFriend';
                    onClickEvent = 'openChatbox('+chatCards[i].personID+', \''+chatCards[i].personName+'\', \''+chatCards[i].username+'\', \''+chatCards[i].profilePicURL+'\')';
                    chatbar.innerHTML += 
                        '<div id="chatcard_'+chatCards[i].personID+'" class="chatCard '+className+'" onClick="'+onClickEvent+'">'+
                            '<img src="'+chatCards[i].profilePicURL+'">'+
                            '<div id="personName">'+chatCards[i].personName+'</div>'+
                            '<div id="onlineGreenLight">'+chatCards[i].chatStatus+'</div>'+
                        '</div>';
                }
            }
        });
    } else
        document.querySelector('.chatbarContent').innerHTML = listedChatCards;
}

function closeChatbox(personID){
    document.getElementById('chatbox_'+personID).remove();
    ajax('closeChatbox='+personID, function(){
        console.log(xmlHttp.responseText);
    })
}

function openChatbox(personID, personName, personUsername, profilePicURL, type){
    if(document.querySelectorAll('.topMenuWrapper')[1]) document.querySelectorAll('.topMenuWrapper')[1].style.display = 'none';

    var scrolledChatboxes = new Array();
        if(document.querySelector('.chatboxContent')){
            var totalChatbox0 = document.querySelectorAll('.chatboxContent');
            for(i=0; i<totalChatbox0.length; i++){
                scrolledChatboxes[i] = totalChatbox0[i].scrollTop;
            }
        }

    if(!document.querySelector('#chatbox_'+personID)){
        document.querySelector('#chatBoxs').innerHTML +=
            '<div id="chatbox_'+personID+'" class="chatbox">'+
                '<div class="chatboxTitle">'+
                    '<div class="personName"><a href="http://localhost/TetramandoX/'+personUsername+'">'+personName+'</a></div>'+
                    '<img src="http://localhost/TetramandoX/img/close_4_hover.png" onclick="closeChatbox('+personID+')" class="closeMark">'+
                    '<img src="http://localhost/TetramandoX/img/settings_gear.png" class="settingsGear">'+
                '</div>'+
                '<div id="chatboxContent_'+personID+'" class="chatboxContent">'+
                '</div>'+
                '<div class="chatboxSettings" style="display:none">'+
                    '<ul>'+
                        '<a href=""><li>See Full Conversation</li></a>'+
                        '<li>Turn off chat of Albedo</li>'+
                        '<li onclick="document.getElementById(\'chatbox_'+personID+'\').querySelector(\'.chatboxContent\').innerHTML = null; ">Clear window</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="chatboxTextarea">'+
                    '<textarea class="chatboxTextareaBox" onkeydown="sendMsgChatbox(event, '+personID+', \''+personName+'\', \''+personUsername+'\', \''+profilePicURL+'\'); "></textarea>'+
                '</div>'+
            '</div>';
        fillChatbox(personID, true);
    }

    var totalChatbox = document.querySelectorAll('.chatboxContent');
    for(i=0; i<totalChatbox.length; i++){
        totalChatbox[i].scrollTop = scrolledChatboxes[i];
    }
}

function fillChatbox(personID, type){
    fillEmptyChatboxs = true;
    var chatboxContent = document.querySelector('#chatbox_'+personID).querySelector('.chatboxContent');
    var theVar = ('chatboxConversation='+personID) + (type === true? '&&type=0': '&&type=1');
     ajax(theVar, function(){
        console.log(xmlHttp.responseText);
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            fillEmptyChatboxs = false;
            response_JSN = JSON.parse(xmlHttp.responseText);
            var messages = response_JSN.messages;
            var prevChatboxMsgs = chatboxContent.querySelectorAll('.chatboxMsg');
            chatboxContent.innerHTML = '';
            for(i = 0; i<messages.length; i++){
                if(chatboxContent.querySelector('msgID_'+messages[i].id)) continue;
                var className = messages[i].type == 1? 'sent': 'recv';    
                var profilePic = className == 'recv'? '<img src="'+response_JSN.profilePicURL+'" class="chatboxProfilePic">': '';
                
                chatboxContent.innerHTML += 
                    '<div id="msgID_'+messages[i].id+'" class="chatboxMsg '+className+'">'+
                        profilePic+
                        '<div class="chatboxMsgText">'+messages[i].msg+'</div>'+
                    '</div>'
                ;
            }

            for(i = 0; i<prevChatboxMsgs.length; i++){
                if(chatboxContent.querySelector('msgID_'+messages[i].id)) continue;
                chatboxContent.innerHTML += '<div id="msgID_'+prevChatboxMsgs[i].id+'" class="'+prevChatboxMsgs[i].className+'">' + prevChatboxMsgs[i].innerHTML + '</div>';
            }
            
            chatboxContent.scrollTop = chatboxContent.scrollHeight;
        } else
            chatboxContent.innerHTML = 'Cant fetch last messages';
    });
}

function filterVar(str){
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function sendMsgChatbox(e, personID, personName, username, profilePicURL){
    chatboxMsgSeen(personID);
    var isSend = e.keyCode==13 && !e.shiftKey? true: false;
    var msg = isSend? document.querySelector('#chatbox_'+personID).querySelector('.chatboxTextareaBox').value.trim(): msg;
    var chatboxContent = document.querySelector('#chatbox_'+personID).querySelector('.chatboxContent');
    if(isSend){
        e.preventDefault();
        if(msg.length>0){
            document.getElementById('chatbox_'+personID).querySelector('.chatboxTextareaBox').value = null;
            var newMsg = '<div class="chatboxMsg sent"><div class="chatboxMsgText">'+filterVar(msg)+'</div></div>';
            document.querySelector('#chatbox_'+personID).querySelector('.chatboxContent').innerHTML += newMsg;
            chatboxContent.scrollTop = chatboxContent.scrollHeight;
            if(chatboxContent.querySelector('.chatboxMsgSeen')) chatboxContent.querySelector('.chatboxMsgSeen').remove();
                
            var messageWrapper = document.querySelectorAll('.topMenuWrapper')[1].querySelector('.content');
            if(messageCard = messageWrapper.querySelector('#eachMessage_'+personID)) messageCard.remove();
            
            messageWrapper.innerHTML = 
                '<div id="eachMessage_'+personID+'" class="eachMessage" onClick="openChatbox('+personID+', \''+personName+'\', \''+username+'\', \''+profilePicURL+'\')">'+
                    '<img src="'+profilePicURL+'" class="profilePic">'+
                    '<div class="messageDetails">'+
                        '<div class="personName">'+personName+'</div>'+
                        '<div class="lastMessage">'+msg+'</div>'+
                    '</div>'+
                '</div>' + messageWrapper.innerHTML
            ;

            var totalMsgs = chatboxContent.querySelectorAll('.chatboxMsg');
            msgID = totalMsgs.length>1? str2Int(totalMsgs[totalMsgs.length-2].id)+1: 1;
            totalMsgs[totalMsgs.length-1].id = 'msgID_'+msgID;
            ajax('chatboxSendMsg=1&&message='+encodeURIComponent(msg)+'&&personID='+personID, function(){
                var JSN_response = JSON.parse(xmlHttp.responseText);
                //if(JSN_response.eC == 0) 
                //chatboxContent.querySelectorAll('.chatboxMsg')[chatboxContent.querySelectorAll('.chatboxMsg').length-1].id = 'msgID_'+JSN_response.mID;
                //console.log(chatboxContent.querySelectorAll('.chatboxMsg')[chatboxContent.querySelectorAll('.chatboxMsg').length-1]);

                //console.log((xmlHttp.responseText));
            });
        } else document.querySelector('#chatbox_'+personID).querySelector('.chatboxTextareaBox').value = null;
    }
}
function ajax(theVar, successCallback, errorCallback){
    xmlHttp.onreadystatechange = function(){
        if(xmlHttp.readyState == 4){
            if(xmlHttp.status == 200)
                typeof(successCallback) == 'function'? successCallback(xmlHttp): null;
            else
                typeof(errorCallback) == 'function'? errorCallback(xmlHttp): null;
        }
    }
    xmlHttp.open('POST', 'http://localhost/TetramandoX/ajax.php', true);
    xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlHttp.setRequestHeader('enctype', 'multipart/form-data');
    xmlHttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xmlHttp.send(theVar);
}

function chatboxMsgSeen(personID){
    var chatboxContent = document.querySelector('#chatbox_'+personID).querySelector('.chatboxContent');
    if(chatboxContent.className == 'chatboxContent unread'){
        chatboxContent.className = 'chatboxContent';
        ajax('msgSeen='+personID, null);
    }
}

function updateChatbox(){
    var totalChatboxs = document.querySelectorAll('.chatbox');
    if(totalChatboxs.length == 0)
        return 0;
    var chatboxPersonsIDs = '';
    var theVar = '';
    for(i = 0; i<totalChatboxs.length; i++){
        personID = totalChatboxs[i].id.replace(/[^0-9]/g, '');
        var totalMsgs = totalChatboxs[i].querySelectorAll('.chatboxMsg').length;
        if(totalMsgs>0) theVar += (theVar == ''? '': '-') + personID + '_' + str2Int(totalChatboxs[i].querySelectorAll('.chatboxMsg')[totalMsgs-1].id);
        chatboxPersonsIDs = chatboxPersonsIDs == ''? personID: chatboxPersonsIDs+'-'+personID;
    }

    ajax('updateChatbox='+theVar, function(){
        console.log(xmlHttp.responseText);
        if(xmlHttp.responseText != ''){
            if(JSON.parse(xmlHttp.responseText).eC == 0){
                JSN_response = JSON.parse(xmlHttp.responseText);
                var newMsgs = JSN_response.newMsgs;
                for(i = 0; i < newMsgs.length; i++){
                    personID = newMsgs[i].pID;
                    chatboxContent = document.querySelector('#chatbox_'+personID).querySelector('.chatboxContent');
                    chatboxContent.className = 'chatboxContent unread';
                    if(chatboxContent.querySelector('.chatboxMsgSeen')) chatboxContent.querySelector('.chatboxMsgSeen').remove();
                    
                    if(newMsgs[i].status == 0){
                        chatboxContent.parentNode.remove();
                    } else if(newMsgs[i].seen == 1){
                        if(chatboxContent.querySelectorAll('.chatboxMsg').length>0)
                            chatboxContent.innerHTML += '<div class="chatboxMsgSeen">Seen '+newMsgs[i].time+'</div>';
                    } else {
                        topMenuTotalMsgs = document.querySelectorAll('.topMenu_noOfNotifs')[1];
                        //topMenuTotalMsgs.style.display = 'block';
                        topMenuTotalMsgs.innerHTML = str2Int(topMenuTotalMsgs.innerHTML)+1;
                        var profilePicURL = newMsgs[i].proPic;
                        var personName = chatboxContent.parentNode.querySelector('.personName').getElementsByTagName('a')[0].innerHTML;
                        var username = chatboxContent.parentNode.querySelector('.personName').getElementsByTagName('a')[0].getAttribute('href');
                        
                        msgs = newMsgs[i].msgs;
                        for(j = 0; j < msgs.length; j++){
                            if(chatboxContent.querySelector('msgID_'+msgs[j]['id'])) continue;
                            if(msgs[j]['type'] == 1){
                                chatboxContent.innerHTML += 
                                    '<div id="msgID_'+msgs[j]['id']+'" class="chatboxMsg sent">'+
                                        '<div class="chatboxMsgText">'+msgs[j]['msg']+'</div>'+
                                    '</div>'
                                ;
                            } else{
                                chatboxContent.innerHTML += 
                                    '<div id="msgID_'+msgs[j]['id']+'" class="chatboxMsg recv">'+
                                        '<img src="'+profilePicURL+'" class="chatboxProfilePic">'+
                                        '<div class="chatboxMsgText">'+msgs[j]['msg']+'</div>'+
                                    '</div>'
                                ;
                            }
                        }

                        var messageWrapper = document.querySelectorAll('.topMenuWrapper')[1].querySelector('.content');
                        if(messageCard = messageWrapper.querySelector('#eachMessage_'+personID)) messageCard.remove();
                        
                        messageWrapper.innerHTML = 
                        '<div id="eachMessage_'+personID+'" class="eachMessage" onClick="openChatbox('+personID+', \''+personName+'\', \''+username+'\', \''+profilePicURL+'\')">'+
                            '<img src="'+profilePicURL+'" class="profilePic">'+
                            '<div class="messageDetails">'+
                                '<div class="personName">'+personName+'</div>'+
                                '<div class="lastMessage">'+msgs[j-1]+'</div>'+
                            '</div>'+
                        '</div>' + messageWrapper.innerHTML;
                    }
                    
                    chatboxContent.scrollTop = chatboxContent.scrollHeight;
                }
            }
        }
    });
}

function postStatus(){
    var textarea = document.querySelector('.timelinePostbox').getElementsByTagName('textarea')[0];
    var postImage = document.querySelector('.postImgUploadImg');
    var statusText = textarea.value;//.replace(/([>\r\n]?)(\r\n|\n\r|\r|\n)/g, '<br>');
    ajaxPost('postImage='+postImage, function(){
        console.log(xmlHttpPost.responseText);
    });
    // ajaxPost('createPost=1&&postText='+statusText, function(){
    //     console.log(statusText+'=>'+xmlHttp.responseText);
    // });
}

function deletePost(postID){
        MessageBox('Delete Confirmation', 'Are you sure to permanently delete this post? ',  'Delete', 'Cancel', true, function(){
            document.querySelector('.popupOverlay').remove();
            document.getElementById(postID).style.display = 'none';
            ajax('deletePost='+postID, function(){
                console.log(xmlHttp.responseText);
                if(JSON.parse(xmlHttp.responseText).errorCode == 0)
                    document.getElementById(postID).remove();
                else
                    document.getElementById(postID).style.display = 'block';
            });
        });
}

function MessageBox(title, content, buttonOK, buttonCancel, style, callback){
    if(buttonOK != '') buttonOK = '<button class="buttonOK">' + buttonOK + '</button>';
    if(buttonCancel != '') buttonCancel = '<button class="buttonCancel" onclick="this.parentNode.parentNode.parentNode.remove()">' + buttonCancel + '</button>';
    if(document.querySelector('.popupOverlay')) return 0;
    setTimeout(function(){
        document.body.innerHTML += 
            '<div class="popupOverlay">'+
                '<div class="popupBox">'+
                    '<div class="popupTitle">'+title+'</div>'+
                    '<div class="popupContent">'+content+'</div>'+
                    '<div class="popupButtons">'+buttonOK+buttonCancel+'</div>'+
                '</div>'+
            '</div>'
        ;
        if(document.querySelector('.popupOverlay').querySelector('.buttonOK') && typeof(callback) == 'function')
            document.querySelector('.popupOverlay').querySelector('.buttonOK').addEventListener('click', callback);
    }, 50);
}

function likeHate(event, postID){
    var action = event.target.id;
   
    var likeUnlike = document.getElementById(postID).querySelector('#likeUnlike');
    var totalLikes = document.getElementById(postID).querySelector('#totalLikes');
    var totalLikesInt = parseInt(totalLikes.innerHTML.replace(/(<([^>]+)>)/ig,""));
    var thumbsUp = '<img src="http://localhost/TetramandoX/img/like.png">';

    var hateUnhate = document.getElementById(postID).querySelector('#hateUnhate');
    var totalHates = document.getElementById(postID).querySelector('#totalHates');
    var totalHatesInt = parseInt(totalHates.innerHTML.replace(/(<([^>]+)>)/ig,""));
    var thumbsDown = '<img src="http://localhost/TetramandoX/img/hate.png">';

    document.getElementById(postID).querySelector('.commentArea').style.display = 'block';
    if(action == 'hateUnhate'){
        if(hateUnhate.innerHTML == 'Hate'){
            hateUnhate.innerHTML = 'Unhate';
            totalHates.innerHTML = thumbsDown + (totalHatesInt+1);
           
            if(likeUnlike.innerHTML == 'Unlike'){
                likeUnlike.innerHTML = 'Like';
                totalLikes.innerHTML = thumbsUp + (totalLikesInt-1);
                var isAlreadyLiked = true;
            }

            ajax('hatePost='+postID, function(){
                if(JSON.parse(xmlHttp.responseText).errorCode != 0){
                    hateUnhate.innerHTML = 'Hate';
                    totalHates.innerHTML = thumbsDown + (totalHatesInt);

                    if(isAlreadyLiked){
                        likeUnlike.innerHTML = 'Unlike';
                        totalLikes.innerHTML = thumbsUp + (totalLikesInt);
                    }
                }
            });
       
        } else if(hateUnhate.innerHTML == 'Unhate'){
            hateUnhate.innerHTML = 'Hate';
            totalHates.innerHTML = thumbsDown + (totalHatesInt-1);

            ajax('unhatePost='+postID, function(){
                if(JSON.parse(xmlHttp.responseText).errorCode != 0){
                    hateUnhate.innerHTML = 'Unhate';
                    totalHates.innerHTML = thumbsDown + (totalHatesInt);
                }
            });
        }

    } else if(action == 'likeUnlike'){
        if(likeUnlike.innerHTML == 'Like'){
            likeUnlike.innerHTML = 'Unlike';
            totalLikes.innerHTML = thumbsUp + (totalLikesInt+1);

            if(hateUnhate.innerHTML == 'Unhate'){
                hateUnhate.innerHTML = 'Hate';
                totalHates.innerHTML = thumbsDown + (totalHatesInt-1);
                var isAlreadyHated = true;
            }

            ajax('likePost='+postID, function(){
                if(JSON.parse(xmlHttp.responseText).errorCode != 0){
                    likeUnlike.innerHTML = 'Like';
                    totalLikes.innerHTML = thumbsUp + (totalLikesInt);

                    if(isAlreadyHated == true){
                        hateUnhate.innerHTML = 'Unhate';
                        totalHates.innerHTML = thumbsDown + (totalHatesInt);
                    }
                }
            });

        } else if(likeUnlike.innerHTML == 'Unlike'){
            likeUnlike.innerHTML = 'Like';
            totalLikes.innerHTML = thumbsUp + (totalLikesInt-1);
            
            ajax('unlikePost='+postID, function(){
                if(JSON.parse(xmlHttp.responseText).errorCode != 0){
                    likeUnlike.innerHTML = 'Unlike';
                    totalLikes.innerHTML = thumbsUp + (totalLikesInt);
                }
            });
        }
    }
    
}

function postComment(event, postID){
    if(event.keyCode==13&&!event.shiftKey){
        event.preventDefault();
        var post = document.getElementById(postID);
        var textarea = post.querySelector('.writeCommentTextarea');
        var commentText = filterVar(textarea.value.trim());
        textarea.value = '';
        //CODE : UNFOCUS THE COMMENT TEXT AREA 
        var commentID = '';
        if(commentText.length>0){
            var totalComments = post.querySelector('#totalComments');
            var totalCommentsInt = parseInt(totalComments.innerHTML.replace(/(<([^>]+)>)/ig,""));
            var commentsImg = '<img src="http://localhost/TetramandoX/img/comment.png">';
            var totalCommentsElement = post.querySelector('#totalComments');
            totalComments.innerHTML = commentsImg + (totalCommentsInt+1);

            var newComment = 
                '<div class="eachComment">'+
                    '<div class="profilePic"><img src="' + userDetails.profilePicURL + '"></div>'+
                    '<div class="content">'+
                        '<span class="personName"><a href="http://localhost/TetramandoX/' + userDetails.username + '">' + userDetails.entireName + '</a></span>'+
                        ' <span class="commentText">' + commentText + '</span>'+
                        '<div class="time">' + time2String(true) + '</div>'+
                    '</div>'+
                    '<div class="deleteComment"></div>'+
                '</div>';
            
            post.querySelector('.comments').innerHTML = newComment + post.querySelector('.comments').innerHTML;
            ajax('postComment=1&&postID='+postID+'&&commentText='+commentText, function(){
                console.log(xmlHttp.responseText);
                if(JSON.parse(xmlHttp.responseText)){
                    commentID = postID + '_' + JSON.parse(xmlHttp.responseText).commentLocalID;
                    post.querySelectorAll('.eachComment')[0].id = commentID;
                    post.querySelectorAll('.eachComment')[0].querySelector('.deleteComment').innerHTML = '<img onclick="deleteComment(\''+commentID+'\')" src="http://localhost/TetramandoX/img/close1.png">';
                }
            });
        }
    }
 }
 function time2String(type){
    var timeNow = new Date();
    var monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    var date = monthNames[timeNow.getMonth()]+' '+timeNow.getDate()+', '+timeNow.getFullYear();
    var hours = timeNow.getHours() > 12? timeNow.getHours()-12: timeNow.getHours();
    var minutes = timeNow.getMinutes() < 10? '0'+timeNow.getMinutes(): timeNow.getMinutes();
    var session = timeNow.getHours() > 12? 'PM': 'AM';
    hours = hours < 10? (hours == 00? '12': '0'+hours): hours;
    var time = hours + ':' + minutes + ' ' + session;
    return type == true? date+' at '+time: time;
 }

 function deleteComment(commentID){
    var commentElement = document.getElementById(commentID);
    var postID = commentElement.parentNode.parentNode.parentNode.id;
            
    var totalComments = document.getElementById(postID).querySelector('#totalComments');
    var totalCommentsInt = parseInt(totalComments.innerHTML.replace(/(<([^>]+)>)/ig,""));
    totalComments.innerHTML = '<img src="http://localhost/TetramandoX/img/comment.png">' + (totalCommentsInt-1);

    commentElement.style.display = 'none';
    ajax('deleteComment='+commentID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0)
            commentElement.remove();
        else {
            commentElement.style.display = 'inline-block';
            totalComments.innerHTML = '<img src="http://localhost/TetramandoX/img/comment.png">' + (totalCommentsInt);
        }
    });
 }

function unFriend(t, personID){
    t.innerHTML = 'Add Friend';
    ajax('unFriend='+personID, function(){
        console.log(xmlHttp.responseText);
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.setAttribute('onClick', 'sendRequest(this, '+personID+')');
        }
    });
}

function sendRequest(t, personID){
    t.innerHTML = 'Cancel Request';
    ajax('sendRequest='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.setAttribute('onClick', 'cancelRequest(this, '+personID+')');
        }
    });
}

function cancelRequest(t, personID){
    t.innerHTML = 'Add Friend';
    ajax('cancelRequest='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.setAttribute('onClick', 'sendRequest(this, '+personID+')');
        }
    });
}

function acceptRequest(t, personID){
    t.setAttribute('onClick', '');
    var totalRequests = document.querySelectorAll('.topMenuWrapper')[0].querySelector('.title').querySelector('.desc').getElementsByTagName('span')[0];
    totalRequests.innerHTML = '('+(str2Int(totalRequests.innerHTML)-1)+')';
    ajax('acceptRequest='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.innerHTML = 'Friends';
            t.id = 'friends';
            t.parentNode.querySelector('#delete').remove();
        } else if(JSON.parse(xmlHttp.responseText).errorCode == 1){
            t.parentNode.parentNode.querySelector('.status').style.display = 'none';
            t.parentNode.parentNode.querySelector('.desc').innerHTML = '<div class="requestNoLonger">This friend request is no longer Available.</div>';
        }
    });
}

function deleteRequest(t, personID){
    var totalRequests = document.querySelectorAll('.topMenuWrapper')[0].querySelector('.title').querySelector('.desc').getElementsByTagName('span')[0];
    totalRequests.innerHTML = '('+(str2Int(totalRequests.innerHTML)-1)+')';
    ajax('deleteRequest='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.parentNode.querySelector('#confirm').remove();
            t.innerHTML = 'Mark as spam';
            t.setAttribute('onClick', 'markSpam(this, '+personID+')');
        } else if(JSON.parse(xmlHttp.responseText).errorCode == 1){
            t.parentNode.parentNode.querySelector('.status').style.display = 'none';
            t.parentNode.parentNode.querySelector('.desc').innerHTML = '<div class="requestNoLonger">This friend request is no longer Available.</div>';
        }
    });
}

function markSpam(t, personID){
    t.setAttribute('onClick', '');
    ajax('markSpam='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.innerHTML = 'Undo';
            t.setAttribute('onClick', 'undoSpam(this, '+personID+')');
        } else if(JSON.parse(xmlHttp.responseText).errorCode == 1){
            t.parentNode.parentNode.querySelector('.status').style.display = 'none';
            t.parentNode.parentNode.querySelector('.desc').innerHTML = '<div class="requestNoLonger">This person is no longer available.</div>';
        }
    });
}

function undoSpam(t, personID){
    t.setAttribute('onClick', '');
    ajax('undoSpam='+personID, function(){
        if(JSON.parse(xmlHttp.responseText).errorCode == 0){
            t.remove();
        } else if(JSON.parse(xmlHttp.responseText).errorCode == 1){
            t.parentNode.parentNode.querySelector('.status').style.display = 'none';
            t.parentNode.parentNode.querySelector('.desc').innerHTML = '<div class="requestNoLonger">This person is no longer available.</div>';
        }
    });
}

function showMorePosts(){
    var allPosts = document.querySelectorAll('.post');
    var button = document.querySelector('.showMorePosts');
    if(button.id == ''){
        button.id = 'notFocus';
        button.innerHTML = '';
        if(allPosts.length>=0){
            var lastPost = allPosts[allPosts.length-1];
            ajax('seeMorePosts='+lastPost.id, function(){
                document.querySelector('.showMorePosts').remove();
                document.querySelector('.posts').innerHTML += xmlHttp.responseText;
            });
        }
    }
}

function showMoreComments(postID){
    var comments = document.getElementById(postID).querySelectorAll('.eachComment');
    var lastCommentID = comments[comments.length-1].id;
    var seeMoreLink = document.getElementById(postID).querySelector('.showMoreComments');
    if(seeMoreLink.id == ''){
        seeMoreLink.innerHTML = 'Loading...';
        seeMoreLink.id = 'notFocus';
        var remainingComments = '';
        ajax('showMoreComments='+lastCommentID, function(){
            console.log(xmlHttp.responseText);
            var response_JSN = JSON.parse(xmlHttp.responseText);
            if(response_JSN.errorCode == 0){
                var comments_JSN = response_JSN[0];
                var showMoreComments = response_JSN.showMoreComments == 1? '<div class="showMoreComments" onclick="showMoreComments(\''+postID+'\');">See more...</div>': '';
                seeMoreLink.remove();

                for(i = 0; i < comments_JSN.length; i++){
                    var deleteComment = '';
                    if(comments_JSN[i].deleteComment == 1){
                        deleteComment = 
                            '<div class="deleteComment"><img onclick="deleteComment(\''+comments_JSN[i].id+'\')" src="http://localhost/TetramandoX/img/close1.png"></div>'
                        ;
                    }
                    document.getElementById(postID).querySelector('.comments').innerHTML +=
                            '<div id="'+comments_JSN[i].id+'" class="eachComment">'+
                                '<div class="profilePic"><img src="'+comments_JSN[i].profilePicURL+'"></div>'+
                                '<div class="content">'+
                                    '<span class="personName"><a href="'+comments_JSN[i].username+'">'+comments_JSN[i].personName+'</a></span>'+
                                    '<span class="commentText"> '+comments_JSN[i].commentText+'</span>'+
                                    '<div class="time">'+comments_JSN[i].time+'</div>'+
                                '</div>'+
                                deleteComment+
                            '</div>'
                    ;
                }
                document.getElementById(postID).querySelector('.comments').innerHTML += showMoreComments;
            }          
        });
    }
}

function createPost(form){
    var postText = filterVar(form.postText.value.trim());
    var imageName = form.postImage.value;
    if(imageName != ''){
        if(!imageName.match(/\.(jpg|jpeg|png|gif)$/ig)){
            console.log('images only');
            form.postImage.value = '';
            return false;
        }
    
    } else if(postText != ''){
        form.postText.value = '';
        //var postLoading = '<div class="postUploading"><img src="http://localhost/TetramandoX/img/loading.gif"></div>';
        //document.querySelector('.posts').innerHTML = postLoading + document.querySelector('.posts').innerHTML;
        if(document.querySelector('.posts').querySelector('.noActivity')) document.querySelector('.posts').querySelector('.noActivity').remove();
        var postID = document.querySelectorAll('.post').length + 1;
        var postContent = 
            '<div id="postID_'+postID+'" class="post">'+
                '<div class="postTitle">'+
                    '<div class="profilePic"><img src="'+userDetails.profilePicURL+'"></div>'+
                    '<div class="nameAndTime">'+
                        '<div class="name"><a href="'+userDetails.username+'">'+userDetails.entireName+'</a></div>'+
                        '<div class="time"><a href="">'+time2String(true)+'</a></div>'+
                    '</div>'+
                '</div>'+
               '<div class="postBody">'+
                    '<div class="postBodyText">'+postText+'</div>'+
                    '<div class="postBodyImage"></div>'+
                '</div>'+
                '<div class="promotePost">'+
                    '<ul>'+
                        '<li id="likeUnlike">Like</li>'+
                        '<li id="hateUnhate">Hate</li>'+
                        '<li id="focusCommentArea">Comment</li>'+
                        '<li>Share</li>'+
                        '<li class="postPromotionDetails" id="totalShares"><img src="http://localhost/TetramandoX/img/share.png">18</li>'+
                        '<li class="postPromotionDetails" id="totalComments"><img src="http://localhost/TetramandoX/img/comment.png">0</li>'+
                        '<li class="postPromotionDetails" id="totalHates"><img src="http://localhost/TetramandoX/img/hate.png">0</li>'+
                        '<li class="postPromotionDetails" id="totalLikes"><img src="http://localhost/TetramandoX/img/like.png">0</li>'+
                    '</ul>'+
                '</div>'+
                '<div class="commentArea">'+
                    '<div class="writeComment">'+
                        '<img src="'+userDetails.profilePicURL+'">'+
                        '<textarea class="writeCommentTextarea" placeholder="Write a comment..."></textarea>'+
                    '</div>'+
                '</div>'+
                '<div class="comments"></div>'+
            '</div>'
        ;

        document.querySelector('.posts').innerHTML = postContent + document.querySelector('.posts').innerHTML;
        ajax('createPost=1&&postText='+postText, function(){
            console.log((xmlHttp.responseText));
            if(JSON.parse(xmlHttp.responseText).errorCode == 0){
                var currentPost = document.querySelector('#postID_' + postID), newPostID = JSON.parse(xmlHttp.responseText).postID;
                currentPost.id = newPostID;
                currentPost.querySelector('#likeUnlike').setAttribute('onclick', 'likeHate(event, \''+newPostID+'\')');
                currentPost.querySelector('#hateUnhate').setAttribute('onclick', 'likeHate(event, \''+newPostID+'\')');
                currentPost.querySelector('#focusCommentArea').setAttribute('onclick', 'focusCommentArea(\''+newPostID+'\')');
                currentPost.querySelector('.writeCommentTextarea').setAttribute('onkeydown', 'postComment(event, \''+newPostID+'\')');
                currentPost.querySelector('.postTitle').querySelector('.time').getElementsByTagName('a')[0].setAttribute('href', 'http://localhost/TetramandoX/post.php?postID='+newPostID);
                currentPost.querySelector('.postTitle').innerHTML += 
                    '<div class="settings">'+
                        '<img src="http://localhost/TetramandoX/img/settings_gear.png" class="postSettingsGear">'+
                    '</div>'+
                    '<div class="postSettingsMenu">'+
                        '<ul>'+
                            '<li onclick="deletePost(\''+newPostID+'\')">Delete Post</li>'+
                        '</ul>'+
                    '</div>'
                ;
            }
        });

        var postSettings = '';
        return false;
    
    } else if(imageName == '' && postText == ''){
        return false;
    }
}

function focusCommentArea(postID){
    document.getElementById(postID).querySelector('.writeCommentTextarea').focus();
    document.getElementById(postID).querySelector('.commentArea').style.display = 'block';
}

function uploadImg(form){
    var imageName = form.postImage.value;
    if(imageName.match(/\.(jpg|jpeg|png|gif)$/ig)){
       form.querySelector('.cameraPicture').style.display = 'none';
       imageName = imageName.length>16? String.substring(imageName, 0, 16)+' ...': imageName;
       form.querySelector('.pictureName').innerHTML = imageName;
       form.querySelector('.removeImg').style.display = 'block';
    } else form.postImage.value = '';
    
    return false;
}
 function removeImage(form){
    form.postImage.value = '';
    form.querySelector('.cameraPicture').style.display = 'block';
    form.querySelector('.pictureName').innerHTML = '';
    form.querySelector('.removeImg').style.display = 'none';
    
    return false;
 }

function blockPerson(personID){
    console.log(personID);
}

function reportSpam(personID){
   // document.body.innerHTML = 'a';
   MessageBox('Reporting as spam', 'Hereafter this person cannot send you friend requests ', 2);
}

function showAllMutualFriends(){
    MessageBox('Mutual Friends', 'Content', 1);
}

function instantSearch(){
    var searchDiv = document.querySelector('.topMenu').querySelector('.searchBox');
    var searchResultDiv = searchDiv.querySelector('.searchResult');
    var searchString = encodeURIComponent(searchDiv.getElementsByTagName('input')[0].value.trim());
    searchResultDiv.innerHTML = '<div class="loading"><img src="http://localhost/TetramandoX/img/loading.gif"></div>';
    if(searchString.length == 0){
        searchResultDiv.innerHTML = defaultSearchResults;
        return 0;
    }
    ajax('instantSearch='+searchString, function(){
        console.log(xmlHttp.responseText);
        var response_JSN = JSON.parse(xmlHttp.responseText);
        if(response_JSN.errorCode == 0){
            var matchedPersons = response_JSN.matchedPersons;
            var searchResult = '';
            for(i = 0; i < matchedPersons.length; i++){
                searchResult += 
                    '<a href="http://localhost/TetramandoX/'+matchedPersons[i].username+'">'+
                        '<div class="matchedPerson">'+
                            '<img class="profilePic" src="'+matchedPersons[i].profilePicURL+'">'+
                            '<div class="personDetail">'+
                                '<div class="personName">'+matchedPersons[i].personName+'</div>'+
                                '<div class="personDesc">'+matchedPersons[i].personDesc+'</div>'+
                            '</div>'+
                        '</div>'+
                    '</a>';
            }
            if(searchResult != ''){
                searchResults = searchResult + 
                                    '<a href="http://localhost/TetramandoX/search.php?name='+searchString+'">'+
                                        '<div class="allResults">Show All</div>'+
                                    '</a>'
                ;
            } else {
                searchResults = '<div class="noResultFound">No results found.</div>';
            }

            searchResultDiv.innerHTML = searchResults; 
        }
    });    
}

function addSuggestedFriend(personID){
    var suggBox = document.querySelector('.suggestionBox').querySelector('#eachSuggest_' + personID);
    if(suggBox.className == 'eachSuggest focus'){console.log('already sent'); return 0};
    suggestedIDs = '';
    var allSuggestions = document.querySelector('.suggestionBox').querySelectorAll('.eachSuggest');
    for(i = 0; i < allSuggestions.length; i++){
        suggestedIDs += (i == 0? '': '-') + str2Int(allSuggestions[i].id);
    }
    console.log(suggestedIDs);
    suggBox.className = 'eachSuggest focus';
    ajax('addSuggestedFriend=' + personID + '&&suggestedIDs=' + suggestedIDs, function(){
        console.log(xmlHttp.responseText);
        errorCode = JSON.parse(xmlHttp.responseText).errorCode;
        if(errorCode == 0)
            suggBox.remove();
        else
           suggBox.className = 'eachSuggest'; 

    }, function(){
        suggBox.className = 'eachSuggest';
    });
}

function likePage(t, pageID){
    t.innerHTML = 'Unlike';
    t.setAttribute('onClick', 'unlikePage(this, '+pageID+')');
    ajax('likePage='+pageID, function(){
        console.log(xmlHttp.responseText);
        if(JSON.parse(xmlHttp.responseText).errorCode == 1){
            t.innerHTML = 'Like';
            t.setAttribute('onClick', 'likePage(this, '+pageID+')');
        }
    }, function(){
        t.innerHTML = 'Like';
        t.setAttribute('onClick', 'likePage(this, '+pageID+')');
    });
}

function unlikePage(t, pageID){
    t.innerHTML = 'Like';
    t.setAttribute('onClick', 'likePage(this, '+pageID+')');
    ajax('unlikePage='+pageID, function(){
        console.log(xmlHttp.responseText);
    }, function(){
        t.innerHTML = 'Unlike';
        t.setAttribute('onClick', 'unlikePage(this, '+pageID+')');
    });
}

function showHoverCard(e, t, personID){
    var totalProfileCards = document.querySelectorAll('.hoverCard');
    for(i = 0; i < totalProfileCards.length; i++){
        if(totalProfileCards[i].parentNode == t)//'hoverCard_'+personID)
            continue;
        totalProfileCards[i].remove();
    }
    if(t.parentNode.querySelector('#hoverCard_'+personID)){
        return 0;
    }
    setTimeout(function(){
        var hoverElement = document.querySelectorAll(':hover')[document.querySelectorAll(':hover').length - 1];
        if(hoverElement == undefined || !isParent(hoverElement, t)){//.parentNode.querySelector("#personName_"+personID))){
            return 0;
        }

        var className = e.clientY > 275? 'Up': 'Down';
        if(className == 'Up')
            var hoverCardCornerUP = '<div class="hoverCardCorner Up"></div>', hoverCardCornerDown = '';
        else
            var hoverCardCornerUP = '', hoverCardCornerDown = '<div class="hoverCardCorner Down"></div>';

        var flag = true;
        for(i = 0; i < hoverCards.length; i++){
            if(hoverCards[i].id == 'hoverCard_' + personID){
                flag = false;
                break;
            }
        }
        console.log(hoverCards);
        if(flag){
            var profileCard = 
                '<div class="hoverCard '+className+'" id="hoverCard_'+personID+'">'+
                    hoverCardCornerDown+
                    '<div class="hoverCardBody">'+
                        '<div class="hoverCardHeader">'+
                            '<div class="hoverCardLoading"><img src="http://localhost/TetramandoX/img/loading.gif"></div>'+
                        '</div>'+
                        '<div class="hoverCardFooter"></div>'+
                    '</div>'+
                    hoverCardCornerUP+
                '</div>';
            t.innerHTML += profileCard;
            var hoverCard = t.querySelector('#hoverCard_'+personID);           

            ajax('hoverCard='+personID, function(xmlHttpObj){
                console.log(xmlHttp.responseText);
                if(!hoverCard.querySelector('.coverPic') && xmlHttpObj.responseText.length > 0){
                   var hoverCardElem = JSON.parse(xmlHttpObj.responseText).hoverCardDetails;
                    hoverCard.querySelector('.hoverCardHeader').innerHTML = 
                        '<img class="coverPic" src="'+hoverCardElem.coverPic+'">'+
                            '<div class="linearShadow"/>'+
                            '<div class="picAndDetails">'+
                                '<div class="profilePic"><img src="'+hoverCardElem.profilePic+'"></div>'+
                                '<div class="personDetails1">'+
                                    '<div class="personName"><a href="http://localhost/TetramandoX/' + hoverCardElem.userName + '">'+hoverCardElem.personName+'</a></div>'+
                                    '<div class="aboutPerson1">'+hoverCardElem.aboutPerson1+'</div>'+
                                    '<div class="aboutPerson2">'+hoverCardElem.aboutPerson2+'</div>'+
                                    '<div class="aboutPerson3">'+hoverCardElem.aboutPerson3+'</div>'+
                                '</div>'+
                            '</div>'
                    ;

                    hoverCard.querySelector('.hoverCardFooter').innerHTML = hoverCardElem.button;console.log(hoverCardElem.button);
                    flag2 = true;
                    for(i = 0; i < hoverCards.length; i++){
                        if(hoverCards[i].id == 'hoverCard_'+personID){
                            flag2 = false;
                            break;
                        }
                    }
                    if(flag2)
                        hoverCards[hoverCards.length] = hoverCard;
                }
            });
        } else {
            console.log('By Var');
            t.innerHTML += '<div class="hoverCard '+className+'" id="hoverCard_'+personID+'">'+hoverCardCornerDown+hoverCards[i].innerHTML+hoverCardCornerUP+'</div>';
        }
        
        document.querySelector('.hoverCard').setAttribute('onmouseout', 'hideProfileCard('+personID+', this)');
        t.setAttribute('onmouseout', 'hideProfileCard('+personID+', this)');
    }, 200);
}

function hideProfileCard(personID, t){
    setTimeout(function(){
        if(document.querySelector('#hoverCard_'+personID)){
            var hoverElements = document.querySelectorAll(':hover');
            var hoverElement = hoverElements[hoverElements.length - 1];
            if((hoverElement == undefined) || (!isParent(hoverElement, document.querySelector("#hoverCard_"+personID)) && !isParent(hoverElement, t.parentNode.querySelector("#personName_"+personID)))){ 
                document.querySelector('.hoverCard').remove();
            }
        }
    }, 200);
}