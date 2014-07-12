<?php
// multiple recipients
$to  = 'dzuroslav@gmail.com' . ', '; // note the comma
$to .= 'jurezila@gmail.com';

// subject
$subject = 'Test mail sending to multiple with FROM set';

// message
$message = '
<html>
<head>
  <title>Test mail sending</title>
</head>
<body>
  <p>Here are the birthdays upcoming in August!</p>
  <table>
    <tr>
      <th>Person</th><th>Day</th><th>Month</th><th>Year</th>
    </tr>
    <tr>
      <td>Joe</td><td>3rd</td><td>August</td><td>1970</td>
    </tr>
    <tr>
      <td>Sally</td><td>17th</td><td>August</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: Mary <jurezila@gmail.com>, Kelly <dzuroslav@gmail.com>' . "\r\n";
$headers .= 'From: Jureko <dzuroslav@gmail.com>' . "\r\n";
$headers .= 'Cc: jurezila@gmail.com' . "\r\n";
$headers .= 'Bcc: jurezila@gmail.com' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);
?>