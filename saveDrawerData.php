<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Saving Drawer Data";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

$output = "";

if(isset($_POST["saveData"])) {

	$drawerId = $_POST["drawerId"];
	$drawerName = $_POST["drawerName"];
	$drawerDescription = $_POST["drawerDescription"];
	$contentData =  isset($_POST["contentData"]) ? $_POST["contentData"] : null ;
	$contentDelete =  isset($_POST["contentDelete"]) ? $_POST["contentDelete"]: null;

	if(strstr($drawerId, "noId")) {
		//insert new drawer and return new drawerId, content comes later
		//$drawerId = insertNewDrawerData($freezerId, $drawerName, $drawerDescription);
	} else {
		//update drawer data only, content comes later
		//updateDrawerData($drawerId, $drawerName, $drawerDescription);
	}
	if($contentDelete != null) {
		foreach ($contentDelete as $id) {
			$output .= $id . "<br/> ";
			$output = deleteContentById($id);
		}
	}




//	if(strstr($drawerId, "noId")) {
//		echo "hellooo it is treueee";
//	} else {
//		echo "falsic";
//	}
	echo "saveSuccessful";
}


?>