<?php
require_once "../../../tools/phpmailer/class.phpmailer.php";

$mail 		= new PHPMailer(); // defaults to using php "mail()"
$body 		= $_POST['message'];
$subject 	= $_POST['subject'];

$mail->SetFrom('no-reply@'.DOMAIN, $db->checkSettings('site-name'));
$mail->AddReplyTo('no-reply@'.DOMAIN, $db->checkSettings('site-name'));

$mail->AddAddress($_POST['email'], $_POST['email']);

$mail->Subject = $subject;
$mail->AltBody = strip_tags($message);
$mail->MsgHTML($body);

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
