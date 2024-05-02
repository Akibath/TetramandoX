function showMoreResults(){
	if(document.querySelector('.showMoreResults').id != 'notFocus'){
		document.querySelector('.showMoreResults').innerHTML = ' ';
		document.querySelector('.showMoreResults').id = 'notFocus';
    	var lastResult = document.querySelector('.searchResults').querySelectorAll('.eachResult').length;

 		var theVar = 'showMoreResults=1&&lastResultPosition=' + lastResult +'&&'+ ajaxRequest_search();
    	ajax(theVar, function(){
    		var responseJSN = xmlHttp.responseText != ''? JSON.parse(xmlHttp.responseText): '';
    		if(responseJSN.errorCode == 0){
    			var matchedPersons = responseJSN.matchedPersons;
    			remainResults = searchResult(matchedPersons);
    			document.querySelector('.showMoreResults').remove();
    			
    			if(responseJSN.showMore == 1)
    				document.querySelector('.resultDetails').querySelector('.allResults').innerHTML += remainResults+'<div class="showMoreResults" onclick="showMoreResults()">See more...</div>';
    			else
    				document.querySelector('.resultDetails').querySelector('.allResults').innerHTML += remainResults;
    		}
    	});
	}
}

function searchPersons(){
	var theVar = 'searchNames=1&&'+ajaxRequest_search();
	window.history.pushState('', '', 'search.php?'+ajaxRequest_search());
	ajax(theVar, function(){
		console.log(xmlHttp.responseText);
		var responseJSN = xmlHttp.responseText != ''? JSON.parse(xmlHttp.responseText): '';
		if(responseJSN.errorCode == 0){
			var personPropt = responseJSN.matchedPersons;
			searchResults = searchResult(personPropt);

			var showMoreResults = responseJSN.showMore == 1? '<div class="showMoreResults" onclick="showMoreResults()">See more...</div>': '';
			document.querySelector('.resultDetails').querySelector('.allResults').innerHTML = searchResults + showMoreResults;
			document.querySelector('.resultDetails').querySelector('.totalResults').innerHTML = responseJSN.totalResults+(responseJSN.totalResults>1? ' results found': ' result found');
	
		} else if(responseJSN.errorCode == 1){
			document.querySelector('.resultDetails').querySelector('.totalResults').innerHTML = '0 result found';
			document.querySelector('.resultDetails').querySelector('.allResults').innerHTML = '';
		}
	});
}

function ajaxRequest_search(){
	var searchTools = document.querySelector('.searchToolContent');
	if(searchTools.querySelector('.genderMale').checked)
		var gender = 'm';
	else if(searchTools.querySelector('.genderFemale').checked)
		var gender = 'f';
	else
		var gender = '';

	var theVar = 
		'name=' + searchTools.querySelector('.name').value + '&&gender=' + gender +
		'&&ageFrom=' + searchTools.querySelector('.ageFrom').value + '&&ageTo=' + searchTools.querySelector('.ageTo').value +
		'&&mobileNumber=' + searchTools.querySelector('.mobileNo').value +
		'&&emailID=' + searchTools.querySelector('.email').value +
		'&&work=' + searchTools.querySelector('.work').value +
		'&&location=' + searchTools.querySelector('.location').value
	;

	return theVar;
}

function searchResult(personPropt){
	var searchResults = '';
	for(i=0; i<personPropt.length; i++){
		var personWork = personPropt[i].work == null? '': '<div>'+personPropt[i].work+'</div><br>';
		var personLocation = personPropt[i].location == null? '': '<div>'+personPropt[i].location+'</div><br>';
		
		if(personPropt[i].mutualFriends != 0 && personPropt[i].mutualFriends != null)
			var mutualFriends = '<div>' + personPropt[i].mutualFriends + (personPropt[i].mutualFriends>1? ' mutual friends</div>': ' mutual friend</div>');
		else
			var mutualFriends = '';
		
		searchResults += 
			'<li class="eachResult">'+
			'<a href="'+personPropt[i].username+'">'+
				'<div class="profilePic">'+
					'<img src="'+personPropt[i].profilePicURL+'">'+
				'</div>'+
			'<div class="personDetail">'+
				'<div class="personName">'+personPropt[i].personName+'</div>'+
			'</a><br>'+
				'<div class="personDesc">'+
				personWork+personLocation+mutualFriends+
				'</div>'+
			'</div>'+
		'</li>';
	}

	return searchResults;
}