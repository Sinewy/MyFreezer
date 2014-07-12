<?php

$errors = array();

// is there some value
function hasPresence($value) {
	return isset($value) && $value !== "";
}

function hasMaxLength($value, $max) {
	return strlen($value) <= $max;
}

function hasMinLength($value, $min) {
	return strlen($value) >= $min;
}

function formErrors($errors=array()) {
	$output = "";
	if (!empty($errors)) {
	  $output .= "<div class=\"error\">";
	  $output .= "Please fix the following errors:";
	  $output .= "<ul>";
	  foreach ($errors as $key => $error) {
	    $output .= "<li>{$error}</li>";
	  }
	  $output .= "</ul>";
	  $output .= "</div>";
	}
	return $output;
}







// // inclusion in a set
// function hasInclusionIn($value, $set) {
// 	return in_array($value, $set);
// }

// function validateMaxLengths($validateFields) {
// 	global $errors;
// 	// $validteFields is associative array like: $validteFields = array("username" => 30, "password" => 8);
// 	foreach($validateFields as $field => $max) {
// 		$value = trim($_POST[$field]);
// 	  if (!hasMaxLength($value, $max)) {
// 	    $errors[$field] = ucfirst($field) . " is too long.";
// 	  }
// 	}
// }

// function validateMinLengths($validateFields) {
// 	global $errors;
// 	// $validteFields is associative array like: $validteFields = array("username" => 30, "password" => 8);
// 	foreach($validateFields as $field => $min) {
// 		$value = trim($_POST[$field]);
// 	  if (!hasMinLength($value, $min)) {
// 	    $errors[$field] = ucfirst($field) . " is too short.";
// 	  }
// 	}
// }



?>