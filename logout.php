<?php //ob_start(); 
// turns on output buffering
// we need it for redirects
// we can also do it globally in php.ini
?>

<?php require_once("session.php"); ?>
<?php require_once("globalFunctions.php"); ?>

<?php
	//destroy session
	//assumes nothing else in session to keep
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
	  setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy(); 
	redirectTo("index.php");
?>

<?php //ob_end_flush(); 
// turns off output buffering ?>
