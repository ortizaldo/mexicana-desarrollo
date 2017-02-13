<?php
session_start();
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();


$ROW_HEADER_REPOPRTE = 3;
$ROW_DATAOS_REPOPRTE = 4;


$objPHPExcel = new PHPExcel();

// TITULO DEL REPORTE
$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', "REPORTE DE RECHAZOS");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A1")->getFont()->setSize(16);

// HEADERS DE LA TABLA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'. $ROW_HEADER_REPOPRTE, "No. Contrato")
    ->setCellValue('B'. $ROW_HEADER_REPOPRTE, "Agencia")
    ->setCellValue('C'. $ROW_HEADER_REPOPRTE, "Rechazado Por")
    ->setCellValue('D'. $ROW_HEADER_REPOPRTE, "Motivo de Rechazo");

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);


$objPHPExcel->getActiveSheet()->setTitle('Reporte Ventas');

$id = "";
$agreementNumber = "";
$agencia = "";
$validadoPor = "";
$reason = "";

$data = array();
$response = array();

$fechaInicial = (isset($_POST['dateF']) && $_POST['dateF'] != "") ? $_POST['dateF'] : '';
$fechaFinal = (isset($_POST['dateT']) && $_POST['dateT'] != "") ? $_POST['dateT'] : '';
$nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';

$stmt = $conn->prepare("call spReporteRechazos(?,?,?);");
mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);


// BODY DEL  REPORTE
if ($stmt->execute()) 
{
    $stmt->store_result();
    $stmt->bind_result($id, $agreementNumber, $agencia, $validadoPor, $reason);

    $i = $ROW_DATAOS_REPOPRTE;
    while ($stmt->fetch()) 
    {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $agreementNumber);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $agencia);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $validadoPor);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $reason);
        $i++;
    }
}
else
{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "No hay resultados con el criterio de busqueda");
}
    
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteRechazos.xlsx"');
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
