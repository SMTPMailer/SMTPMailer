<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$mail = new Mailer;
$mail->setFrom('sender@example.com', 'Sender Name');
$mail->setTo('recipient@example.com', 'Recipient Name');
$mail->setSubject('Subject...');
$mail->setBody('Body...');

$mail->addFile('files/ha_long_bay.jpg');
$mail->addFile('files/ba_na_hills.jpg');
$mail->addFile('files/son_doong_cave.png');

if ($mail->send()) {
    echo "Sent successfully";
} else {
    echo "Sent failed";
}
