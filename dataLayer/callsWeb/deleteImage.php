<?php
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
//error_reporting(E_ALL); // or E_STRICT
//ini_set("display_errors",1);
ini_set("memory_limit","1024M");
set_time_limit(0);
ini_set('upload_max_filesize', '500M');
ini_set('post_max_size', '500M');
ini_set('max_input_time', 4000); // Play with the values
ini_set('max_execution_time', 4000); // Play with the values
//var_dump($_POST);
//die();
$filenameImg = $_POST["filenameImg"];
$tipoImg = $_POST["tipoImg"];
$idReporte = intval($_POST["idReporte"]);
$financialServiceVal=$_POST["financialServiceVal"];
$getDatosStatus = getNumeroContrato($idReporte);
$base_url2= "http://siscomcmg.com:8080/";
//$base_url2= "http://siscomcmg.com/";
//$base_url2= "http://localhost/mexicanaDesarrollo/mexicana-de-gas-backoffice/";
$CARPETA_NOMBRE_PROYECTO = "mexicana-de-gas-backoffice";
$CARPETA_IMAGENES_TEMPORAL = "uploads";
$CARPETA_IMAGENES = "files_img";
$CARPETA_VENTAS = "Venta";
$CARPETA_PLOMERIA = "Plomeria";
$CARPETA_INSTALACION = "Instalacion";
$CARPETA_CENSO = "Censo";
$CARPETA_EMPLEADOS = "Empleados";
$CARPETA_TERMINADOS = "Terminados";
$CARPETA_EN_PROCESO = "Proceso";
$CARPETA_AYOPSA = "AYOPSA";
$CARPETA_CMG = "CMG";
//generamos la ruta para la carpeta en proceso
$dataDate=getMonthAndYear($idReporte, $tipoImg);
$year=date('Y', strtotime($dataDate["fechaCreacion"]));
$month=date('m', strtotime($dataDate["fechaCreacion"]));
$carpetaVenta="";
if ((intval($getDatosStatus['estatusInstalacion']) != 51 || intval($getDatosStatus['estatusInstalacion']) != 54) && 
    ($tipoImg != "cuadro" || $tipoImg == "caratula")) {
    
    if ($financialServiceVal != "" && $financialServiceVal == 1) {
        $carpetaVenta=$CARPETA_AYOPSA;
    }elseif ($financialServiceVal != "" && $financialServiceVal == 0) {
        $carpetaVenta=$CARPETA_CMG;
    }
    if ($tipoImg == "tuberia" || $tipoImg == "pie") {
        $rutaImagenes = '../../'.$CARPETA_IMAGENES."/".$CARPETA_PLOMERIA."/".$CARPETA_EN_PROCESO."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
        $base_url2= $base_url2.$CARPETA_IMAGENES."/".$CARPETA_PLOMERIA."/".$CARPETA_EN_PROCESO."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
    }elseif ($tipoImg == "comprobante" || 
             $tipoImg == "identificacion" || 
             $tipoImg == "solicitud" || 
             $tipoImg == "pagare" ||
             $tipoImg == "aviso" || 
             $tipoImg == "contrato") {
        $rutaImagenes = '../../'.$CARPETA_IMAGENES."/".$CARPETA_VENTAS."/".$CARPETA_EN_PROCESO."/".$carpetaVenta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
        $base_url2= $base_url2.$CARPETA_IMAGENES."/".$CARPETA_VENTAS."/".$CARPETA_EN_PROCESO."/".$carpetaVenta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
    }
    //mandamos la url generada a la funcion que eliminara el archivo
}elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
    if (intval($getDatosStatus['estatusInstalacion']) == 51 || intval($getDatosStatus['estatusInstalacion']) == 54) {
        $carpeta=$CARPETA_TERMINADOS;
    }else{
        $carpeta=$CARPETA_EN_PROCESO;
    }
    $rutaImagenes = '../../'.$CARPETA_IMAGENES."/".$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
    $base_url2= $base_url2.$CARPETA_IMAGENES."/".$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
}
eliminarArchivo($CARPETA_IMAGENES,$rutaImagenes, $tipoImg, $CARPETA_PLOMERIA, $CARPETA_EN_PROCESO, $CARPETA_VENTAS,$CARPETA_INSTALACION,$carpeta, $year, $month, $getDatosStatus['numContrato'], $filenameImg, $base_url2, $idReporte, $carpetaVenta);
function eliminarArchivo($CARPETA_IMAGENES,$rutaImagenes, $tipoImg, $CARPETA_PLOMERIA, $CARPETA_EN_PROCESO, $CARPETA_VENTAS,$CARPETA_INSTALACION,$carpeta, $year, $month, $numContrato, $filenameImg, $base_url2, $idReporte, $carpetaVenta)
{
    if (file_exists($rutaImagenes)) {
        //echo "The directory $dirname exists.";
        $rutaActual=getcwd();
        chdir('../../'.$CARPETA_IMAGENES);
        //echo "actual ".getcwd();
        if ($tipoImg == "tuberia" || $tipoImg == "pie") {
            $rutaModificada=getcwd().'/'.$CARPETA_PLOMERIA."/".$CARPETA_EN_PROCESO."/".$year."/".$month."/".$numContrato."/";
        }elseif ($tipoImg == "comprobante" || 
                 $tipoImg == "identificacion" || 
                 $tipoImg == "solicitud" || 
                 $tipoImg == "pagare" ||
                 $tipoImg == "aviso" || 
                 $tipoImg == "contrato") {
            $rutaModificada=getcwd().'/'.$CARPETA_VENTAS."/".$CARPETA_EN_PROCESO."/".$carpetaVenta."/".$year."/".$month."/".$numContrato."/";
        }elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
            $rutaModificada=getcwd().'/'.$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$numContrato."/";
        }
        $targetPath = $rutaModificada . $filenameImg;
        //echo "targetPath ".$targetPath;
        if (unlink($targetPath)) {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $stmtSelectFoto="SELECT id FROM multimedia WHERE name=?;";
            if ($selectFoto = $conn->prepare($stmtSelectFoto)) {
                $selectFoto->bind_param("s",$filenameImg);
                if ($selectFoto->execute()) {
                    $selectFoto->store_result();
                    $selectFoto->bind_result($idMultimedia);
                    if ($selectFoto->num_rows > 0) {
                        if ($selectFoto->fetch()) {
                            if (intval($idMultimedia) > 0) {
                                if ($tipoImg == "tuberia" || $tipoImg == "pie") {
                                    $getMulForm = "SELECT id FROM form_plumber_multimedia WHERE idMultimedia=?";
                                }elseif ($tipoImg == "comprobante" || 
                                         $tipoImg == "identificacion" || 
                                         $tipoImg == "solicitud" || 
                                         $tipoImg == "pagare" ||
                                         $tipoImg == "aviso" || 
                                         $tipoImg == "contrato") {
                                    $getMulForm = "SELECT id FROM form_sells_multimedia WHERE idMultimedia=?";
                                }elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
                                    $getMulForm = "SELECT id FROM form_installation_multimedia WHERE idMultimedia=?";
                                }
                                if ($getFormMult = $conn->prepare($getMulForm)) {
                                    $getFormMult->bind_param("i", $idMultimedia);
                                    if ($getFormMult->execute()) {
                                        $getFormMult->store_result();
                                        $getFormMult->bind_result($idFPM);
                                        if ($getFormMult->num_rows > 0) {
                                            if ($getFormMult->fetch()) {
                                                $deleteMulSQL="DELETE FROM multimedia where id=?;";//$idSecondSell
                                                if ($deleteMul = $conn->prepare($deleteMulSQL)) {
                                                    $deleteMul->bind_param("i", $idMultimedia);
                                                    if ($deleteMul->execute()) {
                                                        if ($tipoImg == "tuberia" || $tipoImg == "pie") {
                                                            $deleteFMulSQL="DELETE FROM form_plumber_multimedia where id=?;";//$idSecondSell
                                                        }elseif ($tipoImg == "comprobante" || 
                                                                 $tipoImg == "identificacion" || 
                                                                 $tipoImg == "solicitud" || 
                                                                 $tipoImg == "pagare" ||
                                                                 $tipoImg == "aviso" || 
                                                                 $tipoImg == "contrato") {
                                                            $deleteFMulSQL="DELETE FROM form_sells_multimedia where id=?;";//$idSecondSell
                                                        }elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
                                                            $deleteFMulSQL="DELETE FROM form_installation_multimedia where id=?;";//$idSecondSell
                                                        }
                                                        if ($deleteFPMul = $conn->prepare($deleteFMulSQL)) {
                                                            $deleteFPMul->bind_param("i", $idFPM);
                                                            if ($deleteFPMul->execute()) {
                                                                $response["status"] = "EXITO";
                                                                $response["code"] = "200";
                                                                $response["responseImg"] = getImgs($idReporte, $tipoImg);
                                                                $response["responseRuta"] = $base_url2;
                                                                $response["response"] = "La imagen se elimino correctamente";
                                                                echo json_encode($response);
                                                                chdir('../');
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            $response["status"] = "ERROR";
                                            $response["code"] = "500";
                                            $response["response"] = "Error al traer la relacion con multimedia";
                                            echo json_encode($response);
                                        }   
                                    }
                                }
                            }
                        }
                    }else{
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] = "Error al traer el id multimedia";
                        echo json_encode($response);
                    }
                }
            }else{
                error_log($conn->error);
                chdir($rutaActual);
            }
        }else{
            //mandamos error 500
            $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["ruta"] = $targetPath;
            $response["response"] = "Hubo un problema al cargar la imagen";
            echo json_encode($response);
            chdir($rutaActual);
        }
    }else{
        $response["status"] = "ERROR";
            $response["code"] = "500";
            $response["response"] = "No existe la imagen solicitada";
            $response["ruta"] = $rutaImagenes;
            echo json_encode($response);
    } 
}
function generateRandomString($length = 25) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function getNumeroContrato($idReporte)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReporte != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNumContratoSQL = "SELECT a.agreementNumber, b.estatusAsignacionInstalacion
                       FROM report a, tEstatusContrato b
                       where 0=0
                       and a.id=b.idReporte
                       and id = $idReporte;";
        $result = $conn->query($getNumContratoSQL);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res['numContrato']=$row[0];
                $res['estatusInstalacion']=$row[1];
            }
        }
        $conn->close();
    }
    return $res;
}

