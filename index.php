<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedInOnIndex(); ?>

<?php
	$pageTitle = "Login Page";

	if(isset($_POST["login"])) {

		$username = trim($_POST["username"]);
		$password = trim($_POST["password"]);

		if(!hasPresence($username) || !hasPresence($password)) {
			$errors["authenticUser"] = "Both username and password are required.";
		}

		if(empty($errors)) {
			$userFound = attemptLogin($username, $password);
		    if ($userFound) {
		    	$_SESSION["UserID"] = $userFound["UserID"];
				$_SESSION["Username"] = $userFound["Username"];
        		redirectTo("mainDashboard.php");
    		} else {
      			// Failure
      			$_SESSION["message"] = "Username/password not found.";
      			//$errors["authenticUser"] = "Username and password do not match our records.<br />";
				//$errors["authenticUser"] .= "Please check your username and password and try again.<br />";
				//$errors["authenticUser"] .= "If you are a new user, you can register <a href=\"registerNewUser.php\">HERE</a>.";
    		}
  		}


		// 	// TODO
		// 	// add activation check - if activation = 1 OK, otherwise activate account.
		// 	$query = "SELECT * FROM user  WHERE Username ='$username' AND Password = '$password' LIMIT 1";
  //      	 	$result = mysqli_query($dbc, $query);
  //       	if (!$result) {
  //           	echo "Database Error Occured";
  //       	}
  //       	$authenticUser = mysqli_num_rows($result);
  //       	if ($authenticUser == 1) {
  //       		$_SESSION["UserId"] = "123456";
		// 		$_SESSION["Username"] = $username;
  //       		redirectTo("mainFridgeArea.php");
		// 	} else {
		// 		$errors["authenticUser"] = "Username and password do not match our records.<br />";
		// 		$errors["authenticUser"] .= "Please check your username and password and try again.<br />";
		// 		$errors["authenticUser"] .= "If you are a new user, you can register <a href=\"registerNewUser.php\">HERE</a>.";
		// 	}
		// 	mysqli_free_result($result);
		// }
	} else {
		$username = "";
	}

?>

<?php include("includes/header.php"); ?>
			
			<?php echo displayMessage(); ?>
			<?php echo formErrors($errors); ?>

			<form action="index.php" method="POST" class="loginForm">
			  <fieldset>
			    <legend>Please LogIn</legend>

			    <div>
			    	<label for="username">Username :</label>
			    	<input type="text" id="username" name="username" size="25" value="<?php echo htmlspecialchars($username); ?>" />
			    </div>
			    <div>
			    	<label for="password">Password:</label>
			    	<input type="password" id="password" name="password" size="25" value="" />
			    </div>
			    <div>
			       	<input type="hidden" name="login" value="TRUE" />
			    	<input type="submit" value="Login" />
			    </div>

			    <p>New user? Register <a href="registerNewUser.php">HERE.</a></p>

			  </fieldset>
			</form>

			<div>
				<pre>
					<?php

						//print_r($_POST);
						print_r($_SESSION);

					?>
				</pre>
			</div>

			<?php 

				$q = "SELECT * FROM user";
	        	$result = mysqli_query($dbc, $q);
	        	if (!$result) {
					die("Database query failed.");
				}
				while($users = mysqli_fetch_assoc($result)) {
					echo "<div>" . $users["Username"]. "</div>";
				}
				mysqli_free_result($result);


				
			?>

<?php include("includes/footer.php"); ?>
