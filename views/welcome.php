<?php

	// Connects to Database
	
	include("config.php");
	//Checks if there is a login cookie
	if(cookieExists())
	//if there is a username cookie, we need to check it against our password cookie
	{ 
		if (!validCookie()) {
			//Cookie doesn't match password go to index
			header("Location: ../index.html"); 
		}
		else{
			//Cookie matches, show what they want
			header("Location: home.php");
 		}
	}
	if (isset($_POST['login'])) { 
		//If user pressed login
		login(); 

	} 
	else if (isset($_POST['register'])) {
		//If user pressed register
		register();
	
	} 
	else {	 
	//If they are not logged in
	?> 
<script type="text/javascript">
	
	$('#login').click(function(e) {
		e.preventDefault();
		$(this).addClass('active');
		$('#signup').removeClass('active');
		$('#credentials').slideDown();
		$('#new_user').hide();
	});
	
	$('#signup').click(function(e) {
		e.preventDefault();
		$(this).addClass('active');
		$('#login').removeClass('active');
		$('#new_user').slideDown();
		$('#credentials').hide();
	});
	
</script>


	<img src="public/images/logo.png"/><br/>
	
	<div id="rollover_imgs">
		<div id="login" class="rollover clickable"></div>
		<div id="signup" class="rollover clickable"></div>
	</div>
	<br/>
	

	<div id="welcomeForm">
	
		<div id="credentials" style="display:none">
			<br/>
			<form id="signinForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 
			<table>
				<tr>
					<td>Email:</td>
					<td><input id="username"  type="text" name="username" maxlength="25" class="required"/><br/><br/></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input id="password" type="password" name="pass" maxlength="100" class="required"/></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="login" value="Login"/></td>
				</tr>
			</table>
			</form>
		</div>
	
		<div id="new_user" style="display:none">
			<br/>
			<form id="registerForm" action="<?php echo $_SERVER['PHP_SELF']?>" method="post"> 
			<table>
				<tr>
					<td>Email (required):</td>
					<td><input id="username"  type="text" name="username" maxlength="25" class="required email"/><br/><br/></td>
				</tr>
				<tr>
					<td>First Name:</td>
					<td><input id="firstName"  type="text" name="firstName" maxlength="20" class="required"/><br/><br/></td>
				</tr>
				<tr>
					<td>Last Name:</td>
					<td><input id="lastName" type="text" name="lastName" maxlength="20" class="required"/></td>
				</tr>
				<tr>
					<td>Street:</td>
					<td><input id="street"  type="text" name="street" maxlength="25" /><br/><br/></td>
				</tr>
				<tr>
					<td>City:</td>
					<td><input id="city" type="text" name="city" maxlength="25" /></td>
				</tr>
				<tr>
					<td>State:</td>
					<td><input id="state"  type="text" name="state" maxlength="2"/><br/><br/></td>
				</tr>
				<tr>
					<td>Zip:</td>
					<td><input id="zip" type="text" name="zip" maxlength="5" /></td>
				</tr>
				<tr>
					<td>Phone:</td>
					<td><input id="phone"  type="text" name="phone" maxlength="25"/><br/><br/></td>
				</tr>
				<tr>
					<td>Interests:</td>
					<td><input id="interests" type="text" name="interests" maxlength="4000" /></td>
				</tr>
				<tr>
					<td>Activities:</td>
					<table align="left">
					<?php $activities = mysql_query("select * from ACTIVITIES");
						while($activities1 = mysql_fetch_array($activities)){
							echo "<tr><input id='activities'  type='checkbox' name='activities' value='".
							$activities1['ACTIVITY_NAME']."' />".$activities1['ACTIVITY_NAME']."</tr></br>";
						}?>
					</table>
				</tr>
				<tr>
					<td>Password (required):</td>
					<td><input id="password" type="password" name="password" maxlength="100" class="required" minlength="6"/></td>
				</tr>
				<tr>
					<td>Confirm Password (required):</td>
					<td><input id="confirm" type="password" name="confirm" maxlength="100" equalTo="#password"/></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="register" value="Get ConnActed!"/></td>
				</tr>
			</table>
			</form>
		</div>
		</div> <!-- /welcome_form-->
	
 <?php 
 } 
 ?>
