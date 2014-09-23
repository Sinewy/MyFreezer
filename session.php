<?php

	session_start();
	
	function displayMessage() {
		if (isset($_SESSION["message"])) {
			$output = "<div class='message'>";
			//$output .= htmlentities($_SESSION["message"]);
			$output .= "<div><img src='images/messageIcon.svg'></div>";
			$output .= "<div>";
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= $_SESSION["message"];
			$output .= "</div>";
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