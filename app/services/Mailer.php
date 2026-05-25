<?php

class Mailer
{
    private $from = "no-reply@ovtms.com";

    public function send($to, $subject, $message)
    {
        $headers  = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: OVTMS <{$this->from}>" . "\r\n";

        return mail($to, $subject, $message, $headers);
    }
}