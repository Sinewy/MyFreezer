<?php require_once("includes/session.php"); ?>
<?php require_once("includes/dbc.php"); ?>
<?php require_once("includes/globalFunctions.php"); ?>
<?php require_once("includes/formValidationFunctions.php"); ?>

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

<?php include("includes/head.php"); ?>
<?php include("includes/header.php"); ?>

<section class="loginSection">

	<form action="index.php" method="POST" class="loginForm">
		<fieldset>
			<legend>LOGIN</legend>
			<div>
				<input type="text" id="username" name="username" size="25" value="<?php echo htmlspecialchars($username); ?>" placeholder="username"/>
			</div>
			<div>
				<input type="password" id="password" name="password" size="25" value="" placeholder="password" />
			</div>
			<div>
				<input type="hidden" name="login" value="TRUE" />
				<input class="button" type="submit" value="SIGN IN" />
			</div>
			<p><a href="registerNewUser.php">New user? Register HERE.</a></p>
			<p><a href="#">Forgot your password?</a></p>
		</fieldset>
	</form>

	<?php echo displayMessage(); ?>
	<?php echo formErrors($errors); ?>

</section>

<?php include("includes/footerLogin.php"); ?>
