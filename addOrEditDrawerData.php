<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

$pageTitle = "Add or Edit Drawer Data";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

//$_POST["drawerID"] = "noId";
$_POST["drawerID"] = 2;

$arrayOfRows = [];
$deleteContent = [];

$contentOutput = "";

if(isset($_POST["addOrEditData"])) {
	echo("you came from freezer detail.");
	if(isset($_POST["drawerID"]) && $_POST["drawerID"] == "noId") {
		echo("adding new drawer");
	} elseif(isset($_POST["drawerID"]) && $_POST["drawerID"] != "noId") {
		$drawerId = $_POST["drawerID"];
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
				<input type="button" id="addNewContentBtn" name="addNewContent2" value="+" /><span>Add new Content line</span>
			</div>
			<hr />
			<div class="submitOrCancel">
				<input type="hidden" name="submittedSaveData" value="true" />
				<input id="saveEditDrawerDataForm" type="button" name="save" value="Save" />
				<input type="button" name="cancelAndClose" value="Cancel" />
			</div>
		</fieldset>
	</form>
</div>


<!---->
<!--<form action="addOrEditDrawerData.php" method="POST">-->
<!--	<fieldset>-->
<!--		<legend>Add/Modify Drawer Data</legend>-->
<!---->
<!--		<p>Add/Modify Drawer - enter data</p>-->
<!---->
<!--		<div>-->
<!--			<label for="freezerName">Drawer Name:</label>-->
<!--			<input type="text" id="freezerName" name="freezerName" size="25" value="--><?php //echo htmlspecialchars($freezerName); ?><!--" />-->
<!--		</div>-->
<!--		<div>-->
<!--			<label for="freezerDescription">Description :</label>-->
<!--			<input type="text" id="freezerDescription" name="freezerDescription" size="25" value="--><?php //echo htmlspecialchars($freezerDescription); ?><!--" />-->
<!--		</div>-->
<!--		-->
<!--		<div>-->
<!--			<label for="freezerLocation">Location :</label>-->
<!--			<input type="text" id="freezerLocation" name="freezerLocation" size="25" value="--><?php //echo htmlspecialchars($freezerLocation); ?><!--" />-->
<!--		</div>-->
<!--		<div>-->
<!--			<label for="freezerMake">Make :</label>-->
<!--			<input type="text" id="freezerMake" name="freezerMake" size="25" value="--><?php //echo htmlspecialchars($freezerMake); ?><!--" />-->
<!--		</div>-->
<!--		<div>-->
<!--			<input type="hidden" name="submitted" value="TRUE" />-->
<!--			<input type="submit" value="Save" />-->
<!--		</div>-->
<!---->
<!--	</fieldset>-->
<!--</form>-->

<div>
	<pre>
	<?php
		print_r($_POST);
		print_r($_SESSION);
	?>
	</pre>
</div>


<?php include("includes/footer.php"); ?>
