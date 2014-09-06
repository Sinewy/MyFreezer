<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Manage Ffeezer drawers and its content";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_GET["fid"])) {

	$_SESSION["FreezerID"] = $_GET["fid"];

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
		<input type="button" name="addNewDrawerBtn" value="Add New Drawer" />
	</div>

	<!-- ui-dialog -->
	<div id="deleteDrawerDialog" title="Warning!">
		<p>Drawer you are trying to delete still contains some content.<br />
			Are you sure you want to delete this drawer and all of its containing content?</p>
	</div>

	<div>
		<pre>
			<?php print_r($_SESSION); ?>
		</pre>
	</div>

<?php include("includes/footer.php"); ?>