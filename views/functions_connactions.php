<?

/*
*
** Connactions
*
*/

	function postConnaction(){
	
	/*
	**
	* HEY ROBBBBBER ROB can you fix this so it takes the date the way datepicker inputs it? KCOOOOOL -Kim
	* Done - Rob
	**
	*/
	
		//This function will post the connaction to the database!
		$start = myDateParser($_POST['startDate']);
		$end = myDateParser($_POST['endDate']);
		$today = date("Y-m-d");
		$startTime = $start." ".$_POST['startHour'].":".$_POST['startMin'].":00";
		$endTime = $end." ".$_POST['endHour'].":".$_POST['endMin'].":00";
		
		
		$query = "INSERT INTO connactions(POST_TIME, USER_ID, LOCATION, START_TIME, MESSAGE, END_TIME, ACTIVITY_ID, NETWORK_ID, IS_PRIVATE)
			VALUES ('".$today."', '".getUserID()."', '".$_POST['location']."', '".$startTime."', '".$_POST['message']."', '".$endTime."'
					, '".$_POST['activity']."', '".$_POST['network']."', '".$_POST['private']."')";
					
		$insert = mysql_query($query) or die(mysql_error());
		header("Location: ../index.html");
	}
	
	/*		///This function was replaced by the getConnactions functions
	function getConnactionUsers($n_aID, $option){
		//The option is whether the ID is network_ID or Activity_ID
		//0 = Activity_ID, 1 = network_ID
		//This function returns an array of all users names who posted a connaction
		$connactionUsers = array();
		
		if($option == 0){
			//ID is activity
			$resourceID = getResourceIDs("connactions", "activity_id", $n_aID);
			while($info = mysql_fetch_array($resourceID)){
				$connactionUsers[] = $info["USER_ID"];
			}
			return $connactionUsers;
		}
		else if($option == 1){
			//ID is network
			//print $n_aID;
			$resourceID = getResourceIDs("connactions", "network_id", $n_aID);
			while($info = mysql_fetch_array($resourceID)){
				$connactionUsers[] = $info["USER_ID"];
			}
			return $connactionUsers;
		}
		else{
			return "error";
		}
		
	}*/
	

	function getPastConnactions($userid){
		$query = "select connaction_id from connactions c, connaction_attending ca where ca.user_id = ".$userid." and c.end_time < getdate() and c.connaction_id = ca.connaction_id";
		$past = mysql_query($query);
		return $past;
		
	}

	function getConnactions($n_aID, $option){
		//The option is whether the ID is network_ID or Activity_ID
		//0 = Activity_ID, 1 = network_ID
		//This function returns an array of all users names who posted a connaction
		$connactionUsers = array();
		
		if($option == 0){
			//ID is activity
			//$resourceID = getResourceIDs("connactions", "activity_id", $n_aID);
			$result = mysql_query("SELECT * FROM connactions WHERE activity_id = '$n_aID' ORDER BY connaction_id DESC")or die(mysql_error()); //returns true if you do not assign

			while($info = mysql_fetch_array($result)){
				$connactionUsers[] = $info;
			}
			return $connactionUsers;
		}
		else if($option == 1){
			//$resourceID = getResourceIDs("connactions", "network_id", $n_aID);
			$result = mysql_query("SELECT * FROM connactions WHERE network_id = '$n_aID' ORDER BY connaction_id DESC")or die(mysql_error()); //returns true if you do not assign

			while($info = mysql_fetch_array($result)){
				$connactionUsers[] = $info;
			}
			return $connactionUsers;
		}
		else{
			return "error";
		}
	}
	function getConnactionActivity($connactionID){
		//This function will return the activity of connaction
		$activityID = getDatabaseInfo("connactions", "connaction_id", $connactionID);
		$activity = getDatabaseInfo("activities", "activity_id", $activityID['ACTIVITY_ID']);	
		$activityName = $activity['ACTIVITY_NAME'];
		
		return $activityName;
	}
	function getConnactionNetwork($connactionID){
		//This function will return the network of connaction
		$networkID = getDatabaseInfo("connactions", "connaction_id", $connactionID);
		$network = getDatabaseInfo("networks", "network_id", $networkID['NETWORK_ID']);	
		$networkName = $network['AREA'];
		
		return $networkName;
	}
	function getConnactionDate($connactionID, $argument){
		//This function will return the date of connaction
		//Argument should be POST, START or END
		$dateID = getDatabaseInfo("connactions", "connaction_id", $connactionID);	
		$date = $dateID[$argument.'_TIME'];
		$dateParsed = date_parse($date);
		$newDate = $dateParsed["month"].'/'.$dateParsed["day"].'/'.$dateParsed["year"];
		return $newDate;
	}
	function printArray($array){
		//This function echos the passed in array
		for($i = 0; $i < count($array); $i++){
			echo $array[$i];
		}
	}
	function getCurYear(){
		$today = getdate();
		
		return $today["year"];
	}
	function getCurMonth($condition){
		//This function returns the current month
		// 0 = the word
		// 1 = the number
		$today = getdate();
		
		//return $today;
		if($condition == 0){
			return $today["month"];
		}
		else if($condition == 1){
			return $today["mon"];
		}
		else{
			return -1;
		}
	}
	function getCurDay(){
		//This function returns the current day
		$today = getdate();
		
		return $today["mday"];
	}
	function getCurHour(){
		//This function returns the current hour
		$today = getdate();
		
		return $today["hours"];
	}
	function getCurMin(){
		//This function returns the current hour
		$today = getdate();
		
		return $today["minutes"];
	}
	function getDays($month){
		//This function returns the total days in a month
		//30 sept april june nov
		if($month == 1){
			return 31;
		}
		else if($month == 2){
			return 28;
		}
		else if($month == 3){
			return 31;
		}
		else if($month == 4){
			return 30;
		}
		else if($month == 5){
			return 31;
		}
		else if($month == 6){
			return 30;
		}
		else if($month == 7){
			return 31;
		}
		else if($month == 8){
			return 31;
		}
		else if($month == 9){
			return 31;
		}
		else if($month == 10){
			return 31;
		}
		else if($month == 11){
			return 30;
		}
		else if($month == 12){
			return 31;
		}
		else{
			return -1;
		}
	}



?>
