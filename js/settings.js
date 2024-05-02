function showSettings(t){
	var totalSecurityPanes = document.querySelector('.settingsMainPane').querySelectorAll('#eachSettingsPane');
	for(var i = 0; i<totalSecurityPanes.length; i++){
		totalSecurityPanes[i].style.display = 'none';
	}

	var contentClassName = '.'+t.innerHTML+'Pane';
	document.querySelector(contentClassName).style.display = 'block';
}

function showOptionPane(settingsPane){
	settingsPane.id = '';
	settingsPane.querySelector('.eachSettingOptionPane').className = 'eachSettingOptionPane unHide';
	settingsPane.querySelector('.eachSettingStatus').innerHTML = '';
	settingsPane.id = 'notHover';
	
	var totOptionPanes = document.querySelectorAll('.eachSettingOptionPane');
	for(var i = 0; i<totOptionPanes.length; i++){
		if(totOptionPanes[i] != settingsPane.querySelector('.eachSettingOptionPane')){
			hideOptionPane(totOptionPanes[i].parentNode);
		}
	}	
}

function hideOptionPane(settingsPane){
	var optionPane = settingsPane.querySelector('.eachSettingOptionPane');
	optionPane.className = 'eachSettingOptionPane hide';
	settingsPane.id = 'hover';
	settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Edit';
	
	var totInputBoxs = optionPane.getElementsByTagName('input');
	for(var i = 0; i<totInputBoxs.length; i++){
		if(totInputBoxs[i].type == 'text')
			totInputBoxs[i].value = '';
	}

	var totStatusSpan = optionPane.querySelectorAll('.availStatus');
	for(var j = 0; j<totStatusSpan.length; j++){
		totStatusSpan[j].innerHTML = '';
	}

	var totOptionPaneLogs = optionPane.querySelectorAll('.optionPaneLog');
	for(var k = 0; k<totOptionPaneLogs.length; k++){
		if(totOptionPaneLogs[k].className == 'optionPane error'){
			totOptionPaneLogs[k].className = 'optionPaneLog';
			totOptionPaneLogs[k].innerHTML = '';
		}
	}

}

