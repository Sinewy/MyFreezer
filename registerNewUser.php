<?php require_once("includes/session.php"); ?>
<?php require_once("includes/dbc.php"); ?>
<?php require_once("includes/globalFunctions.php"); ?>
<?php require_once("includes/formValidationFunctions.php"); ?>

<?php
	$pageTitle = "Register New User";

	//$message = "";
	$registrationFormClass = "registrationForm";
	$activationInfoClass = "activationInfoHidden";

	if(isset($_POST["submitted"])) {

		// form was submitted
		$username = makeSqlSafe(trim($_POST["username"]));
		$email = makeSqlSafe(trim($_POST["email"]));
		$firstName = makeSqlSafe(trim($_POST["firstName"]));
		$lastName = makeSqlSafe(trim($_POST["lastName"]));

		$password = trim($_POST["password"]);

		$gender = $_POST["gender"];
		$ageGroup = $_POST["ageGroup"];
		$country = $_POST["country"];

		$requiredField = array("username", "email", "password");
		$fieldMaxLengths = array("username" => 30, "email" => 60, "password" => 30);
		$fieldMinLengths = array("username" => 4, "email" => 5, "password" => 4);

		foreach ($requiredField as $field) {
			$value = trim($_POST[$field]);
			if(hasPresence($value)) {
				if(!hasMaxLength($value, $fieldMaxLengths[$field])) {
					$errors[$field] = ucfirst($field) . " is too long.";			
				}
				if(!hasMinLength($value, $fieldMinLengths[$field])) {
					$errors[$field] = ucfirst($field) . " is too short.";		
				}
				if($field == "email") {
					if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)) {
           				$errors[$field] = "Your email address is invalid.";
           			}
				}
			} else {
				$errors[$field] = ucfirst($field) . " can't be blank";
			}
		}

		$queryEmail = "SELECT * FROM user  WHERE Email ='$email'";
        $resultEmail = mysqli_query($dbc, $queryEmail);
        confirmQuery($resultEmail);
        $uniqueEmail = mysqli_num_rows($resultEmail);
        if($uniqueEmail != 0) {
        	$errors["email"] = "This email is already registered. Please use a different email address.";
        }
    	mysqli_free_result($resultEmail);

        $queryUsername = "SELECT * FROM user  WHERE Username ='$username'";
        $resultUsername = mysqli_query($dbc, $queryUsername);
        confirmQuery($resultUsername);
        $uniqueUsername = mysqli_num_rows($resultUsername);
        if ($uniqueUsername != 0) {
        	$errors["username"] = "This username is already registered. Please use a different username.";//
    	}
    	mysqli_free_result($resultUsername);
		if(empty($errors)) {
			// Create a unique  activation code:
            $activation = md5(uniqid(rand(), true));
            $hashedPassword = passwordEncrypt($password);
            $queryInsertUser = "INSERT INTO `user` (`Email`, `Username`, `Password`, `FirstName`,  `LastName`, `Gender`, `AgeGroup`, `Country`, `Activation`) ";
			$queryInsertUser .= "VALUES ('$email', '$username', '$hashedPassword', '$firstName', '$lastName', '$gender', '$ageGroup', '$country', '$activation')";
	        $resultInsertUser = mysqli_query($dbc, $queryInsertUser);
            confirmQuery($resultInsertUser);

        	if (mysqli_affected_rows($dbc) == 1) { //If the Insert Query was successfull.
                // Send the email:
                //$message = " To activate your account, please click on this link:\n\n";
                //$message .= WEBSITE_URL . '/activate.php?email=' . urlencode($email) . "&key=$activation";
                //mail($email, 'Registration Confirmation', $message, 'From: ismaakeel@gmail.com');
                // Flush the buffered output.

                // Finish the page:
                //$_SESSION["message"] = "Thank you for registering!<br /> A confirmation email has been sent to " . $email . ".<br />Please click on the activation link in email you received to activate your account.";
		        $_SESSION["message"] = "Thank you for registering!<br /> A confirmation email has been sent to ";
		        $_SESSION["message"] .= $email;
		        $_SESSION["message"] .= ".<br />Please click on the activation link in email you received to activate your account.";
		        $_SESSION["message"] .= ".<br /><br />";
		        $_SESSION["message"] .= "Link sent in the mail: ";
		        $_SESSION["message"] .= "<a href=\"activate.php?email=" . urlencode($email) . "&key={$activation}\">activate here.</a>";
		        $registrationFormClass = "registrationFormHidden";
		        $activationInfoClass = "activationInfo";
			} else { // If it did not run OK.
		        $_SESSION["message"] = "You could not be registered due to a system error.<br /> We apologize for any inconvenience. <br/ >Please try again later.";
            	$registrationFormClass = "registrationFormHidden";
            	$activationInfoClass = "activationInfo";
       		}
       		//mysqli_free_result($resultInsertUser);
		}

	} else {
		$username = "";
		$email = "";
		$firstName = "";
		$lastName = "";
		$gender = "";
		$ageGroup = "";
		$country = "";
	}


