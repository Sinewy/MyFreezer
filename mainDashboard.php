<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

$_SESSION["FreezerID"] = null;
$pageTitle = "Main Dashboard";
$userId = $_SESSION["UserID"];

?>

<?php include("includes/head.php"); ?>
<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />
<script src="js/jquery.colorbox.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/mainDashboard.js"></script>
<?php include("includes/headPart2.php"); ?>


<?php echo displayMessage(); ?>

<div class="infoBox">
	This is myFridge Site.
	<br />
	Welcome user: <?php echo isset($_SESSION["Username"]) ? $_SESSION["Username"] : "Unknown User!!" ?>
	<br /><br />

	The best fridge tracker ever.
	<br /><br />
	<a href="logout.php"><button>LogOut</button></a>
</div>

<div class="mainField">
	<div id="addNewFreezerBtn">
		<input type="button" name="addNewFreezerBtn" value="Add New Freezer" />
	</div>

	<div class="freezers">
		<?php echo createMainDashboardView($userId); ?>
	</div>
</div>

<!-- ui-dialog -->
<div id="deleteFreezerDialog" title="Warning!">
	<p>Freezer you are trying to delete still contains drawers and content.<br />
		Are you sure you want to delete this freezer and all of its drawers and content?</p>
</div>

<?php include("includes/footer.php"); ?>
