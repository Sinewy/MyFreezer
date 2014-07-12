
<?php
//require_once('includes/PHPMailer/class.phpmailer.php');
//require_once('includes/PHPMailer/class.smtp.php');
//
//$mail = new PHPMailer();
//$mail->IsSMTP();
//$mail->CharSet="UTF-8";
//$mail->SMTPSecure = 'ssl';
//$mail->Host = 'smtp.gmail.com';
//$mail->Port = 465;
//$mail->Username = 'dzuroslav@gmail.com';
//$mail->Password = 'd7ur0sl4v7';
//$mail->SMTPAuth = true;
//
//$mail->From = 'jz.info@gmx.com';
//$mail->FromName = 'Mohammad dzuroslav';
//$mail->AddAddress('jurezila@gmail.com');
//$mail->AddReplyTo('jz.info@gmail.com', 'Information');
//
//$mail->IsHTML(true);
//$mail->Subject    = "PHPMailer Test Subject via Sendmail, basic";
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";
//$mail->Body    = "Hello";
//
//if(!$mail->Send())
//{
//	echo "Mailer Error: " . $mail->ErrorInfo;
//}
//else
//{
//	echo "Message sent!";
//}
//





	// Include the PHPMailer classes
	// If these are located somewhere else, simply change the path.
	require_once("PHPMailer/class.phpmailer.php");
	require_once("PHPMailer/class.smtp.php");
	//require_once("includes/PHPMailer/language/phpmailer.lang-en.php");

	//require "includes/PHPMailer/PHPMailerAutoload.php";

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'dzuroslav@gmail.com';                 // SMTP username
$mail->Password = 'd7ur0sl4v7';                           // SMTP password
$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
//$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
//$mail->Port     = 587;
$mail->Port     = 465;

$mail->From = 'dzuroslav@gmail.com';
$mail->FromName = 'Dzureee';
$mail->addAddress('dzuroslav@gmail.com', 'dzureee');     // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('jurezila@gmail.com');
//$mail->addBCC('bcc@example.com');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
	echo 'Message could not be sent.';
	echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
	echo 'Message has been sent';
}


//	// mostly the same variables as before
//	// ($to_name & $from_name are new, $headers was omitted)
//	$to_name = "Recipient Name";
//	$to = "";
//	$subject = "Mail Test at ".strftime("%T", time());
//	$message = "This is a test.";
//	$message = wordwrap($message,70);
//	$from_name = "Sender Name";
//	$from = "";
//
//	// PHPMailer's Object-oriented approach
//	$mail = new PHPMailer();
//
//	// Can use SMTP
//	// comment out this section and it will use PHP mail() instead
//	$mail->IsSMTP();
//	$mail->Host     = "smtp.gmail.com";
//	$mail->Port     = 25;
//	$mail->SMTPAuth = true;
//	$mail->Username = "dzuroslav";
//	$mail->Password = "d7ur0sl4v7";
//
//	// Could assign strings directly to these, I only used the
//	// former variables to illustrate how similar the two approaches are.
//	$mail->FromName = $from_name;
//	$mail->From     = $from;
//	$mail->AddAddress($to, $to_name);
//	$mail->Subject  = $subject;
//	$mail->Body     = $message;
//
//	$result = $mail->Send();
//	echo $result ? 'Sent' : 'Error';
//
//
//




?>