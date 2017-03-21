<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
session_start();
$agency=strtoupper($_SESSION["nickname"]);
$obtenerDirAssign = "SELECT idsolicitud,agencia,calle,entreCalles,num,colonia,mun,idDireccion,fechaSol,estatusDir,estatus, comentarios, fechaLib, tiempoLib FROM solicitudLibDir";
if ($connDir = $conn->prepare($obtenerDirAssign)) {
    if ($connDir->execute()) {
        $connDir->store_result();
        $connDir->bind_result($idsolicitud,$agencia,$calle,$entreCalles,$num,$colonia,$mun,$idDireccion,$fechaSol,$estatusDir,$estatus, $comentarios, $fechaLib, $tiempoLib);
        $cont=0;
        while ($connDir->fetch()) {
            $requests[$cont]['idsolicitud'] = $idsolicitud;
            $requests[$cont]['agencia'] = $agencia;
            $requests[$cont]['calle'] = $calle;
            $requests[$cont]['entreCalles'] = $entreCalles;
            $requests[$cont]['num'] = $num;
            $requests[$cont]['colonia'] = $colonia;
            $requests[$cont]['mun'] = $mun;
            $requests[$cont]['idDireccion'] = $idDireccion;
            $requests[$cont]['fechaSol'] = $fechaSol;
            $requests[$cont]['estatusDir'] = $estatusDir;
            $requests[$cont]['estatus'] = $estatus;
            $requests[$cont]['comentarios'] = $comentarios;
            $requests[$cont]['fechaLib'] = $fechaLib;
            $requests[$cont]['tiempoLib'] = $tiempoLib;
            $cont++;
        }
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = $requests;
    }
}
echo json_encode($response);
$conn->close();