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

<?php //include("includes/head.php"); ?>
<!--	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />-->
<!--	<script src="js/jquery.colorbox.js"></script>-->
<!--	<script src="js/jquery-ui.js"></script>-->
<!--	<script src="js/freezerDetail.js"></script>-->
<?php //include("includes/headPart2.php"); ?>

<?php include("includes/head.php"); ?>
	<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" charset="utf-8" />
	<script src="js/jquery.colorbox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/freezerDetail.js"></script>
<?php include("includes/header.php"); ?>

<nav>
	<div><a href="mainDashboard.php"><img src="images/homeIcon.png">Home</a></div>
	<div><a href="logout.php"><img src="images/logoutIcon.png"></a></div>
	<div><a href="#"><img src="images/userIcon.png"><?php echo isset($_SESSION["Username"]) ? $_SESSION["Username"] : "Unknown User!!" ?></a></div>
	<div id="addNewFreezerBtn"><a href="#"><img src="images/addIcon.png">Add New Freezer</a></div>
</nav>

<section class="sectionContainerFreezerDetailBackground"></section>
<section class="sectionContainerFreezerDetail">
	<div class="freezerName"><?php echo $freezerName; ?></div>
	<div class="freezerDescription">Description: <?php echo $freezerDescription; ?></div>
	<div class="freezerLocation">Location: <?php echo $freezerLocation; ?></div>
	<div class="freezerMake">Make: <?php echo $freezerMake; ?></div>

<!--	<div class="drawers">-->
<!--		<div class="drawer">-->
<!--			<div class="row drawerNameRow">-->
<!--				<div class="drawerName left">Ime mojega prvega predala</div>-->
<!--				<div class="moreInfo left">I</div>-->
<!--				<div class="edit left">Edit</div>-->
<!--				<div class="delete left">Delete</div>-->
<!--			</div>-->
<!--			<div class="row columnNames clearFix">-->
<!--				<div class="contentDescr left">DESCRIPTION</div>-->
<!--				<div class="amount left">AMOUNT</div>-->
<!--				<div class="quantity left">QUANTITY</div>-->
<!--				<div class="date left">DATE</div>-->
<!--			</div>-->
<!--			<div class="row clearFix">-->
<!--				<div class="contentDescr left">Super food in my first drawer</div>-->
<!--				<div class="amount left">ena posoda, za dve osebi</div>-->
<!--				<div class="quantity left">2</div>-->
<!--				<div class="date left">2014-12-03 15:25:23</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->



<!---->
<!--	$output = "<div class=\"drawers\">";-->
<!--		if(mysqli_num_rows($drawerData) > 0) {-->
<!--		while($drawer = mysqli_fetch_assoc($drawerData)) {-->
<!--		$output .= "<div class=\"drawer\" id=\"drawer" . $drawer["DrawerID"] . "\">";-->
<!--		$output .= createDrawerInfo($drawer["DrawerID"], $drawer["Name"], $drawer["Description"]);-->
<!--		$output .= addEditDeleteButtonsJS("Drawer", $drawer["DrawerID"]);-->
<!--		$output .= createDrawerContentView($drawer["DrawerID"]);-->
<!--		$output .= "</div>";-->
<!--	}-->
<!--	} else {-->
<!--	$output .= "<p id='noDrawersYet'>There are no drawers in this freezer yet. Please add them.</p>";-->
<!--	}-->
<!--	$output .= "</div><br />";-->

<!---->
<!--	<h1 xmlns="http://www.w3.org/1999/html">--><?php //echo $freezerName; ?><!--</h1>-->
<!--	<h2>--><?php //echo $freezerDescription; ?><!--</h2>-->
<!--	<h3>--><?php //echo $freezerLocation; ?><!--</h3>-->
<!--	<h3>--><?php //echo $freezerMake; ?><!--</h3>-->

	<?php echo $drawersView; ?>

	<div id="addNewDrawerBtn">
		<input type="button" name="addNewDrawerBtn" value="Add New Drawer" />
	</div>

	<?php echo formErrors($errors); ?>
	<?php echo displayMessage(); ?>
</section>






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