<?php
	// Connects to Database
	//mysql_connect("localhost", "xgamings_connact", "connactive123") or die(mysql_error()); //This is our database credentials
	mysql_connect("localhost", "root", "") or die(mysql_error()); 	//This is wamp database credentials
	mysql_select_db("xgamings_connactiv") or die(mysql_error()); 

	function cookieExists(){
		//Check to see if user ID cookie exists
		if(isset($_COOKIE['ID_my_site']))
			return TRUE;
		else
			return FALSE;
	}
	function validCookie(){
		//Check to make sure the user ID cookie matches the password cookie
		$username = $_COOKIE['ID_my_site']; 
		$pass = $_COOKIE['Key_my_site'];
		$info = getDatabaseInfo("users", "email", $username);
		//while($info = getDatabaseInfo("users", "email", $username)) 	{ 		//Since we have unique cookies I do not think we need to have this
																//loop. Can anyone confirm?
			if ($pass != $info['PASSWORD']) {
				//ID cookie doesn't match password cookie
				return FALSE;
			}
			else{
				//ID cookie matches, password cookie
				return TRUE;
 			}
		//}
	}
	function login(){
		//This function logs in the user when they press the login button
		// makes sure they filled it in
		if(!$_POST['username'] | !$_POST['pass']) {
			die('You did not fill in a required field.');
		}
		// checks it against the database
		//if (!get_magic_quotes_gpc()) {
		//	$_POST['email'] = addslashes($_POST['email']);		//I do not know where this came from. Did you add it dave?
		//}
		$check = mysql_query("SELECT * FROM Users WHERE email = '".$_POST['username']."'")or die(mysql_error());
		//Gives error if user dosen't exist
		$check2 = mysql_num_rows($check);
		if ($check2 == 0) {
			die('That user does not exist in our database. <a href=registration.php>Click Here to Register</a>');
		}
		while($info = mysql_fetch_array( $check )) 	
		{
			$_POST['pass'] = stripslashes($_POST['pass']);
			$info['PASSWORD'] = stripslashes($info['PASSWORD']);
			$_POST['pass'] = md5($_POST['pass']);
			//gives error if the password is wrong
			if ($_POST['pass'] != $info['PASSWORD']) {
				die('Incorrect password, please try again.');
			}
			else { 
			// if login is ok then we add a cookie 
			 $_POST['username'] = stripslashes($_POST['username']);
			 $hour = time() + 100000;
			setcookie('ID_my_site', $_POST['username'], $hour);
			setcookie('Key_my_site', $_POST['pass'], $hour);
			//then redirect them to the members area 
			header("Location: ../index.html");
			} 
		}
	}
	function register(){
	//This function registers the user when they press the register button
	//check to make sure the required fields were filled in.
		if(!$_POST['username'] | !$_POST['password'] | !$_POST['confirm']) {
			die('You did not fill in a required field.');
		}
		//check to make sure the email is not already registered.
		$check = mysql_query("SELECT * FROM Users WHERE email = '".$_POST['username']."'")or die(mysql_error());
		$check2 = mysql_num_rows($check);
		
		
		//if email has not been registered before
		if($check2 == 0){
			//check to make sure the password was confirmed			
			if($_POST['password'] == $_POST['confirm']){			
				//check to make sure the password is longer than 6 characters, may want to use regexp to improve
				//security				
				if(strlen($_POST['password'] > 6)){
				//insert information into respective tables.
				$insert = mysql_query("Insert into Users(email,firstname, lastname, street, city, state, zip, phone, interests, 				password)  values('".$_POST['username']."','".$_POST['firstName']."','".$_POST['lastName']."','".
				$_POST['street']."','".$_POST['city']."','".$_POST['state']."','".$_POST['zip']."','".$_POST['phone']."','".
				$_POST['interests']."','".md5($_POST['password'])."'") or die(mysql_error());

				//get userid of new user since the user_id is auto incremented and will not be explicitly added.
				$userid = mysql_query("Select USER_ID where email = '".$_POST['username']."'");
				$userid1 = mysql_fetch_array($userid);
				
				//insert all of the user selected activities into the user_activities table.				
				foreach($_POST['activities'] as $act){
					$activityid = mysql_query("select ACTIVITY_ID from ACTIVITIES where ACTIVITY_NAME = '".$act."'");
					$activityid1 = mysql_fetch_array($activityid);

					$insert = mysql_query("Insert into USER_ACTIVITIES(USER_ID, ACTIVITY_ID) values('".$userid1['USER_ID']."','".
					$activityid1['ACTIVITY_ID']."'");
				}

				//create cookie
				$_POST['username'] = stripslashes($_POST['username']); 
				$hour = time() + 100000; 
				setcookie(ID_my_site, $_POST['username'], $hour); 
				setcookie(Key_my_site, $_POST['pass'], $hour);
				//redirect to home				
				header("Location: ../index.html");
				}
			}	
			//if the passwords do not match ask them to enter the information again
			else{ die("the passwords do not match, please re-enter your information");}
		}
		//if the email is already registered then display message
		else{
			die('This email has already been registered.  click here if you forgot your password.');
		}
	}
	function getDatabaseInfo($table, $attribute, $value){
		//Returns an array of strings that corresponds to the fetched row
		$check = getResourceIDs($table, $attribute, $value); 
		
		return mysql_fetch_array($check);
	}
	function getResourceIDs($table, $attribute, $value){
		//This function returns resource IDs
		
		$check = mysql_query("SELECT * FROM $table WHERE $attribute = '$value'")or die(mysql_error()); //retuns true if you do not assign
		return $check;																				   //mysql_query() to a variable
	}
	function getResourceIDs2($table, $attribute1, $value1, $attribute2, $value2){
		//This function returns resource IDs
		
		$check = mysql_query("SELECT * FROM $table WHERE $attribute1 = '$value1' AND $attribute2 = '$value2'")or die(mysql_error()); //retuns true if you do not assign
		return $check;																				   //mysql_query() to a variable
	}
	function getName(){
		//This function returns the users First and Last name
		if(cookieExists())
		//if there is a username cookie, we need to check it against our password cookie
		{
			if (validCookie()) {
				//Cookie matches display their name
				$username = $_COOKIE['ID_my_site'];
				$info = getDatabaseInfo("users", "email", $username);
				return $info['FIRST_NAME'] . " " . $info['LAST_NAME'];
			}
		}
	}
	function getUserID(){
		//This function retusn the users ID
		if(cookieExists())
		//if there is a username cookie, we need to check it against our password cookie
		{
			if (validCookie()) {
				//Cookie matches display their name
				$username = $_COOKIE['ID_my_site'];
				$info = getDatabaseInfo("users", "email", $username);
				return $info['USER_ID'];
			}
		}
	}
	function getNetworkID($networkName){
		//This function returns the ID value of the network name that is inputed
		
		$network = getDatabaseInfo("networks","area", $networkName);
		$networkID = $network['NETWORK_ID'];
		
		return $networkID;
	}
	function getNetworkNames(){
		//This function returns an array of user's network names
		$userID = getUserID();
		$networkName = array();
		$resourceID = getResourceIDs("user_networks", "user_id", $userID);
		while($info = mysql_fetch_array($resourceID)){
			$network = getDatabaseInfo("networks","network_id", $info['NETWORK_ID']);
			$networkName[] = $network['AREA'];
		}
		
		return $networkName;
	}
	function getAllNetworkActivites(){
		//This function returns an array of all network activities
		$networkNames = getNetworkNames();
		$networkActivities = array();
		
		for($i = 0; $i < count($networkNames); $i++){
			$resourceID = getResourceIDs("networks", "area", $networkNames[$i]);
			while($info = mysql_fetch_array($resourceID)){
				$activityToken = strtok($info['ACTIVITY_ID'], ",");
				while($activityToken != NULL){
					$activity = getDatabaseInfo("activities","activity_id", $activityToken);
					$networkActivities[] = $activity['ACTIVITY_NAME'];
					$activityToken = strtok(",");
				}
			}
		}
		return $networkActivities;
	}
	function getNetworkActivites($networkName){
		//This function returns an array of all network activities
		$networkActivities = array();
		
		$resourceID = getResourceIDs("networks", "area", $networkName);
		while($info = mysql_fetch_array($resourceID)){
			$activityToken = strtok($info['ACTIVITY_ID'], ",");
			while($activityToken != NULL){
				$activity = getDatabaseInfo("activities","activity_id", $activityToken);
				$networkActivities[] = $activity['ACTIVITY_NAME'];
				$activityToken = strtok(",");
			}
		}
		return $networkActivities;
	}
	function getUserNetworkActivities($networkName){
		//This function returns an array of the user's network activites
		$userID = getUserID();
		$userActivities = array();
		$resourceID = getResourceIDs2("user_networks", "user_id", $userID, "network_id", getNetworkID($networkName));
		while($info = mysql_fetch_array($resourceID)){
			$activityToken = strtok($info['ACTIVITY_ID'], ",");
			while($activityToken != NULL){
				$activity = getDatabaseInfo("activities","activity_id", $activityToken);
				$UsersNetworkActivities[] = $activity['ACTIVITY_NAME'];
				$activityToken = strtok(",");
			}
		}
		return $UsersNetworkActivities;
		
	}
	function getUserActivities(){
		//This function returns an array of all the activities a user has
		$userID = getUserID();
		$userActivities = array();
		
		$resourceID = getResourceIDs("user_activities", "user_id", $userID);
		while($info = mysql_fetch_array($resourceID)){
			$activity = getDatabaseInfo("activities", "activity_id", $info['ACTIVITY_ID']);
			$userActivities[] = $activity['ACTIVITY_NAME'];
		}
		return $userActivities;
	}
	function printArray($array){
		//This function echos the passed in array
		for($i = 0; $i < count($array); $i++){
			echo $array[$i];
		}
	}
?>