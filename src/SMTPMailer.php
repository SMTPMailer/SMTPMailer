<?php

namespace SMTPMailer;

class SMTPMailer
{
    const NL = "\r\n";

    protected $host = 'smtp.gmail.com';
    protected $port = 587;
    protected $secure = 'tls';
    protected $username = '';
    protected $password = '';

    protected $from = [];
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $replyTo = [];
    protected $recipients = [];

    public $files     = [];
    public $charset  = 'utf-8';
    public $encoding = '8bit';
    public $subject = '';
    public $body = '';
    public $text = '';

    protected $connection = null;
    protected $timeout = 30;
    
    public $hostname = '';
    public $debug = false;
    
    /**
     * Usage: SMTPMailer([
     *  'host'  => 'localhost', 
     *  'port'  => '25', 
     *  'secure'  => '', 
     *  'username'  => '', 
     *  'password'  => '', 
     * ]);
     */

    public function __construct($configs = [])
    {
        extract($configs);
        
        if ($host) {
            $this->host = preg_replace('#^[\w]+://#', '', $host); //Remove protocol
        }

        if ($port) {
            $this->port = (int) $port;
        }

        if ($secure) {
            $this->secure = strtolower($secure);
        }

        if ($this->secure === 'ssl') {
            $this->host = $this->secure . '://' . $this->host;
        }

        if ($username) {
            $this->username = $username;
        }

        if ($password) {
            $this->password = $password;
        }
        
        if (empty($this->hostname)) {
            $this->hostname = gethostname();
        }
    }

    public function setFrom($address, $name = '') 
    {
        if ($address = $this->valid($address)) {
            $this->from = [$address, static::stripnl($name)];
        }
    }

