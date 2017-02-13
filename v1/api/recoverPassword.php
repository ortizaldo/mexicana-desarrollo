<?php include_once dirname(dirname(dirname(__FILE__))).'/dataLayer/DAO.php';
include_once dirname(dirname(dirname(__FILE__))).'/dataLayer/mailSender.php';

if( isset($_POST["email"]) ) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    function generateRandomString($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $nickname=""; $email=""; $rol=""; $activationToken="";
    $inputEmail = $_POST["email"];
    $getUserNickname = $conn->prepare("SELECT `user`.`id`, `user`.`nickname`, `user`.`email` FROM `user` LEFT JOIN `user_rol` ON `user_rol`.`id` = `user`.`id` LEFT JOIN `rol` ON `rol`.`id` = `user_rol`.`id` AND `user_rol`.`id` = `user`.`id` WHERE `user`.`email` = ? AND `user`.`active` = 1;");
    $getUserNickname->bind_param('s', $inputEmail);

    if( $getUserNickname->execute() ) {
        $getUserNickname->store_result();
        $getUserNickname->bind_result($id, $nickname, $email);

        if( $getUserNickname->fetch() ) {

            //$activationToken = rand(5, 25);
            $activationToken = generateRandomString();
            $message = "http://siscomcmg.com:8080/newPass.php?activation=". $activationToken;
            //$response["status"] = "OK";
            //$response["code"] = "200";
            //$response["response"]="Email de Notificacion Enviado: ". $message;
            $response["response"] = $message;
            echo json_encode($response);
        } else {
            $response["status"]="ERROR";
            $response["code"]="404";
            $response["response"]="Usuario no Encontrado";
            echo json_encode($response);
        }
    }
} else {
    $response["status"]="ERROR";
    $response["code"]="404";
    $response["response"]="Introduce un Parametro de Busqueda";
    echo json_encode($response);
}
?>