function isSettingAvail(t, type){
	//var type = t.parentNode.parentNode.parentNode.querySelector('.eachSettingTitle').innerHTML;
	if(type == 'Name' || type == 'Password' || type == 'Mobile Number' || type == 'currentPosition' || type == 'workStatus' || type == 'dob')
		var time = 200;
	else if(type == 'Username' || type == 'Email')
		var time = 1000;
	setTimeout(function(){
		var name = t.value;
		var element = t.parentNode.querySelector('.availStatus');
		var saveButton = element.parentNode.parentNode.querySelector('.saveChangeBut');
		if(type == 'Name'){
			var availStatusArr = t.parentNode.parentNode.querySelectorAll('.availStatus');
			var saveButton = element.parentNode.parentNode.querySelector('.saveChangeBut');
			
			if(name.trim().length>0){
				if(name.trim().length>1 &&  name.trim().length<33 && !String(name).match(/[^a-zA-Z\s]/)){
					element.style.color = '#2390ea';
					element.innerHTML = 'Available';
				
				} else{
					if(name.trim().length>32)
						element.innerHTML = 'Too long';
					else if(String(name).match(/[^a-zA-Z\s]/))
						element.innerHTML = 'Invalid characters';
					else if(name.trim().length<2)
						element.innerHTML = 'Too short';

					element.style.color = '#ef4800';
				}
			} else
				element.innerHTML = '';
			
			
			if(availStatusArr[0].innerHTML == 'Available'){
				saveButton.id = '';
				for(var i = 0; i<availStatusArr.length; i++){
					if(availStatusArr[i].innerHTML != 'Available' && availStatusArr[i].innerHTML != ''){
						saveButton.id = 'notFocus';
						break;
					}
				}

			} else
				element.parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
		} else if(type == 'Username'){
			if(name.trim().length>2){
				ajax('chkUsernameAvail='+name, function(){
					if(xmlHttp.responseText == 1){
						element.parentNode.parentNode.parentNode.querySelector('.eachSettingContent').innerHTML = 'http://localhost/TetramandoX/'+name;
						element.style.color = '#2390ea';
						element.innerHTML = 'Available';
						element.parentNode.parentNode.querySelector('.saveChangeBut').id = '';
					} else if(xmlHttp.responseText == 0){
						element.style.color = '#ef4800';
						element.innerHTML = 'Not Available';
						element.parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
					}
					var elements = t.parentNode.parentNode.querySelector('.availStatus');
				});
			} else{
				element.innerHTML = '';
				element.parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
				t.parentNode.parentNode.parentNode.querySelector('.eachSettingContent').innerHTML = 'http://localhost/TetramandoX/'+userDetails.username;
			}
		} else if(type == 'Email'){
			var email = t.value;
			if(email.trim().length>4){
				ajax('set_isMailAvail='+email, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						element.style.color = '#2390ea';
						element.innerHTML = 'Available';
						element.parentNode.parentNode.querySelector('.saveChangeBut').id = '';
					} else if(errorCode == 1){
						element.style.color = '#ef4800';
						element.innerHTML = 'Not Available';
						element.parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
					}
				});
			} else{
				element.innerHTML = '';
				element.parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
			}
		} else if(type == 'Password'){
			var passwordBoxs = t.parentNode.parentNode.getElementsByTagName('input');
			var availStatusArr = t.parentNode.parentNode.querySelectorAll('.availStatus');
			var availStatus = t.parentNode.querySelector('.availStatus');
			var currentPassword = passwordBoxs[0].value, newPassword = passwordBoxs[1].value, newPasswordAgain = passwordBoxs[2].value;
			var currentBox = t;

			for(i=0; i<availStatusArr.length; i++){
				if(availStatusArr[i].innerHTML != 'Passwords match' && availStatusArr[i].innerHTML != 'Passwords do not match')
					availStatusArr[i].innerHTML = '';
			}

			if(newPassword.length>5 && newPasswordAgain.length>5){	
				if(newPassword.localeCompare(newPasswordAgain) == 0){
					availStatusArr[2].style.color = '#2390ea';
					availStatusArr[2].innerHTML = 'Passwords match';
					
					if(currentPassword.length>5)
						availStatusArr[2].parentNode.parentNode.querySelector('.saveChangeBut').id = '';
					else
						availStatusArr[2].parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
				
				} else{
					availStatusArr[2].style.color = '#ef4800';
					availStatusArr[2].innerHTML = 'Passwords do not match';
					availStatusArr[2].parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
				}

			} else{
				availStatusArr[2].innerHTML = '';
				availStatusArr[2].parentNode.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
			}
		} else if(type == 'dob'){
			var optionPane = t.parentNode;
			var date = optionPane.getElementsByTagName('input')[0].value;
			var year = optionPane.getElementsByTagName('input')[1].value;
			var month = optionPane.getElementsByTagName('select')[0].value;
			if(date.length >= 1 && year.length == 4 && month != 0 && !String(date).match(/[^0-9]/) && !String(year).match(/[^0-9]/))
				saveButton.id = '';
			else
				saveButton.id = 'notFocus';

		} else if(type == 'mobileNum'){
			var textBox = t;
			var availStatus = t.parentNode.parentNode.querySelector('.availStatus');
			if(textBox.value.length == 10 && !String(textBox.value).match(/[^0-9]/)){
				ajax('set_isMobNumberAvail='+textBox.value, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						availStatus.innerHTML = 'Available';
						availStatus.style.color = '#2390ea';
						saveButton.id = '';
					} else{
						availStatus.innerHTML = 'Not Available';
						availStatus.style.color = '#ef4800';
						saveButton.id = 'notFocus';
					}
				});
			} else{
				availStatus.innerHTML = '';
				saveButton.id = 'notFocus';
			}

		} else if(type == 'secondaryMail'){
			var textBox = t;
			var availStatus = t.parentNode.parentNode.querySelector('.availStatus');
			if(textBox.value.trim().length>4){
				ajax('set_isSecMailAvail='+textBox.value, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						availStatus.style.color = '#2390ea';
						availStatus.innerHTML = 'Available';
						saveButton.id = '';
					} else if(errorCode == 1){
						availStatus.style.color = '#ef4800';
						availStatus.innerHTML = 'Not Available';
						saveButton.id = 'notFocus';
					}
				});
			} else {
				availStatus.innerHTML = '';
				saveButton.id = 'notFocus';
			}
		} else if(type == 'currentPosition'){
			var textBox = t;
			var availStatus = t.parentNode.querySelector('.availStatus');
			var availStatusArr = t.parentNode.parentNode.querySelectorAll('.availStatus');
			if(textBox.value.trim().length>2){
				if(!String(textBox.value).match(/[^a-zA-Z\s]/)){
					availStatus.style.color = '#2390ea';
					availStatus.innerHTML = 'Available';
				} else {
					availStatus.style.color = '#ef4800';
					availStatus.innerHTML = 'Invalid characters';
					saveButton.id = 'notFocus';
				}

				saveButton.id = '';
				for(var i = 0; i<availStatusArr.length; i++){
					if(availStatusArr[i].innerHTML != 'Available'){
						saveButton.id = 'notFocus';
						break;
					}
				}
			} else{
				availStatus.innerHTML = '';
				saveButton.id = 'notFocus';
			}
		} else if(type == 'workStatus'){
			var textBox = t;
			var availStatus = t.parentNode.querySelector('.availStatus');
			var availStatusArr = t.parentNode.parentNode.querySelectorAll('.availStatus');
			if(textBox.value.trim().length>2){
				if(!String(textBox.value).match(/[^a-zA-Z\s]/)){
					availStatus.style.color = '#2390ea';
					availStatus.innerHTML = 'Available';
				} else {
					availStatus.style.color = '#ef4800';
					availStatus.innerHTML = 'Invalid characters';
					saveButton.id = 'notFocus';
				}

				saveButton.id = '';
				for(var i = 0; i<availStatusArr.length; i++){
					if(availStatusArr[i].innerHTML != 'Available'){
						saveButton.id = 'notFocus';
						break;
					}
				}
			} else{
				availStatus.innerHTML = '';
				saveButton.id = 'notFocus';
			}
		}
	}, time);
}