    public function addReplyTo($address, $name = '')
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->replyTo[$address])) {
                $this->replyTo[$address] = [$address, static::stripnl($name)];
            }
        }
    }

    public function addTo($address, $name = '') 
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->to[] = [$address, static::stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function addCc($address, $name = '') 
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->cc[] = [$address, static::stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function addBcc($address, $name = '') 
    {
        if ($address = $this->valid($address)) {
            if(!isset($this->recipients[$address])) {
                $this->bcc[] = [$address, static::stripnl($name)];
                $this->recipients[$address] = true;
            }
        }
    }

    public function send() 
    { 
        if (empty($this->from)) {
            $this->from = [$this->username, ''];
        }

        try {
            $this->check();
            $this->connect();
            $this->auth();

            $this->request('MAIL FROM: <'.$this->from[0].'>', 250);
            $addresses = array_keys($this->recipients);
            foreach($addresses as $address) {
                $this->request('RCPT TO: '.$address, 250);
            }

            $message = $this->createHeader() . $this->createContent();
            $this->request('DATA', 354);
            $this->request($message, 250);
            $this->request('QUIT', 221);
            $this->close();
            return true;
        } catch(Exception $e) {
            $this->debug($e->getMessage());
            $this->close();
            return false;
        }

        return true;
    }

    private function createHeader()
    {
        $headers[] = 'Date: '. date('r');
        $headers[] = 'To: '.$this->concatAddress($this->to);
        $headers[] = 'From: '.$this->formatAddress($this->from);
        
        if (!empty($this->cc)) {
            $headers[] = 'Cc: '.$this->concatAddress($this->cc);
        }

        if (!empty($this->bcc)) {
            $headers[] = 'Bcc: '.$this->concatAddress($this->bcc);
        }

        if (!empty($this->replyTo)) {
            $headers[] = 'Reply-To: '.$this->concatAddress($this->replyTo);
        }

        $headers[] = iconv_mime_encode('Subject', $this->subject);
        $headers[] = 'Message-ID: '. $this->generateMessageID();
        $headers[] = 'X-Mailer: SMTPMailer v1.0.0 https://github.com/smtpmailer/smtpmailer';
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = ''; //for last breakline
        
        return implode(static::NL, $headers);
    }

    private function createContent()
    {
        $boundary = md5(uniqid());
        $contents = [];

        if (empty($this->text)) {
            $this->text = $this->html2text($this->body);
        }

        $contents[] = 'Content-Type: multipart/'. (empty($this->files) ? 'alternative' : 'mixed') .'; boundary="'.$boundary.'"';
        $contents[] = '';
        $contents[] = 'This is a multi-part message in MIME format.';
        $contents[] = '--'. $boundary;

        $contents[] = $this->formatContent('plain', 'text');
        $contents[] = '--'. $boundary;

        $contents[] = $this->formatContent('html', 'body');
        $contents[] = '--'. $boundary;

        if (!empty($this->files)) {
            $contents[] = $this->createAttachment($boundary);
        }

        $contents[count($contents)-1] .= '--';
        $contents[] = ".";
        
        return implode(static::NL, $contents);
    }

    private function formatContent($type, $content)
    {
        $contents[] = 'Content-Type: text/'.$type.'; charset="'.$this->charset.'"';
        $contents[] = 'Content-Transfer-Encoding: '. $this->encoding;
        $contents[] = '';
        $contents[] = ($this->encoding == 'quoted-printable') ? quoted_printable_encode($this->$content) : $this->$content;
        return implode(static::NL, $contents);
    }

    private function createAttachment($boundary) 
    {
        $contents = [];
        foreach ($this->files as $file) {
            if (file_exists($file)) {
                $contents[] = 'Content-Type: application/octet-stream; '.'name="'. basename($file) .'"';
                $contents[] = 'Content-Transfer-Encoding: base64';
                $contents[] = 'Content-Disposition: attachment';
                $contents[] = '';
                $contents[] = chunk_split(base64_encode(file_get_contents($file)));
                $contents[] = '--'.$boundary;
            }
        }  
        return implode(static::NL, $contents);
    }

    protected function check() 
    {
        if ($this->secure !== 'ssl' && $this->secure !== 'tls') {
            throw new Exception('ERROR: Only supports SSL/TLS protocol');
        }

        if ($this->port !== 465 && $this->port !== 587) {
            throw new Exception('ERROR: Only supports 465/587 ports');
        }

        if (empty($this->username) || empty($this->password)) {
            throw new Exception('ERROR: We need username and password for: '. $this->host);
        }

        if (empty($this->to)) {
            throw new Exception('ERROR: We need a valid email address to send to');
        }

        if (strlen(trim($this->body)) < 3) {
            throw new Exception('ERROR: Message body empty');
        }
    }

    public function connect() 
    {
        $this->debug("Connecting to {$this->host}:{$this->port}");

        $this->connection = @stream_socket_client($this->host . ':' . $this->port, $errno, $errstr, $this->timeout);
        
        if (!$this->connection) {
            extract(error_get_last());
            throw new Exception("ERROR: $message");
        }

        $this->debug('Connecting successfully');

        $this->response(220);

        if ($this->secure === 'tls') {
            $this->request('STARTTLS', 220);
            stream_socket_enable_crypto($this->connection, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
        }

        $this->request('EHLO '.$this->hostname, 250);

        return true;
    }

    protected function auth() 
    {
        $this->request('AUTH LOGIN', 334);
        $this->request(base64_encode($this->username), 334);
        $this->request(base64_encode($this->password), 235);
        return true;
    }

    protected function request($cmd, $code) 
    {
        $this->debug('REQUEST: ' . $cmd);
        fwrite($this->connection, $cmd . static::NL); 
        return $this->response($code);
    }

    protected function response($code) 
    {
        stream_set_timeout($this->connection, $this->timeout);
        $result = fread($this->connection, 768);

        $meta = stream_get_meta_data($this->connection);
        if ($meta['timed_out']) {
            throw new Exception('ERROR: Stream socket timed-out (' . $this->timeout . 's)');
        }

        if (substr($result, 0, 3) != $code) {
            throw new Exception($result);
        }
        
        $this->debug("RESPONSE: " . $result);

        return true;
    }

    protected function close() 
    {
        if ($this->connection) {
            fclose($this->connection);
            $this->connection = null; 
            $this->debug('Connection: closed');
        }
    }

    protected function debug($str)
    {
        if (!$this->debug) {
            return;
        }
        $str = preg_replace('/\r\n|\r/ms', "\n", $str);
        echo "[", date('Y-m-d H:i:s'), "] ", trim(str_replace("\n","\n" . str_repeat("\t", 4), trim($str))), "\n";
    }

    protected function valid($address)
    {
        $address = strtolower($address);
        $address = filter_var($address, FILTER_SANITIZE_EMAIL);
        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $this->debug('Invalid address: '. $address);
            return false;
        }
        return $address;
    }

    public function setTimeout($seconds = 30) 
    {
        $this->timeout = (int) $seconds;
    } 

    public function getTimeout() 
    {
        return $this->timeout;
    } 

    public static function stripnl($str)
    {
        return trim(str_replace(["\r", "\n"], '', $str));
    }

    private function formatAddress($address)
    {
        if ('' === $address[1]) {
            return $address[0];
        }
        return '"'. preg_replace('#^:\s+#', '', iconv_mime_encode('', $address[1])) .'" <'.$address[0].'>';
        // return '"'. $address[1] .'" <'.$address[0].'>';
    }

    private function concatAddress($addresses)
    {
        $list = [];
        foreach ($addresses as $address) {
            $list[] = $this->formatAddress($address);
        }
        return implode(', ', $list);
    }

    private function generateMessageId()
    {
        return sprintf("<%s.%s@%s>", base_convert(microtime(), 10, 36), base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36), $this->hostname);
    }

    public function html2text($html)
    {
        $html = html_entity_decode($html, ENT_QUOTES, $this->charset);
        return trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/si', '', $html)));
    }
}
