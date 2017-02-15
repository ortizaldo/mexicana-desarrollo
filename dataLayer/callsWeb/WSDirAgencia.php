<?php
date_default_timezone_set('America/Mexico_City');
ini_set('default_socket_timeout', 120);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
$starttime = microtime(true);
/* do stuff here */
error_log('message time starttime: '.date("H:i:s",$starttime));
//require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
//validamos si el archivo existe en la carpeta
error_log('message ruta '.getcwd());
$rutaActual = getcwd();
chdir('../../uploads/csvAgencias');
error_log('message ruta '.getcwd());
$archivoCSV = getcwd()."/direcciones_agencia.csv";
error_log($archivoCSV);
if (file_exists($archivoCSV)) {
    error_log('message existe archivo');
    chdir($rutaActual);
    $stmtDelFirst="TRUNCATE TABLE direccionesAgenciaCSV";
    if ($delDirAgency = $conn->prepare($stmtDelFirst)) {
        if ($delDirAgency->execute()) {
            error_log("registros eliminados");
            mysqli_query($conn,"SET NAMES 'UTF8'");
            $sql2 = "LOAD DATA LOCAL INFILE '../../uploads/csvAgencias/direcciones_agencia.csv' INTO TABLE `direccionesAgenciaCSV` FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' (idAgencia, nombreAgencia, idDireccion, calle, numero, entreCalles, idMunicipio, idColonia)";
            $resultDump=mysqli_query($conn, $sql2);
            //mysqli_set_local_infile_default($conn);
            if($resultDump){
                if (unlink($archivoCSV)) {
                    error_log('Archivo Eliminado..');
                    $getDataAgencys = "SELECT 
                                   a.nickname, b.id
                                   FROM
                                   user a, agency b, agency_profile d
                                   WHERE 0=0
                                   and a.id=b.idUser
                                   and b.id=d.idAgency
                                   and d.idProfile > 2
                                   -- and a.nickname like '%tamiari%'";
                    $result = $conn->query($getDataAgencys);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_array()) {
                            updateIdAgencia($row[0], $row[1]);
                        }
                    }
                }
            }
            $endtime = microtime(true);
            $timediff = date("H:i:s",$endtime-$starttime);
            error_log('message time elappsed: '.$timediff);
        }
    }else{
        error_log($conn->error);
    }
}else{
    error_log('message no existe archivo');
}
function updateIdAgencia($nicknameagencia, $idAgenciaSiscom)
{
    //actualizamos los datos de la agencia en la tabla direccionesAgenciaCSV
    if ($nicknameagencia != "" && $idAgenciaSiscom != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $test_string = '%' . $nicknameagencia . '%';
        $deleteCatalogoSQL="UPDATE direccionesAgenciaCSV SET idAgencia=? where UPPER(nombreAgencia) LIKE ?";
        if ($deleteCatalogo = $conn->prepare($deleteCatalogoSQL)) {
            $deleteCatalogo->bind_param("is", $idAgenciaSiscom,$test_string);
            if ($deleteCatalogo->execute()) {
                error_log($deleteCatalogo->affected_rows.' - #Datos Actualizados de '.$nicknameagencia);
            }
        }
    }
}