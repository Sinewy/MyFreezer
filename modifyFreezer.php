<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

$pageTitle = "Add or Edit Freezer Data";
$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

if(isset($_GET["addOrEditFreezerData"])) {
	echo("you came from main dashboard.");

	if(isset($_GET["freezerID"]) && $_GET["freezerID"] == "noId") {
		$freezerName = "";
		$freezerDescription = "";
		$freezerLocation = "";
		$freezerMake = "";
		$freezerIdGet = $_GET["freezerID"];
	} elseif(isset($_GET["freezerID"]) && $_GET["freezerID"] != "noId") {
		$freezerIdGet = $_GET["freezerID"];
		$freezerIdGetSafe = makeSqlSafe($freezerIdGet);
		$freezerData = findFreezerByIdAndUserId($freezerIdGetSafe, $userId);
		$freezerName = $freezerData["Name"];
		$freezerDescription = $freezerData["Description"];
		$freezerLocation = $freezerData["Location"];
		$freezerMake = $freezerData["Make"];
//		$_SESSION["freezerID"] = $freezerIdPostSafe;
	} else {
		echo("this is a strange case");
	}

} elseif(isset($_POST["submittedSaveData"])) {
	// form was submitted
	$freezerName = makeSqlSafe(trim($_POST["freezerName"]));
	$freezerDescription = makeSqlSafe(trim($_POST["freezerDescription"]));
	$freezerLocation = makeSqlSafe(trim($_POST["freezerLocation"]));
	$freezerMake = makeSqlSafe(trim($_POST["freezerMake"]));

	//$requiredField = array("freezerName");
	$fieldMaxLengths = array("freezerName" => 40, "freezerDescription" => 90, "freezerLocation" => 30, "freezerMake" => 30);
	$fieldMinLengths = array("freezerName" => 2);

	foreach ($fieldMaxLengths as $fieldName => $maxLength) {
		$value = trim($_POST[$fieldName]);
		if($fieldName == "freezerName") {
			if(hasPresence($value)) {
				if(!hasMaxLength($value, $maxLength)) {
					$errors[$fieldName] = ucfirst($fieldName) . " is too long.";
				}
				if(!hasMinLength($value, $fieldMinLengths[$fieldName])) {
					$errors[$fieldName] = ucfirst($fieldName) . " is too short.";
				}
			} else {
				$errors[$fieldName] = ucfirst($fieldName) . " can't be blank";
			}
		}
		if(!hasMaxLength($value, $maxLength)) {
			$errors[$fieldName] = ucfirst($fieldName) . " is too long.";
		}
	}

	//TODO - ce recem EDIT in potem podatkov ne spremenim in recem SAVE - edit failed - v tem primeru v resnic ni failed pac nism spremenu...

	if(empty($errors)) {
		$freezerId = $_SESSION["freezerID"];
		if(isset($freezerId)) {
			$r = updateFreezerDataForFreezerIdAndUserId($userId, $freezerId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake);
			if($r) {
				$_SESSION["message"] = "Freezer data edit successful.";
				redirectTo("mainDashboard.php");
			} else {
				$_SESSION["message"] = "Freezer data edit failed.";
				redirectTo("mainDashboard.php");
			}
		} else {
			$r = insertNewFreezerDataForUserId($userId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake);
			if($r) {
				$_SESSION["message"] = "New freezer added successfully.";
				redirectTo("mainDashboard.php");
			} else {
				$_SESSION["message"] = "New freezer could not be added.";
				redirectTo("mainDashboard.php");
			}
		}
	}
}


?>

<?php include("includes/header.php"); ?>
	<script src="js/validationFunctions.js"></script>
	<script src="js/modifyFreezer.js"></script>
<?php include("includes/header2.php"); ?>

	<?php echo formErrors($errors); ?>
	<?php echo displayMessage(); ?>

	<form action="modifyFreezer.php" method="POST">
		<fieldset>
			<legend>Add/Modify Freezer</legend>

			<p>Add/Modify Freezer - eneter data</p>

			<div>
				<label for="freezerName">Freezer Name:</label>
				<input type="hidden" id="getFreezerId" name="getFreezerId" value="<?php echo $freezerIdGet; ?>" />
				<input type="text" id="freezerName" name="freezerName" size="25" value="<?php echo htmlspecialchars($freezerName); ?>" />
				<div class="formError" id="fNameError"></div>
<!--				<input type="text" id="freezerName" name="freezerName" size="25" value="--><?php //echo htmlspecialchars($freezerName); ?><!--" disabled="true" />-->
			</div>
			<div>
				<label for="freezerDescription">Description :</label>
				<input type="text" id="freezerDescription" name="freezerDescription" size="25" value="<?php echo htmlspecialchars($freezerDescription); ?>" />
				<div class="formError" id="fDescriptionError"></div>
			</div>
			<div>
				<label for="freezerLocation">Location :</label>
				<input type="text" id="freezerLocation" name="freezerLocation" size="25" value="<?php echo htmlspecialchars($freezerLocation); ?>" />
				<div class="formError" id="fLocationError"></div>
			</div>
			<div>
				<label for="freezerMake">Make :</label>
				<input type="text" id="freezerMake" name="freezerMake" size="25" value="<?php echo htmlspecialchars($freezerMake); ?>" />
				<div class="formError" id="fMakeError"></div>
			</div>

			<div id="submitOrCancel">
				<input type="hidden" name="submittedSaveData" value="true" />
				<input id="saveEditFreezerDataForm" type="button" name="save" value="Save" />
				<input id="cancelAndCloseForm" type="button" name="cancelAndClose" value="Cancel" />
			</div>

		</fieldset>
	</form>



			<div>
				<pre>
					<?php

						print_r($_POST);
						print_r($_SESSION);

					?>
				</pre>
			</div>

<!---->
<!--	<div class="testCssClass">-->
<!--		<p>Are You SURE?</p>-->
<!--		<button>Yes</button>-->
<!--	</div>-->

<?php include("includes/footer.php"); ?>