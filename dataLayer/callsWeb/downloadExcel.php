<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Monterrey');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once  '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();

$dateSearch = $_POST["dateReport"];
$reportData = [];
$returnData = [];

$dateSearch = "2016-06-13";

$reportsMap = $conn->prepare("SELECT RP.id, RP.agreementNumber, RPT.name, status.description, CTY.name, RP.colonia, RP.street, RP.innerNumber, RP.outterNumber, UsEMP.nickname, USAG.nickname, DATE(RP.created_at)
FROM report AS RP
LEFT JOIN reportType AS RPT ON RPT.id = RP.idReportType
INNER JOIN country AS CNT ON CNT.id = RP.idCountry
INNER JOIN state AS ST ON ST.id = RP.idState
INNER JOIN city AS CTY ON CTY.id = RP.idCity
INNER JOIN user AS UsEMP ON RP.idEmployee = UsEMP.id
INNER JOIN user AS UsCreator ON RP.idUserCreator = UsCreator.id
INNER JOIN workflow_status_report AS WSR ON RP.id = WSR.idReport
LEFT JOIN status ON status.id = WSR.idStatus
INNER JOIN agency_employee AS AGEM ON RP.idEmployee = AGEM.idemployee
LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency
LEFT JOIN user AS USAG ON USAG.id = AG.idUser
WHERE DATE(RP.created_at) = DATE(?);");
$reportsMap->bind_param('s', $dateSearch);
if ($reportsMap->execute()) {
    $reportsMap->store_result();
    $reportsMap->bind_result($id, $agreementNumber, $reportType, $status, $city, $colonia, $street, $innerNumber, $outterNumber, $employee, $agency, $creationDate);
    while ($reportsMap->fetch()) {
        $reportData["ID"] = $id;
        $reportData["Contrato"] = $agreementNumber;
        $reportData["Tipo"] = $reportType;
        $reportData["Estatus"] = $status;
        $reportData["Municipio"] = $city;
        $reportData["Colonia"] = $colonia;
        $reportData["Calle"] = $street;
        $reportData["Numero"] = $innerNumber;
        $reportData["NumeroExterno"] = $outterNumber;
        $reportData["Empleado"] = $employee;
        $reportData["Agencia"] = $agency;
        $reportData["Fecha"] = $creationDate;
        $returnData[] = $reportData;   
    }
}

//----------------------------------------------------------------------------------------------------

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

 // Set document properties
$objPHPExcel->getProperties()->setCreator("Mexicana de Gas")
    ->setLastModifiedBy("Mexicana de Gas")
    ->setTitle("Reporte General de formularios creados")
    ->setSubject("Reporte de formularios por empleados de la agencia")
    ->setDescription("Documento en formato xls creado en el sitio Web de Mexicana de Gas")
    ->setKeywords("office 2007 openxml excel reportes")
    ->setCategory("Reportes");
    

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', "ID")
			->setCellValue('B1', "Contrato")
		    ->setCellValue('C1', "Tipo")
		    ->setCellValue('D1', "Estatus")
		    ->setCellValue('E1', "Municipio")
		    ->setCellValue('F1', "Colonia")
		    ->setCellValue('G1', "Calle y Numero")
		    ->setCellValue('H1', "Empleado")
		    ->setCellValue('I1', "Agencia")
		    ->setCellValue('J1', "Fecha");
		    
$cells = ["A1", "B1", "C1", "D1", "E1", "F1", "G1", "H1", "I1", "J1"];

foreach ($cells as $key) {
	$objPHPExcel->setActiveSheetIndex(0)->getStyle($key)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => "00853f"
        )
    ));
}
    
/*$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));*/

// Set document properties
/*$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");*/

// Add some data
/*$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');*/

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Reportes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$i = 2;
// Add data
foreach ($returnData as $key => $value) {
    $streetNumber = "";
    if (isset($reportData["Numero"])) {
        $streetNumber = $value["Calle"] . " " . $reportData["Numero"];
    } else {
        $streetNumber = $value["Calle"] . " " . $reportData["NumeroExterno"];
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["ID"])
	            ->setCellValue('B' . $i, $value["Contrato"])
	            ->setCellValue('C' . $i, $value["Tipo"])
	            ->setCellValue('D' . $i, $value["Estatus"])
	            ->setCellValue('E' . $i, $value["Municipio"])
	            ->setCellValue('F' . $i, $value["Colonia"])
	            ->setCellValue('G' . $i, $streetNumber)
	            ->setCellValue('H' . $i, $value["Empleado"])
	            ->setCellValue('I' . $i, $value["Agencia"])
	            ->setCellValue('J' . $i, $value["Fecha"]);
    $i++;
}

// Redirect output to a client’s web browser (Excel2007)
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

header('Location: ../../map.php');
exit;