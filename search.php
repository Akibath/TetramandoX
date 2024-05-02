<?php
	define('core_inc', true);
	define('topMenu_inc', true);
	require 'core.inc.php';
	login2Continue();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Search People</title>
	<script src="http://localhost/TetramandoX/js/main.js"></script>
	<script src="http://localhost/TetramandoX/js/search.js"></script>
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/topMenu.css">
	<link type="text/css" rel="stylesheet" href="http://localhost/TetramandoX/css/search.css">
	<meta charset="UTF-8">
</head>
<body>
	<?php require 'topMenu.inc.php'; ?>
<div class="mainContent">
	<div class="searchResults">
		<div class="title">
				Search Result
		</div>
		<div class="content">
			<ul class="resultDetails">
			<?php
				list($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location) = filterGeted('name', 'gender', 'ageFrom', 'ageTo', 'mobileNumber', 'emailID', 'work', 'location');
				if(!in_array($gender, array('M', 'F'))) $gender = 'B';
				list($matchedPersons, $matchedResults) = array(graphicalSearch($name, $gender, $ageFrom, $ageTo, $mobileNumber, $emailID, $work, $location), null);
				for($i = 0; $i < 25; $i++){
					if(empty($matchedPersons[$i])) break;
					$personID = $matchedPersons[$i];
					$personName = personName($personID, true, 41);
					$profilePicURL = profilePicURL($personID, 128);
					$profileURL = profileURL($personID);
					$personProfession = !empty(personProfession($personID, true))? '<div>'.personProfession($personID, true).'</div><br>': null;
					$personLocation = !empty(personLocation($personID, true))? '<div>'.personLocation($personID, true).'</div><br>': null;
					
					if($personID != userID){
						$totalMutualFriends = totalMutualFriends($personID);
						if($totalMutualFriends > 1) $totalMutualFriends = '<div>'.$totalMutualFriends.' Mutual friends</div>';
						else if($totalMutualFriends > 0) $totalMutualFriends = '<div>'.$totalMutualFriends.' Mutual friend</div>';
					} else $totalMutualFriends = null;
					
					$matchedResults .=
						'<li class="eachResult">
							<a href="'.$profileURL.'">
								<div class="profilePic">
									<img src="'.$profilePicURL.'">	
								</div>
							<div class="personDetail">
								<div class="personName">'.$personName.'</div>
							</a><br>	
								<div class="personDesc">
									'.$personProfession.$personLocation.$totalMutualFriends.'
								</div>
							</div>
						</li>';
				}
				
				
				$seeMoreResults = count($matchedPersons)>$i? '<div class="showMoreResults" onclick="showMoreResults()">See more...</div>': null;
				$noResults = empty($matchedResults)? '<div class="noResults">No results found</div>': null;
				$totalResults = '<div class="totalResults">'.count($matchedPersons).(count($matchedPersons)>1? ' results': ' result').' found</div>';
				echo $totalResults.'<div class="allResults">'.$matchedResults.$seeMoreResults.'</div>';

				//echo '<div class="totalResults">0 result found</div><div class="allResults"></div>';
			?>
			</ul>
		</div>
	</div>
	<div class="searchTools">
		<div class="title">Filter Search</div>
		<ul class="label">
			<li>Name: </li>
			<li>Gender: </li>
			<li>Age: </li>
			<li>Mobile no: </li>
			<li>Email: </li>
			<li>Works at: </li>
			<li>Lives at: </li>
		</ul>
		<ul class="searchToolContent">
			<li><input type="text" class="name" value="<?php echo $name; ?>"></li>
			<li class="gender">
				<input type="radio" class="radio genderSelect" id="genderSelect" name="searchToolGender" <?php echo $gender == 'B'? 'checked': null; ?> >
					<label for="genderSelect">Both</label>
				<input type="radio" class="radio genderMale" id="genderMale" name="searchToolGender"<?php echo $gender == 'M'? 'checked': null; ?> >
					<label for="genderMale">Male</label>
				<input type="radio" class="radio genderFemale" id="genderFemale" name="searchToolGender"<?php echo $gender == 'F'? 'checked': null; ?> >
					<label for="genderFemale">Female</label>
			</li>
			<li class="age">
				<input type="text" class="ageFrom" name="ageFrom" maxlength="3" placeholder="From" value="<?php echo $ageFrom; ?>">
				<input type="text" class="ageTo" name="ageTo" maxlength="3" placeholder="To" value="<?php echo $ageTo; ?>">
			</li>
			<li><input type="text" class="mobileNo" value="<?php echo $mobileNumber; ?>"></li>
			<li><input type="text" class="email" value="<?php echo $emailID; ?>"></li>
			<li><input type="text" class="work" value="<?php echo $work; ?>"></li>
			<li><input type="text" class="location" value="<?php echo $location; ?>"></li>
		</ul>
		<button onclick="searchPersons();">Search</button>
	</div>
</div>
</body>
</html>