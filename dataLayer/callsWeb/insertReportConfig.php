<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];

$txtTiempoRevGreen1 = $_POST['txtTiempoRevGreen1'];
$txtTiempoRevGreen1 = intval($txtTiempoRevGreen1);
$txtTiempoRevGreen2 = $_POST['txtTiempoRevGreen2'];
$txtTiempoRevGreen2 = intval($txtTiempoRevGreen2);
$txtTiempoRevYellow1 = $_POST['txtTiempoRevYellow1'];
$txtTiempoRevYellow1 = intval($txtTiempoRevYellow1);
$txtTiempoRevYellow2 = $_POST['txtTiempoRevYellow2'];
$txtTiempoRevYellow2 = intval($txtTiempoRevYellow2);
$txtTiempoRevRed1 = $_POST['txtTiempoRevRed1'];
$txtTiempoRevRed1 = intval($txtTiempoRevRed1);
$txtTiempoRevFincGreen1 = $_POST['txtTiempoRevFincGreen1'];
$txtTiempoRevFincGreen1 = intval($txtTiempoRevFincGreen1);
$txtTiempoRevFincGreen2 = $_POST['txtTiempoRevFincGreen2'];
$txtTiempoRevFincGreen2 = intval($txtTiempoRevFincGreen2);
$txtTiempoRevFincYellow1 = $_POST['txtTiempoRevFincYellow1'];
$txtTiempoRevFincYellow1 = intval($txtTiempoRevFincYellow1);
$txtTiempoRevFincYellow2 = $_POST['txtTiempoRevFincYellow2'];
$txtTiempoRevFincYellow2 = intval($txtTiempoRevFincYellow2);
$txtTiempoRevFincRed1 = $_POST['txtTiempoRevFincRed1'];
$txtTiempoRevFincRed1 = intval($txtTiempoRevFincRed1);
$txtTiempoDocsGreen1 = $_POST['txtTiempoDocsGreen1'];
$txtTiempoDocsGreen1 = intval($txtTiempoDocsGreen1);
$txtTiempoDocsGreen2 = $_POST['txtTiempoDocsGreen2'];
$txtTiempoDocsGreen2 = intval($txtTiempoDocsGreen2);
$txtTiempoDocsYellow1 = $_POST['txtTiempoDocsYellow1'];
$txtTiempoDocsYellow1 = intval($txtTiempoDocsYellow1);
$txtTiempoDocsYellow2 = $_POST['txtTiempoDocsYellow2'];
$txtTiempoDocsYellow2 = intval($txtTiempoDocsYellow2);
$txtTiempoDocsRed1 = $_POST['txtTiempoDocsRed1'];
$txtTiempoDocsRed1 = intval($txtTiempoDocsRed1);
$txtTiempoCapturasGreen1 = $_POST['txtTiempoCapturasGreen1'];
$txtTiempoCapturasGreen1 = intval($txtTiempoCapturasGreen1);
$txtTiempoCapturasGreen2 = $_POST['txtTiempoCapturasGreen2'];
$txtTiempoCapturasGreen2 = intval($txtTiempoCapturasGreen2);
$txtTiempoCapturasYellow1 = $_POST['txtTiempoCapturasYellow1'];
$txtTiempoCapturasYellow1 = intval($txtTiempoCapturasYellow1);
$txtTiempoCapturasYellow2 = $_POST['txtTiempoCapturasYellow2'];
$txtTiempoCapturasYellow2 = intval($txtTiempoCapturasYellow2);
$txtTiempoCapturasRed1 = $_POST['txtTiempoCapturasRed1'];
$txtTiempoCapturasRed1 = intval($txtTiempoCapturasRed1);
$txtTiempoAsigGreen1 = $_POST['txtTiempoAsigGreen1'];
$txtTiempoAsigGreen1 = intval($txtTiempoAsigGreen1);
$txtTiempoAsigGreen2 = $_POST['txtTiempoAsigGreen2'];
$txtTiempoAsigGreen2 = intval($txtTiempoAsigGreen2);
$txtTiempoAsigYellow1 = $_POST['txtTiempoAsigYellow1'];
$txtTiempoAsigYellow1 = intval($txtTiempoAsigYellow1);
$txtTiempoAsigYellow2 = $_POST['txtTiempoAsigYellow2'];
$txtTiempoAsigYellow2 = intval($txtTiempoAsigYellow2);
$txtTiempoAsigRed1 = $_POST['txtTiempoCapturasRed1'];
$txtTiempoAsigRed1 = intval($txtTiempoAsigRed1);
$txtTiempoRealizadoGreen1 = $_POST['txtTiempoRealizadoGreen1'];
$txtTiempoRealizadoGreen1 = intval($txtTiempoRealizadoGreen1);
$txtTiempoRealizadoGreen2 = $_POST['txtTiempoRealizadoGreen2'];
$txtTiempoRealizadoGreen2 = intval($txtTiempoRealizadoGreen2);
$txtTiempoRealizadoYellow1 = $_POST['txtTiempoRealizadoYellow1'];
$txtTiempoRealizadoYellow1 = intval($txtTiempoRealizadoYellow1);
$txtTiempoRealizadoYellow2 = $_POST['txtTiempoRealizadoYellow2'];
$txtTiempoRealizadoYellow2 = intval($txtTiempoRealizadoYellow2);
$txtTiempoRealizadoRed1 = $_POST['txtTiempoRealizadoRed1'];
$txtTiempoRealizadoRed1 = intval($txtTiempoRealizadoRed1);
$txtTiempoPhAnomaliasGreen1 = $_POST['txtTiempoPhAnomaliasGreen1'];
$txtTiempoPhAnomaliasGreen1 = intval($txtTiempoPhAnomaliasGreen1);
$txtTiempoPhAnomaliasGreen2 = $_POST['txtTiempoPhAnomaliasGreen2'];
$txtTiempoPhAnomaliasGreen2 = intval($txtTiempoPhAnomaliasGreen2);
$txtTiempoPhAnomaliasYellow1 = $_POST['txtTiempoPhAnomaliasYellow1'];
$txtTiempoPhAnomaliasYellow1 = intval($txtTiempoPhAnomaliasYellow1);
$txtTiempoPhAnomaliasYellow2 = $_POST['txtTiempoPhAnomaliasYellow2'];
$txtTiempoPhAnomaliasYellow2 = intval($txtTiempoPhAnomaliasYellow2);
$txtTiempoPhAnomaliasRed1 = $_POST['txtTiempoPhAnomaliasRed1'];
$txtTiempoPhAnomaliasRed1 = intval($txtTiempoPhAnomaliasRed1);
$txtTiempoAsigInstallGreen1 = $_POST['txtTiempoAsigInstallGreen1'];
$txtTiempoAsigInstallGreen1 = intval($txtTiempoAsigInstallGreen1);
$txtTiempoAsigInstalGreen2 = $_POST['txtTiempoAsigInstalGreen2'];
$txtTiempoAsigInstalGreen2 = intval($txtTiempoAsigInstalGreen2);
$txtTiempoAsigInstalYellow1 = $_POST['txtTiempoAsigInstalYellow1'];
$txtTiempoAsigInstalYellow1 = intval($txtTiempoAsigInstalYellow1);
$txtTiempoAsigInstalYellow2 = $_POST['txtTiempoAsigInstalYellow2'];
$txtTiempoAsigInstalYellow2 = intval($txtTiempoAsigInstalYellow2);
$txtTiempoAsigInstalRed1 = $_POST['txtTiempoAsigInstalRed1'];
$txtTiempoAsigInstalRed1 = intval($txtTiempoAsigInstalRed1);
$txtTiempoRealizInstalGreen1 = $_POST['txtTiempoRealizInstalGreen1'];
$txtTiempoRealizInstalGreen1 = intval($txtTiempoRealizInstalGreen1);
$txtTiempoRealizInstalGreen2 = $_POST['txtTiempoRealizInstalGreen2'];
$txtTiempoRealizInstalGreen2 = intval($txtTiempoRealizInstalGreen2);
$txtTiempoRealizInstalYellow1 = $_POST['txtTiempoRealizInstalYellow1'];
$txtTiempoRealizInstalYellow1 = intval($txtTiempoRealizInstalYellow1);
$txtTiempoRealizInstalYellow2 = $_POST['txtTiempoRealizInstalYellow2'];
$txtTiempoRealizInstalYellow2 = intval($txtTiempoRealizInstalYellow2);
$txtTiempoRealizInstalRed1 = $_POST['txtTiempoRealizInstalRed1'];
$txtTiempoRealizInstalRed1 = intval($txtTiempoRealizInstalRed1);
$txtTiempoInstalAnomGreen1 = $_POST['txtTiempoInstalAnomGreen1'];
$txtTiempoInstalAnomGreen1 = intval($txtTiempoInstalAnomGreen1);
$txtTiempoInstalAnomGreen2 = $_POST['txtTiempoInstalAnomGreen2'];
$txtTiempoInstalAnomGreen2 = intval($txtTiempoInstalAnomGreen2);
$txtTiempoInstalAnomYellow1 = $_POST['txtTiempoInstalAnomYellow1'];
$txtTiempoInstalAnomYellow1 = intval($txtTiempoInstalAnomYellow1);
$txtTiempoInstalAnomYellow2 = $_POST['txtTiempoInstalAnomYellow2'];
$txtTiempoInstalAnomYellow2 = intval($txtTiempoInstalAnomYellow2);
$txtTiempoInstalAnomRed1 = $_POST['txtTiempoInstalAnomRed1'];
$txtTiempoInstalAnomRed1 = intval($txtTiempoInstalAnomRed1);
//validamos si hay registros en la tabla si esta vacia insertamos los datos de las metricas
$resCountTabla=getCoutnTablaTimeLabels($conn);
$redTo=3600;
if (intval($resCountTabla) == 0) {
	//insertamos
	$etiquetaName="TIEMPO REV. VENTA - VERDE";
	$subIdTimesG=1;
	$subIdTimesI=2;
	$subIdTimesR=3;
	$idTimes=1;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevGreen1, $txtTiempoRevGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO REV. VENTA - AMARILLO";
	$idTimes=1;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevYellow1, $txtTiempoRevYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO REV. VENTA - ROJO";
	$idTimes=1;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO REV. FINANCIERA - VERDE";
	$idTimes=2;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevFincGreen1, $txtTiempoRevFincGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO REV. FINANCIERA - AMARILLO";
	$idTimes=2;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevFincYellow1, $txtTiempoRevFincYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO REV. FINANCIERA - ROJO";
	$idTimes=2;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRevFincRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - VERDE";
	$idTimes=3;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoDocsGreen1, $txtTiempoDocsGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - AMARILLO";
	$idTimes=3;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoDocsYellow1, $txtTiempoDocsYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - ROJO";
	$idTimes=3;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoDocsRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - VERDE";
	$idTimes=4;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoCapturasGreen1, $txtTiempoCapturasGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - AMARILLO";
	$idTimes=4;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoCapturasYellow1, $txtTiempoCapturasYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - ROJO";
	$idTimes=4;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoCapturasRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO ASIGNACION PH - VERDE";
	$idTimes=5;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigGreen1, $txtTiempoAsigGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO ASIGNACION PH - AMARILLO";
	$idTimes=5;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigYellow1, $txtTiempoAsigYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO ASIGNACION PH - ROJO";
	$idTimes=5;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - VERDE";
	$idTimes=6;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizadoGreen1, $txtTiempoRealizadoGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - AMARILLO";
	$idTimes=6;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizadoYellow1, $txtTiempoRealizadoYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - ROJO";
	$idTimes=6;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizadoRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - VERDE";
	$idTimes=7;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasGreen1, $txtTiempoPhAnomaliasGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - AMARILLO";
	$idTimes=7;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasYellow1, $txtTiempoPhAnomaliasYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - ROJO";
	$idTimes=7;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - VERDE";
	$idTimes=8;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigInstallGreen1, $txtTiempoAsigInstalGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - AMARILLO";
	$idTimes=8;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigInstalYellow1, $txtTiempoAsigInstalYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - ROJO";
	$idTimes=8;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoAsigInstalRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - VERDE";
	$idTimes=9;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalGreen1, $txtTiempoRealizInstalGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - AMARILLO";
	$idTimes=9;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalYellow1, $txtTiempoRealizInstalYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - ROJO";
	$idTimes=9;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalRed1, $redTo, $idTimes, $subIdTimesR);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - VERDE";
	$idTimes=10;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomGreen1, $txtTiempoInstalAnomGreen2, $idTimes, $subIdTimesG);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - AMARILLO";
	$idTimes=10;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomYellow1, $txtTiempoInstalAnomYellow2, $idTimes, $subIdTimesI);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - ROJO";
	$idTimes=10;
	$resInsertDatos=insertTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomRed1, $redTo, $idTimes, $subIdTimesR);

	$returnData["RESULT"] = true;
	$returnData["CODE"] = 200;
	$returnData["RESPONSE"] = "Successful Transaction";
}else if (intval($resCountTabla) > 0){
	//actualizamos
	$etiquetaName="TIEMPO REV. VENTA - VERDE";
	$idTimes=1;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevGreen1, $txtTiempoRevGreen2, $idTimes);
	$etiquetaName="TIEMPO REV. VENTA - AMARILLO";
	$idTimes=1;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevYellow1, $txtTiempoRevYellow2, $idTimes);
	$etiquetaName="TIEMPO REV. VENTA - ROJO";
	$idTimes=1;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO REV. FINANCIERA - VERDE";
	$idTimes=2;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevFincGreen1, $txtTiempoRevFincGreen2, $idTimes);
	$etiquetaName="TIEMPO REV. FINANCIERA - AMARILLO";
	$idTimes=2;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevFincYellow1, $txtTiempoRevFincYellow2, $idTimes);
	$etiquetaName="TIEMPO REV. FINANCIERA - ROJO";
	$idTimes=2;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRevFincRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - VERDE";
	$idTimes=3;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoDocsGreen1, $txtTiempoDocsGreen2, $idTimes);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - AMARILLO";
	$idTimes=3;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoDocsYellow1, $txtTiempoDocsYellow2, $idTimes);
	$etiquetaName="TIEMPO DOCUMENTACION RECHAZADA - ROJO";
	$idTimes=3;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoDocsRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - VERDE";
	$idTimes=4;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoCapturasGreen1, $txtTiempoCapturasGreen2, $idTimes);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - AMARILLO";
	$idTimes=4;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoCapturasYellow1, $txtTiempoCapturasYellow2, $idTimes);
	$etiquetaName="TIEMPO 1ERA - 2DA CAPTURA - ROJO";
	$idTimes=4;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoCapturasRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION PH - VERDE";
	$idTimes=5;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigGreen1, $txtTiempoAsigGreen2, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION PH - AMARILLO";
	$idTimes=5;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigYellow1, $txtTiempoAsigYellow2, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION PH - ROJO";
	$idTimes=5;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - VERDE";
	$idTimes=6;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizadoGreen1, $txtTiempoRealizadoGreen2, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - AMARILLO";
	$idTimes=6;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizadoYellow1, $txtTiempoRealizadoYellow2, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - ROJO";
	$idTimes=6;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizadoRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - VERDE";
	$idTimes=7;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasGreen1, $txtTiempoPhAnomaliasGreen2, $idTimes);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - AMARILLO";
	$idTimes=7;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasYellow1, $txtTiempoPhAnomaliasYellow2, $idTimes);
	$etiquetaName="TIEMPO PH CON ANOMALIAS - ROJO";
	$idTimes=7;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoPhAnomaliasRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - VERDE";
	$idTimes=8;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigInstallGreen1, $txtTiempoAsigInstalGreen2, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - AMARILLO";
	$idTimes=8;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigInstalYellow1, $txtTiempoAsigInstalYellow2, $idTimes);
	$etiquetaName="TIEMPO ASIGNACION INSTALACION - ROJO";
	$idTimes=8;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoAsigInstalRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - VERDE";
	$idTimes=9;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalGreen1, $txtTiempoRealizInstalGreen2, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - AMARILLO";
	$idTimes=9;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalYellow1, $txtTiempoRealizInstalYellow2, $idTimes);
	$etiquetaName="TIEMPO REALIZACION INSTALACION - ROJO";
	$idTimes=9;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoRealizInstalRed1, $redTo, $idTimes);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - VERDE";
	$idTimes=10;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomGreen1, $txtTiempoInstalAnomGreen2, $idTimes);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - AMARILLO";
	$idTimes=10;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomYellow1, $txtTiempoInstalAnomYellow2, $idTimes);
	$etiquetaName="TIEMPO INSTALACION ANOMALIAS - ROJO";
	$idTimes=10;
	$resInsertDatos=updateTableTimes($conn, $etiquetaName, $txtTiempoInstalAnomRed1, $redTo, $idTimes);

	$returnData["RESULT"] = true;
	$returnData["CODE"] = 200;
	$returnData["RESPONSE"] = "Successful Transaction";
}
echo json_encode($returnData);
function insertTableTimes($conn, $etiquetaName, $txtTiempofrom, $txtTiempoto, $idTimes, $subIdTimes)
{
	$insertReportLabelSQL="INSERT INTO times_check_labels(`idTimes`, `subIdTimes`,`name`,`from`, `to`, `created_at`, `updated_at`) VALUES(?,?,?,?, ?, NOW(), NOW());";
    if ($insertReportLabel = $conn->prepare($insertReportLabelSQL)) {
        $insertReportLabel->bind_param("iisii", $idTimes, $subIdTimes, $etiquetaName, $txtTiempofrom, $txtTiempoto);
        $insertReportLabel->execute();
    }else{
    	error_log('message error - '.$conn->error);
    }
    $insertReportLabel=null;
}

function updateTableTimes($conn, $etiquetaName, $txtTiempofrom, $txtTiempoto, $idTimes)
{
	$updateReportLabelSQL="UPDATE times_check_labels 
						   SET `from` = ? , `to` = ? , `updated_at` = NOW() 
						   WHERE `name` = ?;";
    if ($updateReportLabel = $conn->prepare($updateReportLabelSQL)) {
        $updateReportLabel->bind_param("iis", $txtTiempofrom, $txtTiempoto, $etiquetaName);
        $updateReportLabel->execute();
    }else{
    	error_log('message error - '.$conn->error);
    }
    $updateReportLabel=null;
}
function getCoutnTablaTimeLabels($conn)
{
	$getTimeReports ="SELECT 
	                    count(id) as contador
                	FROM times_check_labels ;";

	$result = $conn->query($getTimeReports);
	$res="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $res=$row[0];
        }
    }
    return $res;
}
?>