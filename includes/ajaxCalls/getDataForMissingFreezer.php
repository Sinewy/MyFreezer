<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Getting missing freezer For Display";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["freezerId"])) {
	$fId = $_POST["freezerId"];
//	$freezerData = createFreezer($fId, $userId);
	$freezerData = createFreezerData($fId, $userId);
	echo $freezerData;
}