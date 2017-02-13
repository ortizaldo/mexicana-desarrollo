<?php 
include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reasons = [];

$getTimeReports ="SELECT 
                    rtv.id,
                    ec.idClienteGenerado AS noContrato,
                    rtv.fechaInicioVenta,
                    rtv.fechaFinVenta,
                    rtv.fechaInicioFinanciera,
                    rtv.fechaFinFinanciera,
                    rtv.fechaInicioRechazo,
                    rtv.fechaFinRechazo,
                    rtv.fechaPrimeraCaptura,
                    rtv.fechaSegundaCaptura,
                    rtv.fechaInicioAsigPH,
                    rtv.fechaFinAsigPH,
                    rtv.fechaInicioRealizoPH,
                    rtv.fechaFinRealizoPH,
                    rtv.fechaInicioAnomPH,
                    rtv.fechaFinAnomPH,
                    rtv.fechaInicioAsigPH,
                    rtv.fechaInicioAsigInst,
                    rtv.fechaFinAsigInst,
                    rtv.fechaInicioRealInst,
                    rtv.fechaFinRealInst,
                    rtv.fechaInicioAnomInst,
                    rtv.fechaFinAnomInst
                FROM report AS r
                    INNER JOIN tEstatusContrato AS ec ON r.id = ec.idReporte
                    INNER JOIN reportTiempoVentas AS rtv ON r.id = rtv.idReporte;";

$result = $conn->query($getTimeReports);
$contador=0;
while( $row = $result->fetch_array() ) {
    $reasons[$contador]['Id'] = $row[0];
    $reasons[$contador]['No_Cliente'] = $row[1];
    $reasons[$contador]['fechaInicioVenta'] = $row[2];
    $reasons[$contador]['fechaFinVenta'] = $row[3];
    $reasons[$contador]['fechaInicioFinanciera'] = $row[4];
    $reasons[$contador]['fechaFinFinanciera'] = $row[5];
    $reasons[$contador]['fechaInicioRechazo'] = $row[6];
    $reasons[$contador]['fechaFinRechazo'] = $row[7];
    $reasons[$contador]['fechaPrimeraCaptura'] = $row[8];
    $reasons[$contador]['fechaSegundaCaptura'] = $row[9];
    $reasons[$contador]['fechaInicioAsigPH'] = $row[10];
    $reasons[$contador]['fechaFinAsigPH'] = $row[11];
    $reasons[$contador]['fechaInicioRealizoPH'] = $row[12];
    $reasons[$contador]['fechaFinRealizoPH'] = $row[13];
    $reasons[$contador]['fechaInicioAnomPH'] = $row[14];
    $reasons[$contador]['fechaFinAnomPH'] = $row[15];
    $reasons[$contador]['fechaInicioAsigPH'] = $row[16];
    $reasons[$contador]['fechaInicioAsigInst'] = $row[17];
    $reasons[$contador]['fechaFinAsigInst'] = $row[18];
    $reasons[$contador]['fechaInicioRealInst'] = $row[19];
    $reasons[$contador]['fechaFinRealInst'] = $row[20];
    $reasons[$contador]['fechaInicioAnomInst'] = $row[21];
    $reasons[$contador]['fechaFinAnomInst'] = $row[22];
    $contador++;
}
$returnData['calculos'] = $reasons;
$returnData['conf'] = getRangoTiempo($conn);
$returnData['diasFestivos'] = getDiasFestivos($conn);
$result->free_result();
echo json_encode($returnData);

function getRangoTiempo($conn)
{
    //obtenemos los rangos de tiempo para pintar los row del reporte de tiempos
    $getReportsConfig = "SELECT `id`,`idTimes`,`subIdTimes`, `name` , `from`, `to` FROM times_check_labels";
    $result = $conn->query($getReportsConfig);
    $res=[];
    $contador=0;
    if ($result->num_rows > 0) {
        while( $row = $result->fetch_array() ) {
            $res[$contador]['id'] = $row[0];
            $res[$contador]['idTimes'] = $row[1];
            $res[$contador]['subIdTimes'] = $row[2];
            $res[$contador]['labelTitle'] = $row[3];
            $res[$contador]['labelFrom'] = $row[4];
            $res[$contador]['labelTo'] = $row[5];
            $contador++;
        }
    }
    return $res;
}

function getDiasFestivos($conn)
{
    //obtenemos los rangos de tiempo para pintar los row del reporte de tiempos
    $getReportsConfig = "SELECT dia,descripcion FROM diasNoLaborales";
    $result = $conn->query($getReportsConfig);
    $res=[];
    $contador=0;
    if ($result->num_rows > 0) {
        while( $row = $result->fetch_array() ) {
            $res[$contador]['dia'] = $row[0];
            $res[$contador]['descripcion'] = $row[1];
            $contador++;
        }
    }
    return $res;
}