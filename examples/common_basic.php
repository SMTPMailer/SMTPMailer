<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$mail = new Mailer('smtp.example.com', 587, 'tls');
$mail->setAuth('username@example.com', 'password');
$mail->setFrom('sender@example.com', 'Sender Name');
$mail->setTo('recipient@example.com', 'Recipient Name');
$mail->setSubject('Subject...');
$mail->setBody('Body...');

if ($mail->send()) {
    echo "Sent successfully";
} else {
    echo "Sent failed";
}
