<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Updating Freezer View";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["freezerId"])) {
	$fId = $_POST["freezerId"];
	$freezerData = findFreezerByIdAndUserId($fId, $userId);
	$returnData["content"] = createFreezerData($freezerData["Name"], $freezerData["Description"], $freezerData["Location"], $freezerData["Make"]);
	echo json_encode($returnData);
}