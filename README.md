# SMTPMailer
A simple, secure SMTP Client library written in PHP, only supports TLS, SSL

## Install

Via Composer

``` bash
$ composer require smptmailer/smtpmailer
```

## Usage

``` php
$mail = new SMTPMailer\SMTPMailer([
    'host'      => 'smtp.gmail.com',
    'port'      => 587,
    'secure'    => 'tls',
    'username'  => '',
    'password'  => ''
]);
$mail->addTo('name@domain.com');
$mail->subject = 'Hello';
$mail->body = "Hello World!";
$mail->send();
```

## Security

If you discover any security related issues, please email dilongfa@gmail.com instead of using the issue tracker.

## Credits

- [DiLong Fa][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-packagist]: https://packagist.org/packages/smtpmailer/smtpmailer
[link-downloads]: https://packagist.org/packages/smtpmailer/smtpmailer
[link-author]: https://github.com/dilongfa


