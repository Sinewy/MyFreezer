<?php require_once("dbc.php"); ?>
<?php
	$message = "";

	if(isset($_GET['email']) && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $_GET['email'])) {
		$email = $_GET['email'];
	}
	if(isset($_GET['key']) && (strlen($_GET['key']) == 32)) { //The Activation key will always be 32 since it is MD5 Hash
		$key = $_GET['key'];
	}

	if(isset($email) && isset($key)) {

		// Update the database to set the "activation" field to null
		$query = "UPDATE user SET Activation=1 WHERE(Email ='{$email}' AND Activation='{$key}')LIMIT 1";
		$result = mysqli_query($dbc, $query);

		// Print a customized message:
		if (mysqli_affected_rows($dbc) == 1) {
			$message = "<div class=\"success\">Your account is now active. You may now <a href=\"index.php\">Log in</a></div>";
		} else {
			$message = "<div class=\"errormsgbox\">Oops !Your account could not be activated. Please recheck the link or contact the system administrator.</div>";
		}
		mysqli_close($dbc);
	} else {
		$message = "<div class=\"errormsgbox\">Error Occured .</div>";
	}

?>

<!doctype html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>What's in My Freezer(s)? - Activate account</title>
	<link rel="stylesheet" href="css/mainStyles.css" type="text/css" charset="utf-8" />
</head>
<body>
	<div class="main">
        <?php echo $message; ?>
	</div>
</body>
</html>