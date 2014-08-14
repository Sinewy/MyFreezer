<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Manage Ffeezer drawers and its content";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_GET["fid"])) {
	$freezerId = makeSqlSafe($_GET["fid"]);
	$freezerData = findFreezerByIdAndUserId($freezerId, $userId);
	//TODO - check if not null than do this
	$freezerName = $freezerData["Name"];
	$freezerDescription = $freezerData["Description"];
	$freezerLocation = $freezerData["Location"];
	$freezerMake = $freezerData["Make"];

	$drawersView = createFreezerDrawerView($freezerId);

}

?>

<?php include("includes/header.php"); ?>
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />
	<script src="js/jquery.colorbox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/freezerDetail.js"></script>
<?php include("includes/header2.php"); ?>

<?php echo formErrors($errors); ?>
<?php echo displayMessage(); ?>

<h1 xmlns="http://www.w3.org/1999/html"><?php echo $freezerName; ?></h1>
<h2><?php echo $freezerDescription; ?></h2>
<h3><?php echo $freezerLocation; ?></h3>
<h3><?php echo $freezerMake; ?></h3>

<?php echo $drawersView; ?>

<div id="addNewDrawerBtn">
<!--	<button>Add New Drawer</button>-->
	<input type="button" name="addNewDrawerBtn" value="Add New Drawer" />
</div>


<!---->
<!---->
<!--	<div id="addEditDrawer" class="hiddenElement">-->
<!--		<form action="freezerDetail.php" method="POST">-->
<!--			<fieldset>-->
<!--				<div class="drawerDetails">-->
<!--					<p>Add or Edit Drawer data</p>-->
<!--					<label for="drawerName">Drawer Name:</label>-->
<!--					<input type="text" id="drawerName" name="drawerName" size="45" placeholder="enter drawer name" />-->
<!--					<label for="drawerDescription">Drawer Description:</label>-->
<!--					<input type="text" id="drawerDescription" name="drawerDescription" size="45" placeholder="enter drawer description" />-->
<!--				</div>-->
<!--				<div id="drawerContent">-->
<!--					<p>Content</p>-->
<!--					<p>Name/Description | Amount | Quantity | Date</p>-->
<!--				</div>-->
<!--				<div class="addNewContentLine">-->
<!--					<input type="button" id="addNewContentBtn" name="addNewContent2" value="+" /><span>Add new Content line</span>-->
<!--				</div>-->
<!--				<div class="submitOrCancel">-->
<!--					<!--					<button type="submit" name="submutDrawerData">Save</button>-->
<!--					<input type="button" id="saveDrawerData" name="saveDrawerData" value="Save" />-->
<!--					<button id="cancelDrawerDataBtn" type="button" name="cancelAndClose">Cancel</button>-->
<!--				</div>-->
<!--			</fieldset>-->
<!--		</form>-->
<!---->
<!--	</div>-->
<!---->
<!--<!---->
<!--	<form action="addOrEditDrawerData.php" method="POST">-->
<!--		<fieldset>-->
<!--			<input type="hidden" name="addOrEditData" value="true" />-->
<!--			<input type="submit" value="Add/Edit data" />-->
<!--		</fieldset>-->
<!--	</form>-->
<!---->
<!--	<div>-->
<!--		<pre>-->
<!--			--><?php
//			print_r($_GET);
//			print_r($_SESSION);
//			print_r($_POST);
//			?>
<!--		</pre>-->
<!--	</div>-->

<!---->
<!--	<h2 class="demoHeaders">Dialog</h2>-->
<!--	<p><a href="#" id="dialog-link" class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-newwin"></span>Open Dialog</a></p>-->

	<!-- ui-dialog -->
	<div id="deleteDrawerDialog" title="Warning!">
		<p>Drawer you are trying to delete still contains some content.<br />
		Are you sure you want to delete this drawer and all of its containing content?</p>
	</div>


<?php
//	echo "*********************";
//	findDrawerByDrawerId(1);
//?>

<?php include("includes/footer.php"); ?>