<?php 
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
session_start();

$DB = new DAO();
$conn = $DB->getConnect();
$searchContratoSQL ="
    SELECT DISTINCT(financiamiento)
    FROM catalogoTiposDeContrato
    WHERE 0=0
    and idContrato= ?;
";
$idContrato=$_GET['idContrato'];
if ($idContrato != '') {
    if ($searchContrato = $conn->prepare($searchContratoSQL)) {
        $searchContrato->bind_param("s",$idContrato);
        if ($searchContrato->execute()) {
            $searchContrato->store_result();
            $searchContrato->bind_result($financiamiento);
            $cont=0;
            while ($searchContrato->fetch()) {
                $contato[$cont]['financiamiento'] = $financiamiento;
                //$returnData[] = $contato;
                $cont++;
            }
        }
        $searchContrato->free_result();
        $response["CodigoRespuesta"] = 1;
        $response["MensajeRespuesta"] = "EXITO";
        $response["response"] = $contato;
        echo json_encode($response);
    }else{
        $response["CodigoRespuesta"] = 0;
        $response["MensajeRespuesta"] = "Datos Ingresados Erroneamente.";
        echo json_encode($response);
    }
}else{
    $response["CodigoRespuesta"] = 0;
    $response["MensajeRespuesta"] = "No se ha seleccionado un tipo de Contrato";
    echo json_encode($response);
}
    

    