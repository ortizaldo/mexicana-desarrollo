<?php
require_once  '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();
$returnData = [];
$reportData = [];
$reasons = [];

//$getReasons = "SELECT id, reason FROM rejected_reason;";
$getReasons ="SELECT RP.agreementNumber, USAG.nickname, USAGADMIN.nickname, USADMIN.name + USADMIN.lastName, RJRSN.reason
FROM report AS RP
INNER JOIN report_employee_form AS RPEMP ON RP.id = RPEMP.idReport
INNER JOIN form_sells AS FRMS ON RPEMP.idForm = FRMS.id
INNER JOIN form_sells_validation AS FRMVAL ON FRMS.id = FRMVAL.idFormSell
INNER JOIN form_sell_rejected_reasons AS FRMSRJ ON FRMS.id = FRMSRJ.idFormSell
INNER JOIN rejected_reason AS RJRSN ON FRMSRJ.idRejectedReasons = RJRSN.id
INNER JOIN employee AS EMP ON RPEMP.idEmployee = EMP.id
INNER JOIN agency_employee AS AGEMP ON EMP.id = AGEMP.idemployee
INNER JOIN agency AS AGEN ON AGEMP.idAgency = AGEN.id
INNER JOIN agency_profile AS AGPROF ON AGEN.id = AGPROF.idAgency
INNER JOIN user AS USAG ON AGEN.idUser = USAG.id
INNER JOIN user AS USADMIN ON FRMVAL.idUser = USADMIN.id
INNER JOIN employee AS EMPADMIN ON USADMIN.id = EMPADMIN.idUser
INNER JOIN agency_employee AS AGEMPADMIN ON EMPADMIN.id = AGEMPADMIN.idemployee
INNER JOIN agency AS AGADMIN ON AGEMPADMIN.idAgency = AGADMIN.id
INNER JOIN user AS USAGADMIN ON AGADMIN.idUser = USAGADMIN.id;
";

$result = $conn->query($getReasons);

while( $row = $result->fetch_array() ) {
    $reasons['Contrato'] = $row[0];
    $reasons['RechazadoUno'] = $row[1];
    $reasons['RechazadoDos'] = $row[2];
    $reasons['Empleado'] = $row[3];
    $reasons['Motivo'] = $row[4];
    $returnData[] = $reasons;
}


$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Mexicana de Gas")
    ->setLastModifiedBy("Mexicana de Gas")
    ->setTitle("Reporte General de formularios creados")
    ->setSubject("Reporte de formularios por empleados de la agencia")
    ->setDescription("Documento en formato xls creado en el sitio Web de Mexicana de Gas")
    ->setKeywords("office 2007 openxml excel reportes")
    ->setCategory("Reportes");


$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "No. contrato")
    ->setCellValue('B1', "Agencia")
    ->setCellValue('C1', "Rechazado por")
    ->setCellValue('D1', "Rechazado por")
    ->setCellValue('E1', "Motivo de rechazo");


$objPHPExcel->getActiveSheet()->setTitle('Reportes');

$i = 2;
foreach($returnData as $key =>$value){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i,$value["Contrato"])
        ->setCellValue('B'.$i,$value["RechazadoUno"])
        ->setCellValue('C'.$i,$value["RechazadoDos"])
        ->setCellValue('D'.$i,$value["Empleado"])
        ->setCellValue('E'.$i,$value["Motivo"]);
    $i++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteFormularios.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

header('Location: ../../report.php');
exit;
?>