<?php

	session_start();
	
	function displayMessage() {
		if (isset($_SESSION["message"])) {
			$output = "<div class='message'>";
			$output .= "<p><img src='images/messageIcon.svg'>Warning:</p>";
			$output .= "<p>";
			$output .=  htmlentities($_SESSION["message"]);
			$output .= "</p>";
			$output .= "</div>";

			// clear message after use
			$_SESSION["message"] = null;

			return $output;
		}
	}

	// function errors() {
	// 	if (isset($_SESSION["errors"])) {
	// 		$errors = $_SESSION["errors"];
			
	// 		// clear message after use
	// 		$_SESSION["errors"] = null;
			
	// 		return $errors;
	// 	}
	// }
	
?>