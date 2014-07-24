<?php

function redirectTo($newLocation) {
	header("Location: " . $newLocation);
	exit;
}

//******************* Password/Login Functions *********************\\

function passwordEncrypt($password) {
	$hashFormat = "$2y$10$";   			// Tells PHP to use Blowfish with a "cost" of 10
	$saltLength = 22; 					// Blowfish salts should be 22-characters or more
	$salt = generateSalt($saltLength);
	$formaAndSalt = $hashFormat . $salt;
	$hash = crypt($password, $formaAndSalt);
	return $hash;
}

function generateSalt($length) {
	// Not 100% unique, not 100% random, but good enough for a salt
	// MD5 returns 32 characters
	$uniqueRandomString = md5(uniqid(mt_rand(), true));

	// Valid characters for a salt are [a-zA-Z0-9./]
	$base64String = base64_encode($uniqueRandomString);

	// But not '+' which is valid in base64 encoding
	$modifiedBase64String = str_replace('+', '.', $base64String);

	// Truncate string to the correct length
	$salt = substr($modifiedBase64String, 0, $length);

	return $salt;
}

function passwordCheck($password, $existingHash) {
	// existing hash contains format and salt at start
	$hash = crypt($password, $existingHash);
	if ($hash === $existingHash) {
		return true;
	} else {
		return false;
	}
}

function attemptLogin($username, $password) {
	$user = findUserByUsername($username);
	if($user) {
		// found USER, now check password
		if (passwordCheck($password, $user["Password"])) {
			// password matches
			return $user;
		} else {
			// password does not match
			return false;
		}
	} else {
		// USER not found
		return false;
	}
}

function loggedIn() {
	//if we have set user id in session we are logged in
	return isset($_SESSION['UserID']);
}

function confirmLoggedIn() {
	if (!loggedIn()) {
		redirectTo("index.php");
	}
}

function confirmLoggedInOnIndex() {
	if (loggedIn()) {
		redirectTo("mainFridgeArea.php");
	}
}

//******************* Password/Login Functions END *********************\\

//******************* Database Query Functions *********************\\

function makeSqlSafe($string) {
	global $dbc;
	$safeString = mysqli_real_escape_string($dbc, $string);
	return $safeString;
}

function confirmQuery($resultSet) {
	global $dbc;
	if (!$resultSet) {
		echo mysqli_error($dbc);
		die("Database query failed.");
	}
}

function findUserByUsername($username) {
	global $dbc;
	$safeUsername = mysqli_real_escape_string($dbc, $username);

	$query  = "SELECT * ";
	$query .= "FROM user ";
	$query .= "WHERE Username = '{$safeUsername}' ";
	$query .= "AND Activation = 1 ";
	$query .= "LIMIT 1";
	$userSet = mysqli_query($dbc, $query);
	confirmQuery($userSet);
	if($user = mysqli_fetch_assoc($userSet)) {
		return $user;
	} else {
		return null;
	}
}

function getRegistrationDropdownOptionsFor($dbTable, $columnName, $selected) {
	global $dbc;
	$dropDownOptions = "";

	$query  = "SELECT * ";
	$query .= "FROM $dbTable ";
	$query .= "ORDER BY $columnName ASC";
	$result = mysqli_query($dbc, $query);
	if (!$result) {
		die("Database query failed.");
	}

	while($options = mysqli_fetch_assoc($result)) {
		$dropDownOptions .= "<option ";
		if($selected == $options[$columnName]) {
			$dropDownOptions .= "selected";
		}
		$dropDownOptions .= ">" . $options[$columnName] . "</option>";
	}

	mysqli_free_result($result);
	return $dropDownOptions;
}

function findFreezerByIdAndUserId($freezerId, $userId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM freezer ";
	$query .= "WHERE UserID = {$userId} ";
	$query .= "AND FreezerID = {$freezerId} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($freezerData = mysqli_fetch_assoc($result)) {
		return $freezerData;
	} else {
		return null;
	}
}

function  updateFreezerDataForFreezerIdAndUserId($userId, $freezerId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake) {
	global $dbc;
	$query  = "UPDATE freezer SET ";
	$query .= "Name = '{$freezerName}', ";
	$query .= "Description = '{$freezerDescription}', ";
	$query .= "Location = '{$freezerLocation}', ";
	$query .= "Make = '{$freezerMake}' ";
	$query .= "WHERE UserID = {$userId} ";
	$query .= "AND FreezerID = {$freezerId} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($result && mysqli_affected_rows($dbc) == 1) {
		// Success
		return $result;
	} else {
		// Failure
		return null;
	}
}