function getIdForm($idReporte, $tipoImg)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReporte != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($tipoImg == "tuberia" || $tipoImg == "pie") {
            $getNumContratoSQL = "SELECT a.id
                                    FROM form_plumber a, reportHistory b
                                    where 0=0
                                    and a.id=b.idFormulario
                                    and b.idReportType=3
                                    and b.idReport=$idReporte ;";
        }elseif ($tipoImg == "comprobante" ||
                 $tipoImg == "identificacion" || 
                 $tipoImg == "solicitud" || 
                 $tipoImg == "pagare" ||
                 $tipoImg == "aviso" || 
                 $tipoImg == "contrato") {
            $getNumContratoSQL = "SELECT 
                                  fs.id
                                  FROM
                                  form_sells AS fs,
                                  reportHistory AS rh
                                  WHERE
                                  0 = 0 AND fs.id = rh.idFormSell
                                  AND idReport = $idReporte
                                  AND rh.idReportType = 2";
        }elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
            $getNumContratoSQL = "SELECT 
                                    a.id
                                FROM
                                    form_installation a,
                                    reportHistory b,
                                    report c
                                WHERE
                                    a.consecutive = b.idFormSell
                                    and c.id=b.idReport
                                    AND b.idReport = $idReporte
                                    AND b.idReportType = 4;";
        }
        $result = $conn->query($getNumContratoSQL);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res['idForm']=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getImgs($idReporte, $tipo)
{
    if ($idReporte != "") {
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($tipo == 'tuberia') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id
                                    FROM reportHistory AS RP
                                    -- INNER JOIN report_employee_form AS REF ON RP.idReport = REF.idReport
                                    INNER JOIN form_plumber AS FP ON FP.id = RP.idFormulario
                                    LEFT JOIN form_plumber_multimedia AS FPM ON FPM.idFormPlumber = FP.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FPM.idMultimedia
                                    WHERE RP.idReportType=3 AND RP.idReport =$idReporte and MUL.name LIKE 'tuberia%';";
        }elseif ($tipo == 'pie') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id
                                    FROM reportHistory AS RP
                                    -- INNER JOIN report_employee_form AS REF ON RP.idReport = REF.idReport
                                    INNER JOIN form_plumber AS FP ON FP.id = RP.idFormulario
                                    LEFT JOIN form_plumber_multimedia AS FPM ON FPM.idFormPlumber = FP.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FPM.idMultimedia
                                    WHERE RP.idReportType=3 AND RP.idReport =$idReporte and MUL.name LIKE 'pie%';";
        }elseif ($tipo == 'comprobante') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'comprobante%';";
        }elseif ($tipo == 'identificacion') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'identificacion%';";
        }elseif ($tipo == 'solicitud') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'solicitud%';";
        }elseif ($tipo == 'pagare') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'pagare%';";
        }elseif ($tipo == 'aviso') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'aviso%';";
        }elseif ($tipo == 'contrato') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_sells AS FS ON FS.id = RP.idFormSell
                                    LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FSM.idMultimedia
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and MUL.name LIKE 'contrato%';";
        }elseif ($tipo == 'cuadro') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_installation AS FI ON FI.consecutive = RP.idFormSell
                                    LEFT JOIN form_installation_multimedia AS FIM ON FIM.idFormInstallation = FI.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FIM.idMultimedia
                                    WHERE RP.idReportType=4 AND RP.idReport =$idReporte and MUL.name LIKE 'foto_cuadro%';";
        }elseif ($tipo == 'caratula') {
            $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id, MUL.created_at
                                    FROM reportHistory AS RP
                                    INNER JOIN form_installation AS FI ON FI.consecutive = RP.idFormSell
                                    LEFT JOIN form_installation_multimedia AS FIM ON FIM.idFormInstallation = FI.id
                                    LEFT JOIN multimedia AS MUL ON MUL.id = FIM.idMultimedia
                                    WHERE RP.idReportType=4 AND RP.idReport =$idReporte and MUL.name LIKE 'foto_caratula%';";
        }
        $arrIMG=array();
        //echo 'query '.$querySmtFrmPlumbIMG;
        $contImg=0;
        $result = $conn->query($querySmtFrmPlumbIMG);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $arrIMG[$contImg]= array('contentIMG' => $row[0],
                                         'nameIMG' => $row[1],
                                         'idIMG' => $row[2],
                                        );
                $contImg++;
            }
            $responseArray['arrIMG']=$arrIMG;
        }
        return $responseArray;
    }
}

