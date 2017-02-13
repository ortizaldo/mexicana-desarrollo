<?php 
include_once "../DAO.php";
require_once('../libs/nusoap_lib/nusoap.php');
session_start();

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['idReporte']) && $_POST['form']) 
{
    $formNumber = $_POST['form']; 
    $formNumber = intval($formNumber); 
    $nickName = $_POST['id'];
    $reason = isset($_POST['reasons']) ? $_POST['reasons'] : array();
    
    $trustedHome = $_POST['trustedHome']; 
    $requestImage = $_POST['requestImage'];
    $privacyAdvice = $_POST['privacyAdvice']; 
    $identificationImage = $_POST['identificationImage'];
    $payerImage = $_POST['payerImage']; 
    $agreegmentImage = $_POST['agreegmentImage'];
    $financieraValidate = $_POST['financieraValidate'];
    $estatusVenta = $_POST["validacionEstatus"];
    $idReporte = (int) $_POST["idReporte"];
    $idEstatusContrato = 9;
    
    $trustedHome = ($trustedHome == "true") ? 1 : 0;
    $requestImage = ($requestImage == "true") ? 1 : 0;
    $privacyAdvice = ($privacyAdvice == "true") ? 1 : 0;
    $identificationImage = ($identificationImage == "true") ? 1 : 0;
    $payerImage = ($payerImage == "true") ? 1 : 0;
    $agreegmentImage = ($agreegmentImage == "true") ? 1 : 0;
    $financieraValidate = ($financieraValidate == "true") ? 1 : 0;
    
    $validacion = 0;
    // REBISAMOS SI LA VALIDACION SE CUMPLIO, TIENEN QUE ESTAR TODOS LOS CHECK SELECCIONADOS
    if($trustedHome && $requestImage  && $privacyAdvice && $identificationImage && $payerImage && $agreegmentImage && $financieraValidate)
    {
        $validacion = 1;
    }
    
    $ip_usr_id = NULL;
    $searchIdEmployee = $conn->prepare("SELECT id FROM user WHERE nickname = ?;");
    $searchIdEmployee->bind_param("s", $nickName);
    if ($searchIdEmployee->execute()) {
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($ip_usr_id);
        if ($searchIdEmployee->fetch()) {
            $ip_usr_id;
        }
    }
    
    // VALIDAMOS SII EXISTE UNA VENTA CON ESTE ID 
    $getFormSell = $conn->prepare("SELECT fs.id,fs.financialService,rh.idUserAssigned FROM report AS r INNER JOIN reportHistory AS rh ON r.id = rh.idReport INNER JOIN form_sells  AS fs ON rh.idFormSell = fs.id WHERE rh.idReportType = 2  AND r.id = ?;");
    $getFormSell->bind_param("i", $idReporte);
    if ($getFormSell->execute()) 
    {
        $getFormSell->store_result();
        $getFormSell->bind_result($idForm, $financialService, $idUserAdigned);
        
        if ($getFormSell->fetch()) 
        {
            $getFormSellValidation = $conn->prepare("SELECT id FROM form_sells_validation WHERE idFormSell = ?;");
            $getFormSellValidation->bind_param("i", $formNumber); 
            $getFormSellValidation->execute();
            
            $getFormSellValidation->store_result();
            $getFormSellValidation->bind_result($idFormValidation);
            $getFormSellValidation->fetch();
                
            $idEmpleadoVenta = getEmpleado($idUserAdigned);
            if($idFormValidation > 0)
            {
                if($validacion == 1){
                    // entra cuando si existe una validacion previa y tiene que actualizar
                    updateFormSellValidation($idFormValidation, $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $financieraValidate, $validacion);
                    
                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = "";
                    $response["validacion"] = $validacion;
                    echo json_encode($response);
                }else{
                    if($validacion == 0 && isset($reason) && count($reason) > 0){
                        // entra cuando si existe una validacion previa y tiene que actualizar
                        updateFormSellValidation($idFormValidation, $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $financieraValidate, $validacion);

                        // si fue un rechazo se tiene que guardar en la tabla tEstatusContrato
                        updateEstatusContrato($idReporte, $nickName, $estatusVenta, $idEstatusContrato, $idEmpleadoVenta);
                        updateReportHistory($idReporte);
                        updateReporteVentas($idReporte);

                        eliminarMotivosRechazos($formNumber);
                        insertarMotivosRechazos($formNumber, $reason, $ip_usr_id);

                        $response["status"] = "OK";
                        $response["code"] = "200";
                        $response["response"] = "El proceso a sido rechazado";
                        $response["validacion"] = $validacion;
                        echo json_encode($response);
                    }else{
                        $response["status"] = "BAD";
                        $response["code"] = "500";
                        $response["response"] = "Debes seleccionar motivos de rechazo";
                        $response["validacion"] = $validacion;
                        echo json_encode($response);
                    }
                }
            }else{
                
                if($validacion == 1){
                    insertarFormSellValidation($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion, $formNumber);
                    
                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = "";
                    $response["validacion"] = $validacion;
                    echo json_encode($response);
                }else{
                    if($validacion == 0 && isset($reason) && count($reason) > 0){
                          // si no existe una validacion previa se inserta
                          insertarFormSellValidation($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion, $formNumber);
                          updateEstatusContrato($idReporte, $nickName, $estatusVenta, $idEstatusContrato, $idEmpleadoVenta);
                          updateReportHistory($idReporte);
                          updateReporteVentas($idReporte);

                          eliminarMotivosRechazos($formNumber);
                          insertarMotivosRechazos($formNumber, $reason, $ip_usr_id);

                          $response["status"] = "OK";
                          $response["code"] = "200";
                          $response["response"] = "El proceso a sido rechazado";
                          $response["validacion"] = $validacion;
                          echo json_encode($response);
                    }else{
                        $response["status"] = "BAD";
                        $response["code"] = "500";
                        $response["response"] = "Debes seleccionar motivos de rechazo";
                        $response["validacion"] = $validacion;
                        echo json_encode($response);
                    } 
                }
              
            }
            
        }else{
            $response["status"] = "BAD";
            $response["code"] = "500";
            $response["response"] = "No se encontro el reporte solcitado";
            $response["validacion"] = NULL;
            echo json_encode($response);
        }
    }else{
        $response["status"] = "BAD";
        $response["code"] = "500";
        $response["response"] = "No se encontro el reporte solcitado";
        echo json_encode($response);
    }
    
}else {
    $response["status"] = "BAD";
    $response["code"] = "500";
    $response["response"] = "Datos incompletos";
    echo json_encode($response);
}


