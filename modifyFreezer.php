<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
	$pageTitle = "Add or Edit Freezer Data";
	$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;

	if(isset($_POST["submitted"])) {

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

		//TODO - ce kliknem cance in mam v sessionu freezer ID ga zbrisem
		//TODO - ce recem EDIT in potem podatkov ne spremenim in recem SAVE - edit failed - v tem primeru v resnic ni failed pa nism spremenu...

		if(empty($errors)) {
			$freezerId = $_SESSION["freezerID"];
			if(isset($freezerId)) {
				$r = updateFreezerDataForFreezerIdAndUserId($userId, $freezerId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake);
				if($r) {
					$_SESSION["message"] = "Freezer data edit successful.";
					$_SESSION["freezerID"] = null;
					redirectTo("mainDashboard.php");
				} else {
					$_SESSION["message"] = "Freezer data edit failed.";
					$_SESSION["freezerID"] = null;
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
	} else if(isset($_POST["btnPressed"])) {
		$freezerIdPost = isset($_POST["FreezerID"]) ? $_POST["FreezerID"] : null;
		$freezerIdPostSafe = makeSqlSafe($freezerIdPost);

		$freezerData = findFreezerByIdAndUserId($freezerIdPostSafe, $userId);
		//TODO - check if not null than do this
		$freezerName = $freezerData["Name"];
		$freezerDescription = $freezerData["Description"];
		$freezerLocation = $freezerData["Location"];
		$freezerMake = $freezerData["Make"];

		$_SESSION["freezerID"] = $freezerIdPostSafe;
	} else {
		$freezerName = "";
		$freezerDescription = "";
		$freezerLocation = "";
		$freezerMake = "";
	}

?>

<?php include("includes/header.php"); ?>

	<?php echo formErrors($errors); ?>
	<?php echo displayMessage(); ?>

	<form action="modifyFreezer.php" method="POST">
		<fieldset>
			<legend>Add/Modify Freezer</legend>

			<p>Add/Modify Freezer - eneter data</p>

			<div>
				<label for="freezerName">Freezer Name:</label>
				<input type="text" id="freezerName" name="freezerName" size="25" value="<?php echo htmlspecialchars($freezerName); ?>" />
<!--				<input type="text" id="freezerName" name="freezerName" size="25" value="--><?php //echo htmlspecialchars($freezerName); ?><!--" disabled="true" />-->
			</div>
			<div>
				<label for="freezerDescription">Description :</label>
				<input type="text" id="freezerDescription" name="freezerDescription" size="25" value="<?php echo htmlspecialchars($freezerDescription); ?>" />
			</div>
			<div>
				<label for="freezerLocation">Location :</label>
				<input type="text" id="freezerLocation" name="freezerLocation" size="25" value="<?php echo htmlspecialchars($freezerLocation); ?>" />
			</div>
			<div>
				<label for="freezerMake">Make :</label>
				<input type="text" id="freezerMake" name="freezerMake" size="25" value="<?php echo htmlspecialchars($freezerMake); ?>" />
			</div>
			<div>
				<input type="hidden" name="submitted" value="TRUE" />
				<input type="submit" value="Save" />
			</div>

		</fieldset>
	</form>

	<div>
		<a href="mainDashboard.php"><button>Cancel</button></a>
	</div>


<!---->
<!---->
<!---->
<!--			<div>-->
<!--				<pre>-->
<!--					--><?php
//
//						print_r($_POST);
//
//					?>
<!--				</pre>-->
<!--			</div>-->
<!---->

	<div class="testCssClass">
		<p>Are You SURE?</p>
		<button>Yes</button>
	</div>

<?php include("includes/footer.php"); ?>