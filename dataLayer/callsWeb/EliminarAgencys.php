<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if(isset($_POST["idUsuario"]))
{
    
    $response = eliminarAgency((int) $_POST["idUsuario"]);
    echo json_encode($response);
}
else
{
    $response["CodigoRespuesta"] = "BAD";
    $response["MensajeRespuesta"] = "No se selecciono ninguna agencia.";
    echo json_encode($response);
}


function eliminarAgency($idAgencia)
{
    $USUARIO_SUPER_ADMIN = 1;
    $USUARIO_AYOPSA = 4;
    $USUARIO_CALLCENTER = 24;
    
    $response = array();
    
    if(!in_array($idAgencia, array($USUARIO_SUPER_ADMIN, $USUARIO_AYOPSA, $USUARIO_CALLCENTER)))
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $eliminarSQL = "DELETE FROM user WHERE id = ?;";
        $smtEliminar = $conn->prepare($eliminarSQL);
        $smtEliminar->bind_param('i', $idAgencia);
        
        if($smtEliminar->execute())
        {
            $response["CodigoRespuesta"] = "OK";
            $response["MensajeRespuesta"] = "Agencia eliminada.";
        }
        else
        {
            $response["CodigoRespuesta"] = "BAD";
            $response["MensajeRespuesta"] = "Error al tratar de eliminar la agencia." . $conn->error;
        }
    }
    else
    {
        $response["CodigoRespuesta"] = "BAD";
        $response["MensajeRespuesta"] = "No es posible eliminar esta agencia.";
    }
    
    return $response;
}