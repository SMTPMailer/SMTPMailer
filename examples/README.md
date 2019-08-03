# SMTPMailer examples

This folder contains a collection of examples of using [SMTPMailer](https://github.com/SMTPMailer/SMTPMailer).

## About testing email sending

Here are some free services and software that help you set up a fake SMTP server for testing email sending:

* [Ethereal](https://ethereal.email/), Ethereal is a fake SMTP service, mostly aimed at Nodemailer users (but not limited to). It's a completely free anti-transactional email service where messages never get delivered. (**Recommended**)
* [Mailtrap](https://mailtrap.io), Mailtrap is a solution that allows testing email notifications without sending them to the real users of your application. 
* [OrigamiSMTP](https://github.com/travispessetto/OrigamiSMTP), Origami SMTP is a fake SMTP server with SSL (STARTTLS) support.
* [Mailslurper](https://github.com/mailslurper/mailslurper), MailSlurper is a small SMTP mail server that slurps mail into oblivion.
* [PaperCut](https://github.com/changemakerstudios/papercut), Papercut is a 2-in-1 quick email viewer AND built-in SMTP server (designed to receive messages only)
* [Fake SMTP Server](https://github.com/ReachFive/fake-smtp-server), Fake SMTP Server is an email testing tool for QA & development teams.
* [smtp4dev](https://github.com/rnwood/smtp4dev), A dummy SMTP server for Windows, Linux, Mac OS-X (and maybe elsewhere where .NET Core is available).

## Notes
Most of these examples use the `example.com` and `example.net` domains. These domains are reserved by IANA for illustrative purposes, as documented in [RFC 2606](http://tools.ietf.org/html/rfc2606). Don't use made-up domains like 'mydomain.com' or 'somedomain.com' in examples as someone, somewhere, probably owns them!
