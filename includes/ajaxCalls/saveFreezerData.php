<?php require_once("../session.php"); ?>
<?php require_once("../dbc.php"); ?>
<?php require_once("../globalFunctions.php"); ?>
<?php require_once("../formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Saving Freezer Data";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["saveData"])) {

	$freezerId = $_POST["freezerId"];
	$freezerName = $_POST["freezerName"];
	$freezerDescription = $_POST["freezerDescription"];
	$freezerLocation = $_POST["freezerLocation"];
	$freezerMake = $_POST["freezerMake"];

	if(strstr($freezerId, "noId")) {
		//insert new freezer and return new freezerId, content comes later
		insertNewFreezerDataForUserId($userId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake);
	} else {
		//update drawer data only, content comes later
		updateFreezerDataForFreezerIdAndUserId($userId, $freezerId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake);
	}
	echo "saveSuccessful";
}

?>