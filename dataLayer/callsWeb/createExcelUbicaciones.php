<?php
date_default_timezone_set('America/Monterrey');
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();
$datosForms=$_POST["collection"];
if (isset($_POST["dateFrom"]) && isset($datosForms)) {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Mexicana de Gas")
        ->setLastModifiedBy("Mexicana de Gas")
        ->setTitle("Reporte General de formularios creados")
        ->setSubject("Reporte de formularios por empleados de la agencia")
        ->setDescription("Documento en formato xls creado en el sitio Web de Mexicana de Gas")
        ->setKeywords("office 2007 openxml excel reportes")
        ->setCategory("Reportes");
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', "ID");
    $objPHPExcel->getActiveSheet()->setCellValue('B1', "Agencia");
    $objPHPExcel->getActiveSheet()->setCellValue('C1', "Rol del Empleado");
    $objPHPExcel->getActiveSheet()->setCellValue('D1', "Empleado");
    $objPHPExcel->getActiveSheet()->setCellValue('E1', "Contrato");
    $objPHPExcel->getActiveSheet()->setCellValue('F1', "Tipo");
    $objPHPExcel->getActiveSheet()->setCellValue('G1', "Municipio");
    $objPHPExcel->getActiveSheet()->setCellValue('H1', "Colonia");
    $objPHPExcel->getActiveSheet()->setCellValue('I1', "Calle y Número");
    $objPHPExcel->getActiveSheet()->setCellValue('J1', "Distancia Recorrida - KM");
    $objPHPExcel->getActiveSheet()->setCellValue('K1', "Tiempo de trayecto");
    $objPHPExcel->getActiveSheet()->setCellValue('L1', "Estatus");
    $cellsDatosGrales = ['A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1'];
    foreach ($cellsDatosGrales as $key) {
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($key)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => "00853f"
            )
        ));
    }
    
    $objPHPExcel->createSheet(1); //Setting index when creating
    $objPHPExcel->setActiveSheetIndex(1);
      //Write cells
    $objPHPExcel->getActiveSheet()->setCellValue('A1', "Fecha");
    $objPHPExcel->getActiveSheet()->setCellValue('B1', "Agencia");
    $objPHPExcel->getActiveSheet()->setCellValue('C1', "Rol Empleado");
    $objPHPExcel->getActiveSheet()->setCellValue('D1', "Nombre Empleado");
    $objPHPExcel->getActiveSheet()->setCellValue('E1', "Distancia recorrida");
    $objPHPExcel->getActiveSheet()->setCellValue('F1', "Tiempo de trayecto");

    $cellsDatosGrales = ['A1','B1','C1','D1','E1','F1'];
    foreach ($cellsDatosGrales as $key) {
        $objPHPExcel->setActiveSheetIndex(1)->getStyle($key)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => "00853f"
            )
        ));
    }
    $objPHPExcel->getActiveSheet()->setTitle('Trayectoria');
    $objPHPExcel->getActiveSheet()->freezePane('A2');
    $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
    $i = 2;
    foreach ($datosForms['trayectoria'] as $key => $value) {
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value["created"])
            ->setCellValue('B' . $i, $_POST["agency"])
            ->setCellValue('C' . $i, $value["tipoPerfil"])
            ->setCellValue('D' . $i, $value["nickname"])
            ->setCellValue('E' . $i, $value["distanciaRecorrida"])
            ->setCellValue('F' . $i, $value["tiempoRecorrido"]);
        $i++;
    }


    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('Vectores');
    $objPHPExcel->getActiveSheet()->freezePane('A2');
    $objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
    $i = 2;
    foreach ($datosForms['vectores'] as $key => $value) {
        $streetNumber = "";
        if (isset($value["innerNumber"])) {
            $streetNumber = $value["street"] .' '. $value["innerNumber"];
        } else {
            $streetNumber = $value["street"] .' '. $value["outterNumber"];
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $value["id"])
            ->setCellValue('B' . $i, $_POST["agency"])
            ->setCellValue('C' . $i, $value["perfil"])
            ->setCellValue('D' . $i, $value["nickname"])
            ->setCellValue('E' . $i, $value["agreementNumber"])
            ->setCellValue('F' . $i, $value["tipoReporte"])
            ->setCellValue('G' . $i, $value["idCity"])
            ->setCellValue('H' . $i, $value["colonia"])
            ->setCellValue('I' . $i, $streetNumber)
            ->setCellValue('J' . $i, $value["distanciaRecorrida"])
            ->setCellValue('K'.  $i, $value["tiempoRecorrido"])
            ->setCellValue('L' . $i, $value["status"]);
        $i++;
    }

    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="reporteFormularios.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
     ob_start();
    $objWriter->save("php://output");
    $xlsData = ob_get_contents();
    ob_end_clean();

    $response =  array(
        'op' => 'ok',
        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
    );
    die(json_encode($response));
}