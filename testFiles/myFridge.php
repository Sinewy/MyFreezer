<?php
	
	require_once("connection.php");

	if (isset($_POST['formsubmitted'])) {
	    $error = array();//Declare An Array to store any error message  
	    if (empty($_POST['username'])) {//if no name has been supplied 
	        $error[] = 'Please enter desired username.';//add to array "error"
	    } else {
	        $username = $_POST['username'];//else assign it a variable
	    }

	    if (empty($_POST['email'])) {
	        $error[] = 'Please enter your email.';
	    } else {
			if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $_POST['email'])) {
           //regular expression for email validation
            $email = $_POST['email'];
        	} else {
             	$error[] = 'Your email address is invalid.';
        	}
		}

	    if (empty($_POST['password'])) {
	        $error[] = 'Please enter desired password.';
	    } else {
	        $password = $_POST['password'];
	    }


    	if (empty($error)) { //send to Database if there's no error '
			// If everything's OK...
			// Make sure the email address is available:
	        $query_verify_email = "SELECT * FROM user  WHERE Email ='$email'";
	        $result_verify_email = mysqli_query($dbc, $query_verify_email);
	        if (!$result_verify_email) {//if the Query Failed ,similar to if($result_verify_email==false)
	            echo ' Database Error Occured ';
	        }
	        $query_verify_username = "SELECT * FROM user  WHERE Username ='$username'";
	        $result_verify_username = mysqli_query($dbc, $query_verify_username);
	        if (!$result_verify_username) {//if the Query Failed ,similar to if($result_verify_email==false)
	            echo ' Database Error Occured ';
	        }
	        $uniqueEmail = mysqli_num_rows($result_verify_email);
	        $uniqueUsername = mysqli_num_rows($result_verify_username);
	        if ($uniqueEmail == 0 && $uniqueUsername == 0) { // IF no previous user is using this email or username
				// Create a unique  activation code:
	            $activation = md5(uniqid(rand(), true));
	            $query_insert_user = "INSERT INTO `user` ( `Username`, `Email`, `Password`, `Activation`) VALUES ( '$username', '$email', '$password', '$activation')";
		        $result_insert_user = mysqli_query($dbc, $query_insert_user);
	            if (!$result_insert_user) {
	                echo 'Query Failed ';
	            }

            	if (mysqli_affected_rows($dbc) == 1) { //If the Insert Query was successfull.
	                // Send the email:
	                $message = " To activate your account, please click on this link:\n\n";
	                $message .= WEBSITE_URL . '/activate.php?email=' . urlencode($email) . "&key=$activation";
	                mail($email, 'Registration Confirmation', $message, 'From: ismaakeel@gmail.com');
	                // Flush the buffered output.

	                // Finish the page:
	                echo '<div class="success">Thank you for registering! A confirmation email has been sent to '.$email.' Please click on the Activation Link to Activate your account </div>';
				} else { // If it did not run OK.
                	echo '<div class="errormsgbox">You could not be registered due to a system error. We apologize for any inconvenience.</div>';
           		}
        	} else if ($uniqueEmail != 0) { // The email address is not available.
            	echo '<div class="errormsgbox" >That email address has already been registered. </div>';
        	} else if ($uniqueUsername != 0) { // The email address is not available.
            	echo '<div class="errormsgbox" >That username has already been registered. </div>';
        	}
    	} else {//If the "error" array contains error msg , display them
			echo '<div class="errormsgbox"> <ol>';
        	foreach ($error as $key => $values) {
                echo '<li>' . $values . '</li>';
			}
        	echo '</ol></div>';
	    }
   		//mysqli_close($dbc);
	} // End of the main Submit conditional.

?>


<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Start page - What's in My Fridge?</title>
		<!-- <link rel="stylesheet" href="css/cssreset.css" type="text/css" charset="utf-8" /> -->
    	<!-- <link rel="stylesheet" href="css/main.css" type="text/css" charset="utf-8" /> -->
    	<!-- <script type="text/javascript" src="js/jquery-2.0.3.js" ></script>
    	<script type="text/javascript" src="js/mainScript.js"></script> -->
	</head>
	<body>
		<div class="main">
			This is myFridge Site.

			<br /><br />

			<form action="myFridge.php" method="post" class="registration_form">
			  <fieldset>
			    <legend>Registration Form </legend>

			    <p>Create A new Account <span style="background:#EAEAEA none repeat scroll 0 0;line-height:1;margin-left:210px;;padding:5px 7px;">
				Already a member? <a href="login.php">Log in</a></span> </p>

			    <div class="elements">
			    	<label for="username">Name :</label>
			    	<input type="text" id="username" name="username" size="25" />
			    </div>
			    <div class="elements">
			    	<label for="email">E-mail :</label>
			    	<input type="text" id="email" name="email" size="25" />
			    </div>
			    <div class="elements">
			    	<label for="password">Password:</label>
			    	<input type="password" id="password" name="password" size="25" />
			    </div>
			    <div class="submit">
			    	<input type="hidden" name="formsubmitted" value="TRUE" />
			    	<input type="submit" value="Register" />
			    </div>
			  </fieldset>
			</form>

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


				mysqli_close($dbc);
			?>	

		</div>
	</body>
</html>
