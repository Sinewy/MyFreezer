<?php require_once("includes/session.php"); ?>
<?php require_once("includes/dbc.php"); ?>
<?php require_once("includes/globalFunctions.php"); ?>
<?php require_once("includes/formValidationFunctions.php"); ?>
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
<?php include("includes/header.php"); ?>

<nav>
	<div><a href="#"><img src="images/homeIcon.png">Home</a></div>
	<div><a href="logout.php"><img src="images/logoutIcon.png"></a></div>
	<div><a href="#"><img src="images/userIcon.png"><?php echo isset($_SESSION["Username"]) ? $_SESSION["Username"] : "Unknown User!!" ?></a></div>
	<div id="addNewFreezerBtn"><a href="#"><img src="images/addIcon.png">Add New Freezer</a></div>
</nav>

<section class="sectionContainer clearFix">
    <div class="freezers">

		<?php echo createMainDashboardView($userId); ?>

    </div>
	<?php echo displayMessage(); ?>
</section>

<section class="shadowOnly">&nbsp;</section>

<!-- ui-dialog -->
<div id="deleteFreezerDialog" title="Warning!">
	<p class="popupMsg">Freezer you are trying to delete still contains drawers and content.</p>
	<p class="popupMsg">Are you sure you want to delete this freezer and all of its drawers and content?</p>
</div>

<?php include("includes/footer.php"); ?>
