<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$mail = new Mailer;
$mail->setFrom('sender@example.com', 'Sender Name');

$mail->addReplyTo('sender@example.com', 'Sender Name');
$mail->addReplyTo('another@example.com', 'Another Name');

$mail->addTo('recipient1@example.com', 'Recipient Name 1');
$mail->addTo('recipient2@example.com', 'Recipient Name 2');

$mail->addCc('recipient3@example.com', 'Recipient Name 3');
$mail->addCc('recipient4@example.com', 'Recipient Name 4');

$mail->addBcc('recipient5@example.com', 'Recipient Name 5');
$mail->addBcc('recipient6@example.com', 'Recipient Name 6');

$mail->setSubject('Subject...');
$mail->setBody('Body...');

if ($mail->send()) {
    echo "Sent successfully";
} else {
    echo "Sent failed";
}
