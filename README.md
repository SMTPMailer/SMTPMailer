# SMTPMailer
A simple, lightweight and secure SMTP Client library written in PHP, only supports TLS, SSL

## Install

Via Composer

``` bash
$ composer require smptmailer/smtpmailer
```

## Usage
``` php
use SMTPMailer\Mailer;

$mail = new Mailer([
    'host'      => 'smtp.example.com'
    'port'      => 587
    'secure'    => 'tls'
    'username'  => 'user@example.com'
    'password'  => 'password'
]);

if ($mail->send('recipient@example.com', 'Subject...', 'Body...')) {
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

[link-packagist]: https://packagist.org/packages/smtpmailer/smtpmailer
[link-downloads]: https://packagist.org/packages/smtpmailer/smtpmailer
[link-author]: https://github.com/dilongfa
