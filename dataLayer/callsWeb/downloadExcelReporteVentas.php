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
$objPHPExcel->getActiveSheet()->mergeCells('A1:M1');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', "REPORTE DE VENTAS");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A1")->getFont()->setSize(16);

// HEADERS DE LA TABLA
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'. $ROW_HEADER_REPOPRTE, "ID")
    ->setCellValue('B'. $ROW_HEADER_REPOPRTE, "Contrato")
    ->setCellValue('C'. $ROW_HEADER_REPOPRTE, "No. Cliente")
    ->setCellValue('D'. $ROW_HEADER_REPOPRTE, "PH")
    ->setCellValue('E'. $ROW_HEADER_REPOPRTE, "Venta")
    ->setCellValue('F'. $ROW_HEADER_REPOPRTE, "Segunda Venta")
    ->setCellValue('G'. $ROW_HEADER_REPOPRTE, "Instalación")
    ->setCellValue('H'. $ROW_HEADER_REPOPRTE, "Municipio")
    ->setCellValue('I'. $ROW_HEADER_REPOPRTE, "Colonia")
    ->setCellValue('J'. $ROW_HEADER_REPOPRTE, "Calle")
    ->setCellValue('K'. $ROW_HEADER_REPOPRTE, "Usuario")
    ->setCellValue('L'. $ROW_HEADER_REPOPRTE, "Agencia")
    ->setCellValue('M'. $ROW_HEADER_REPOPRTE, "Fecha");

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


$objPHPExcel->getActiveSheet()->setTitle('Reporte Ventas');

$id = "";
$agreementNumber = "";
$idClienteGenerado = "";
$estatus_ph = "";
$estatus_venta = "";
$estatus_instalacion = "";
$idCity = "";
$colonia = "";
$street = "";
$nombre_usuario = "";
$agencia = "";
$fecha = "";

$estatusVenta = "";
$phEstatus = "";
$estatusAsignacionInstalacion = "";
$data = array();
$response = array();

$fechaInicial = (isset($_POST['dateF']) && $_POST['dateF'] != "") ? $_POST['dateF'] : '';
$fechaFinal = (isset($_POST['dateT']) && $_POST['dateT'] != "") ? $_POST['dateT'] : '';
$nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';


    
$stmt = $conn->prepare("call spReporteVentas(?,?,?);");
mysqli_stmt_bind_param($stmt, 'sss', $fechaInicial, $fechaFinal, $nickName);


// BODY DEL  REPORTE
if ($stmt->execute()) 
{
    $stmt->store_result();
    $stmt->bind_result($id, $agreementNumber, $idClienteGenerado,$phEstatus, $estatus_ph, $estatusVenta,$estatus_venta, $estatusAsignacionInstalacion,$validacionSegundaVenta,$estatus_seg_venta,  $estatusReporte,$estatus_instalacion, $idCity, $colonia, $street,$innerNumber,$outterNumber, $nombre_usuario, $agencia, $fecha);

    $i = $ROW_DATAOS_REPOPRTE;
    while ($stmt->fetch()) 
    {
        if ($estatusReporte === 66) {
            $estatus_ph = "CANCELADO";
            $estatus_venta = "CANCELADO";
            $estatus_seg_venta = "CANCELADO";
            $estatus_instalacion = "CANCELADO";
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $id);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $agreementNumber);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $idClienteGenerado);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $estatus_ph);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $estatus_venta);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $estatus_seg_venta);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $estatus_instalacion);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H' . $i, $idCity);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I' . $i, $colonia);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $i, $street.' - Num: '.$innerNumber);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $i, $nombre_usuario);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $i, $agencia);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $i, $fecha);   
        $i++;
    }
}
else
{
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

