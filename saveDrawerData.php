<?php require_once("session.php"); ?>
<?php require_once("dbc.php"); ?>
<?php require_once("globalFunctions.php"); ?>
<?php require_once("formValidationFunctions.php"); ?>
<?php confirmLoggedIn(); ?>

<?php

if(isset($_POST["saveData"])) {

	$output = "<br />";
	$output .= "<div><p>hej kwa je zdeeejjj </p>";
	$output .= "<br /><pre>";
	$output .= print_r($_POST);
	//$output .= implode(" ", $_POST);
	$output .= "</pre><br />";

	//$output .= "</div>";
	$output .= "<br />";
	$output .=  "drawer id isset: " . isset($_POST["drawerID"]);
	$output .= "<br />";
	$output .=  "drawer id isnulll: " . is_null($_POST["drawerID"]);
	$output .= "<br />";
	$output .=  "drawer id empty: " . empty($_POST["drawerID"]);
	$output .= "<br />";
	$output .=  "drawer id is the folowwing: " . $_POST["drawerID"];
	$output .= "<br />";
	$output .=  "delete data id isset: " . isset($_POST["deleteContent"]);
	$output .= "</div>*******************<br/><br/>";

	echo $output;
}

?>