/**
 * Metodo que se encarga de insertar los datos en form_sell_validation ingresando las banderas
 * 
 * @param int $trustedHome
 * @param int $requestImage
 * @param int $privacyAdvice
 * @param int $identificationImage
 * @param int $payerImage
 * @param int $agreegmentImage
 * @param int $validacion
 * @param int $formNumber
 * @return boolean 
 */
function insertarFormSellValidation($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion, $formNumber)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $bandera = NULL;
    $imagesStatus = $conn->prepare("INSERT INTO form_sells_validation (trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validateFinanciera, validate, idFormSell, created_at, updated_at, active) VALUES (?, ?, ?, ?, ? ,?, ?, ?,?, NOW(), NOW(), 1);");
    $imagesStatus->bind_param("iiiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion, $formNumber);
    if($imagesStatus->execute())
    {
        $bandera = true;
    }
    else
    {
        $bandera = false;
        error_log("error al insertar en form_Sell_validation");
    }
    
    $conn->close();
    return $bandera;
}

/**
 * Metodo que sirve para actualizar la tabla form_sells_validation
 * 
 * @param int $idFormValidation
 * @param int $trustedHome
 * @param int $requestImage
 * @param int $privacyAdvice
 * @param int $identificationImage
 * @param int $payerImage
 * @param int $agreegmentImage
 * @param int $validacion
 * @param int $formNumber
 * @return boolean
 */
