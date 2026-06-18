<?php

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private string $smtp_hostname;
    private string $smtp_port;
    private string $smtp_username;
    private string $smtp_password;

    public function __construct(string $hostname, string $port, string $username, string $password)
    {
        $this->smtp_hostname = $hostname;
        $this->smtp_port = $port;
        $this->smtp_username = $username;
        $this->smtp_password = $password;
    }

    public function sendEmail(string $to, string $subject, string $body, bool $asHTML = true): void
    {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $this->smtp_hostname;
            $mail->Port = $this->smtp_port;
            $mail->Username = $this->smtp_username;
            $mail->Password = $this->smtp_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            $mail->setFrom($this->smtp_username, "Preguntados");
            $mail->addAddress($to);

            $mail->isHTML($asHTML);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $body;

            $mail->send();
            Log::info("[Mail] Sent email to " . $subject);
        } catch (Exception $e) {
            Log::error("[Mail] Failed to send email to " . $subject . " with error: " . $e);
        }
    }
}
