<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$_SESSION["freezerID"] = null;
$pageTitle = "Main Dashboard";
$userId = $_SESSION["UserID"];
$freezersOutput = "";

//global $dbc;
$query  = "SELECT * ";
$query .= "FROM freezer ";
$query .= "WHERE UserID = '{$userId}' ";
$result = mysqli_query($dbc, $query);
confirmQuery($result);
//return $result;

if(mysqli_num_rows($result) > 0) {
	while($freezer = mysqli_fetch_assoc($result)) {
		$freezersOutput .= "<div class=\"freezerBox\">";
		$freezersOutput .= "<div class=\"modifyBox\">";
		$freezersOutput .= "<form enctype=\"multipart/form-data\" action=\"modifyFreezer.php\" method=\"POST\" class=\"fakeForm\">";
		$freezersOutput .= "<input type=\"hidden\" name=\"FreezerID\" value=\"" . $freezer["FreezerID"] . "\"/>";
		$freezersOutput .= "<input type=\"submit\" name=\"btnPressed\" value=\"Edit\" />";
		$freezersOutput .= "</form>";
		$freezersOutput .= "<form enctype=\"multipart/form-data\" action=\"deleteFreezer.php\" method=\"POST\" class=\"fakeForm\">";
		$freezersOutput .= "<input type=\"hidden\" name=\"FreezerID\" value=\"" . $freezer["FreezerID"] . "\"/>";
		$freezersOutput .= "<input type=\"submit\" name=\"btnPressed\" value=\"Delete\" />";
		$freezersOutput .= "</form>";
		$freezersOutput .= "</div><a href=\"freezerDetail.php?fid=" . $freezer["FreezerID"] . "\"><div class=\"freezerData\">";
		$freezersOutput .= "<p>" . htmlentities($freezer["Name"]) . "</p>";
		if(hasPresence($freezer["Description"])) { $freezersOutput .=  "<p>" . htmlentities($freezer["Description"]) . "</p>";}
		if(hasPresence($freezer["Location"])) { $freezersOutput .=  "<p>" . htmlentities($freezer["Location"]) . "</p>";}
		if(hasPresence($freezer["Make"])) { $freezersOutput .=  "<p>" . htmlentities($freezer["Make"]) . "</p>";}
		$freezersOutput .= "</div></a></div>";
	}
} else {
	$freezersOutput = "<p>You don't have any freezers yet.</p>";
}

?>

<?php include("includes/header.php"); ?>

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
	<div class="addNewFreezerBtn">
		<a href="modifyFreezer.php"><button>+</button></a>
		<p>Add New Freezer</p>
	</div>

	<div class="freezers">
		<?php echo $freezersOutput; ?>
	</div>
</div>

<?php include("includes/footer.php"); ?>
