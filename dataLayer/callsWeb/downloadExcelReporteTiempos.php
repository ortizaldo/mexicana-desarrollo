<?php
session_start();
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/classes/reporteTiemposVentas.php";

$DB = new DAO();
$conn = $DB->getConnect();

$ROW_HEADER_REPOPRTE = 3;
$ROW_DATAOS_REPOPRTE = 4;

$objPHPExcel = new PHPExcel();

// TITULO DEL REPORTE
$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', "REPORTE TIEMPOS DE VENTAS");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A1")->getFont()->setSize(16);

// HEADERS DE LA TABLA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'. $ROW_HEADER_REPOPRTE, "No. cliente")
    ->setCellValue('B'. $ROW_HEADER_REPOPRTE, "Tiempo Rev. Venta")
    ->setCellValue('C'. $ROW_HEADER_REPOPRTE, "Tiempo Rev. Financiera")
    ->setCellValue('D'. $ROW_HEADER_REPOPRTE, "Tiempo doc. rechazada")
    ->setCellValue('E'. $ROW_HEADER_REPOPRTE, "Tiempo 1era-2da Cap.")
    ->setCellValue('F'. $ROW_HEADER_REPOPRTE, "Tiempo asign. PH")
    ->setCellValue('G'. $ROW_HEADER_REPOPRTE, "Tiempo realiz. PH")
    ->setCellValue('H'. $ROW_HEADER_REPOPRTE, "Tiempo PH anomalia")
    ->setCellValue('I'. $ROW_HEADER_REPOPRTE, "Tiempo asign. Inst.")
    ->setCellValue('J'. $ROW_HEADER_REPOPRTE, "Tiempo realiz. Inst.")
    ->setCellValue('K'. $ROW_HEADER_REPOPRTE, "Tiempo Inst. anomalia");

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


$objPHPExcel->getActiveSheet()->setTitle('Reporte Tiempos de Ventas');
$data = json_decode(json_encode($_POST['collectionData']));
$response = array();
if (count($_POST['collectionData']) > 0){
    $i = $ROW_DATAOS_REPOPRTE;
    foreach ($_POST['collectionData'] as $value){
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["No_Cliente"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["convFecTotVenta"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["convFecTotFin"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["convFecTotRech"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["convFecTotSegC"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["convFecTotPHA"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["convFecTotRPH"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["convFecTotAnPH"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["convFecTotRIAs"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["convFecTotRI"]);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["convFecTotRIAn"]);
        $i++;
    }
}else{
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "No hay resultados con el criterio de busqueda");
}
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteVentas.xlsx"');
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