function updateFormSellValidation($idFormValidation,$trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $bandera = NULL;
    $Status = $conn->prepare("UPDATE form_sells_validation SET trustedHome = ?, requestImage = ?, privacyAdvice = ?, identificationImage = ?, payerImage = ?, agreegmentImage = ?, validateFinanciera = ?, validate = ?, updated_at = NOW() WHERE id = ?;");
    $Status->bind_param("iiiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage,$financieraValidate, $validacion, $idFormValidation);
    if($Status->execute())
    {
        $bandera = true;
    }
    else
    {
        $bandera = false;
        error_log($error);
    }
    
    $conn->close();
    return $bandera;
}

/**
 * Metodo que se encarga de actualziar el estatus del contrato solo cuando es rechazo, si no es un rechazo lo debe de hacer en el proceso de validacion de la venta
 */
function updateEstatusContrato($idReporte,$nickName,  $estatusVenta, $idEstatusContrato, $idEmpleadoVenta)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    if($nickName == "AYOPSA")
    {
         // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
         $update = $conn->prepare("UPDATE tEstatusContrato SET validadoAyopsa=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
         $update->bind_param("iiii",$estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReporte);
         $update->execute();
    }
    else
    {
         // CAMBIAMOS EL ESTATUS A TESTATUS CONTRATO 
         $update = $conn->prepare("UPDATE tEstatusContrato SET validadoMexicana=?,estatusVenta=?, idEmpleadoParaVenta = ?, fechaMod=NOW() WHERE idReporte = ?;");
         $update->bind_param("iiii", $estatusVenta, $idEstatusContrato, $idEmpleadoVenta, $idReporte);
         $update->execute();
    }
    
    $conn->close();
}

/**
 * Metodo que se encarga de actualizar el estatus del chezaso en reportHistory
 * 
 * @param int $idReport
 * @param int $idReportType
 */
function updateReportHistory($idReporte)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $idReportHistory = NULL;
    $idReportType = 2;
    $sqlHistory = "SELECT  rh.idReportHistory FROM reportHistory AS rh WHERE rh.idReport = ? AND rh.idReportType = ?;";
    $getHistory = $conn->prepare($sqlHistory);
    $getHistory->bind_param("ii", $idReporte, $idReportType);
    $getHistory->execute();

    $getHistory->store_result();
    $getHistory->bind_result($idReportHistory);

    $idEstatusWorkflow = 2;
    if($getHistory->fetch())
    {
          // SI HAY ACTUALIZA
         $sqlHistory = "UPDATE reportHistory SET  idStatusReport = ?,rechazado=1, updated_at = NOW() WHERE idReportHistory = ?;";
         $updateHistory = $conn->prepare($sqlHistory);
         $updateHistory->bind_param("ii", $idEstatusWorkflow, $idReportHistory);
         $updateHistory->execute();
    }
    
    $conn->close();
}

/**
 * 
 */
function updateReporteVentas($idReporte)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $reportTiempo = $conn->prepare("UPDATE reportTiempoVentas SET fechaInicioRechazo = NOW() WHERE idReporte = ?;");
    $reportTiempo->bind_param("i", $idReporte);
    $reportTiempo->execute();
    
    $conn->close();
}

function eliminarMotivosRechazos($formNumber)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $rejectedReson = $conn->prepare("DELETE FROM form_sells_rejected_reason WHERE idSell = ?;");
    $rejectedReson->bind_param("i", $formNumber);
    $rejectedReson->execute();
    
    $conn->close();
}

function insertarMotivosRechazos($formNumber, $reason,  $ip_usr_id)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $idEstatusFormSell = 2;
    
    // SE GUARDAN LAS RAZONES DE RECHAZO
    foreach ($reason AS $value)
    {
        $val = intval($value['Val']);
        $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at, idUsuario) VALUES(?, ?, ?, NOW(), NOW(), ?);");
        $rejectedReson->bind_param("iiii", $formNumber, $val, $idEstatusFormSell,  $ip_usr_id);
        $rejectedReson->execute();
    }
    
    $conn->close();
}

/**
 * Metodo que se encarga de recuperar el id del usuario
 * @param int $idUsuario
 * @return int
 */
function getEmpleado($idUsuario)
{
    $DB = new DAO();
    $conn = $DB->getConnect();

    $idEmpleado = NULL;
    $sql = "SELECT e.id FROM user AS u INNER JOIN employee AS e ON u.id = e.idUser WHERE u.id = ?;";
    $smtEmpleado = $conn->prepare($sql);
    $smtEmpleado->bind_param("i", $idUsuario); 
    
    if ($smtEmpleado->execute()) 
    {
        $smtEmpleado->store_result();
        $smtEmpleado->bind_result($idEmpleado);
        $smtEmpleado->fetch();
    }
    else
    {
        error_log("error al recuperar el idEmpleado");
    }
    
    $conn->close();
    return $idEmpleado;
}