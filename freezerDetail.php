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

if(isset($_POST["submitted"])) {


} else if(isset($_POST["btnPressed"])) {
	//
} else {
//	$freezerName = "";
//	$freezerDescription = "";
//	$freezerLocation = "";
//	$freezerMake = "";
}

?>

<?php include("includes/header.php"); ?>
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




	<div id="addEditDrawer" class="hiddenElement">
		<form action="freezerDetail.php" method="POST">
			<fieldset>
				<div class="drawerDetails">
					<p>Add or Edit Drawer data</p>
					<label for="drawerName">Drawer Name:</label>
					<input type="text" id="drawerName" name="drawerName" size="45" placeholder="enter drawer name" />
					<label for="drawerDescription">Drawer Description:</label>
					<input type="text" id="drawerDescription" name="drawerDescription" size="45" placeholder="enter drawer description" />
				</div>
				<div id="drawerContent">
					<p>Content</p>
					<p>Name/Description | Amount | Quantity | Date</p>
<!--					<div class="contentRow0">-->
<!--						<input type="text" id="contentDescription1" name="contentDescription1" size="60" placeholder="enter content: stakes" />-->
<!--						<input type="text" id="contentAmount1" name="contentAmount1" size="30" placeholder="enter amount: 2 pieces" />-->
<!--						<input type="number" id="contentQuantity1" name="contentQuantity1" min="1" max="20" placeholder="enter quantity: 4" />-->
<!--						<input type="date" id="contentDate1" name="contentDate1" placeholder="mm/dd/YYYY" />-->
<!--						<button type="button" name="delete">Delete</button>-->
<!--						<input class="deleteCurrentRowBtn" type="button" name="delete" value="Delete" />-->
<!--					</div>-->
				</div>
				<div class="addNewContentLine">
<!--					<button type="button" name="addNewContent" onclick="addNewContentRow();">+</button><span>Add new Content line</span>-->
					<input type="button" id="addNewContentBtn" name="addNewContent2" value="+" /><span>Add new Content line</span>
				</div>
				<div class="submitOrCancel">
					<button type="submit" name="submutDrawerData">Save</button>
					<button id="cancelDrawerDataBtn" type="button" name="cancelAndClose">Cancel</button>
				</div>
			</fieldset>
		</form>

	</div>

	<div>
		<pre>
			<?php
			print_r($_GET);
			print_r($_SESSION);
			print_r($_POST);
			?>
		</pre>
	</div>

<?php
//	echo "*********************";
//	findDrawerByDrawerId(1);
//?>

<?php include("includes/footer.php"); ?>