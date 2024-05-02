<?php
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
?>