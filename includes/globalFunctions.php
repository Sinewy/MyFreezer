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
		redirectTo("mainDashboard.php");
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

function findAllFreezersForUserId($userId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM freezer ";
	$query .= "WHERE UserID = '{$userId}' ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
}

function findAllFreezersForUserIdFirstOne($userId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM freezer ";
	$query .= "WHERE UserID = '{$userId}' ";
	$query .= "LIMIT 1 ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($freezerData = mysqli_fetch_assoc($result)) {
		return $freezerData;
	} else {
		return null;
	}
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

function deleteFreezerById($freezerId) {
	global $dbc;
	$query  = "DELETE ";
	$query .= "FROM freezer ";
	$query .= "WHERE FreezerID = {$freezerId} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
}

function findAllDrawersForFreezer($freezerId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM drawer ";
	$query .= "WHERE FreezerID = {$freezerId} ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
}

function findAllDrawersForFreezerFirstOne($freezerId) {
	global $dbc;
	$query  = "SELECT * ";
	$query .= "FROM drawer ";
	$query .= "WHERE FreezerID = {$freezerId} ";
	$query .= "LIMIT 1 ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	if($drawerData = mysqli_fetch_assoc($result)) {
		return $drawerData;
	} else {
		return null;
	}
}

function insertNewDrawerData($freezerId, $drawerName, $drawerDescription) {
	global $dbc;
	$query  = "INSERT INTO drawer ";
	$query .= "(Name, Description, FreezerID) ";
	$query .= "VALUES ('{$drawerName}', '{$drawerDescription}', {$freezerId}) ";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	$resultId = mysqli_insert_id($dbc);
	return $resultId;
}

