<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Updating Drawer View";
//$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["drawerId"])) {
	$dId = $_POST["drawerId"];
//	$drawerData = createDrawer($dId);
//	$drawerData = findDrawerByDrawerId($dId);
//	$returnData["drawerInfo"] = createDrawerInfo($dId, $drawerData["Name"], $drawerData["Description"]);
//	$returnData["content"] = createDrawerContentView($dId);
	$returnData["drawer"] = createDrawer($dId);
	echo json_encode($returnData);
}