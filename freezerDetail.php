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

<?php include("includes/head.php"); ?>
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />
	<script src="js/jquery.colorbox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/freezerDetail.js"></script>
<?php include("includes/header.php"); ?>

<nav>
	<div><a href="mainDashboard.php"><img src="images/homeIcon.png">Home</a> / <?php echo $freezerName; ?></div>
	<div><a href="logout.php"><img src="images/logoutIcon.png"></a></div>
	<div><a href="#"><img src="images/userIcon.png"><?php echo isset($_SESSION["Username"]) ? $_SESSION["Username"] : "Unknown User!!" ?></a></div>
	<div id="addNewDrawerBtn"><a href="#"><img src="images/addIcon.png">Add New Drawer</a></div>
</nav>

<section class="sectionContainerFreezerDetail clearFix">
	<div class="freezerName"><?php echo $freezerName; ?></div>
	<div class="freezerDescription">Description: <?php echo $freezerDescription; ?></div>
	<div class="freezerLocation">Location: <?php echo $freezerLocation; ?></div>
	<div class="freezerMake">Make: <?php echo $freezerMake; ?></div>

	<?php echo $drawersView; ?>

	<?php echo formErrors($errors); ?>
	<?php echo displayMessage(); ?>
</section>

<section class="shadowOnly">&nbsp;</section>

	<!-- ui-dialog -->
	<div id="deleteDrawerDialog" title="Warning!">
		<p class="popupMsg">Drawer you are trying to delete still contains some content.</p>
		<p class="popupMsg">Are you sure you want to delete this drawer and all of its containing content?</p>
	</div>


<?php include("includes/footer.php"); ?>