?>

<?php include("includes/head.php"); ?>
<!--	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />-->
<!--	<script src="js/jquery.colorbox.js"></script>-->
<!--	<script src="js/jquery-ui.js"></script>-->
<!--	<script src="js/mainDashboard.js"></script>-->
<?php include("includes/header.php"); ?>


<!--<section class="registrationFormSection">-->

	<form action="registerNewUser.php" method="POST" class="<?php echo $registrationFormClass; ?>"
		  xmlns="http://www.w3.org/1999/html">
			  <fieldset>
			    <legend>Registration Form / Create New Account</legend>

			    <div>
			    	<input type="text" id="email" name="email" size="25" value="<?php echo htmlspecialchars($email); ?>" />
			    	<label for="email">Email Address:</label>
			    </div>
			    <div>
			    	<input type="text" id="username" name="username" size="25" value="<?php echo htmlspecialchars($username); ?>" />
			    	<label for="username">Username:</label>
			    </div>
			    <div>
			    	<input type="password" id="password" name="password" size="25" value="" />
			    	<label for="password">Password:</label>
			    </div>
			    <div>
			    	<input type="text" id="firstName" name="firstName" size="25" value="<?php echo htmlspecialchars($firstName); ?>" />
			    	<label for="firstName">First Name:</label>
			    </div>
			    <div>
			    	<input type="text" id="lastName" name="lastName" size="25" value="<?php echo htmlspecialchars($lastName); ?>" />
			    	<label for="lastName">Last Name:</label>
			    </div>
			    <div>
			    	<select id="gender" name="gender">
			    		<?php echo getRegistrationDropdownOptionsFor("gender", "GenderType", $gender); ?>
			    	</select>
			    	<label for="gender">Gender:</label>
			    </div>
			    <div>
			    	<select id="ageGroup" name="ageGroup">
			    		<?php echo getRegistrationDropdownOptionsFor("age", "AgeGroup", $ageGroup); ?>
			    	</select>
			    	<label for="ageGroup">Age Group:</label>
			    </div>
			    <div>
			    	<select id="country" name="country">
			    		<?php echo getRegistrationDropdownOptionsFor("country", "Country", $country); ?>
			    	</select>
			    	<label for="country">Country:</label>
			    </div>
			    <div class="buttons">
			       	<input type="hidden" name="submitted" value="TRUE" />
					<a href="index.php"><div class="button">Cancel / Login page</div></a>
			    	<input class="button" type="submit" value="Register" />
			    </div>
			  </fieldset>
			</form>

<?php echo formErrors($errors); ?>

	<div class="<?php echo $activationInfoClass; ?>">
		<?php echo displayMessage(); ?>
		<a href="index.php"><div class="button">Back to Login page</div></a>
	</div>

<!--</section>-->


<!--			<div>-->
<!--				<pre>-->
<!--					--><?php
//
//						print_r($_POST);
//
//					?>
<!--				</pre>-->
<!--			</div>-->

<!--			<div class="--><?php //echo $activarionInfoClass; ?><!--">-->
<!--				--><?php //echo displayMessage(); ?>
<!--				<a href="index.php"><button>Go to Login page</button></a>-->
<!--			</div>-->
<!--			-->
<!--			--><?php //
//
//				$q = "SELECT * FROM user";
//	        	$result = mysqli_query($dbc, $q);
//	        	if (!$result) {
//					die("Database query failed.");
//				}
//				while($users = mysqli_fetch_assoc($result)) {
//					echo "<div>" . $users["Username"]. "</div>";
//				}
//				mysqli_free_result($result);
//
//
//
//			?>

<?php include("includes/footerLogin.php"); ?>