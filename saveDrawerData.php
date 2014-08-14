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
		$drawerId = insertNewDrawerData(7, $drawerName, $drawerDescription);
	} else {
		//update drawer data only, content comes later
		updateDrawerDataForId($drawerId, $drawerName, $drawerDescription);
	}
	if($contentDelete != null) {
		// delete the required content
		foreach ($contentDelete as $id) {
			deleteContentById($id);
		}
	}

	foreach($contentData as $dataObj) {
		if(strstr($dataObj["id"], "noId")) {
			// we have new content, insert it
			insertNewContentRowForDrawerId($drawerId, $dataObj["description"], $dataObj["amount"], $dataObj["qty"], $dataObj["date"]);
		} else {
			// update existing content data
			updateContentRowById($dataObj["id"], $dataObj["description"], $dataObj["amount"], $dataObj["qty"], $dataObj["date"]);
		}
//		var rowObj = new Object();
//                rowObj.id = id;
//                rowObj.description = $("#contentDescription" + id).val();
//                rowObj.amount = $("#contentAmount" + id).val();
//                rowObj.qty = $("#contentQuantity" + id).val();
//                rowObj.date = $("#contentDate" + id).val();
//                contentRowData.push(rowObj);
	}



//	if(strstr($drawerId, "noId")) {
//		echo "hellooo it is treueee";
//	} else {
//		echo "falsic";
//	}
	echo "saveSuccessful";
}


?>