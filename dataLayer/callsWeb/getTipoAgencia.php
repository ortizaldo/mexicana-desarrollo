<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
//consultamos el catalogo de vivienda
$DB = new DAO();
$conn = $DB->getConnect();
$nickname=$_GET['nicknamAgencia'];
$getTipoAgenciaSQL = "SELECT 
                        b.tipo
                    FROM
                        user a,
                        agency b
                    WHERE
                        0 = 0 
                        AND a.id = b.idUser 
                        AND a.nickname=?";
if ($getTipoAgencia = $conn->prepare($getTipoAgenciaSQL)) {
    //devolvemos la respuesta
    $getTipoAgencia->bind_param("s", $nickname);
    if ($getTipoAgencia->execute()) {
        $getTipoAgencia->store_result();
        $getTipoAgencia->bind_result($tipoAgencia);
        $cont=0;
        while ($getTipoAgencia->fetch()) {
            $requests[$cont]["tipoAgencia"] = $tipoAgencia;
            $cont++;
        }
    }else{
        error_log('message error');
    }
    $response["status"] = "OK";
    $response["code"] = "200";
    $response["response"] = $requests;
    $requests = null;
}
echo json_encode($response);