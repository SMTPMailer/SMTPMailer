<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$configs = include("smtp_configs.php");

$mail = new Mailer($configs['host'], $configs['port'], $configs['secure']);
$mail->setAuth($configs['username'], $configs['password']);

$mail->setFrom('sender@example.com', 'Sender Name');
$mail->setTo('recipient@example.com', 'Recipient Name');
$mail->setSubject('Subject...');
$mail->setBody('Body...');

if ($mail->send()) {
    echo "Sent successfully";
} else {
    echo "Sent failed";
}
