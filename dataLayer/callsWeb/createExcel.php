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
                ->setCellValue('T1', "Quien cancela?")
                ->setCellValue('U1', "Motivo de cancelación")
                //plomero
                ->setCellValue('U1', "Número de dictamen técnico")
                ->setCellValue('V1', "Color del tapón")
                ->setCellValue('W1', "RI menor a 40 mts")
                ->setCellValue('X1', "Observaciones")
                ->setCellValue('Y1', "Cálculo de caída de presión")
                ->setCellValue('Z1', "Se requiere tubería")
                ->setCellValue('AA1', "Resultado PH")
                ->setCellValue('AB1', "Número de tomas")
                //censo
                ->setCellValue('AC1', "Tipo de lote")
                ->setCellValue('AD1', "Estatus de vivienda")
                ->setCellValue('AE1', "Niveles socioeconónimos de la vivienda(NSE)")
                ->setCellValue('AF1', "Giro")
                ->setCellValue('AG1', "Acometida")
                ->setCellValue('AH1', "Color de Tapón")
                ->setCellValue('AI1', "Medidor")
                ->setCellValue('AJ1', "Marca de medidor")
                ->setCellValue('AK1', "Tipo de medidor")
                ->setCellValue('AL1', "No. de Serie")
                ->setCellValue('AM1', "Niple de corte")
                //SegundaVenta
                ->setCellValue('AN1', "Pagaré")
                ->setCellValue('AO1', "Número de contrato")
                ->setCellValue('AP1', "Fecha de solicitud")
                ->setCellValue('AQ1', "Nombre")
                ->setCellValue('AR1', "Apellido paterno")
                ->setCellValue('AS1', "Apellido materno")
                ->setCellValue('AT1', "RFC")
                ->setCellValue('AU1', "CURP")
                ->setCellValue('AV1', "Correo")
                ->setCellValue('AW1', "Sexo")
                ->setCellValue('AX1', "Estado civil")
                ->setCellValue('AY1', "Tipo de identificación")
                ->setCellValue('AZ1', "Identificación")
                ->setCellValue('BA1', "Fecha de Nacimiento")
                ->setCellValue('BB1', "País de Nacimiento")
                ->setCellValue('BC1', "Estado")
                ->setCellValue('BD1', "Municipio")
                ->setCellValue('BE1', "Colonia")
                ->setCellValue('BF1', "Calle")
                ->setCellValue('BG1', "Numero")
                ->setCellValue('BH1', "Vive en casa")
                ->setCellValue('BI1', "Teléfono celular")
                ->setCellValue('BJ1', "Teléfono de casa")
                ->setCellValue('BK1', "Empresa")
                ->setCellValue('BL1', "Puesto")
                ->setCellValue('BM1', "Dirección")
                ->setCellValue('BN1', "Teléfono")
                ->setCellValue('BO1', "Actividad/Área")
                ->setCellValue('BP1', "Tipo de contrato")
                ->setCellValue('BQ1', "Plazo")
                ->setCellValue('BR1', "Precio")
                ->setCellValue('BS1', "Mensualidad")
                ->setCellValue('BT1', "RI")
                ->setCellValue('BU1', "Fecha RI")
                ->setCellValue('BV1', "Nombre referencia 1")
                ->setCellValue('BW1', "Teléfono de trabajo referencia 1")
                ->setCellValue('BX1', "Teléfono particular referencia")
                ->setCellValue('BY1', "Extensión referencia 1")
                ->setCellValue('BZ1', "Nombre Ref. 2")
                ->setCellValue('CA1', "Teléfono de trabajo referencia 2")
                ->setCellValue('CB1', "Teléfono particular referencia2")
                ->setCellValue('CC1', "Extensión referencia 2")
                //instalacion
                ->setCellValue('CD1', "Quien libero anomalia?")
                ->setCellValue('CE1', "Motivo de Liberación")
                ->setCellValue('CF1', "Color de etiqueta de PH")
                ->setCellValue('CG1', "Número de agencia PH?")
                ->setCellValue('CH1', "Número de la agencia")
                ->setCellValue('CI1', "Procede a instalación?")
                ->setCellValue('CJ1', "Catálogo de anomalías")
                ->setCellValue('CK1', "Comentarios")
                ->setCellValue('CL1', "Marca de medidor")
                ->setCellValue('CM1', "Tipo de medidor")
                ->setCellValue('CN1', "No. de Serie")
                ->setCellValue('CO1', "Lectura del medidor")
                ->setCellValue('CP1', "t_Union_3-4")
                ->setCellValue('CQ1', "Cantidad_t_Union_3-4")
                ->setCellValue('CR1', "t_Red_Camp_3-4_1-2")
                ->setCellValue('CS1', "Cantidad_t_Red_Camp_3-4_1-2")
                ->setCellValue('CT1', "t_t_Codo3-4x90_150")
                ->setCellValue('CU1', "Cantidad_t_t_Codo3-4x90_150")
                ->setCellValue('CV1', "t_CodoNip_3-4_150")
                ->setCellValue('CW1', "Cantidad_t_CodoNip_3-4_150")
                ->setCellValue('CX1', "t_Tee3-4")
                ->setCellValue('CY1', "Cantidad_t_Tee3-4")
                ->setCellValue('CZ1', "t_Cople3-4_150")
                ->setCellValue('DA1', "Cantidad_t_Cople3-4_150")
                ->setCellValue('DB1', "t_TaponM_3-4_Neg")
                ->setCellValue('DC1', "Cantidad_t_TaponM_3-4_Neg")
                ->setCellValue('DD1', "t_Conex_p_medid_dom")
                ->setCellValue('DE1', "Cantidad_t_Conex_p_medid_dom")
                ->setCellValue('DF1', "t_Val_esfer_3-4_125lbs")
                ->setCellValue('DG1', "Cantidad_t_Val_esfer_3-4_125lbs")
                ->setCellValue('DH1', "t_Reg_Ameri_CR-4000_3-4")
                ->setCellValue('DI1', "Cantidad_t_Reg_Ameri_CR-4000_3-4")
                ->setCellValue('DJ1', "t_Niple_3-4_RC")
                ->setCellValue('DK1', "Cantidad_t_Niple_3-4_RC")
                ->setCellValue('DL1', "t_Niple_3-4_x2")
                ->setCellValue('DM1', "Cantidad_t_Niple_3-4_x2")
                ->setCellValue('DN1', "t_Niple_3-4_x3")
                ->setCellValue('DO1', "Cantidad_t_Niple_3-4_x3")
                ->setCellValue('DP1', "t_Niple_3-4_x4")
                ->setCellValue('DQ1', "Cantidad_t_Niple_3-4_x4")
                ->setCellValue('DR1', "t_Niple_3-4_x5")
                ->setCellValue('DS1', "Cantidad_t_Niple_3-4_x5")
                ->setCellValue('DT1', "t_Niple_3-4_x6")
                ->setCellValue('DU1', "Cantidad_t_Niple_3-4_x6")
                ->setCellValue('DV1', "t_Niple_3-4_x7")
                ->setCellValue('DW1', "Cantidad_t_Niple_3-4_x7")
                ->setCellValue('DX1', "t_Niple_3-4_x8")
                ->setCellValue('DY1', "Cantidad_t_Niple_3-4_x8")
                ->setCellValue('DZ1', "t_Niple_3-4_x9")
                ->setCellValue('EA1', "Cantidad_t_Niple_3-4_x9")
                ->setCellValue('EB1', "t_Niple_3-4_x10")
                ->setCellValue('EC1', "Cantidad_t_Niple_3-4_x10")
                ->setCellValue('ED1', "t_Union_1Std")
                ->setCellValue('EE1', "Cantidad_t_Union_1Std")
                ->setCellValue('EF1', "t_Red_Camp_1x3-4")
                ->setCellValue('EG1', "Cantidad_t_Red_Camp_1x3-4")
                ->setCellValue('EH1', "t_Red_Bush_1x3-4")
                ->setCellValue('EI1', "Cantidad_t_Red_Bush_1x3-4")
                ->setCellValue('EJ1', "t_Val_esfer_1_125lbs")
                ->setCellValue('EK1', "Cantidad_t_Val_esfer_1_125lbs")
                ->setCellValue('EL1', "t_Niple_1-2_x_2")
                ->setCellValue('EM1', "Cantidad_t_Niple_1-2_x_2")
                ->setCellValue('EN1', "t_reduccion")
                ->setCellValue('EO1', "Cantidad_t_reduccion")
                ->setCellValue('EP1', "t_blanco_plomo")
                ->setCellValue('EQ1', "Cantidad_t_blanco_plomo")
                ->setCellValue('ER1', "t_cinta_teflon")
                ->setCellValue('ES1', "Cantidad_t_cinta_teflon")
                ->setCellValue('ET1', "Agencia Vendedora")
                ->setCellValue('EU1', "Fecha");
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
                         'EO1','EP1','EQ1','ER1','ES1','ET1','EU1'];
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
        if (isset($value["innerNumber"])) {
            $streetNumber = $value["street"] . " " . $value["innerNumber"];
        } else {
            $streetNumber = $value["street"] . " " . $reportData['datosGrales']["outterNumber"];
        }
        switch ($value["name"]) {
            case 'Censo':
                $tapon      = (intval($value['censo']['tapon']) == 1) ? 'Sí' : 'No';
                $acometida  = (intval($value['censo']['acometida']) == 1) ? 'Sí' : 'No';
                $medidor    = (intval($value['censo']['medidor']) == 1) ? 'Sí' : 'No';
                $niple      = (intval($value['censo']['niple']) == 1) ? 'Sí' : 'No';

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                            ->setCellValue('B' . $i, $value["idCliente"])
                            ->setCellValue('C' . $i, $value["agreementNumber"])
                            ->setCellValue('D' . $i, $value["name"])
                            ->setCellValue('E' . $i, $value["estatusReporte"])
                            ->setCellValue('F' . $i, $value["idCity"])
                            ->setCellValue('G' . $i, $value["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value["nicknameAgencia"])
                            ->setCellValue('AC' . $i, $value['censo']['lote'])
                            ->setCellValue('AD' . $i, $value['censo']['houseStatus'])
                            ->setCellValue('AE' . $i, $value['censo']['nivel'])
                            ->setCellValue('AF' . $i, $value['censo']['giro'])
                            ->setCellValue('AG' . $i, $acometida)
                            ->setCellValue('AH' . $i, $tapon)
                            ->setCellValue('AI' . $i, $medidor)
                            ->setCellValue('AJ' . $i, $value['censo']['marca'])
                            ->setCellValue('AK' . $i, $value['censo']['tipo'])
                            ->setCellValue('AL' . $i, $value['censo']['NoSerie'])
                            ->setCellValue('AM' . $i, $niple)
                            ->setCellValue('EU' . $i, $value["created_at"]);
                break;
            case 'Venta':
                $nombreFinanciera      = (intval($value['venta']['financialService']) == 1) ? 'AYOPSA' : 'MEXICANA DE GAS';
                $formaDePago      = (intval($value['venta']['payment']) == 1) ? 'FINANCIADO' : 'CONTADO';
                $owner      = (intval($value['venta']['owner']) == 1) ? 'Sí' : 'No';
                $uninteresting      = ($value['venta']['uninteresting'] == 'true') ? 'Sí' : 'No';
                
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                            ->setCellValue('B' . $i, $value["idCliente"])
                            ->setCellValue('C' . $i, $value["agreementNumber"])
                            ->setCellValue('D' . $i, $value["name"])
                            ->setCellValue('E' . $i, $value["estatusReporte"])
                            ->setCellValue('F' . $i, $value["idCity"])
                            ->setCellValue('G' . $i, $value["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value["nicknameAgencia"])
                            ->setCellValue('K' . $i, $uninteresting)
                            ->setCellValue('L' . $i, $value['venta']['motivosDesinteres'])
                            ->setCellValue('M' . $i, $value['venta']['comments'])
                            ->setCellValue('N' . $i, $owner)
                            ->setCellValue('O' . $i, $value['venta']['name'])
                            ->setCellValue('P' . $i, $value['venta']['lastName'])
                            ->setCellValue('Q' . $i, $value['venta']['lastNameOp'])
                            ->setCellValue('R' . $i, $nombreFinanciera)
                            ->setCellValue('S' . $i, $formaDePago)
                            ->setCellValue('T' . $i, $value['quienCancela'])
                            ->setCellValue('U' . $i, $value['motivoCancelado'])
                            ->setCellValue('EU' . $i, $value["created_at"]);
                break;
            case 'Plomero':
                if ($value['estatusReporte'] == 'EN PROCESO') {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                                ->setCellValue('B' . $i, $value["idCliente"])
                                ->setCellValue('C' . $i, $value["agreementNumber"])
                                ->setCellValue('D' . $i, $value["name"])
                                ->setCellValue('E' . $i, $value["estatusReporte"])
                                ->setCellValue('F' . $i, $value["idCity"])
                                ->setCellValue('G' . $i, $value["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value["nicknameAgencia"])
                                ->setCellValue('EU' . $i, $value["created_at"]);
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
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                                ->setCellValue('B' . $i, $value["idCliente"])
                                ->setCellValue('C' . $i, $value["agreementNumber"])
                                ->setCellValue('D' . $i, $value["name"])
                                ->setCellValue('E' . $i, $value["estatusReporte"])
                                ->setCellValue('F' . $i, $value["idCity"])
                                ->setCellValue('G' . $i, $value["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value["nicknameAgencia"])
                                ->setCellValue('U' . $i, $documentNumber)
                                ->setCellValue('V' . $i, $tapon)
                                ->setCellValue('W' . $i, $ri)
                                ->setCellValue('X' . $i, $observations)
                                ->setCellValue('Y' . $i, $detalles)
                                ->setCellValue('Z' . $i, $newPipe)
                                ->setCellValue('AA' . $i, $ph)
                                ->setCellValue('AB' . $i, $pipesCount)
                                ->setCellValue('EU' . $i, $value["created_at"]);
                }
                break;
            case 'Instalacion':
                if ($value['estatusReporte'] == 'EN PROCESO') {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                                ->setCellValue('B' . $i, $value["idCliente"])
                                ->setCellValue('C' . $i, $value["agreementNumber"])
                                ->setCellValue('D' . $i, $value["name"])
                                ->setCellValue('E' . $i, $value["estatusReporte"])
                                ->setCellValue('F' . $i, $value["idCity"])
                                ->setCellValue('G' . $i, $value["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value["nicknameAgencia"])
                                ->setCellValue('EU' . $i, $value["created_at"]);
                }else{
                    $procede = (intval($value['installation']['installation']) == 1 ) ? 'SI' : 'NO';
                    $phLabel = (intval($value['installation']['phLabel']) == 1 ) ? 'Verde' : 'Rojo';
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
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                                ->setCellValue('B' . $i, $value["idCliente"])
                                ->setCellValue('C' . $i, $value["agreementNumber"])
                                ->setCellValue('D' . $i, $value["name"])
                                ->setCellValue('E' . $i, $value["estatusReporte"])
                                ->setCellValue('F' . $i, $value["idCity"])
                                ->setCellValue('G' . $i, $value["colonia"])
                                ->setCellValue('H' . $i, $streetNumber)
                                ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                                ->setCellValue('J' . $i, $value["nicknameAgencia"])
                                ->setCellValue('CD' . $i, $value['quienLibAnomalia'])
                                ->setCellValue('CE' . $i, $value['motivoLiberacion'])
                                ->setCellValue('CF' . $i, $value['installation']['phLabel'])
                                ->setCellValue('CG' . $i, $value['installation']['agencyPh'])
                                ->setCellValue('CH' . $i, $value['installation']['agencyNumber'])
                                ->setCellValue('CI' . $i, $procede)
                                ->setCellValue('CJ' . $i, $value['installation']['abnormalities'])
                                ->setCellValue('CK' . $i, $value['installation']['comments'])
                                ->setCellValue('CL' . $i, $value['installation']['brand'])
                                ->setCellValue('CM' . $i, $value['installation']['type'])
                                ->setCellValue('CN' . $i, $value['installation']['serialNumber'])
                                ->setCellValue('CO' . $i, $value['installation']['measurement'])
                                ->setCellValue('CP' . $i, $material1)
                                ->setCellValue('CQ' . $i, $cantMat1)
                                ->setCellValue('CR' . $i, $material2)
                                ->setCellValue('CS' . $i, $cantMat2)
                                ->setCellValue('CT' . $i, $material3)
                                ->setCellValue('CU' . $i, $cantMat3)
                                ->setCellValue('CV' . $i, $material4)
                                ->setCellValue('CW' . $i, $cantMat4)
                                ->setCellValue('CX' . $i, $material5)
                                ->setCellValue('CY' . $i, $cantMat5)
                                ->setCellValue('CZ' . $i, $material6)
                                ->setCellValue('DA' . $i, $cantMat6)
                                ->setCellValue('DB' . $i, $material7)
                                ->setCellValue('DC' . $i, $cantMat7)
                                ->setCellValue('DD' . $i, $material8)
                                ->setCellValue('DE' . $i, $cantMat8)
                                ->setCellValue('DF' . $i, $material9)
                                ->setCellValue('DG' . $i, $cantMat9)
                                ->setCellValue('DH' . $i, $material10)
                                ->setCellValue('DI' . $i, $cantMat10)
                                ->setCellValue('DJ' . $i, $material11)
                                ->setCellValue('DK' . $i, $cantMat11)
                                ->setCellValue('DL' . $i, $material12)
                                ->setCellValue('DM' . $i, $cantMat12)
                                ->setCellValue('DN' . $i, $material13)
                                ->setCellValue('DO' . $i, $cantMat13)
                                ->setCellValue('DP' . $i, $material14)
                                ->setCellValue('DQ' . $i, $cantMat14)
                                ->setCellValue('DR' . $i, $material15)
                                ->setCellValue('DS' . $i, $cantMat15)
                                ->setCellValue('DT' . $i, $material16)
                                ->setCellValue('DU' . $i, $cantMat16)
                                ->setCellValue('DV' . $i, $material17)
                                ->setCellValue('DW' . $i, $cantMat17)
                                ->setCellValue('DX' . $i, $material18)
                                ->setCellValue('DY' . $i, $cantMat18)
                                ->setCellValue('DZ' . $i, $material19)
                                ->setCellValue('EA' . $i, $cantMat19)
                                ->setCellValue('EB' . $i, $material20)
                                ->setCellValue('EC' . $i, $cantMat20)
                                ->setCellValue('ED' . $i, $material21)
                                ->setCellValue('EE' . $i, $cantMat21)
                                ->setCellValue('EF' . $i, $material22)
                                ->setCellValue('EG' . $i, $cantMat22)
                                ->setCellValue('EH' . $i, $material23)
                                ->setCellValue('EI' . $i, $cantMat23)
                                ->setCellValue('EJ' . $i, $material24)
                                ->setCellValue('EK' . $i, $cantMat24)
                                ->setCellValue('EL' . $i, $material25)
                                ->setCellValue('EM' . $i, $cantMat25)
                                ->setCellValue('EN' . $i, $material26)
                                ->setCellValue('EO' . $i, $cantMat26)
                                ->setCellValue('EP' . $i, $material27)
                                ->setCellValue('EQ' . $i, $cantMat27)
                                ->setCellValue('ER' . $i, $material28)
                                ->setCellValue('ES' . $i, $cantMat28)
                                ->setCellValue('ET' . $i, $value['installation']['agenciaVendedora'])
                                ->setCellValue('EU' . $i, $value["created_at"]);
                } 
                break;
            case 'Segunda Venta':
                $clientIdNumber_agrr=(string)$value['formSegVta']['clientIdNumber_agrr'];
                $celullarTelephone_agrr=(string)$value['formSegVta']['celullarTelephone_agrr'];
                $homeTelephone_agrr=(string)$value['formSegVta']['homeTelephone_agrr'];
                $clientJobTelephone_agrr=(string)$value['formSegVta']['clientJobTelephone_agrr'];
                $jobTelephone=(string)$value['formSegVta']['referencias'][0]['jobTelephone'];
                $telephone=(string)$value['formSegVta']['referencias'][0]['telephone'];
                $ext=(string)$value['formSegVta']['referencias'][0]['ext'];
                $jobTelephoneUno=(string)$value['formSegVta']['referencias'][1]['jobTelephone'];
                $telephoneUno=(string)$value['formSegVta']['referencias'][1]['telephone'];
                $extUno=(string)$value['formSegVta']['referencias'][1]['ext'];
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["id"])
                            ->setCellValue('B' . $i, $value["idCliente"])
                            ->setCellValue('C' . $i, $value["agreementNumber"])
                            ->setCellValue('D' . $i, $value["name"])
                            ->setCellValue('E' . $i, $value["estatusReporte"])
                            ->setCellValue('F' . $i, $value["idCity"])
                            ->setCellValue('G' . $i, $value["colonia"])
                            ->setCellValue('H' . $i, $streetNumber)
                            ->setCellValue('I' . $i, $value["nicknameEmpleado"])
                            ->setCellValue('J' . $i, $value["nicknameAgencia"])
                            ->setCellValue('AN' . $i, $value['formSegVta']['payment_agrr'])
                            ->setCellValue('AO' . $i, $value['agreementNumber'])
                            ->setCellValue('AP' . $i, $value['formSegVta']['requestDate_agrr'])
                            ->setCellValue('AQ' . $i, $value['formSegVta']['clientName_agrr'])
                            ->setCellValue('AR' . $i, $value['formSegVta']['clientlastName2_agrr'])
                            ->setCellValue('AS' . $i, $value['formSegVta']['clientlastName_agrr'])
                            ->setCellValue('AT' . $i, $value['formSegVta']['clientRFC_agrr'])
                            ->setCellValue('AU' . $i, $value['formSegVta']['clientCURP_agrr'])
                            ->setCellValue('AV' . $i, $value['formSegVta']['clientEmail_agrr'])
                            ->setCellValue('AW' . $i, $value['formSegVta']['clientgender_agrr'])
                            ->setCellValue('AX' . $i, $value['formSegVta']['clientRelationship_agrr'])
                            ->setCellValue('AY' . $i, $value['formSegVta']['identificationType_agrr'])
                            ->setCellValueExplicit('AZ' . $i, $clientIdNumber_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BA' . $i, $value['formSegVta']['clientBirthDate_agrr'])
                            ->setCellValue('BB' . $i, $value['formSegVta']['clientBirthCountry_agrr'])
                            ->setCellValue('BC' . $i, $value['formSegVta']['idState_agrr'])
                            ->setCellValue('BD' . $i, $value['formSegVta']['idCity_agrr'])
                            ->setCellValue('BE' . $i, $value['formSegVta']['idColonia_agrr'])
                            ->setCellValue('BF' . $i, $value['formSegVta']['street_agrr'])
                            ->setCellValue('BG' . $i, $value['formSegVta']['street_agrr'])
                            ->setCellValue('BH' . $i, $value['formSegVta']['inHome_agrr'])
                            ->setCellValueExplicit('BI' . $i, $celullarTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BJ' . $i, $homeTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BK' . $i, $value['formSegVta']['clientJobEnterprise_agrr'])
                            ->setCellValue('BL' . $i, $value['formSegVta']['clientJobRange_agrr'])
                            ->setCellValue('BM' . $i, $value['formSegVta']['clientJobLocation_agrr'])
                            ->setCellValueExplicit('BN' . $i, $clientJobTelephone_agrr, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BO' . $i, $value['formSegVta']['clientJobActivity_agrr'])
                            ->setCellValue('BP' . $i, $value['formSegVta']['agreementType_agrr'])
                            ->setCellValue('BQ' . $i, $value['formSegVta']['agreementExpires_agrr'])
                            ->setCellValue('BR' . $i, $value['formSegVta']['price_agrr'])
                            ->setCellValue('BS' . $i, $value['formSegVta']['agreementMonthlyPayment_agrr'])
                            ->setCellValue('BT' . $i, $value['formSegVta']['agreementRi_agrr'])
                            ->setCellValue('BU' . $i, $value['formSegVta']['agreementRiDate_agrr'])
                            ->setCellValue('BV' . $i, $value['formSegVta']['referencias'][0]['name'])
                            ->setCellValueExplicit('BW' . $i, $jobTelephone, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BX' . $i, $telephone, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('BY' . $i, $ext, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('BZ' . $i, $value['formSegVta']['referencias'][1]['name'])
                            ->setCellValueExplicit('CA' . $i, $jobTelephoneUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('CB' . $i, $telephoneUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValueExplicit('CC' . $i, $extUno, PHPExcel_Cell_DataType::TYPE_STRING)
                            ->setCellValue('EU' . $i, $value["created_at"]);
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
    