function getMonthAndYear($idReporte, $tipoImg)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReporte != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($tipoImg == "tuberia" || $tipoImg == "pie") {
            $getYearContrato = "SELECT a.created_at 
                                FROM form_plumber a, reportHistory b 
                                WHERE a.id=b.idFormulario 
                                and b.idReport=$idReporte
                                and b.idReportType=3";
        }elseif ($tipoImg == "comprobante" ||
                 $tipoImg == "identificacion" || 
                 $tipoImg == "solicitud" || 
                 $tipoImg == "pagare" ||
                 $tipoImg == "aviso" || 
                 $tipoImg == "contrato") {
            $getYearContrato = "SELECT 
                                tec.fechaAlta AS created_at
                                FROM
                                form_sells AS fs,
                                reportHistory AS rh,
                                tEstatusContrato as tec
                                WHERE
                                0 = 0 
                                AND fs.id = rh.idFormSell
                                AND tec.idReporte = rh.idReport
                                AND idReport = $idReporte
                                AND rh.idReportType = 2;";
        }elseif ($tipoImg == "cuadro" || $tipoImg == "caratula") {
            $getYearContrato = "SELECT 
                                    a.created_at
                                FROM
                                    form_installation a,
                                    reportHistory b
                                WHERE
                                    a.consecutive = b.idFormSell
                                        AND b.idReport = $idReporte
                                        AND b.idReportType = 4;";
        }
        //$getYearContrato = "SELECT created_at FROM report WHERE id = $idReporte;";
        $result = $conn->query($getYearContrato);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res['fechaCreacion']=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getImagen($tipoImagen, $consPlumber, $imgInstTub, $imgEtiqueta)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($tipoImagen != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        if ($tipo == 'tuberia') {
            $stmtTipoImg = "SELECT 
                            m.id, m.name as nombreImagen
                        FROM
                            form_plumber a,
                            form_plumber_multimedia b,
                            multimedia m
                        WHERE
                            0 = 0 
                            AND a.id = b.idFormPlumber
                            AND b.idMultimedia = m.id
                            AND a.consecutive = $consPlumber
                            AND m.name LIKE '".$imgInstTub."%';";
        }elseif ($tipo == 'pie') {
            $stmtTipoImg = "SELECT 
                            m.id, m.name as nombreImagen
                        FROM
                            form_plumber a,
                            form_plumber_multimedia b,
                            multimedia m
                        WHERE
                            0 = 0 
                            AND a.id = b.idFormPlumber
                            AND b.idMultimedia = m.id
                            AND a.consecutive = $consPlumber
                            AND m.name LIKE '".$imgEtiqueta."%';";
        }elseif ($tipo == "comprobante" || 
                 $tipo == "identificacion" || 
                 $tipo == "solicitud" || 
                 $tipo == "pagare" ||
                 $tipo == "aviso" || 
                 $tipo == "contrato") {
            $stmtTipoImg = "SELECT 
                                m.id, m.name as nombreImagen
                            FROM
                                form_sells a,
                                form_sells_multimedia b,
                                reportHistory c,
                                multimedia m
                            WHERE
                                0 = 0 
                                AND a.id = c.idFormSell
                                AND a.id = b.idSell
                                AND b.idMultimedia = m.id
                                AND c.idReport = $consPlumber
                                AND m.name LIKE '".$imgEtiqueta."%';";
        }
        $result = $conn->query($stmtTipoImg);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res['idMultimedia']=$row[0];
                $res['nombreImagen']=$row[1];
            }
        }
        $conn->close();
    }
    return $res;
}
function getMonth($numMonth)
{
    switch ($numMonth) {
        case 0:
            $month='01';
            break;
        case 1:
            $month='02';
            break;
        case 2:
            $month='03';
            break;
        case 3:
            $month='04';
            break;
        case 4:
            $month='05';
            break;
        case 5:
            $month='06';
            break;
        case 6:
            $month='07';
            break;
        case 7:
            $month='08';
            break;
        case 8:
            $month='09';
            break;
        case 9:
            $month='10';
            break;
        case 10:
            $month='11';
            break;
        case 11:
            $month='12';
            break;
    }
    return $month;
}