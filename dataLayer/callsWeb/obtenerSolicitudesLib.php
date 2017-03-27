<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
session_start();
$agency=strtoupper($_SESSION["nickname"]);
if ($agency != "SUPERADMIN") {
    $obtenerDirAssign = "SELECT idsolicitud,agencia,calle,entreCalles,num,colonia,mun,idDireccion,fechaSol,estatusDir,estatus, comentarios, fechaLib, tiempoLib 
                     FROM solicitudLibDir where agencia LIKE '%".$agency."%'";
}else{
    $obtenerDirAssign = "SELECT idsolicitud,agencia,calle,entreCalles,num,colonia,mun,idDireccion,fechaSol,estatusDir,estatus, comentarios, fechaLib, tiempoLib 
                     FROM solicitudLibDir;";
}

$result = $conn->query($obtenerDirAssign);
while( $row = $result->fetch_array() ) {
    $requests['idsolicitud'] = $row[0];
    $requests['agencia'] = $row[1];
    $requests['calle'] = $row[2];
    $requests['entreCalles'] = $row[3];
    $requests['num'] = $row[4];
    $requests['colonia'] = $row[5];
    $requests['mun'] = $row[6];
    $requests['idDireccion'] = $row[7];
    $requests['fechaSol'] = $row[8];
    $requests['estatusDir'] = $row[9];
    $requests['estatus'] = $row[10];
    $requests['comentarios'] = $row[11];
    $requests['fechaLib'] = $row[12];
    $requests['tiempoLib'] = $row[13];
    $reports[] = $requests;
}
$result->free_result();
echo json_encode($reports);
$conn->close();