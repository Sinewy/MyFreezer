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

<h1><?php echo $freezerName; ?></h1>
<h2><?php echo $freezerDescription; ?></h2>
<h3><?php echo $freezerLocation; ?></h3>
<h3><?php echo $freezerMake; ?></h3>

<?php echo $drawersView; ?>


	<div>
		<pre>
			<?php
				print_r($_GET);
				print_r($_SESSION);
				print_r($_POST);
			?>
		</pre>
	</div>

<?php include("includes/footer.php"); ?>