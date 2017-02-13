<?php
session_start();
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';
error_log('message post '.json_encode($_POST));
$DB = new DAO();
$conn = $DB->getConnect();


$ROW_HEADER_SUPERIOR = 3;
$ROW_HEADER_REPOPRTE = 4;
$ROW_DATAOS_REPOPRTE = 5;


$objPHPExcel = new PHPExcel();

// TITULO DEL REPORTE
$objPHPExcel->getActiveSheet()->mergeCells('A1:L1');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', "REPORTE CONCENTRADO DE ESTATUS");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A1")->getFont()->setSize(16);


//HEADER SUPERIOR
$objPHPExcel->getActiveSheet()->mergeCells('B'.$ROW_HEADER_SUPERIOR.':G'.$ROW_HEADER_SUPERIOR);
$objPHPExcel->getActiveSheet()->mergeCells('H'.$ROW_HEADER_SUPERIOR.':J'.$ROW_HEADER_SUPERIOR);
$objPHPExcel->getActiveSheet()->mergeCells('K'.$ROW_HEADER_SUPERIOR.':M'.$ROW_HEADER_SUPERIOR);
$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('B'. $ROW_HEADER_SUPERIOR, "Estatus de Ventas")
        ->setCellValue('H'. $ROW_HEADER_SUPERIOR, "Estatus de PH")
        ->setCellValue('K'. $ROW_HEADER_SUPERIOR, "Estatus de Instalación")
        ;

// HEADERS DE LA TABLA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'. $ROW_HEADER_REPOPRTE, "Agencia")
    ->setCellValue('B'. $ROW_HEADER_REPOPRTE, "Revisión Venta")
    ->setCellValue('C'. $ROW_HEADER_REPOPRTE, "Revisión Financiera")
    ->setCellValue('D'. $ROW_HEADER_REPOPRTE, "Rechazado Venta")
    ->setCellValue('E'. $ROW_HEADER_REPOPRTE, "Rechazado Financiera")
    ->setCellValue('F'. $ROW_HEADER_REPOPRTE, "Segunda Captura")
    ->setCellValue('G'. $ROW_HEADER_REPOPRTE, "Revisión Segunda Captura")
    ->setCellValue('H'. $ROW_HEADER_REPOPRTE, "Por Asignar")
    ->setCellValue('I'. $ROW_HEADER_REPOPRTE, "En Proceso")
    ->setCellValue('J'. $ROW_HEADER_REPOPRTE, "Completo")
    ->setCellValue('K'. $ROW_HEADER_REPOPRTE, "Por Asignar")
    ->setCellValue('L'. $ROW_HEADER_REPOPRTE, "En Proceso")
    ->setCellValue('M'. $ROW_HEADER_REPOPRTE, "Completo")
        ;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);


$objPHPExcel->getActiveSheet()->setTitle('Reporte Concentrado de Estatus');

$agency = "";
$revisionVentas ="";
$revisionFinanciera = "";
$rechazadoVentas = "";
$rechazadoFinanciera = "";
$segundaCaptura = "";
$revisionSegundaCaptura = "";

$phPorAsignar = "";
$phEnProceso = "";
$phPendiente = "";
$phCompleto = "";

$insPorAsignar = "";
$insEnProceso = "";
$insPendiente = "";
$insCompleto = "";

$fechaInicial = "";
$fechaFinal = "";

$data = array();
$response = array();

$fechaInicial = (isset($_POST['dateF']) && $_POST['dateF'] != "") ? $_POST['dateF'] : '';
$fechaFinal = (isset($_POST['dateT']) && $_POST['dateT'] != "") ? $_POST['dateT'] : '';
$nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';


$stmt = $conn->prepare("call spReporteEstatus(?,?,?);");
mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);


// BODY DEL  REPORTE
if ($stmt->execute()) 
{
    $stmt->store_result();
    $stmt->bind_result($agency, $revisionVentas, $revisionFinanciera,$rechazadoVentas, $rechazadoFinanciera, $segundaCaptura, $revisionSegundaCaptura,$phPorAsignar, $phEnProceso, $phCompleto, $insPorAsignar, $insEnProceso, $insCompleto);

    $i = $ROW_DATAOS_REPOPRTE;
    while ($stmt->fetch()) 
    {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $agency);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $revisionVentas);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $revisionFinanciera);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $rechazadoVentas);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $rechazadoFinanciera);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $segundaCaptura);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $revisionSegundaCaptura);
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $phPorAsignar);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $phEnProceso);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $i, $phCompleto);
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $insPorAsignar);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $insEnProceso);  
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $i, $insCompleto); 
        $i++;
    }
}
else
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "No hay resultados con el criterio de busqueda");
}


$conn->close();
    
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteEstatus.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_start();
$objWriter->save("php://output");
$xlsData = ob_get_contents();
ob_end_clean();

$response =  array(
    'op' => 'ok',
    'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
);
die(json_encode($response));

