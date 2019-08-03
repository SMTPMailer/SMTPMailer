<?php

require '../vendor/autoload.php';

use SMTPMailer\Mailer;

$error = '';
$msg = '';
if (!empty($_POST)) {
    $subject = $_POST['subject'] ?? 'Unknown subject';
    $question = $_POST['question'] ?? '';
    $name = $_POST['name'] ?? 'Contact form';
    $to = $_POST['to'] . '@example.com';
    $email = $_POST['email'] ?? '';

    if (empty($question)) {
        $error = 'Error: Please enter your question';
    }
    
    if (empty($email)) {
        $error = 'Error: Please enter your email';
    }
   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Error: Invalid your email';
    }
    
    if (!empty($error)) {
        $mail = new Mailer;
        $mail->setFrom('contact@example.com', $name);
        $mail->addReplyTo($email, $name);
        $mail->setTo($to);
        $mail->setSubject($subject);
        $mail->setBody($question);

        if ($mail->send()) {
            $msg = "Message sent successfully";
        } else {
            $msg = "Message sent failed";
        }
    }
} 
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Contact Form</title>
</head>
<body>
<h1>Contact us</h1>
<?php 
    if (!empty($msg)) {
        echo $msg;
    } else {
        if (!empty($error)) {
            echo $error ."<br>";
        }
?>
    <form method="post">
        <label for="to">Send to:</label>
        <select name="to" id="to">
            <option value="sales">Sales</option>
            <option value="support" selected="selected">Support</option>
            <option value="accounts">Accounts</option>
        </select><br>
        <label for="subject">Subject: <input type="text" name="subject"></label><br>
        <label for="name">Your name: <input type="text" name="name"></label><br>
        <label for="email">Your email: <input type="email" name="email"></label><br>
        <label for="question">Your question:</label><br>
        <textarea name="question" id="question" placeholder="Your question" cols="30" rows="8"></textarea><br>
        <input type="submit" value="Submit">
    </form>
<?php } ?>
</body>
</html>
