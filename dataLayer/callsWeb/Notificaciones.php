<?php
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 20/06/2016
 * Time: 02:44 PM
 */

include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$ACTION_CONSULTAR_NOTIFICACIONES = "1";
$ACTION_ACTUALIZAR_TODAS_LAS_NOTIFICACIONES = "2";
if (isset($_POST['idUser']) && isset($_POST['action'])) {
    $idUser = $_POST['idUser'];
    $action = $_POST['action'];
    if ($action == $ACTION_CONSULTAR_NOTIFICACIONES) {
        consultarNotificacionesPorIdUsuario($conn, $idUser);
    } else if ($action == $ACTION_ACTUALIZAR_TODAS_LAS_NOTIFICACIONES) {
        actualizarTodasLasNotificacionesDelUsuario($conn, $idUser);
    } else {
        $responseJson["CodigoRespuesta"] = "0";
        $responseJson["MensajeRespuesta"] = "Datos mal formados";
        echo json_encode($responseJson);
    }
} else if (isset($_POST['idNotification'])) {
    $idNotification = $_POST['idNotification'];
    actualizarNotificacioneAVista($conn, $idNotification);
} else {
    $responseJson["CodigoRespuesta"] = "0";
    $responseJson["MensajeRespuesta"] = "Datos mal formados";
    echo json_encode($responseJson);
}


function consultarNotificacionesPorIdUsuario($conn, $idUser)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $idNotification = "";
    $message = "";
    $status = "";
    $timeMod = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtleerNotificacionesPorIdUsuario = $conn->prepare("call spLeerNotificacionesPorIdUsuario(?);");
    mysqli_stmt_bind_param($stmtleerNotificacionesPorIdUsuario, 'i', $idUser);
    if ($stmtleerNotificacionesPorIdUsuario->execute()) {
        $stmtleerNotificacionesPorIdUsuario->store_result();
        $stmtleerNotificacionesPorIdUsuario->bind_result($idNotification, $message, $status, $timeMod);

        $responseJson = array(
            'CodigoRespuesta' => '1',
            'MensajeRespuesta' => 'Existen mensajes por ver',
            'notifications' => array()
        );
        $arrayNotifications = array();
        while ($stmtleerNotificacionesPorIdUsuario->fetch()) {
            $arrayNotifications[] = array(
                'idNotification' => $idNotification,
                'message' => $message,
                'status' => $status,
                'timeMod' => $timeMod
            );
        }
        $responseJson['notifications'] = $arrayNotifications;
    } else {
        $responseJson["CodigoRespuesta"] = "0";
        $responseJson["MensajeRespuesta"] = "No se lograron obtener las notificaciones";
    }
    echo json_encode($responseJson);

    $stmtleerNotificacionesPorIdUsuario->close();
    $conn->close();
}

function actualizarNotificacioneAVista($conn, $idNotification)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtActualizarNotificacionAVista = $conn->prepare("call spActualizarNotificacionAVista(?);");
    mysqli_stmt_bind_param($stmtActualizarNotificacionAVista, 'i', $idNotification);
    if ($stmtActualizarNotificacionAVista->execute()) {
        $stmtActualizarNotificacionAVista->store_result();
        $stmtActualizarNotificacionAVista->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtActualizarNotificacionAVista->fetch()) {
            $responseJson = array(
                'CodigoRespuesta' => $CodigoRespuesta,
                'MensajeRespuesta' => $MensajeRespuesta
            );
        }
    } else {
        $responseJson["CodigoRespuesta"] = "0";
        $responseJson["MensajeRespuesta"] = "No se lograron actualizar la notificacion";
    }
    echo json_encode($responseJson);

    $stmtActualizarNotificacionAVista->close();
    $conn->close();
}

function actualizarTodasLasNotificacionesDelUsuario($conn, $idUser)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $CodigoRespuesta = "";
    $MensajeRespuesta = "";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtActualizarTodasLasNotificacionesAVistas = $conn->prepare("call spActualizarTodasLasNotificacionesAVistas(?);");
    mysqli_stmt_bind_param($stmtActualizarTodasLasNotificacionesAVistas, 'i', $idUser);
    if ($stmtActualizarTodasLasNotificacionesAVistas->execute()) {
        $stmtActualizarTodasLasNotificacionesAVistas->store_result();
        $stmtActualizarTodasLasNotificacionesAVistas->bind_result($CodigoRespuesta, $MensajeRespuesta);
        if ($stmtActualizarTodasLasNotificacionesAVistas->fetch()) {
            $responseJson = array(
                'CodigoRespuesta' => $CodigoRespuesta,
                'MensajeRespuesta' => $MensajeRespuesta
            );
        }
    } else {
        $responseJson["CodigoRespuesta"] = "0";
        $responseJson["MensajeRespuesta"] = "No se lograron actualizar la notificacion";
    }
    echo json_encode($responseJson);

    $stmtActualizarTodasLasNotificacionesAVistas->close();
    $conn->close();
}


