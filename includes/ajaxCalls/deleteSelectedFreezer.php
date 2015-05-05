<?php require_once("../session.php"); ?>
<?php require_once("../dbc.php"); ?>
<?php require_once("../globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Deleting Freezer";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_POST["freezerId"])) {

	$freezerId = $_POST["freezerId"];
	$result = deleteFreezerById($freezerId);
	echo "deleteSuccessful";
}

?>