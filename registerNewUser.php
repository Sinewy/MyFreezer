<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>

<?php
	$pageTitle = "Register New User";

	//$message = "";
	$registrationFormClass = "registrationForm";
	$activarionInfoClass = "activationInfoHidden";

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
		        $activarionInfoClass = "activationInfo";
			} else { // If it did not run OK.
		        $_SESSION["message"] = "You could not be registered due to a system error.<br /> We apologize for any inconvenience. <br/ >Please try again.";
            	$registrationFormClass = "registrationFormHidden";
            	$activarionInfoClass = "activationInfo";
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

<?php include("includes/header.php"); ?>
			
			<?php echo formErrors($errors); ?>

			<form action="registerNewUser.php" method="POST" class="<?php echo $registrationFormClass; ?>">
			  <fieldset>
			    <legend>Registration Form</legend>

			    <p>Create A new Account</p>

			    <div>
			    	<label for="email">Email Address:</label>
			    	<input type="text" id="email" name="email" size="25" value="<?php echo htmlspecialchars($email); ?>" />
			    </div>
			    <div>
			    	<label for="username">Username :</label>
			    	<input type="text" id="username" name="username" size="25" value="<?php echo htmlspecialchars($username); ?>" />
			    </div>
			    <div>
			    	<label for="password">Password:</label>
			    	<input type="password" id="password" name="password" size="25" value="" />
			    </div>
			    <div>
			    	<label for="firstName">First Name :</label>
			    	<input type="text" id="firstName" name="firstName" size="25" value="<?php echo htmlspecialchars($firstName); ?>" />
			    </div>
			    <div>
			    	<label for="lastName">Last Name :</label>
			    	<input type="text" id="lastName" name="lastName" size="25" value="<?php echo htmlspecialchars($lastName); ?>" />
			    </div>
			    <div>
			    	<label for="gender">Gender: </label>
			    	<select id="gender" name="gender">
			    		<?php echo getRegistrationDropdownOptionsFor("gender", "GenderType", $gender); ?>
			    	</select>
			    </div>
			    <div>
			    	<label for="ageGroup">Age Group: </label>
			    	<select id="ageGroup" name="ageGroup">
			    		<?php echo getRegistrationDropdownOptionsFor("age", "AgeGroup", $ageGroup); ?>
			    	</select>
			    </div>
			    <div>
			    	<label for="country">Country: </label>
			    	<select id="country" name="country">
			    		<?php echo getRegistrationDropdownOptionsFor("country", "Country", $country); ?>
			    	</select>
			    </div>
			    <div>
			       	<input type="hidden" name="submitted" value="TRUE" />
			    	<input type="submit" value="Register" />
			    </div>
			  </fieldset>
			</form>

			<div>
				<pre>
					<?php

						print_r($_POST);

					?>
				</pre>
			</div>

			<div class="<?php echo $activarionInfoClass; ?>">
				<?php echo displayMessage(); ?>
				<a href="index.php"><button>Go to Login page</button></a>
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