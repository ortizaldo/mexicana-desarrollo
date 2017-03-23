<?php
//error_reporting(E_ALL);
date_default_timezone_set('America/Monterrey');
require_once '../libs/PHPExcelTest/Classes/PHPExcel.php';
include_once '../DAO.php';


$DB = new DAO();
$conn = $DB->getConnect();
$datosForms=$_POST["collection"];
if (isset($_POST["collection"])) {
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Mexicana de Gas")
        ->setLastModifiedBy("Mexicana de Gas")
        ->setTitle("Reporte General de formularios creados")
        ->setSubject("Reporte de formularios por empleados de la agencia")
        ->setDescription("Documento en formato xls creado en el sitio Web de Mexicana de Gas")
        ->setKeywords("office 2007 openxml excel reportes")
        ->setCategory("Reportes");
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', "ID")
                ->setCellValue('B1', "Numero Cliente")
                ->setCellValue('C1', "Contrato")
                ->setCellValue('D1', "Tipo")
                ->setCellValue('E1', "Estatus")
                ->setCellValue('F1', "Municipio")
                ->setCellValue('G1', "Colonia")
                ->setCellValue('H1', "Calle y Numero")
                ->setCellValue('I1', "Empleado")
                ->setCellValue('J1', "Agencia")
                //venta
                ->setCellValue('K1', "Cliente interesado en contratar el servicio?")
                ->setCellValue('L1', "Motivo del desinterés")
                ->setCellValue('M1', "Comentarios")
                ->setCellValue('N1', "Se encuentra el titular")
                ->setCellValue('O1', "Nombre")
                ->setCellValue('P1', "Apellido Paterno")
                ->setCellValue('Q1', "Apellido Paterno")
                ->setCellValue('R1', "Financiera")
                ->setCellValue('S1', "Forma de Pago")
                //plomero
                ->setCellValue('T1', "Número de dictamen técnico")
                ->setCellValue('U1', "Color del tapón")
                ->setCellValue('V1', "RI menor a 40 mts")
                ->setCellValue('W1', "Observaciones")
                ->setCellValue('X1', "Cálculo de caída de presión")
                ->setCellValue('Y1', "Se requiere tubería")
                ->setCellValue('Z1', "Resultado PH")
                ->setCellValue('AA1', "Número de tomas")
                //censo
                ->setCellValue('AB1', "Tipo de lote")
                ->setCellValue('AC1', "Estatus de vivienda")
                ->setCellValue('AD1', "Niveles socioeconónimos de la vivienda(NSE)")
                ->setCellValue('AE1', "Giro")
                ->setCellValue('AF1', "Acometida")
                ->setCellValue('AG1', "Color de Tapón")
                ->setCellValue('AH1', "Medidor")
                ->setCellValue('AI1', "Marca de medidor")
                ->setCellValue('AJ1', "Tipo de medidor")
                ->setCellValue('AK1', "No. de Serie")
                ->setCellValue('AL1', "Niple de corte")
                //SegundaVenta
                ->setCellValue('AM1', "Pagaré")
                ->setCellValue('AN1', "Número de contrato")
                ->setCellValue('AO1', "Fecha de solicitud")
                ->setCellValue('AP1', "Nombre")
                ->setCellValue('AQ1', "Apellido paterno")
                ->setCellValue('AR1', "Apellido materno")
                ->setCellValue('AS1', "RFC")
                ->setCellValue('AT1', "CURP")
                ->setCellValue('AU1', "Correo")
                ->setCellValue('AV1', "Sexo")
                ->setCellValue('AW1', "Estado civil")
                ->setCellValue('AX1', "Tipo de identificación")
                ->setCellValue('AY1', "Identificación")
                ->setCellValue('AZ1', "Fecha de Nacimiento")
                ->setCellValue('BA1', "País de Nacimiento")
                ->setCellValue('BB1', "Estado")
                ->setCellValue('BC1', "Municipio")
                ->setCellValue('BD1', "Colonia")
                ->setCellValue('BE1', "Calle")
                ->setCellValue('BF1', "Numero")
                ->setCellValue('BG1', "Vive en casa")
                ->setCellValue('BH1', "Teléfono celular")
                ->setCellValue('BI1', "Teléfono de casa")
                ->setCellValue('BJ1', "Empresa")
                ->setCellValue('BK1', "Puesto")
                ->setCellValue('BL1', "Dirección")
                ->setCellValue('BM1', "Teléfono")
                ->setCellValue('BN1', "Actividad/Área")
                ->setCellValue('BO1', "Tipo de contrato")
                ->setCellValue('BP1', "Plazo")
                ->setCellValue('BQ1', "Precio")
                ->setCellValue('BR1', "Mensualidad")
                ->setCellValue('BS1', "RI")
                ->setCellValue('BT1', "Fecha RI")
                ->setCellValue('BU1', "Nombre referencia 1")
                ->setCellValue('BV1', "Teléfono de trabajo referencia 1")
                ->setCellValue('BW1', "Teléfono particular referencia")
                ->setCellValue('BX1', "Extensión referencia 1")
                ->setCellValue('BY1', "Nombre Ref. 2")
                ->setCellValue('BZ1', "Teléfono de trabajo referencia 2")
                ->setCellValue('CA1', "Teléfono particular referencia2")
                ->setCellValue('CB1', "Extensión referencia 2")
                //instalacion
                ->setCellValue('CC1', "Color de etiqueta de PH")
                ->setCellValue('CD1', "Número de agencia PH?")
                ->setCellValue('CE1', "Número de la agencia")
                ->setCellValue('CF1', "Procede a instalación?")
                ->setCellValue('CG1', "Catálogo de anomalías")
                ->setCellValue('CH1', "Comentarios")
                ->setCellValue('CI1', "Marca de medidor")
                ->setCellValue('CJ1', "Tipo de medidor")
                ->setCellValue('CK1', "No. de Serie")
                ->setCellValue('CL1', "Lectura del medidor")
                ->setCellValue('CM1', "t_Union_3-4")
                ->setCellValue('CN1', "Cantidad_t_Union_3-4")
                ->setCellValue('CO1', "t_Red_Camp_3-4_1-2")
                ->setCellValue('CP1', "Cantidad_t_Red_Camp_3-4_1-2")
                ->setCellValue('CQ1', "t_t_Codo3-4x90_150")
                ->setCellValue('CR1', "Cantidad_t_t_Codo3-4x90_150")
                ->setCellValue('CS1', "t_CodoNip_3-4_150")
                ->setCellValue('CT1', "Cantidad_t_CodoNip_3-4_150")
                ->setCellValue('CU1', "t_Tee3-4")
                ->setCellValue('CV1', "Cantidad_t_Tee3-4")
                ->setCellValue('CW1', "t_Cople3-4_150")
                ->setCellValue('CX1', "Cantidad_t_Cople3-4_150")
                ->setCellValue('CY1', "t_TaponM_3-4_Neg")
                ->setCellValue('CZ1', "Cantidad_t_TaponM_3-4_Neg")
                ->setCellValue('DA1', "t_Conex_p_medid_dom")
                ->setCellValue('DB1', "Cantidad_t_Conex_p_medid_dom")
                ->setCellValue('DC1', "t_Val_esfer_3-4_125lbs")
                ->setCellValue('DD1', "Cantidad_t_Val_esfer_3-4_125lbs")
                ->setCellValue('DE1', "t_Reg_Ameri_CR-4000_3-4")
                ->setCellValue('DF1', "Cantidad_t_Reg_Ameri_CR-4000_3-4")
                ->setCellValue('DG1', "t_Niple_3-4_RC")
                ->setCellValue('DH1', "Cantidad_t_Niple_3-4_RC")
                ->setCellValue('DI1', "t_Niple_3-4_x2")
                ->setCellValue('DJ1', "Cantidad_t_Niple_3-4_x2")
                ->setCellValue('DK1', "t_Niple_3-4_x3")
                ->setCellValue('DL1', "Cantidad_t_Niple_3-4_x3")
                ->setCellValue('DM1', "t_Niple_3-4_x4")
                ->setCellValue('DN1', "Cantidad_t_Niple_3-4_x4")
                ->setCellValue('DO1', "t_Niple_3-4_x5")
                ->setCellValue('DP1', "Cantidad_t_Niple_3-4_x5")
                ->setCellValue('DQ1', "t_Niple_3-4_x6")
                ->setCellValue('DR1', "Cantidad_t_Niple_3-4_x6")
                ->setCellValue('DS1', "t_Niple_3-4_x7")
                ->setCellValue('DT1', "Cantidad_t_Niple_3-4_x7")
                ->setCellValue('DU1', "t_Niple_3-4_x8")
                ->setCellValue('DV1', "Cantidad_t_Niple_3-4_x8")
                ->setCellValue('DW1', "t_Niple_3-4_x9")
                ->setCellValue('DX1', "Cantidad_t_Niple_3-4_x9")
                ->setCellValue('DY1', "t_Niple_3-4_x10")
                ->setCellValue('DZ1', "Cantidad_t_Niple_3-4_x10")
                ->setCellValue('EA1', "t_Union_1Std")
                ->setCellValue('EB1', "Cantidad_t_Union_1Std")
                ->setCellValue('EC1', "t_Red_Camp_1x3-4")
                ->setCellValue('ED1', "Cantidad_t_Red_Camp_1x3-4")
                ->setCellValue('EE1', "t_Red_Bush_1x3-4")
                ->setCellValue('EF1', "Cantidad_t_Red_Bush_1x3-4")
                ->setCellValue('EG1', "t_Val_esfer_1_125lbs")
                ->setCellValue('EH1', "Cantidad_t_Val_esfer_1_125lbs")
                ->setCellValue('EI1', "t_Niple_1-2_x_2")
                ->setCellValue('EJ1', "Cantidad_t_Niple_1-2_x_2")
                ->setCellValue('EK1', "t_reduccion")
                ->setCellValue('EL1', "Cantidad_t_reduccion")
                ->setCellValue('EM1', "t_blanco_plomo")
                ->setCellValue('EN1', "Cantidad_t_blanco_plomo")
                ->setCellValue('EO1', "t_cinta_teflon")
                ->setCellValue('EP1', "Cantidad_t_cinta_teflon")
                ->setCellValue('EQ1', "Fecha");
    $cellsDatosGrales = ['A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1',
                         'M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1','W1','X1',
                         'Y1','Z1','AA1','AB1','AC1','AD1','AE1','AF1','AG1','AH1',
                         'AI1','AJ1','AK1','AL1','AM1','AN1','AO1','AP1','AQ1','AR1',
                         'AS1','AT1','AU1','AV1','AW1','AX1','AY1','AZ1','BA1','BB1',
                         'BC1','BD1','BE1','BF1','BG1','BH1','BI1','BJ1','BK1','BL1',
                         'BM1','BN1','BO1','BP1','BQ1','BR1','BS1','BT1','BU1','BV1',
                         'BW1','BX1','BY1','BZ1','CA1','CB1','CC1','CD1','CE1','CF1',
                         'CG1','CH1','CI1','CJ1','CK1','CL1','CM1','CN1','CO1','CP1',
                         'CQ1','CR1','CS1','CT1','CU1','CV1','CW1','CX1','CY1','CZ1',
                         'DA1','DB1','DC1','DD1','DE1','DF1','DG1','DH1','DI1','DJ1',
                         'DK1','DL1','DM1','DN1','DO1','DP1','DQ1','DR1','DS1','DT1',
                         'DU1','DV1','DW1','DX1','DY1','DZ1','EA1','EB1','EC1','ED1',
                         'EE1','EF1','EG1','EH1','EI1','EJ1','EK1','EL1','EM1','EN1',
                         'EO1','EP1','EQ1'];
    foreach ($cellsDatosGrales as $key) {
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($key)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                 'rgb' => "00853f"
            )
        ));
    }
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('Reportes');


    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);

    $i = 2;
    foreach ($datosForms as $key => $value) {
        $streetNumber = "";
        if (isset($value['datosGrales']["innerNumber"])) {
            $streetNumber = $value['datosGrales']["street"] . " " . $value['datosGrales']["innerNumber"];
        } else {
            $streetNumber = $value['datosGrales']["street"] . " " . $reportData['datosGrales']["outterNumber"];
        }
        switch ($value['datosGrales']["name"]) {
            case 'Censo':
                $tapon      = (intval($value['censo']['tapon']) == 1) ? 'Sí' : 'No';
                $acometida  = (intval($value['censo']['acometida']) == 1) ? 'Sí' : 'No';
                $medidor    = (intval($value['censo']['medidor']) == 1) ? 'Sí' : 'No';
                $niple      = (intval($value['censo']['niple']) == 1) ? 'Sí' : 'No';

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                            ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                            ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                            ->setCellValue('D' . $i, $value['datosGrales']["name"])
                            ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                            ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                            ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                            ->setCellValue('AB' . $i, $value['censo']['lote'])
                            ->setCellValue('AC' . $i, $value['censo']['houseStatus'])
                            ->setCellValue('AD' . $i, $value['censo']['nivel'])
                            ->setCellValue('AE' . $i, $value['censo']['giro'])
                            ->setCellValue('AF' . $i, $acometida)
                            ->setCellValue('AG' . $i, $tapon)
                            ->setCellValue('AH' . $i, $medidor)
                            ->setCellValue('AI' . $i, $value['censo']['marca'])
                            ->setCellValue('AJ' . $i, $value['censo']['tipo'])
                            ->setCellValue('AK' . $i, $value['censo']['NoSerie'])
                            ->setCellValue('AL' . $i, $niple)
                            ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                break;
            case 'Venta':
                $nombreFinanciera      = (intval($value['venta']['financialService']) == 1) ? 'AYOPSA' : 'MEXICANA DE GAS';
                $formaDePago      = (intval($value['venta']['payment']) == 1) ? 'FINANCIADO' : 'CONTADO';
                $owner      = (intval($value['venta']['owner']) == 1) ? 'Sí' : 'No';
                $uninteresting      = ($value['venta']['uninteresting'] == 'true') ? 'Sí' : 'No';
                
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                            ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                            ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                            ->setCellValue('D' . $i, $value['datosGrales']["name"])
                            ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                            ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                            ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                            ->setCellValue('K' . $i, $uninteresting)
                            ->setCellValue('L' . $i, $value['venta']['motivosDesinteres'])
                            ->setCellValue('M' . $i, $value['venta']['comments'])
                            ->setCellValue('N' . $i, $owner)
                            ->setCellValue('O' . $i, $value['venta']['name'])
                            ->setCellValue('P' . $i, $value['venta']['lastName'])
                            ->setCellValue('Q' . $i, $value['venta']['lastNameOp'])
                            ->setCellValue('R' . $i, $nombreFinanciera)
                            ->setCellValue('S' . $i, $formaDePago)
                            ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                break;
            case 'Plomero':
                if ($value['datosGrales']['estatusReporte'] == 'EN PROCESO') {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                                ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                                ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                                ->setCellValue('D' . $i, $value['datosGrales']["name"])
                                ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                                ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                                ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                                ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                }else{
                    $documentNumber =($value['plomero']['dictamen'] == '' ||
                                      $value['plomero']['dictamen'] == 'null') ? '' : $value['plomero']['dictamen'];
                    $tapon =($value['plomero']['tapon'] == '' || 
                             $value['plomero']['tapon'] == 'null') ? '' : $value['plomero']['tapon'];
                    $ri =($value['plomero']['ri'] == '' || $value['plomero']['ri'] == 'null') ? '' : $value['plomero']['ri'];
                    $observations =($value['plomero']['observations'] == '' || $value['plomero']['observations'] == 'null') ? '' : $value['plomero']['observations'];
                    $newPipe =($value['plomero']['newPipe'] == '' || $value['plomero']['newPipe'] == 'null') ? '' : $value['plomero']['newPipe'];
                    $ph =($value['plomero']['resultadoPH'] == '' || $value['plomero']['resultadoPH'] == 'null') ? '' : $value['plomero']['resultadoPH'];
                    $pipesCount =($value['plomero']['numTomas'] == '' || $value['plomero']['numTomas'] == 'null') ? '' : $value['plomero']['numTomas'];
                    $tapon = ($tapon == 1 ) ? 'Verde' : 'Rojo';
                    $newPipe = ($newPipe == 1 ) ? 'Positivo' : 'Negativo';
                    $ph = ($ph == 1 ) ? 'Positivo' : 'Negativo';
                    $ri = ($ri == 1 ) ? 'Si' : 'No';
                    $detalles='';
                    $cantidadDetalles=count($formPlumbDet);
                    $contador=1;
                    foreach ($value['plomero']['formPlumbDet'] as $key => $detValue) {
                        $detalles .=$contador.'.- Tramo: '.$detValue['path'].', Distancia: '.$detValue['distance'].', Tubería: '.$detValue['pipe'].', caída: '.$detValue['fall'].' - ';
                        //echo "detValue ".$detValue['distance'];
                        $contador++;
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                                ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                                ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                                ->setCellValue('D' . $i, $value['datosGrales']["name"])
                                ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                                ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                                ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                                ->setCellValue('T' . $i, $documentNumber)
                                ->setCellValue('U' . $i, $tapon)
                                ->setCellValue('V' . $i, $ri)
                                ->setCellValue('W' . $i, $observations)
                                ->setCellValue('X' . $i, $detalles)
                                ->setCellValue('Y' . $i, $newPipe)
                                ->setCellValue('Z' . $i, $ph)
                                ->setCellValue('AA' . $i, $pipesCount)
                                ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                }
                break;
            case 'Instalacion':
                if ($value['datosGrales']['estatusReporte'] == 'EN PROCESO') {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                                ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                                ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                                ->setCellValue('D' . $i, $value['datosGrales']["name"])
                                ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                                ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                                ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                                ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                }else{
                    $procede = ($value['installation']['installation'] == 1 ) ? 'SI' : 'NO';
                    $ph = ($value['installation']['phLabel'] == 1 ) ? 'VERDE' : 'ROJO';
                    $detalles='';
                    $contador=0;
                    $material1="";
                    $cantMat1="";
                    $material2="";
                    $cantMat2="";
                    $material3="";
                    $cantMat3="";
                    $material4="";
                    $cantMat4="";
                    $material5="";
                    $cantMat5="";
                    $material6="";
                    $cantMat6="";
                    $material7="";
                    $cantMat7="";
                    $material8="";
                    $cantMat8="";
                    $material9="";
                    $cantMat9="";
                    $material10="";
                    $cantMat10="";
                    $material11="";
                    $cantMat11="";
                    $material12="";
                    $cantMat12="";
                    $material13="";
                    $cantMat13="";
                    $material14="";
                    $cantMat14="";
                    $material15="";
                    $cantMat15="";
                    $material16="";
                    $cantMat16="";
                    $material17="";
                    $cantMat17="";
                    $material18="";
                    $cantMat18="";
                    $material19="";
                    $cantMat19="";
                    $material20="";
                    $cantMat20="";
                    $material21="";
                    $cantMat21="";
                    $material22="";
                    $cantMat22="";
                    $material23="";
                    $cantMat23="";
                    $material24="";
                    $cantMat24="";
                    $material25="";
                    $cantMat25="";
                    $material26="";
                    $cantMat26="";
                    $material27="";
                    $cantMat27="";
                    $material28="";
                    $cantMat28="";
                    foreach ($value['installation']['formInstDet'] as $key => $detValue) {
                        //error_log('message material'.$detValue["material"]);
                        switch ($detValue['material']) {
                            case 'Tue.Union 3/4 G':
                                $material1=$detValue["material"];
                                $cantMat1=$detValue["qty"];
                            break;
                            case 'Red.Camp.3/4-1/2 G':
                                $material2=$detValue["material"];
                                $cantMat2=$detValue["qty"];
                            break;
                            case 'Codo 3/4x90 150# G':
                                $material3=$detValue["material"];
                                $cantMat3=$detValue["qty"];
                            break;
                            case 'Codo Nip.3/4 150# G':
                                $material4=$detValue["material"];
                                $cantMat4=$detValue["qty"];
                            break;
                            case 'Tee 3/4 G':
                                $material5=$detValue["material"];
                                $cantMat5=$detValue["qty"];
                            break;
                            case 'Cople 3/4 150# G':
                                $material6=$detValue["material"];
                                $cantMat6=$detValue["qty"];
                            break;
                            case 'Tapon M.3/4 Neg':
                                $material7=$detValue["material"];
                                $cantMat7=$detValue["qty"];
                            break;
                            case 'Conex.p/medid.dom':
                                $material8=$detValue["material"];
                                $cantMat8=$detValue["qty"];
                            break;
                            case 'Val.esfer.3/4 125lbs Br.':
                                $material9=$detValue["material"];
                                $cantMat9=$detValue["qty"];
                            break;
                            case 'Reg.Ameri.CR-4000 3/4':
                                $material10=$detValue["material"];
                                $cantMat10=$detValue["qty"];
                            break;
                            case 'Niple 3/4 RC G':
                                $material11=$detValue["material"];
                                $cantMat11=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x2 G':
                                $material12=$detValue["material"];
                                $cantMat12=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x3 G':
                                $material13=$detValue["material"];
                                $cantMat13=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x4 G':
                                $material14=$detValue["material"];
                                $cantMat14=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x5 G':
                                $material15=$detValue["material"];
                                $cantMat15=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x6 G':
                                $material16=$detValue["material"];
                                $cantMat16=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x7 G':
                                $material17=$detValue["material"];
                                $cantMat17=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x8 G':
                                $material18=$detValue["material"];
                                $cantMat18=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x9 G':
                                $material19=$detValue["material"];
                                $cantMat19=$detValue["qty"];
                            break;
                            case 'Niple 3/4 x10 G':
                                $material20=$detValue["material"];
                                $cantMat20=$detValue["qty"];
                            break;
                            case 'Tue.Union 1Std.Neg':
                                $material21=$detValue["material"];
                                $cantMat21=$detValue["qty"];
                            break;
                            case 'Red.Camp.1x3/4 G':
                                $material22=$detValue["material"];
                                $cantMat22=$detValue["qty"];
                            break;
                            case 'Red.Bush.1x3/4 G':
                                $material23=$detValue["material"];
                                $cantMat23=$detValue["qty"];
                            break;
                            case 'Val.esfer.1 125lbs Br.':
                                $material24=$detValue["material"];
                                $cantMat24=$detValue["qty"];
                            break;
                            case 'Niple 1/2 x 2 G':
                                $material25=$detValue["material"];
                                $cantMat25=$detValue["qty"];
                            break;
                            case 'Red.Bush.3/4x1/2':
                                $material26=$detValue["material"];
                                $cantMat26=$detValue["qty"];
                            break;
                            case 'Sell.Bco.Plomo':
                                $material27=$detValue["material"];
                                $cantMat27=$detValue["qty"];
                            break;
                            case 'Cta.Teflon 3/4x260pl L':
                                $material28=$detValue["material"];
                                $cantMat28=$detValue["qty"];
                            break;
                        }
                        $contador++;
                    }
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                                ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                                ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                                ->setCellValue('D' . $i, $value['datosGrales']["name"])
                                ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                                ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                                ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                                ->setCellValue('CC' . $i, $ph)
                                ->setCellValue('CD' . $i, $value['installation']['agencyPh'])
                                ->setCellValue('CE' . $i, $value['installation']['agencyNumber'])
                                ->setCellValue('CF' . $i, $procede)
                                ->setCellValue('CG' . $i, $value['installation']['abnormalities'])
                                ->setCellValue('CH' . $i, $value['installation']['comments'])
                                ->setCellValue('CI' . $i, $value['installation']['brand'])
                                ->setCellValue('CJ' . $i, $value['installation']['type'])
                                ->setCellValue('CK' . $i, $value['installation']['serialNumber'])
                                ->setCellValue('CL' . $i, $value['installation']['measurement'])
                                ->setCellValue('CM' . $i, $material1)
                                ->setCellValue('CN' . $i, $cantMat1)
                                ->setCellValue('CO' . $i, $material2)
                                ->setCellValue('CP' . $i, $cantMat2)
                                ->setCellValue('CQ' . $i, $material3)
                                ->setCellValue('CR' . $i, $cantMat3)
                                ->setCellValue('CS' . $i, $material4)
                                ->setCellValue('CT' . $i, $cantMat4)
                                ->setCellValue('CU' . $i, $material5)
                                ->setCellValue('CV' . $i, $cantMat5)
                                ->setCellValue('CW' . $i, $material6)
                                ->setCellValue('CX' . $i, $cantMat6)
                                ->setCellValue('CY' . $i, $material7)
                                ->setCellValue('CZ' . $i, $cantMat7)
                                ->setCellValue('DA' . $i, $material8)
                                ->setCellValue('DB' . $i, $cantMat8)
                                ->setCellValue('DC' . $i, $material9)
                                ->setCellValue('DD' . $i, $cantMat9)
                                ->setCellValue('DE' . $i, $material10)
                                ->setCellValue('DF' . $i, $cantMat10)
                                ->setCellValue('DG' . $i, $material11)
                                ->setCellValue('DH' . $i, $cantMat11)
                                ->setCellValue('DI' . $i, $material12)
                                ->setCellValue('DJ' . $i, $cantMat12)
                                ->setCellValue('DK' . $i, $material13)
                                ->setCellValue('DL' . $i, $cantMat13)
                                ->setCellValue('DM' . $i, $material14)
                                ->setCellValue('DN' . $i, $cantMat14)
                                ->setCellValue('DO' . $i, $material15)
                                ->setCellValue('DP' . $i, $cantMat15)
                                ->setCellValue('DQ' . $i, $material16)
                                ->setCellValue('DR' . $i, $cantMat16)
                                ->setCellValue('DS' . $i, $material17)
                                ->setCellValue('DT' . $i, $cantMat17)
                                ->setCellValue('DU' . $i, $material18)
                                ->setCellValue('DV' . $i, $cantMat18)
                                ->setCellValue('DW' . $i, $material19)
                                ->setCellValue('DX' . $i, $cantMat19)
                                ->setCellValue('DY' . $i, $material20)
                                ->setCellValue('DZ' . $i, $cantMat20)
                                ->setCellValue('EA' . $i, $material21)
                                ->setCellValue('EB' . $i, $cantMat21)
                                ->setCellValue('EC' . $i, $material22)
                                ->setCellValue('ED' . $i, $cantMat22)
                                ->setCellValue('EE' . $i, $material23)
                                ->setCellValue('EF' . $i, $cantMat23)
                                ->setCellValue('EG' . $i, $material24)
                                ->setCellValue('EH' . $i, $cantMat24)
                                ->setCellValue('EI' . $i, $material25)
                                ->setCellValue('EJ' . $i, $cantMat25)
                                ->setCellValue('EK' . $i, $material26)
                                ->setCellValue('EL' . $i, $cantMat26)
                                ->setCellValue('EM' . $i, $material27)
                                ->setCellValue('EN' . $i, $cantMat27)
                                ->setCellValue('EO' . $i, $material28)
                                ->setCellValue('EP' . $i, $cantMat28)
                                ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                } 
                break;
            case 'Segunda Venta':
                $clientIdNumber_agrr=(string)$value['formSegVta']['clientIdNumber'];
                $celullarTelephone_agrr=(string)$value['formSegVta']['celullarTelephone'];
                $homeTelephone_agrr=(string)$value['formSegVta']['homeTelephone'];
                $clientJobTelephone_agrr=(string)$value['formSegVta']['clientJobTelephone'];
                $jobTelephone=(string)$value['formSegVta']['References'][0]['jobTelephone'];
                $telephone=(string)$value['formSegVta']['References'][0]['telephone'];
                $ext=(string)$value['formSegVta']['References'][0]['ext'];
                $jobTelephoneUno=(string)$value['formSegVta']['References'][1]['jobTelephone'];
                $telephoneUno=(string)$value['formSegVta']['References'][1]['telephone'];
                $extUno=(string)$value['formSegVta']['References'][1]['ext'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['datosGrales']["id"])
                            ->setCellValue('B' . $i, $value['datosGrales']["idCliente"])
                            ->setCellValue('C' . $i, $value['datosGrales']["agreementNumber"])
                            ->setCellValue('D' . $i, $value['datosGrales']["name"])
                            ->setCellValue('E' . $i, $value['datosGrales']["estatusReporte"])
                            ->setCellValue('F' . $i, $value['datosGrales']["idCity"])
                            ->setCellValue('G' . $i, $value['datosGrales']["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value['datosGrales']["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value['datosGrales']["nicknameAgencia"])
                            ->setCellValue('AM' . $i, $value['formSegVta']['payment'])
                            ->setCellValue('AN' . $i, $value['datosGrales']['agreementNumber'])
                            ->setCellValue('AO' . $i, $value['formSegVta']['requestDate'])
                            ->setCellValue('AP' . $i, $value['formSegVta']['clientName'])
                            ->setCellValue('AQ' . $i, $value['formSegVta']['clientlastName2'])
                            ->setCellValue('AR' . $i, $value['formSegVta']['clientlastName'])
                            ->setCellValue('AS' . $i, $value['formSegVta']['clientRFC'])
                            ->setCellValue('AT' . $i, $value['formSegVta']['clientCURP'])
                            ->setCellValue('AU' . $i, $value['formSegVta']['clientEmail'])
                            ->setCellValue('AV' . $i, $value['formSegVta']['clientgender'])
                            ->setCellValue('AW' . $i, $value['formSegVta']['clientRelationship'])
                            ->setCellValue('AX' . $i, $value['formSegVta']['identificationType'])
                            ->setCellValueExplicit('AY' . $i, $clientIdNumber_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('AZ' . $i, $value['formSegVta']['clientBirthDate'])
                            ->setCellValue('BA' . $i, $value['formSegVta']['clientBirthCountry'])
                            ->setCellValue('BB' . $i, $value['formSegVta']['idState'])
                            ->setCellValue('BC' . $i, $value['formSegVta']['idCity'])
                            ->setCellValue('BD' . $i, $value['formSegVta']['idColonia'])
                            ->setCellValue('BE' . $i, $value['formSegVta']['street'])
                            ->setCellValue('BF' . $i, $value['formSegVta']['street'])
                            ->setCellValue('BG' . $i, $value['formSegVta']['inHome'])
                            ->setCellValueExplicit('BH' . $i, $celullarTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BI' . $i, $homeTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BJ' . $i, $value['formSegVta']['clientJobEnterprise'])
                            ->setCellValue('BK' . $i, $value['formSegVta']['clientJobRange'])
                            ->setCellValue('BL' . $i, $value['formSegVta']['clientJobLocation'])
                            ->setCellValueExplicit('BM' . $i, $clientJobTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BN' . $i, $value['formSegVta']['clientJobActivity'])
                            ->setCellValue('BO' . $i, $value['formSegVta']['agreementType'])
                            ->setCellValue('BP' . $i, $value['formSegVta']['agreementExpires'])
                            ->setCellValue('BQ' . $i, $value['formSegVta']['price'])
                            ->setCellValue('BR' . $i, $value['formSegVta']['agreementMonthlyPayment'])
                            ->setCellValue('BS' . $i, $value['formSegVta']['agreementRi'])
                            ->setCellValue('BT' . $i, $value['formSegVta']['agreementRiDate'])
                            ->setCellValue('BU' . $i, $value['formSegVta']['References'][0]['name'])
                            ->setCellValueExplicit('BV' . $i, $jobTelephone, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BW' . $i, $telephone, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BX' . $i, $ext, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BY' . $i, $value['formSegVta']['References'][1]['name'])
                            ->setCellValueExplicit('BZ' . $i, $jobTelephoneUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('CA' . $i, $telephoneUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('CB' . $i, $extUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('EQ' . $i, $value['datosGrales']["created_at"]);
                break;
        }
        $i++;
    }

    // Redirect output to a client’s web browser (Excel2007)
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteFormularios.xls"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    //$objWriter->save('php://output');
    //$objWriter->save(str_replace(__FILE__,'C:\Users\aortizg\Documents\mexicanaFTP\/',__FILE__));
    ob_start();
    $objWriter->save("php://output");
    $xlsData = ob_get_contents();
    ob_end_clean();

    $response =  array(
        'op' => 'ok',
        'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
    );
    echo json_encode($response);
    //die(json_encode($response));
}
    
