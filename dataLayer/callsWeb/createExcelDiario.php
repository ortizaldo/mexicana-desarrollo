<?php
session_start();
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';

$DB = new DAO();
$conn = $DB->getConnect();
$datosForms=$_POST["collection"];
$datosFormsT=$_POST["collectionT"];
$now=$_POST["now"];
$ayer=$_POST["ayer"];
//var_dump($datosForms);

//die();
$ROW_HEADER_REPOPRTE = 3;
$ROW_DATAOS_REPOPRTE = 4;


$objPHPExcel = new PHPExcel();

// TITULO DEL REPORTE
$objPHPExcel->getActiveSheet()->mergeCells('A1:Q1');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A1', "REPORTE DIARIO DE VENTAS");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A1")->getFont()->setSize(16);

$objPHPExcel->getActiveSheet()->mergeCells('A2:B2');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A2', "FECHA");

$sheet->getStyle("A2")->getFont()->setSize(13);


$objPHPExcel->getActiveSheet()->mergeCells('C2:E2');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('C2', $ayer);

$sheet->getStyle("C2")->getFont()->setSize(13);


$objPHPExcel->getActiveSheet()->mergeCells('A3:E4');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('A3', "TOTAL");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("A3")->getFont()->setSize(14);

$objPHPExcel->getActiveSheet()->mergeCells('G2:H2');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('G2', "FECHA");

$sheet->getStyle("G2")->getFont()->setSize(13);


$objPHPExcel->getActiveSheet()->mergeCells('I2:K2');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('I2', $now);

$sheet->getStyle("I2")->getFont()->setSize(13);