function updateDrawerDataForId($dId, $dName, $dDescription) {
	global $dbc;
	$query  = "UPDATE drawer SET ";
	$query .= "Name = '{$dName}', ";
	$query .= "Description = '{$dDescription}' ";
	$query .= "WHERE DrawerID = {$dId} ";
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

function deleteDrawerById($drawerId){
	global $dbc;
	$query  = "DELETE ";
	$query .= "FROM drawer ";
	$query .= "WHERE DrawerID = {$drawerId} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
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

function insertNewContentRowForDrawerId($dId, $cDescription, $cAmount, $cQty, $cDate) {
	global $dbc;
	$cDate = $cDate == "" ? "NULL" : $cDate;
	$query  = "INSERT INTO content ";
	$query .= "(Description, Amount, Quantity, PackingDate, DrawerID) ";
	if($cDate == "NULL"){
		$query .= "VALUES ('{$cDescription}', '{$cAmount}', {$cQty}, {$cDate}, {$dId}) ";
	} else {
		$query .= "VALUES ('{$cDescription}', '{$cAmount}', {$cQty}, '{$cDate}', {$dId}) ";
	}
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	$resultId = mysqli_insert_id($dbc);
	return $resultId;
}

function updateContentRowById($cId, $cDescription, $cAmount, $cQty, $cDate) {
	global $dbc;
	$cDate = $cDate == "" ? "NULL" : $cDate;
	$query  = "UPDATE content SET ";
	$query .= "Description = '{$cDescription}', ";
	$query .= "Amount = '{$cAmount}', ";
	$query .= "Quantity = {$cQty}, ";
	if($cDate == "NULL"){
		$query .= "PackingDate = {$cDate} ";
	} else {
		$query .= "PackingDate = '{$cDate}' ";
	}
	$query .= "WHERE ContentID = {$cId} ";
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

function deleteContentById($contentId) {
	global $dbc;
	$query  = "DELETE ";
	$query .= "FROM content ";
	$query .= "WHERE ContentID = {$contentId} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($dbc, $query);
	confirmQuery($result);
	return $result;
}
//******************* Database Query Functions END *********************\\

//******************* Layout Functions ***********************\\

function createMainDashboardView($userId) {
	$freezerData = findAllFreezersForUserId($userId);
	$output = "";
	if(mysqli_num_rows($freezerData) > 0) {
		while($freezer = mysqli_fetch_assoc($freezerData)) {

			$output .= "<div class='freezerBox' id='freezer". $freezer["FreezerID"] . "'>";
			$output .= "<div class='freezerHeader'>";
			$output .= "<a href='freezerDetail.php?fid=" . $freezer["FreezerID"] . "'>";
			$output .= "<div class='fTitle'>" . $freezer["Name"] . "</div>";
			$output .= "</a>";
			$output .= "<div class='deleteBtn deleteFreezerBtn' title='deleteBtn" . $freezer["FreezerID"] . "'><img src='images/trash.png'></div>";
			$output .= "<div class='editBtn editFreezerDataBtn' title='editBtn" . $freezer["FreezerID"] . "'><img src='images/edit.png'></div>";
			$output .= "</div>";
			$output .= "<a href='freezerDetail.php?fid=" . $freezer["FreezerID"] . "'>";
			$output .= "<div class='fMainData'>";
			$output .= "<div class='fIcon'><img src='../images/freezerIcon.png'></div>";
			$output .= "<div class='fMake'>Make: ". $freezer["Make"] . "</div>";
			$output .= "<div class='fLocation'>Location: " . $freezer["Location"] . "</div>";
			$output .= "<div class='fDescription'>Description: " . $freezer["Description"]. "</div>";
			$output .= "</div>";
			$output .= "</a>";
			$output .= "</div>";
//			name=\"editBtn" . $elementId . "\" value=\"Edit\" />";
//			$buttonsOutput .= "<input class=\"delete" . $elementName . "Btn\" type=\"button\" name=\"deleteBtn" . $elementId .

//			$output .= "<div class=\"freezerBox\" id=\"freezer" . $freezer["FreezerID"] . "\">";
//			$output .= addEditDeleteButtonsJS("Freezer", $freezer["FreezerID"]);
//			$output .= "<a href=\"freezerDetail.php?fid=" . $freezer["FreezerID"] . "\">";
//			$output .= createFreezerData($freezer["Name"], $freezer["Description"], $freezer["Location"], $freezer["Make"]);
//			$output .= "</a></div>";
		}
	} else {
		$output = "<p id='noFreezersYet'>There are no freezers in your system yet. Please add them.</p>";
	}
	return $output;
}

//function createFreezerData($name, $desc, $loc, $make){
function createFreezerData($fId, $userId){

	$freezer = findFreezerByIdAndUserId($fId, $userId);
//	$returnData["content"] = createFreezerData($freezerData["Name"], $freezerData["Description"], $freezerData["Location"], $freezerData["Make"]);

	$output = "";
	$output .= "<div class='freezerBox' id='freezer". $freezer["FreezerID"] . "'>";
	$output .= "<div class='freezerHeader'>";
	$output .= "<a href='freezerDetail.php?fid=" . $freezer["FreezerID"] . "'>";
	$output .= "<div class='fTitle'>" . $freezer["Name"] . "</div>";
	$output .= "</a>";
	$output .= "<div class='deleteBtn deleteFreezerBtn' title='deleteBtn" . $freezer["FreezerID"] . "'><img src='images/trash.png'></div>";
	$output .= "<div class='editBtn editFreezerDataBtn' title='editBtn" . $freezer["FreezerID"] . "'><img src='images/edit.png'></div>";
	$output .= "</div>";
	$output .= "<a href='freezerDetail.php?fid=" . $freezer["FreezerID"] . "'>";
	$output .= "<div class='fMainData'>";
	$output .= "<div class='fIcon'><img src='../images/freezerIcon.png'></div>";
	$output .= "<div class='fMake'>Make: ". $freezer["Make"] . "</div>";
	$output .= "<div class='fLocation'>Location: " . $freezer["Location"] . "</div>";
	$output .= "<div class='fDescription'>Description: " . $freezer["Description"]. "</div>";
	$output .= "</div>";
	$output .= "</a>";
	$output .= "</div>";
//
//	$output .= "<div class=\"freezerData\">";
//	$output .= "<p>" . htmlentities($name) . "</p>";
//	if(hasPresence($desc)) { $output .=  "<p>" . htmlentities($desc) . "</p>";}
//	if(hasPresence($loc)) { $output .=  "<p>" . htmlentities($loc) . "</p>";}
//	if(hasPresence($make)) { $output .=  "<p>" . htmlentities($make) . "</p>";}
//	$output .= "</div>";
	return $output;
}

function createFreezer($freezerId, $userId) {
	$freezer =  findFreezerByIdAndUserId($freezerId, $userId);
	$output = "<div class=\"freezerBox\" id=\"freezer" . $freezer["FreezerID"] . "\">";
	$output .= addEditDeleteButtonsJS("Freezer", $freezer["FreezerID"]);
	$output .= "<a href=\"freezerDetail.php?fid=" . $freezer["FreezerID"] . "\">";
	$output .= createFreezerData($freezer["Name"], $freezer["Description"], $freezer["Location"], $freezer["Make"]);
	$output .= "</a></div>";
	return $output;
}

function createEditDrawerContentView($drawerId) {
	$allContent = findAllContentForDrawer($drawerId);
	$output = "";
	if(mysqli_num_rows($allContent) > 0) {
		while($content = mysqli_fetch_assoc($allContent)) {
			$cId = $content["ContentID"];
			$cDesc = $content["Description"];
			$cAmnt = $content["Amount"];
			$cQty = $content["Quantity"];
			$packingDate = $content["PackingDate"];
			
			$output .=  "<div class='contentRowStyle' id='contentRow" . $cId . "'>";
			$output .=  "<input type='text' id='contentDescription" . $cId . "' name='contentDescription" . $cId . "' size='40' placeholder='enter content: stakes' value='" . $cDesc . "' />";
			$output .=  "<input type='text' id='contentAmount" . $cId . "' name='contentAmount" . $cId . "' size='25' placeholder='enter amount: 2 pieces' value='" . $cAmnt . "' />";
			$output .=  "<input type='number' id='contentQuantity" . $cId . "' name='contentQuantity" . $cId . "' min='1' max='20' placeholder='quantity: 4' value='" . $cQty . "' />";
			$output .=  "<input type='date' id='contentDate" . $cId . "' name='contentPackingDate" . $cId . "' placeholder='mm/dd/YYYY' value='" . $packingDate . "' />";
			$output .=  "<input type='button' class='deleteCurrentRowBtn button'  name='" . $cId . "' value='Delete' />";
			$output .=  "</div>";
			$output .=  "<div class='formError' id='fError" . $cId . "'></div>";
		}
	} else {
		$output .= "<p id='emptyDrawer' class='emptyDrawer'>This drawer is still empty. Add some content.</p>";
	}
	return $output;
}

function createFreezerDrawerView($freezerId) {
	$drawerData = findAllDrawersForFreezer($freezerId);
	$output = "<div class=\"drawers\">";
	if(mysqli_num_rows($drawerData) > 0) {
		while($drawer = mysqli_fetch_assoc($drawerData)) {
			$output .= "<div class='drawer' id='drawer" . $drawer["DrawerID"] . "'>";
			$output .= "<div class='row drawerNameRow'>";
			$output .= "<div class='drawerName left'>" . $drawer["Name"] . "</div>";
			$output .= "<div class='moreDrawerInfoBtn left'>&nbsp;</div>";
//			$output .= "<div class='moreDrawerInfoBtn left'><img src='images/moreInfo.svg'></div>";
			$output .= "<div class='editDrawerBtn left' id='editDrawer" . $drawer["DrawerID"] . "'><img src='images/edit.png'></div>";
			$output .= "<div class='deleteDrawerBtn left' id='deleteDrawer" . $drawer["DrawerID"] . "'><img src='images/trash.png'></div>";
			$output .= "</div>";
			$output .= "<div class='row columnNames clearFix'>";
			$output .= "<div class='contentDescr left''>DESCRIPTION</div>";
			$output .= "<div class='amount left'>AMOUNT</div>";
			$output .= "<div class='quantity left'>QUANTITY</div>";
			$output .= "<div class='date left'>DATE</div>";
			$output .= "</div>";

//
//			<div class=\"modifyBox\">";
//	$buttonsOutput .= "<input class=\"edit" . $elementName . "DataBtn\" type=\"button\" name=\"editBtn" . $elementId . "\" value=\"Edit\" />";
//	$buttonsOutput .= "<input class=\"delete" . $elementName . "Btn\" type=\"button\" name=\"deleteBtn" . $elementId . "\" value=\"Delete\" />";
//	$buttonsOutput .= "</div>";
//
//			<div class="row clearFix">
//				<div class="contentDescr left">Super food in my first drawer</div>
//				<div class="amount left">ena posoda, za dve osebi</div>
//				<div class="quantity left">2</div>
//				<div class="date left">2014-12-03 15:25:23</div>
//			</div>
//		</div>
//


//			$output .= createDrawerInfo($drawer["DrawerID"], $drawer["Name"], $drawer["Description"]);
//			$output .= addEditDeleteButtonsJS("Drawer", $drawer["DrawerID"]);
			$output .= createDrawerContentView($drawer["DrawerID"]);
			$output .= "</div>";
		}
	} else {
		$output .= "<p id='noDrawersYet'>There are no drawers in this freezer yet. Please add them.</p>";
	}
	$output .= "</div><br />";
	return $output;
}

function createDrawer($dId) {
	$dData = findDrawerByDrawerId($dId);
//	$output = "<div class=\"drawer\" id=\"drawer" . $dId . "\">";
//	$output .= createDrawerInfo($dId, $dData["Name"], $dData["Description"]);
//	$output .= addEditDeleteButtonsJS("Drawer", $dId);
	$output = "";
	$output .= "<div class='drawer' id='drawer" . $dData["DrawerID"] . "'>";
	$output .= "<div class='row drawerNameRow'>";
	$output .= "<div class='drawerName left'>" . $dData["Name"] . "</div>";
	$output .= "<div class='moreDrawerInfoBtn left'>&nbsp;</div>";
//			$output .= "<div class='moreDrawerInfoBtn left'><img src='images/moreInfo.svg'></div>";
	$output .= "<div class='editDrawerBtn left' id='editDrawer" . $dData["DrawerID"] . "'><img src='images/edit.png'></div>";
	$output .= "<div class='deleteDrawerBtn left' id='deleteDrawer" . $dData["DrawerID"] . "'><img src='images/trash.png'></div>";
	$output .= "</div>";
	$output .= "<div class='row columnNames clearFix'>";
	$output .= "<div class='contentDescr left''>DESCRIPTION</div>";
	$output .= "<div class='amount left'>AMOUNT</div>";
	$output .= "<div class='quantity left'>QUANTITY</div>";
	$output .= "<div class='date left'>DATE</div>";
	$output .= "</div>";

	$output .= createDrawerContentView($dId);
	$output .= "</div>";
	return $output;
}

function createDrawerInfo($dId, $dName, $dDescription) {
	$output = "<div class='drawerName left'>";
	$output .= $dName;
//	$output .= "<p>" . $dDescription . "</p>";
	$output .= "</div>";
	return $output;
}

function createDrawerContentView($drawerId) {
	$allContent = findAllContentForDrawer($drawerId);
//	$output = "<div class=\"content\">";
//	$output .= "<table><tr><td>Description</td><td>Amount</td><td>Quantity</td></tr>";
	$output = "";
	if(mysqli_num_rows($allContent) > 0) {
		while($content = mysqli_fetch_assoc($allContent)) {
			$output .= "<div class='row clearFix'>";
			$output .= "<div class='contentDescr left'>" . $content["Description"] . "</div>";
			$output .= "<div class='amount left'>" . (trim($content["Amount"]) == "" ? "-" : $content["Amount"]) . "</div>";
			$output .= "<div class='quantity left'>" . (trim($content["Quantity"]) == "" ? "-" : $content["Quantity"]) . "</div>";
//			$output .= "<div class='date left'>" . $content["Date"] . "</div>";
//			$output .= "<div class='date left'>2014-11-08</div>";
			$output .= "<div class='date left'>" . (trim($content["PackingDate"]) == "" ? "-" : $content["PackingDate"]) . "</div>";
			$output .= "</div>";

//			$output .= "<tr><td>" . $content["Description"] . "</td>";
//			$output .= "<td>" . $content["Amount"] . "</td>";
//			$output .= "<td>" . $content["Quantity"] . "</td></tr>";
		}
	} else {
//		$output .= "<tr><td class='emptyDrawer' colspan=\"3\">This drawer is empty.</td></tr>";
		$output .= "<p class='emptyDrawer' colspan=\"3\">This drawer is empty.<p>";
	}
//	$output .= "</table>";
//	$output .= "</div>";
	return $output;
}

function addEditDeleteButtonsJS($elementName, $elementId) {
	$buttonsOutput = "<div class=\"modifyBox\">";
	$buttonsOutput .= "<input class=\"edit" . $elementName . "DataBtn\" type=\"button\" name=\"editBtn" . $elementId . "\" value=\"Edit\" />";
	$buttonsOutput .= "<input class=\"delete" . $elementName . "Btn\" type=\"button\" name=\"deleteBtn" . $elementId . "\" value=\"Delete\" />";
	$buttonsOutput .= "</div>";
	return $buttonsOutput;
}



//******************* Layout Functions END ***********************\\
?>
