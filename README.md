# SMTPMailer
A simple, lightweight and secure SMTP Client library written in PHP, only supports TLS, SSL

## Install

Via Composer

``` bash
$ composer require dilongfa/smtpmailer
```

## Usage
``` php
<?php

$mail = new SMTPMailer\Mailer('smtp.example.com', 587, 'tls');
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
```
## Examples
[Click here](examples)

## Security
If you discover any security related issues, please email dilongfa@gmail.com instead of using the issue tracker.

## Credits
- [DiLong Fa][link-author]

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/dilongfa/smtpmailer
[link-downloads]: https://packagist.org/packages/dilongfa/smtpmailer
[link-author]: https://github.com/dilongfa
