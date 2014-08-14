<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Getting missing drawer For Display";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["drawerId"])) {
	$dId = $_POST["drawerId"];
	$drawerData = createDrawer($dId);
	echo $drawerData;
}