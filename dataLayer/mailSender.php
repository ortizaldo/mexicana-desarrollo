<?php require 'libs/PHPMailer-master/PHPMailerAutoload.php';
class MailSender{

    var $Host = 'smtp.sendgrid.net';
    var $Port = 587;
    var $SMTPSecure = 'tls';
    var $SMTPAuth = true;
    var $Username = 'jnixtest';
    var $Password = 'L4mb0rghin1.7!';

    //var $mailToSend;
    //var $to;

    function __construct(){}

    private function initMail()
    {
        $mail = new PHPMailer;
        $mail->isSMTP();

        //$mail->SMTPDebug = 2;
        //$mail->Debugoutput = 'html';
        $mail->Host = $this->Host;
        $mail->Port = $this->Port;
        $mail->SMTPSecure = $this->SMTPSecure;
        $mail->SMTPAuth = $this->SMTPAuth;
        $mail->Username = $this->Username;
        $mail->Password = $this->Password;

        return $mail;
    }

    public function sendMail()
    {

    }

    public function recoveryPasswordMail($mailToSend, $to)
    {
        /*$x = 7; // Amount of digits
        $min = pow(1,$x);
        $max = pow(7,($x+1)-1);
        $activationToken = rand($min, $max);*/
        $activationToken = generateRandomString(25);

        $sendEmail = $this->initMail();
        $sendEmail->setFrom('giovanni_delgado@migesa.com.mx', 'Mailer');
        $sendEmail->addAddress($mailToSend, $to);
        //$sendEmail->addAddress('giovanni_delgado@migesa.com.mx', $to);
        $sendEmail->addReplyTo('giovanni_delgado@migesa.com.mx', 'Information');

        $sendEmail->isHTML(true);                                  // Set email format to HTML

        $sendEmail->Subject = 'Informacion para recuperar cuenta';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

        //cambiar a GET
        $message = "<p>Ve a la siguientedirecci&oacute;n: <a href='http://siscomcmg.com:8080/newPass.php?activation=".$activationToken."'>Restablecer contrase&ntilde;a</a></p>";

        $sendEmail->Body    = $message;
        $sendEmail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $sendEmail->addAttachment('assets/img/logoMexicana.png');

        if(!$sendEmail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $sendEmail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }
    }
}