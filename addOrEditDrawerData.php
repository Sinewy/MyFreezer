<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

$pageTitle = "Add or Edit Drawer Data";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

//$_POST["addOrEditData"] = "true";
//$_POST["drawerID"] = "noId";
//$_POST["drawerID"] = 2;

$arrayOfRows = [];
$deleteContent = [];
$contentOutput = "";

if(isset($_GET["addOrEditData"])) {

	echo("you came from freezer detail.");

	if(isset($_GET["drawerID"]) && $_GET["drawerID"] == "noId") {


		$drawerName = "";
		$drawerDescription = "";
		$contentOutput .= "<p id='emptyDrawer'>This drawer is still empty. Add some content.</p>";

		//echo("adding new drawer");


	} elseif(isset($_GET["drawerID"]) && $_GET["drawerID"] != "noId") {
		$drawerId = $_GET["drawerID"];
		// get data for drawer
		$drawerData = findDrawerByDrawerId($drawerId);
		$drawerName = $drawerData["Name"];
		$drawerDescription = $drawerData["Description"];

		// get data for all content
		//$allContent = findAllContentForDrawer($drawerId);
		//echo $allContent[0]["Description"];
		$contentOutput = createEditDrawerContentView($drawerId);

		echo("just editing drawer data");
	} else {
		echo("this is a strange case");
	}
} elseif(isset($_POST["submittedSaveData"])) {

	echo("You just saved the data.");

} else {
	// TODO - throw him back
	//redirectTo("freezerDetail.php");
	echo("You tried to manipulate in a strange way.. MF");
}


//
//if(isset($_POST["addOrEditData"])) {
//
//	echo("you came from freezer detail.");
//
//	if(isset($_POST["drawerID"]) && $_POST["drawerID"] == "noId") {
//
//
//		$drawerName = "";
//		$drawerDescription = "";
//		$contentOutput .= "<p id='emptyDrawer'>This drawer is still empty. Add some content.</p>";
//
//		//echo("adding new drawer");
//
//
//	} elseif(isset($_POST["drawerID"]) && $_POST["drawerID"] != "noId") {
//		$drawerId = $_POST["drawerID"];
//		// get data for drawer
//		$drawerData = findDrawerByDrawerId($drawerId);
//		$drawerName = $drawerData["Name"];
//		$drawerDescription = $drawerData["Description"];
//
//		// get data for all content
//		//$allContent = findAllContentForDrawer($drawerId);
//		//echo $allContent[0]["Description"];
//		$contentOutput = createEditDrawerContentView($drawerId);
//
//		echo("just editing drawer data");
//	} else {
//		echo("this is a strange case");
//	}
//} elseif(isset($_POST["submittedSaveData"])) {
//	echo("You just saved the data.");
//} else {
//	// TODO - throw him back
//	//redirectTo("freezerDetail.php");
//	echo("You tried to manipulate in a strange way.. MF");
//}

?>


<?php include("includes/header.php"); ?>

<?php echo formErrors($errors); ?>
<?php echo displayMessage(); ?>

<div id="addEditDrawer">
	<form id="addEditDrawerForm" action="addOrEditDrawerData.php" method="POST">
		<fieldset>
			<div class="drawerDetails">
				<p>Add or Edit Drawer data</p>
				<label for="drawerName">Drawer Name:</label>
				<input type="text" id="drawerName" name="drawerName" size="45" placeholder="enter drawer name" value="<?php echo htmlspecialchars($drawerName); ?>" /><br />
				<label for="drawerDescription">Drawer Description:</label>
				<input type="text" id="drawerDescription" name="drawerDescription" size="45" placeholder="enter drawer description" value="<?php echo htmlspecialchars($drawerDescription); ?>" />
			</div>
			<hr />
			<div id="drawerContent">
				<p>Content</p>
				<p>Name/Description | Amount | Quantity | Date</p>
				<?php  echo $contentOutput; ?>
			</div>
			<hr />
			<div class="addNewContentLine">
				<input id="addNewContentBtn" type="button" name="addNewContent" value="+" /><span>Add new Content line</span>
			</div>
			<hr />
			<div id="submitOrCancel">
				<input type="hidden" name="submittedSaveData" value="true" />
				<input id="saveEditDrawerDataForm" type="button" name="save" value="Save" />
				<input id="cancelAndCloseForm" type="button" name="cancelAndClose" value="Cancel" />
			</div>
		</fieldset>
	</form>
</div>

<div>
	<pre>
	<?php
		print_r($_POST);
		print_r($_SESSION);
	?>
	</pre>
</div>


<?php include("includes/footer.php"); ?>
