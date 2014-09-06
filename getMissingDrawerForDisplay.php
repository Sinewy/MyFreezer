<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php
$pageTitle = "Getting missing drawer For Display";
//$userId = isset($_SESSION["UserID"]) ? $_SESSION["UserID"] : null;
$freezerId = isset($_SESSION["FreezerID"]) ? $_SESSION["FreezerID"] : null;

if(isset($_POST["existingDrawers"])) {
	$displayedDrawers = $_POST["existingDrawers"];
	$dbDrawers = [];
	$drawerData = findAllDrawersForFreezer($freezerId);
	//$drawerData = findAllDrawersForFreezer(7);
	if(mysqli_num_rows($drawerData) > 0) {
		while($drawer = mysqli_fetch_assoc($drawerData)) {
			$dbDrawers[] = $drawer["DrawerID"];
		}
	}

	$result = array_diff($dbDrawers, $displayedDrawers);
	$theOne = "";
	foreach($result as $val) {
		$theOne = $val;
	}

//
//	for($i = 0; $i < count($dbDrawers); $i++) {
//		for($j = 0; $j < count($displayedDrawers); $j++) {
//			if($dbDrawers[$i] == $displayedDrawers[$j]) {
//				unset($dbDrawers[$i]);
//			}
//		}
//
//	}
////
//	foreach($dbDrawers as $dbId) {
//		foreach($displayedDrawers as $diId) {
//			if($dbId == $diId) {
//				unset($dbId);
//			}
//		}
//	}

	echo json_encode($theOne);
} else {
	$drawerData = findAllDrawersForFreezerFirstOne($freezerId);
	echo json_encode($drawerData["DrawerID"]);
}