function saveChange(saveButton){
	var t = saveButton;
	var availStatusArr = t.parentNode.parentNode.querySelectorAll('.availStatus');
	var isAvail = true;;
	var type = t.parentNode.parentNode.parentNode.querySelector('.eachSettingTitle').innerHTML;
	if(t.id == ''){
		if(type == 'Name'){
			for(var i = 0; i<availStatusArr.length; i++){
				if(availStatusArr[i].innerHTML != 'Available' && availStatusArr[i].innerHTML != ''){
					isAvail = false;
					break;
				}
			}

		} else if(type == 'Username' || type == 'Email'){
			if(availStatusArr[0].innerHTML != 'Available')
				isAvail = false;
		} else if(type == 'Password'){
				if(availStatusArr[2].innerHTML != 'Passwords match')
					isAvail = false;
		}

		if(isAvail == true){
			var element = t.parentNode.parentNode;
			var optionPane = saveButton.parentNode.parentNode;
			var settingsPane = saveButton.parentNode.parentNode.parentNode;
			if(type == "Name"){
				t.id = 'notFocus';
				var firstName = element.querySelector('#firstName').value;
				var middleName = element.querySelector('#middleName').value;
				var lastName = element.querySelector('#lastName').value;
				var nickName = element.querySelector('#nickName').value;
				var requestVar = 'setChangeName=1&&firstName='+firstName+'&&middleName='+middleName+'&&lastName='+lastName+'&&nickName='+nickName;
			
				
				ajax(requestVar, function(){
					if(xmlHttp.responseText == 1){
						// for(var i = 0; i<availStatusArr.length; i++){
						// 	availStatusArr[i].innerHTML = '';
						// 	var inputBox = availStatusArr[i].parentNode.getElementsByTagName('input')[0];
						// 	inputBox.placeholder = inputBox.value;
						// 	availStatusArr[i].parentNode.getElementsByTagName('input')[0].value = '';
						// }

						fullNameMiddle = middleName? ' '+middleName: '';
						fullNameLast = lastName? ' '+lastName: '';
						fullNameNick = nickName? ' ('+nickName+')': '';
						fullName = firstName+fullNameMiddle+fullNameLast+fullNameNick;
						fullName = fullName.length>30? String.substring(fullName, 0, 26)+'....': fullName;
						firstName = firstName.length>10? String.substring(firstName, 0, 7)+'...': firstName
						document.querySelector('#topmPanelUlLiLinkName').innerHTML = firstName;
						element.parentNode.querySelector('.eachSettingContent').innerHTML = fullName;
						element.parentNode.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						//element.parentNode.querySelector('.eachSettingOptionPane').className = 'eachSettingOptionPane hide';
						element.parentNode.id = 'hover';
						//element.parentNode.style.cursor = 'pointer';
						element.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
						totOptionPanes = optionPane.querySelectorAll('.eachChangeBox');
						for(i = 0; i<totOptionPanes.length; i++){
							totOptionPanes[i].remove();
						}

						optionPaneLog = optionPane.querySelector('.optionPaneLog');
						optionPaneLog.innerHTML = 'Note: You cant change your name at the moment. You should wait for 7 days to change your name';
						optionPaneLog.className = 'optionPaneLog';
						optionPane.className = 'eachSettingOptionPane hide';

						//t.id = 'notFocus';
					} else
						t.id='';
				});
			} else if(type == 'Username'){
				var username = optionPane.querySelector('#username').value;
				ajax('setChangeUsername='+username, function(){
					if(xmlHttp.responseText == 1){
						document.querySelector('.topmProfileLink').href = "http://localhost/TetramandoX/"+username;
						userDetails.username = username;
						optionPane.parentNode.id = 'hover';
						//optionPane.parentNode.style.cursor = 'pointer';
						optionPane.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
						optionPane.parentNode.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						optionPane.className = 'eachSettingOptionPane hide';
						
						optionPane.querySelector('.eachChangeBox').remove();
						optionPaneLog = optionPane.querySelector('.optionPaneLog');
						optionPaneLog.innerHTML = 'Note: You cant change your username at the moment';
						optionPaneLog.className = 'optionPaneLog';
					}
				});
			} else if(type == 'Email'){
				var email = optionPane.querySelector('#email').value;
				ajax('setChangeEmail='+email, function(){
					if(xmlHttp.responseText == 1){
						optionPane.parentNode.id = 'hover';
						optionPane.parentNode.querySelector('.saveChangeBut').id = 'notFocus';
						optionPane.parentNode.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						optionPane.parentNode.querySelector('.eachSettingContent').innerHTML = email;
						optionPane.className = 'eachSettingOptionPane hide';
						
						optionPane.getElementsByTagName('input')[0].value = null;
						optionPane.getElementsByTagName('input')[0].placeholder = email;
						optionPane.querySelector('.availStatus').innerHTML = '';
					}
				});
			} else if(type == 'Password'){
				var currentPassword = optionPane.querySelector('#currentPassword').value, newPassword = optionPane.querySelector('#newPassword').value;
				ajax('setChangePassword=1&&currentPassword='+currentPassword+'&&newPassword='+newPassword, function(){
					xmlHttp_JSN = JSON.parse(xmlHttp.responseText);
					optionPaneLog = optionPane.querySelector('.optionPaneLog');
					if(xmlHttp_JSN.errorCode == 0){						optionPaneLog.innerHTML = ''; 
						hideOptionPane(optionPane.parentNode);
						optionPane.parentNode.querySelector('.eachSettingContent').innerHTML = 'Updated - '+time2String();
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						
					} else if(xmlHttp_JSN.errorCode == 1){
						optionPaneLog.className = 'optionPaneLog error';
						optionPaneLog.innerHTML = 'Note: Incorrect password'; 
					}
				});
			} else if(type == 'Login Notification'){
				if(optionPane.querySelectorAll('.loginNotificationRadio')[0].checked)
					var requestVar = 'setChangeLoginNotif=t';
				else
					var requestVar = 'setChangeLoginNotif=f';

				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						hideOptionPane(settingsPane);
						var onChange_On = optionPane.querySelectorAll('.loginNotificationRadio')[0].getAttribute('onChange');
						var onChange_Off = optionPane.querySelectorAll('.loginNotificationRadio')[1].getAttribute('onChange');
						
						var temp = onChange_Off;
						onChange_Off = onChange_On;
						onChange_On = temp;
						
						optionPane.querySelectorAll('.loginNotificationRadio')[0].setAttribute('onChange', onChange_On);
						optionPane.querySelectorAll('.loginNotificationRadio')[1].setAttribute('onChange', onChange_Off);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';
					}
				});
			} else if(type == 'Gender'){
				if(optionPane.querySelectorAll('.genderRadio')[0].checked){
					var requestVar = 'set_Gender=m';
					var settingsPaneContent = 'Male';
				} else{
					var requestVar = 'set_Gender=f';
					var settingsPaneContent = 'Female';
				}
				ajax(requestVar, function(){
					if(JSON.parse(xmlHttp.responseText).errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingContent').innerHTML = settingsPaneContent;
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';
					}
				});

			} else if(type == 'Date of Birth'){
				var textBox = optionPane.getElementsByTagName('input');
				var month = optionPane.getElementsByTagName('select')[0];
				var requestVar = 'set_DOB=1&&date='+textBox[0].value+'&&month='+month.value+'&&year='+textBox[1].value;
				//var availStatus = t.parentNode
				if(textBox[0].value.length >= 1 && textBox[1].value.length ==4 && month.value != 0 && !String(textBox[0].value).match(/[^0-9]/) && !String(textBox[1].value).match(/[^0-9]/)){
					ajax(requestVar, function(){
						var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
						if(errorCode == 0){
							textBox[0].placeholder = textBox[0].value;
							textBox[1].placeholder = textBox[1].value;
							var monthName = month.getElementsByTagName('option')[month.value].innerHTML;
							settingsPane.querySelector('.eachSettingContent').innerHTML = textBox[0].value+' '+monthName+', '+textBox[1].value;
							hideOptionPane(settingsPane);
							settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						} else if(errorCode == 1){
							saveButton.id = 'notFocus';
							availStatusArr[0].innerHTML = 'Invalid date';
							availStatusArr[0].style.color = '#ef4800';
						}
					});
				} else {
					availStatusArr[0].innerHTML = 'Invalid date';
					availStatusArr[0].style.color = '#ef4800';
				}

			} else if(type == 'Mobile Number'){
				var textBox = t.parentNode.parentNode.getElementsByTagName('input')[0];
				if(textBox.value.length == 10 && !String(textBox.value).match(/[^0-9]/)){
					ajax('set_MobileNumber='+textBox.value, function(){
						var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
						if(errorCode == 0){
							settingsPane.querySelector('.eachSettingContent').innerHTML = textBox.value;
							textBox.placeholder = textBox.value;
							hideOptionPane(settingsPane);
							settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						} else if(errorCode == 1){
							saveButton.id = 'notFocus';
							availStatusArr[0].innerHTML = 'Not Available';
							availStatusArr[0].style.color = '#ef4800';
						}
					});
				}

			} else if(type == 'Secondary Email'){
				var textBox = t.parentNode.parentNode.getElementsByTagName('input')[0];
				ajax('set_SecMail='+textBox.value, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						settingsPane.querySelector('.eachSettingContent').innerHTML = textBox.value;
						textBox.placeholder = textBox.value;
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
					} else if(errorCode == 1){
						saveButton.id = 'notFocus';
						availStatusArr[0].innerHTML = 'Not Available';
						availStatusArr[0].style.color = '#ef4800';
					}
				});
			} else if(type == 'Current Position'){
				var textBox = t.parentNode.parentNode.getElementsByTagName('input');
				var requestVar = 'set_currentPosition=1&&city='+textBox[0].value+'&&state='+textBox[1].value+'&&country='+textBox[2].value;
				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						for(var i=0; i<3; i++){
							textBox[i].placeholder = textBox[i].value;
						}
						
						settingsPane.querySelector('.eachSettingContent').innerHTML = textBox[0].value+', '+textBox[1].value+', '+textBox[2].value;
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
					} else if(errorCode == 1){
						saveButton.id = 'notFocus';
						hideOptionPane(settingsPane);
						showOptionPane(settingsPane);
					}
				});

			} else if(type == 'Work'){
				var textBox = t.parentNode.parentNode.getElementsByTagName('input');
				var requestVar = 'set_workStatus=1&&position='+textBox[0].value+'&&company='+textBox[1].value;
				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						textBox[0].placeholder = textBox[0].value;
						textBox[1].placeholder = textBox[1].value;
						settingsPane.querySelector('.eachSettingContent').innerHTML = textBox[0].value+' at '+textBox[1].value;
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
					} else if(errorCode == 1){
						saveButton.id = 'notFocus';
						hideOptionPane(settingsPane);
						showOptionPane(settingsPane);
					}
				});

			} else if(type == 'Basic Privacy'){
				var totRadios = optionPane.querySelectorAll('.basicPrivacyRadio');
				for(var i = 0; i<totRadios.length; i++){
					if(totRadios[i].checked){
						var requestVar = 'setBasicPrivacy='+totRadios[i].value;
					}
				}
				ajax(requestVar, function(){
					if(JSON.parse(xmlHttp.responseText).errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';

					}
				});
			} else if(type == 'Timeline Privacy'){
				var totRadios = optionPane.querySelectorAll('.timelinePermissionRadio');
				for(var i = 0; i<totRadios.length; i++){
					if(totRadios[i].checked){
						var requestVar = 'setChangeTimelinePrivacy='+totRadios[i].value;
					}
				}

				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						 saveButton.id = 'notFocus';

					}
				});
			} else if(type == 'Request Permission'){
				if(optionPane.querySelectorAll('.requestPermissionRadio')[0].checked)
					var requestVar = 'set_ReqPermission=e';
				else
					var requestVar = 'set_ReqPermission=m';
				
				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';
					}
				});
			} else if(type == 'Chat Privacy'){
				var totRadios = optionPane.querySelectorAll('.chatPermissionRadio');
				for(var i = 0; i<totRadios.length; i++){
					if(totRadios[i].checked){
						var requestVar = 'set_ChatPermission='+totRadios[i].value;
					}
				}
				
				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';
					}
				});
			} else if(type == 'Post Promotion'){
				var totRadios = optionPane.querySelectorAll('.promotePermissionRadio');
				for(var i = 0; i<totRadios.length; i++){
					if(totRadios[i].checked){
						var requestVar = 'set_PromotePermission='+totRadios[i].value;
					}
				}

				ajax(requestVar, function(){
					var errorCode = JSON.parse(xmlHttp.responseText).errorCode;
					if(errorCode == 0){
						hideOptionPane(settingsPane);
						settingsPane.querySelector('.eachSettingStatus').innerHTML = 'Changes Saved';
						saveButton.id = 'notFocus';
					}
				});
			}
		}
	} else{
		if((type == 'Name' || type == 'Username' || type == 'Email') && availStatusArr.length >= 1){
			availStatusArr[0].style.color = '#ef4800';
			availStatusArr[0].innerHTML = 'Required';
		
		} else if(type == 'Password'){
			for(i=0; i<availStatusArr.length; i++){
				var passwordBox = availStatusArr[i].parentNode.getElementsByTagName('input')[0];
				if(passwordBox.value.length == 0){
					availStatusArr[i].style.color = '#ef4800';
					availStatusArr[i].innerHTML = 'Required';
				} else if(passwordBox.value.length <6){
					availStatusArr[i].style.color = '#ef4800';
					availStatusArr[i].innerHTML = 'Minimum 6 characters';
				}
			}
		
		}  else if(type == 'Mobile Number'){
			availStatusArr[0].style.color = '#ef4800';
			availStatusArr[0].innerHTML = 'Required';
		
		} else if(type == 'Secondary Email'){
			availStatusArr[0].style.color = '#ef4800';
			availStatusArr[0].innerHTML = 'Required';
		
		} else if(type == 'Current Position'){
			for(i=0; i<availStatusArr.length; i++){
				var textBox = availStatusArr[i].parentNode.getElementsByTagName('input')[0];
				if(textBox.value.trim().length == 0){
					availStatusArr[i].style.color = '#ef4800';
					availStatusArr[i].innerHTML = 'Required';
				} else if(textBox.value.trim().length <3){
					availStatusArr[i].style.color = '#ef4800';
					availStatusArr[i].innerHTML = 'Minimum 3 characters';
				}
			}
		}
	}
}
function undoSpam(personID){
	var spamBox = document.querySelector('#spamPerson_'+personID);
	spamBox.style.display = 'none';
	ajax('set_UndoSpam='+personID, function(){
		if(JSON.parse(xmlHttp.responseText).errorCode == 0){
			if(document.querySelectorAll('.spammedUsers').length == 1){
				spamBox.parentNode.innerHTML = '<div class="spammedUsers noSpam"><div class="personName">No spam users</div></div>'+spamBox.parentNode.innerHTML;
			}
			spamBox.remove();
		}
	});
}

function unblockPerson(personID){
	var blockBox = document.querySelector('#blockPerson_'+personID);
	blockBox.style.display = 'none';
	ajax('set_UnblockPerson='+personID, function(){
		if(JSON.parse(xmlHttp.responseText).errorCode == 0){
			if(blockBox.parentNode.querySelectorAll('.blockedUsers').length == 1){
				blockBox.parentNode.innerHTML = '<div class="blockedUsers noBlock"><div class="personName">No blocked users</div></div>'+blockBox.parentNode.innerHTML;
			}
			blockBox.remove();
		}
	});	
}




// var hell = "AKIBATH";
// hell = String.toLowerCase(hell);
// console.log("After full lower: "+hell);
// hell[0] = String.toUpperCase(hell[0]);
// console.log("After full ready: "+hell);
// hell[0] = String.toLocaleUpperCase(hell[0]);
// console.log(hell)