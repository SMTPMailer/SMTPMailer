<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$msg = '';
if (isset($_FILES['file'])) {

    $uploadfile = tempnam(sys_get_temp_dir(), md5($_FILES['file']['name']));
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        $mail = new Mailer;
        
        $mail->setFrom('sender@example.com', 'Sender Name');
        $mail->setTo('recipient@example.com', 'Recipient Name');
        $mail->setSubject('Subject...');
        $mail->setBody('Body...');

        $mail->addFile($uploadfile);

        if ($mail->send()) {
            $msg = "Sent successfully";
        } else {
            $msg = "Sent failed";
        }
    } else {
        $msg = 'Failed to move file to ' . $uploadfile;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SMTPMailer Upload</title>
</head>
<body>
<?php 
if (empty($msg)) { 
?>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="100000"> 
        Your file: <input name="file" type="file">
        <input type="submit" value="Send File">
    </form>
<?php 
} else {
    echo $msg;
} 
?>
</body>
</html>
