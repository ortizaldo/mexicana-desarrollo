<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
session_start();
$agency=strtoupper($_SESSION["nickname"]);
$idMunicipioSel=$_GET["idMunicipio"];
//$param="'%".$agency."%'";
$test_string = '%' . $agency . '%';
//consultamos el catalogo de vivienda
if ($idMunicipioSel != "") {
    $stmtDirecciones = "SELECT 
                        c.idAgencia,c.idMunicipio, c.idColonia, d.nombre
                        FROM
                        user a,
                        agency b,
                        direccionesAgenciaCSV c,
                        siscomColAgencia d
                        WHERE
                        a.id = b.idUser 
                        -- AND c.idAgencia = b.id 
                        AND d.coloniaId=c.idColonia
                        AND c.idMunicipio=?
                        AND c.nombreAgencia = ?  group by c.idAgencia, c.idMunicipio, c.idColonia, d.nombre;";
    //echo "query ".$stmtDirecciones;
    if ($connDir = $conn->prepare($stmtDirecciones)) {
        $connDir->bind_param("is",$idMunicipioSel, $agency);
        //devolvemos la respuesta
        if ($connDir->execute()) {
            $connDir->store_result();
            $connDir->bind_result($idAgencia,$idMunicipio, $coloniaId, $nombre);
            $cont=0;
            while ($connDir->fetch()) {
                $requests[$cont]["idAgencia"] = $idAgencia;
                $requests[$cont]["idMunicipio"] = $idMunicipio;
                $requests[$cont]["coloniaId"] = $coloniaId;
                $requests[$cont]["nombre"] = $nombre;
                $cont++;
            }
            $info["respuesta"] = $requests;
            $requests = null;
            $response["status"] = "OK";
            $response["code"] = "200";
            $response["colonias"] = $info;
        }
    }else{
        echo "error ".$conn->error;
    }
    echo json_encode($response);
    $conn->close();
}