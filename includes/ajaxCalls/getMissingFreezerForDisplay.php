<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Getting missing freezer For Display";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
//$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["existingFreezers"])) {
	$displayedFreezers = $_POST["existingFreezers"];
	$dbFreezers = [];
	$freezerData = findAllFreezersForUserId($userId);
	if(mysqli_num_rows($freezerData) > 0) {
		while($freezer = mysqli_fetch_assoc($freezerData)) {
			$dbFreezers[] = $freezer["FreezerID"];
		}
	}

	$result = array_diff($dbFreezers, $displayedFreezers);
	$theOne = "";
	foreach($result as $val) {
		$theOne = $val;
	}

	echo json_encode($theOne);
} else {
	$freezerData = findAllFreezersForUserIdFirstOne($userId);
	echo json_encode($freezerData["FreezerID"]);
}