function  insertNewFreezerDataForUserId($userId, $freezerName, $freezerDescription, $freezerLocation, $freezerMake) {
	global $dbc;
	$query  = "INSERT INTO freezer ";
	$query .= "(Name, Description, Location, Make, UserID) ";
	$query .= "VALUES ('{$freezerName}', '{$freezerDescription}', '{$freezerLocation}', '{$freezerMake}', {$userId}) ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($result && mysqli_affected_rows($dbc) == 1) {
		// Success
		return $result;
	} else {
		// Failure
		return null;
	}
}

function findAllDrawersForFreezer($freezerId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM drawer ";
	$query .= "WHERE FreezerID = {$freezerId} ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
//	if($drawerData = mysqli_fetch_assoc($result)) {
//		return $drawerData;
//	} else {
//		return null;
//	}
}

function findDrawerByDrawerId($drawerId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM drawer ";
	$query .= "WHERE DrawerID = {$drawerId} ";
	$query .= "LIMIT 1 ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($drawerData = mysqli_fetch_assoc($result)) {
		return $drawerData;
	} else {
		return null;
	}
}

function findAllContentForDrawer($drawerId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM content ";
	$query .= "WHERE DrawerID = {$drawerId} ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
}
//******************* Database Query Functions END *********************\\

//******************* Layout Functions ***********************\\

function createDrawerContentView($drawerId) {
	$allContent = findAllContentForDrawer($drawerId);
	$output = "<div class=\"content\">";
	$output .= "<table><tr><td>Description</td><td>Amount</td><td>Quantity</td></tr>";
	if(mysqli_num_rows($allContent) > 0) {
		while($content = mysqli_fetch_assoc($allContent)) {
			$output .= "<tr><td>" . $content["Description"] . "</td>";
			$output .= "<td>" . $content["Amount"] . "</td>";
			$output .= "<td>" . $content["Quantity"] . "</td></tr>";
		}
	} else {
		$output .= "<tr><td colspan=\"3\">This drawer is empty.</td></tr>";
	}
	$output .= "</table>";
	$output .= "</div>";
	return $output;
}

function createFreezerDrawerView($freezerId) {
	$drawerData = findAllDrawersForFreezer($freezerId);
	$output = "<div class=\"drawers\">";
	if(mysqli_num_rows($drawerData) > 0) {
		while($drawer = mysqli_fetch_assoc($drawerData)) {
			$output .= "<div class=\"drawer\" id=\"drawer" . $drawer["DrawerID"] . "\">";
			$output .= $drawer["Name"] . "  ";
			$output .= $drawer["Description"] . "  ";
//			$output .= addEditDeleteButtons("DrawerID", $drawer["DrawerID"]);
			$output .= addEditDeleteButtonsJS("DrawerID", $drawer["DrawerID"]);
			$output .= createDrawerContentView($drawer["DrawerID"]);
			$output .= "</div>";
		}
	} else {
		$output .= "<p>There are no drawers in this freezer yet. Please add them.</p>";
	}
	$output .= "</div><br />";
	return $output;
}

function addEditDeleteButtons($elementName, $elementId) {
	$buttonsOutput = "<div class=\"modifyBox\">";
	$buttonsOutput .= "<form enctype=\"multipart/form-data\" action=\"freezerDetail.php\" method=\"POST\" class=\"fakeForm\">";
	$buttonsOutput .= "<input type=\"hidden\" name=\"{$elementName}\" value=\"" . $elementId . "\"/>";
	$buttonsOutput .= "<input type=\"submit\" name=\"editBtnPressed\" value=\"Edit\" />";
	$buttonsOutput .= "</form>";
	$buttonsOutput .= "<form enctype=\"multipart/form-data\" action=\"freezerDetail.php\" method=\"POST\" class=\"fakeForm\">";
	$buttonsOutput .= "<input type=\"hidden\" name=\"{$elementName}\" value=\"" . $elementId . "\"/>";
	$buttonsOutput .= "<input type=\"submit\" name=\"deleteBtnPressed\" value=\"Delete\" />";
	$buttonsOutput .= "</form></div>";
	return $buttonsOutput;
}

function addEditDeleteButtonsJS($elementName, $elementId) {
	$buttonsOutput = "<div class=\"modifyBox\">";
	$buttonsOutput .= "<input class=\"editDrawerDataBtn\" type=\"button\" name=\"editBtn" . $elementId . "\" value=\"Edit\" />";
	$buttonsOutput .= "<input class=\"deleteDrawerBtn\" type=\"button\" name=\"deleteBtn" . $elementId . "\" value=\"Delete\" />";
	$buttonsOutput .= "</div>";
	return $buttonsOutput;
}



//******************* Layout Functions END ***********************\\
?>
