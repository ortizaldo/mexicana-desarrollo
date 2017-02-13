<?php
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 23/06/2016
 * Time: 11:29 AM
 */

/*TODO TEMPORALMENTE DE PRUEBA CON LA CUENTA DE GABRIEL, YA EN LA LIBERACION SE USARA LA DE MIGESA DE PAGA***/
/*$APP_KEY_APPCELERATOR = "q91fqrvdM8SqfZQTPB5wMjCbbKGChC6H";
$ADMIN_USER_APPCELERATOR = "gabriel_sifuentes@migesa.com.mx";
$ADMIN_PASSWORD_APPCELERATOR = "mexgas";*/

//$APP_KEY_APPCELERATOR = "q91fqrvdM8SqfZQTPB5wMjCbbKGChC6H";
//$ADMIN_USER_APPCELERATOR = "gabrielsifuentes@hotmail.com";
//$ADMIN_PASSWORD_APPCELERATOR = "Eliyeshua1";

$APP_KEY_APPCELERATOR = "xYBixDUXSXzjiQgYQynxWHKbQUzus8Ii";
//$ADMIN_USER_APPCELERATOR = "devadmin@migesa.com.mx";
$ADMIN_USER_APPCELERATOR = "appc_app_user_dev";
$ADMIN_PASSWORD_APPCELERATOR = "migesa2016";

/*
ACS Push Notification Web Service
By: Ricardo Alcocer (@ricardoalcocer)
Description:
A simple reusable PHP Script to send ACS Push Notifications

Original code by @patrickjongmans
posted at http://developer.appcelerator.com/question/140589/how-to-send-push-notifiaction-to-android-using-php-controled-acs-#254798
*/
/*** SETUP ***************************************************/
if (isset($_POST["to_ids"]) && isset($_POST["channel"]) && isset($_POST["title"]) && isset($_POST["message"])) {

    $key = $APP_KEY_APPCELERATOR;
    $username = $ADMIN_USER_APPCELERATOR;
    $password = $ADMIN_PASSWORD_APPCELERATOR;
    $to_ids = $_POST["to_ids"];
    $channel = $_POST["channel"];
    $message = $_POST["message"];
    $title = $_POST["title"];
    $imprimirResponse = $_POST["imprimirResponse"];

    $tmp_fname = 'cookie.txt';
    $json = '{"alert":"' . $message . '","title":"' . $title . '","vibrate":true,"sound":"default","icon":"appicon"}';

    if (!is_null($key) && !is_null($username) && !is_null($password) && !is_null($channel) && !is_null($message) && !is_null($title)) {
        /*** PUSH NOTIFICATION ***********************************/
        $post_array = array('login' => $username, 'password' => $password);

        /*** INIT CURL *******************************************/
        $curlObj = curl_init();
        $c_opt = array(CURLOPT_URL => 'https://api.cloud.appcelerator.com/v1/users/login.json?key=' . $key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => "login=" . $username . "&password=" . $password,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_COOKIEJAR => realpath($tmp_fname),
            CURLOPT_COOKIEFILE, $tmp_fname,
            CURLOPT_TIMEOUT => 60);
        /*** LOGIN **********************************************/
        curl_setopt_array($curlObj, $c_opt);
        $session = curl_exec($curlObj);


        /*** SEND PUSH ******************************************/
        $c_opt[CURLOPT_URL] = "https://api.cloud.appcelerator.com/v1/push_notification/notify.json?key=" . $key;
        $c_opt[CURLOPT_POSTFIELDS] = "channel=" . $channel . "&payload=" . $json . "&to_ids=" . $to_ids;


        curl_setopt_array($curlObj, $c_opt);
        $session = curl_exec($curlObj);
        /*** THE END ********************************************/
        curl_close($curlObj);
        if ($imprimirResponse == 1) {
            header('Content-Type: application/json');
            die(json_encode(array('response' => json_decode($session))));
        }
    } else {
        if ($imprimirResponse == 1) {
            header('Content-Type: application/json');
            die(json_encode(array('response' => json_decode($session))));
        }
    }
} else {
    if ($imprimirResponse == 1) {
        header('Content-Type: application/json');
        echo json_encode(array('response' =>
            array(
                'code' => '500',
                'status' => 'Parametros incorrectos'
            )));
    }
}