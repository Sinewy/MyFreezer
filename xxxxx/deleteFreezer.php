<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

	if(isset($_POST["btnPressed"])) {

		$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
		$freezerId = isset($_POST["FreezerID"]) ? $_POST["FreezerID"] : null;
		$freezerIdSafe = mysqli_real_escape_string($dbc, $freezerId);

		$query  = "DELETE FROM freezer ";
		$query .= "WHERE UserID = {$userId} ";
		$query .= "AND FreezerID = {$freezerIdSafe} ";
		$query .= "LIMIT 1";
		$result = mysqli_query($dbc, $query);

		if($result && mysqli_affected_rows($dbc) == 1) {
			// Success
			$_SESSION["message"] = "Freezer deleted.";
			redirectTo("mainDashboard.php");
		} else {
			// Failure
			$_SESSION["message"] = "Freezer deletion failed.";
			redirectTo("mainDashboard.php");
		}
	} else {
		redirectTo("mainDashboard.php");
	}

?>
