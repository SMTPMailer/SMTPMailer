<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$mail = new Mailer;

$mail->addHeader('X-Header-1', 'Value');
$mail->addHeader('X-Header-2', 'Value');

$mail->setFrom('sender@example.com', 'Sender Name');
$mail->setTo('recipient@example.com', 'Recipient Name');
$mail->setSubject('Subject...');
$mail->setBody('Body...');

if ($mail->send()) {
    echo "Sent successfully";
} else {
    echo "Sent failed";
}
