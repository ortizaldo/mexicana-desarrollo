<?php include_once 'dataLayer/DAO.php';
include_once 'dataLayer/libs/utils.php';
//include_once 'dataLayer/libs/mailer/';
require 'dataLayer/libs/PHPMailer-master/PHPMailerAutoload.php';

session_start();

if( isset($_SESSION["nickname"]) ) {
    movePage(200, "index.php");
} else {
    if( isset($_POST["email"]) ) {
        $DB = new DAO();
        $conn = $DB->getConnect();

        $nickname="";
        $email="";
        $rol="";
        $activationToken="";

        $x = 7; // Amount of digits
        $min = pow(1,$x);
        $max = pow(7,($x+1)-1);
        $activationToken = rand($min, $max);

        $inputEmail = $_POST["email"];

        $getUserNickname = $conn->prepare("SELECT user.id, user.nickname, user.email, rol.type FROM user LEFT JOIN user_rol ON user_rol.id = user.id LEFT JOIN rol ON rol.id = user_rol.id AND user_rol.id = user.id WHERE user.email = ? OR user.nickname = ? AND user.active = 1");
        $getUserNickname->bind_param('ss', $inputEmail, $inputEmail);

        if($getUserNickname->execute()) {
            $getUserNickname->store_result();
            $getUserNickname->bind_result($id, $nickname, $email, $rol);
            if($getUserNickname->fetch()) {
                if( isset($email) ) {

                    $setActivation = $conn->prepare("UPDATE user SET user.activation_token = ? WHERE id = ?");
                    $setActivation->bind_param('si', $activationToken, $id);

                    if($setActivation->execute()) {
                        $mail = new PHPMailer;
                        $mail->isSMTP();

                        //$mail->SMTPDebug = 2;
                        //$mail->Debugoutput = 'html';
                        $mail->Host = 'smtp.sendgrid.net';
                        $mail->Port = 587;
                        $mail->SMTPSecure = 'tls';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'jnixtest';
                        $mail->Password = 'L4mb0rghin1.7!';

                        //$mail->setFrom('giovanni_delgado@migesa.com.mx', 'Mailer');
                        $mail->addAddress($email, $nickname);
                        $mail->addAddress("giovanniyatze@gmail.com", $nickname);
                        $mail->addReplyTo('giovanni_delgado@migesa.com.mx', 'Information');

                        $mail->isHTML(true);                                  // Set email format to HTML


                        $mail->Subject = utf8_decode('Información para reestablecer contraseña');
                        //Read an HTML message body from an external file, convert referenced images to embedded,
                        //convert HTML into a basic plain-text alternative body
                        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

                        //cambiar a GET
                        $message = "<p>Favor de reestablecer tu contrase&ntilde;a mediante el siguiente enlace: <a href='http://siscomcmg.com:8080/newPass.php?activation=".$activationToken."'>Restablecer contrase&ntilde;a</a></p>";

                        $mail->Body    = $message;
                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                        $mail->addAttachment('assets/img/logoMexicana.png');

                        if(!$mail->send()) {
                            echo 'Message could not be sent.';
                            echo 'Mailer Error: ' . $mail->ErrorInfo;
                        } else {
                            echo 'Message has been sent';
                        }
                    }
                }
            } else {
                printf("Comment statement error: %s\n", $getUserNickname->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin">
    <meta name="keywords" content="admin dashboard, admin, flat, flat ui, ui kit, app, web app, responsive">
    <link rel="shortcut icon" href="img/ico/favicon.png">
    <title>Mexicana de Gas - Olvid&eacute; mi contrase&ntilde;a</title>

    <!-- Base Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/ico/favicon.png">

    <!-- Base Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="assets/js/html5shiv.min.js"></script>
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-body">
<div class="login-logo">
    <img src="assets/img/logoMexicana.png" alt="Logotipo de la empresa"/>
</div>
<div class="container log-row">
    <form class="form-signin" method="POST" action='<?=$_SERVER['PHP_SELF']?>'>
        <div class="login-wrap">
            <center><p>&nbsp;Introduce tu usuario de acceso o correo electrónico para mandarte las instrucciones que debes seguir y recuperar tu cuenta</p></center>
            <input type="text" class="form-control" name="email" placeholder="Usuario de acceso o Correo electr&oacute;nico" autofocus required="required">
            <button class="btn btn-lg btn-success btn-block" type="submit">Confirmar</button>
            <button class="btn btn-lg btn-danger btn-block" type="button" id="btnCancel">Cancelar</button>
        </div>
    </form>
</div>
<!--jquery-1.10.2.min-->
<script src="assets/js/jquery-1.11.1.min.js"></script>
<!--Bootstrap Js-->
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/respond.min.js"></script>

<script type="text/javascript">
    $(document).on('click', '#btnCancel', function () {
        console.log("Cancel clicked");
        window.location = "login.php";
    });
</script>
</body>
</html>