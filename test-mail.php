<?php
require_once('class.phpmailer.php');
$mail = new PHPMailer();
$mail->IsHTML(true);
$mail->IsSMTP();
$mail->SMTPAuth = true; // enable SMTP authentication
$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
$mail->Port = 465; // set the SMTP port for the GMAIL server
//$mail->Username = "itsupervisor.cdip@gmail.com"; // GMAIL username
//$mail->Password = "p6olbdhk"; // GMAIL password
$mail->From = "itsupervisor.cdip@gmail.com"; // "name@yourdomain.com";
//$mail->AddReplyTo = "support@thaicreate.com"; // Reply
$mail->FromName = "Arachaporn";  // set from Name
$mail->Subject = "Test sending mail."; 
$mail->Body = "My Body & <b>My Description</b>";

$mail->AddAddress("itsupervisor.cdip@gmail.com", "Arachaporn"); // to Address
		
$mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low

$mail->Send();
?>