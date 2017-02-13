<?php
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 21/06/2016
 * Time: 10:17 AM
 */

if (isset($_POST["action"])) {
    $action = $_POST["action"];

    /**CLAVES DEL PROYECTO MEXICANA DE GAS QUE ESTAN EN LA CONSOLA DE URBANAIRSHIP
     * https://go.urbanairship.com/apps/9dCK4uUtRmqzBs-aqYx1ig/api/ ****/
    $APP_KEY = "9dCK4uUtRmqzBs-aqYx1ig";
    $APP_SECRET = "YbmWFWH9SV6YiKvpgdQJAw";
    $APP_MASTER_SECRET = "JF3xwq7URMeNvotSNXHhIw";
    $URL_URBANARISHIP = "https://go.urbanairship.com/api/push/";

    define('APPKEY', $APP_KEY); // Your App Key
    define('PUSHSECRET', $APP_MASTER_SECRET); // Your Master Secret
    define('PUSHURL', $URL_URBANAIRSHIP);

    /**DICCIONARIO DE EVENTOS ENVIO **/
    $ENVIAR_PUSH_NOTIFICATION = "EP";

    if ($acttion == $ENVIAR_PUSH_NOTIFICATION) {
        enviarPushNotificationAlMovil($tokenDevice, $message);
    }
} else {
    $responseJson = array(
        "CodigoRespuesta" => 0,
        "MensajeRespuesta" => "NO AUTHORIZED!!!",
    );
    echo json_encode($responseJson);
}

/**VISTO EN STACKOVERFLOW
 * http://stackoverflow.com/a/21338331 ***/
function enviarPushNotificationAlMovil($tokenDevice, $message)
{
    $contents = array();
    $contents['alert'] = $message;
    $notification = array();
    $notification['android'] = $contents;
    /**SE PUEDEN AGREGAR MAS PLATAFORMAS EN EL ARREGLO DE ENVIO**/
    $platform = array();
    array_push($platform, "android");

    $push = array("audience" => "all", "notification" => $notification, "device_types" => $platform);

    $json = json_encode($push);
    echo "Payload: " . $json . "\n"; //show the payload

    $session = curl_init(PUSHURL);
    curl_setopt($session, CURLOPT_USERPWD, APPKEY . ':' . PUSHSECRET);
    curl_setopt($session, CURLOPT_POST, True);
    curl_setopt($session, CURLOPT_POSTFIELDS, $json);
    curl_setopt($session, CURLOPT_HEADER, False);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/vnd.urbanairship+json; version=3;'));
    $content = curl_exec($session);
    echo "Response: " . $content . "\n";

    // Check if any error occured
    $response = curl_getinfo($session);
    if ($response['http_code'] != 202) {
        echo "Got negative response from server: " . $response['http_code'] . "\n";
    } else {

        echo "Wow, it worked!\n";
    }

    curl_close($session);
}