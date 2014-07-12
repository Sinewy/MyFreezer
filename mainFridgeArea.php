<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>What's in My Fridge? - Main Area</title>
	</head>
	<body>
		<div class="main">
			This is myFridge Site.
			<br />
			wLCome user: <?php echo $_SESSION["Username"] ?>
			<br /><br />

			The best fridge tracker ever.
			<br /><br />
			<a href="logout.php"><button>LogOut</button></a>
			
		</div>
	</body>
</html>
