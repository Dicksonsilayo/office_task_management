<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

class Mailer
{
    public function send($to, $subject, $message)
    {
        $mail = new PHPMailer(true);

        try {

            /*
            |--------------------------------------------------------------------------
            | SMTP CONFIG
            |--------------------------------------------------------------------------
            */

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username = 'yourgmail@gmail.com';

            $mail->Password = 'YOUR_APP_PASSWORD';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            /*
            |--------------------------------------------------------------------------
            | EMAIL INFO
            |--------------------------------------------------------------------------
            */

            $mail->setFrom(
                'yourgmail@gmail.com',
                'OVTMS System'
            );

            $mail->addAddress($to);

            /*
            |--------------------------------------------------------------------------
            | CONTENT
            |--------------------------------------------------------------------------
            */

            $mail->isHTML(true);

            $mail->Subject = $subject;

            $mail->Body = $message;

            /*
            |--------------------------------------------------------------------------
            | SEND
            |--------------------------------------------------------------------------
            */

            return $mail->send();

        } catch (Exception $e) {

            error_log($mail->ErrorInfo);

            return false;
        }
    }
}