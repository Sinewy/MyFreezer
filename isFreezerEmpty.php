<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Is this freezer empty?";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_POST["freezerId"])) {

	$freezerId = $_POST["freezerId"];
	$result = findAllDrawersForFreezer($freezerId);
	if(mysqli_num_rows($result) > 0) {
		echo "notEmpty";
	} else {
		echo "empty";
	}
}

?>