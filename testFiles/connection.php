<?php
	
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "masterj";
	$dbname = "myfridge";
	$dbc = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	if(mysqli_connect_errno()) {
		die("Database connection failed: " . 
		     mysqli_connect_error() . " (" . mysqli_connect_errno() . ")"
		);
	}

	/*Default time zone ,to be able to send mail */
	date_default_timezone_set('UTC');

	/*You might not need this */
	//ini_set('SMTP', "mail.myt.mu");
	// Overide The Default Php.ini settings for sending mail

	//This is the address that will appear coming from ( Sender )
	define('EMAIL', 'email@gmail.com');

	/*Define the root url where the script will be found such as
	http://website.com or http://website.com/Folder/ */
	DEFINE('WEBSITE_URL', 'http://localhost');

?>