$objPHPExcel->getActiveSheet()->mergeCells('G3:K4');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('G3', "TOTAL");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('G3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("G3")->getFont()->setSize(14);


$objPHPExcel->getActiveSheet()->mergeCells('M3:Q4');
$objPHPExcel->setActiveSheetIndex(0)
             ->setCellValue('M3', "TOTALES");

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('M3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$sheet->getStyle("M3")->getFont()->setSize(14);


if (intval($datosForms["total"]["contratos"]) > 0 && intval($datosForms["total"]["medidores"]) > 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A5', "Contratos");

    $sheet->getStyle("A5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C5', $datosForms["total"]["contratos"]);

    $sheet->getStyle("C5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A6', "Medidores Instalados");

    $sheet->getStyle("A6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C6:E6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C6', $datosForms["total"]["medidores"]);

    $sheet->getStyle("C6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A7:E8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("A7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('A9:B9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A9', "Agencia Comercializadora");

    $sheet->getStyle("A9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C9:E9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C9', "Contratos");

    $sheet->getStyle("C9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosForms["desglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A'.$i, $value["agencia"]);

        $sheet->getStyle("A".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('C'.$i, $value["numReportes"]);

        $sheet->getStyle("C".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "SUCURSAL");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYOPSA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "MEX. CONTADO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "FINANCIERAS");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYOPSA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "MEX. CONTADO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.EXP");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["Exp"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.LA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["loteActual"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.ZM");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["zonaMadura"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('C'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosForms["desglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A'.$i, $value["agencia"]);

        $sheet->getStyle("A".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('C'.$i, $value["numReportes"]);

        $sheet->getStyle("C".$i)->getFont()->setSize(13);

        $i++;
    }
}elseif (intval($datosForms["total"]["contratos"]) == 0 && intval($datosForms["total"]["medidores"]) == 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A5', "Contratos");

    $sheet->getStyle("A5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C5', 0);

    $sheet->getStyle("C5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A6', "Medidores Instalados");

    $sheet->getStyle("A6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C6:E6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C6', 0);

    $sheet->getStyle("C6")->getFont()->setSize(13);
}elseif (intval($datosForms["total"]["contratos"]) > 0 && intval($datosForms["total"]["medidores"]) == 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A5', "Contratos");

    $sheet->getStyle("A5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C5', $datosForms["total"]["contratos"]);

    $sheet->getStyle("C5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A6', "Medidores Instalados");

    $sheet->getStyle("A6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C6:E6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C6', $datosForms["total"]["medidores"]);

    $sheet->getStyle("C6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A7:E8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("A7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('A9:B9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A9', "Agencia Comercializadora");

    $sheet->getStyle("A9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C9:E9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C9', "Contratos");

    $sheet->getStyle("C9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosForms["desglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A'.$i, $value["agencia"]);

        $sheet->getStyle("A".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('C'.$i, $value["numReportes"]);

        $sheet->getStyle("C".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "SUCURSAL");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYOPSA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "MEX. CONTADO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "FINANCIERAS");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYOPSA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "MEX. CONTADO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.EXP");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["Exp"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.LA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["loteActual"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AYO.ZM");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, $datosForms["desglose"]["desgloseFinanciamiento"]["financieras"]["zonaMadura"]);

    $sheet->getStyle("C".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('C'.$i)->getFont()->setSize(13);
}elseif (intval($datosForms["total"]["contratos"]) == 0 && intval($datosForms["total"]["medidores"]) > 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('A5:B5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A5', "Contratos");

    $sheet->getStyle("A5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C5:E5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C5', 0);

    $sheet->getStyle("C5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('A6:B6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A6', "Medidores Instalados");

    $sheet->getStyle("A6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C6:E6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C6', $datosForms["total"]["medidores"]);

    $sheet->getStyle("C6")->getFont()->setSize(13);
    $i = 7;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('A'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("A".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('C'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('C'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosForms["desglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':B'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('A'.$i, $value["agencia"]);

        $sheet->getStyle("A".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('C'.$i.':E'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('C'.$i, $value["numReportes"]);

        $sheet->getStyle("C".$i)->getFont()->setSize(13);

        $i++;
    }
}

if (intval($datosForms["hoy"]["total"]["contratos"]) > 0 && intval($datosForms["hoy"]["total"]["medidores"]) > 0) {
    $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G5', "Contratos");

    $sheet->getStyle("G5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I5:K5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I5', $datosForms["hoy"]["total"]["contratos"]);

    $sheet->getStyle("I5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G6', "Medidores Instalados");

    $sheet->getStyle("G6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I6:K6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I6', $datosForms["hoy"]["total"]["medidores"]);

    $sheet->getStyle("I6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('G7:K8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('G7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("G7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('G9:H9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G9', "Agencia Comercializadora");

    $sheet->getStyle("G9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I9:K9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I9', "Contratos");

    $sheet->getStyle("I9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosForms["hoy"]["desglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('G'.$i, $value["agencia"]);

        $sheet->getStyle("G".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('I'.$i, $value["numReportes"]);

        $sheet->getStyle("I".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "SUCURSAL");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AYOPSA");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "MEX. CONTADO");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "FINANCIERAS");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AYOPSA");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "MEX. CONTADO");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AYO.EXP");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["desgloseFinanciamiento"]["financieras"]["Exp"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AYO.LA");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["desgloseFinanciamiento"]["financieras"]["loteActual"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AYO.ZM");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, $datosForms["hoy"]["desglose"]["desgloseFinanciamiento"]["financieras"]["zonaMadura"]);

    $sheet->getStyle("I".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('I'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosForms["hoy"]["desglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('G'.$i, $value["agencia"]);

        $sheet->getStyle("G".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('I'.$i, $value["numReportes"]);

        $sheet->getStyle("I".$i)->getFont()->setSize(13);

        $i++;
    }

    /*---------------------TERMINA TOTALES HOY-------------------------------------*/


    /*---------------------TOTALES-------------------------------------*/
    $objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M5', "Contratos");

    $sheet->getStyle("M5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O5:Q5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O5', $datosFormsT["totalContratosMed"]["total"]["contratos"]);

    $sheet->getStyle("O5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M6:N6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M6', "Medidores Instalados");

    $sheet->getStyle("M6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O6:Q6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O6', $datosFormsT["totalContratosMed"]["total"]["medidores"]);

    $sheet->getStyle("O6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M7:Q8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('M7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("M7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('M9:N9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M9', "Agencia Comercializadora");

    $sheet->getStyle("M9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O9:Q9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O9', "Contratos");

    $sheet->getStyle("O9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosFormsT["totalContratosMed"]["totalDesglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("M".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "SUCURSAL");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "FINANCIERAS");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.EXP");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["exp"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.LA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["loteActual"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.ZM");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["zonaMadura"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('O'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosFormsT["totalContratosMed"]["totalDesglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("M".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }
    /*---------------------TERMINA TOTALES-------------------------------------*/
}elseif (intval($datosForms["hoy"]["total"]["contratos"]) == 0 && intval($datosForms["hoy"]["total"]["medidores"]) == 0){
    $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G5', "Contratos");

    $sheet->getStyle("G5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I5:K5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I5', 0);

    $sheet->getStyle("I5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G6', "Medidores Instalados");

    $sheet->getStyle("G6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I6:K6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I6', 0);

    $sheet->getStyle("I6")->getFont()->setSize(13);


    /*---------------------TOTALES-------------------------------------*/
    $objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M5', "Contratos");

    $sheet->getStyle("M5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O5:Q5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O5', $datosFormsT["totalContratosMed"]["total"]["contratos"]);

    $sheet->getStyle("O5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M6:N6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M6', "Medidores Instalados");

    $sheet->getStyle("M6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O6:Q6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O6', $datosFormsT["totalContratosMed"]["total"]["medidores"]);

    $sheet->getStyle("O6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M7:Q8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('M7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("M7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('M9:N9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M9', "Agencia Comercializadora");

    $sheet->getStyle("M9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O9:Q9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O9', "Contratos");

    $sheet->getStyle("O9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosFormsT["totalContratosMed"]["desglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("M".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "SUCURSAL");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "FINANCIERAS");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.EXP");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["Exp"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.LA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosForms["totales"]["totalDesglose"]["desgloseFinanciamiento"]["loteActual"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.ZM");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["zonaMadura"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('O'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosFormsT["totalContratosMed"]["totalDesglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("N".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }
    /*---------------------TERMINA TOTALES-------------------------------------*/
}elseif (intval($datosForms["hoy"]["total"]["contratos"]) == 0 && intval($datosForms["hoy"]["total"]["medidores"]) > 0){
    $objPHPExcel->getActiveSheet()->mergeCells('G5:H5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G5', "Contratos");

    $sheet->getStyle("G5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I5:K5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I5', $datosForms["hoy"]["total"]["contratos"]);

    $sheet->getStyle("I5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('G6:H6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G6', "Medidores Instalados");

    $sheet->getStyle("G6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I6:K6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I6', $datosForms["hoy"]["total"]["medidores"]);

    $sheet->getStyle("I6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('G7:K8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('G7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("G7")->getFont()->setSize(14);
    $i = 9;

    $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('G'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("G".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('I'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('I'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosForms["hoy"]["desglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('G'.$i.':H'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('G'.$i, $value["agencia"]);

        $sheet->getStyle("G".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('I'.$i.':K'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('I'.$i, $value["numReportes"]);

        $sheet->getStyle("I".$i)->getFont()->setSize(13);

        $i++;
    }

    /*---------------------TERMINA TOTALES HOY-------------------------------------*/


    /*---------------------TOTALES-------------------------------------*/
    $objPHPExcel->getActiveSheet()->mergeCells('M5:N5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M5', "Contratos");

    $sheet->getStyle("M5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O5:Q5');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O5', $datosFormsT["totalContratosMed"]["total"]["contratos"]);

    $sheet->getStyle("O5")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M6:N6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M6', "Medidores Instalados");

    $sheet->getStyle("M6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O6:Q6');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O6', $datosFormsT["totalContratosMed"]["total"]["medidores"]);

    $sheet->getStyle("O6")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('M7:Q8');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M7', "DESGLOSE");

    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('M7')->getAlignment()->applyFromArray(
        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
    );
    $sheet->getStyle("M7")->getFont()->setSize(14);

    $objPHPExcel->getActiveSheet()->mergeCells('M9:N9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M9', "Agencia Comercializadora");

    $sheet->getStyle("M9")->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O9:Q9');
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O9', "Contratos");

    $sheet->getStyle("O9")->getFont()->setSize(13);

    $i = 10;
    foreach ($datosFormsT["totalContratosMed"]["totalDesglose"]["agencias"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("M".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "SUCURSAL");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["sucursal"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);


    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "FINANCIERAS");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYOPSA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosAyo"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "MEX. CONTADO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["financieras"]["numContratosMex"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "DESGLOSE DEL FINANCIAMIENTO");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.EXP");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["exp"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.LA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["loteActual"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AYO.ZM");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, $datosFormsT["totalContratosMed"]["totalDesglose"]["desgloseFinanciamiento"]["zonaMadura"]);

    $sheet->getStyle("O".$i)->getFont()->setSize(13);

    $i++;
    $i++;
    $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('M'.$i, "AGENCIA INSTALADORA");

    $sheet->getStyle("M".$i)->getFont()->setSize(13);

    $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
    $objPHPExcel->setActiveSheetIndex(0)
                 ->setCellValue('O'.$i, "MEDIDORES INSTALADOS");

    $sheet->getStyle('O'.$i)->getFont()->setSize(13);
    $i++;

    foreach ($datosFormsT["totalContratosMed"]["totalDesglose"]["agenciaInst"] as $key => $value) {
        $objPHPExcel->getActiveSheet()->mergeCells('M'.$i.':N'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('M'.$i, $value["agencia"]);

        $sheet->getStyle("N".$i)->getFont()->setSize(13);

        $objPHPExcel->getActiveSheet()->mergeCells('O'.$i.':Q'.$i);
        $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue('O'.$i, $value["numReportes"]);

        $sheet->getStyle("O".$i)->getFont()->setSize(13);

        $i++;
    }
    /*---------------------TERMINA TOTALES-------------------------------------*/
}

$objPHPExcel->getActiveSheet()->setTitle('Reporte Ventas');
    
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="ReporteDiarioVentas.xlsx"');
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

