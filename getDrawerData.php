<?php header('content-type: application/json; charset=utf-8'); ?>
<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

if(isset($_POST["drawerID"])) {
	$drawerId = $_POST["drawerID"];

	$data = "{";
	$data .= "\"info\":" . json_encode(findDrawerByDrawerId($drawerId));
	$data .= ", ";

	$allContent = findAllContentForDrawer($drawerId);
	$dataContent = array();
	if(mysqli_num_rows($allContent) > 0) {
		while($content = mysqli_fetch_assoc($allContent)) {
			$dataContent[] = $content;
		}
	}
	$data .= "\"content\":" . json_encode($dataContent);
	$data .= "}";
	echo $data;
}


?>