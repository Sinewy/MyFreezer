<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Deleting Drawer";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_POST["drawerId"])) {

	$drawerId = $_POST["drawerId"];
	$result = deleteDrawerById($drawerId);
	echo "deleteSuccessful";
}

?>