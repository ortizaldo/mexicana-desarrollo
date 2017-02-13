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
$idReporte=intval($_POST["reportID"]);
$tipoImg=$_POST["tipoImg"];
$imgInstTub=$_POST["imgInstTub"];
$imgEtiqueta=$_POST["imgEtiqueta"];
$imgComprobante=$_POST["imgComprobante"];
$financialServiceVal=$_POST["financialServiceVal"];
$statusInstalacion=$_POST["statusInstalacion"];
$getDatosStatus = getNumeroContrato($idReporte);
//var_dump($getDatosStatus);
//die();
$base_url2= "http://siscomcmg.com:8080/";
//$base_url2= "http://siscomcmg.com/";
//$base_url2= "http://localhost/mexicanaDesarrollo/mexicana-de-gas-backoffice/";
//var base_url2= "http://localhost/mexicana-de-gas-backoffice/";
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

if (isset($_FILES["PostImage"]["type"]) && isset($_POST["reportID"])) {
    $validextensions = array(
        "jpeg",
        "jpg",
        "png"
    );
    $temporary       = explode(".", $_FILES["PostImage"]["name"]);
    $file_extension  = end($temporary);
    if ((($_FILES["PostImage"]["type"] == "image/png") || ($_FILES["PostImage"]["type"] == "image/jpg") || ($_FILES["PostImage"]["type"] == "image/jpeg"))&& (in_array($file_extension, $validextensions))) {
        if ($_FILES["PostImage"]["error"] > 0) {
            //echo "Return Code: " . $_FILES["PostImage"]["error"] . "<br/><br/>";
        } else {
            if ((intval($getDatosStatus['estatusInstalacion']) != 51 || intval($getDatosStatus['estatusInstalacion']) != 54) &&
                $tipoImg != "cuadro") {
                //generamos la ruta para la carpeta en proceso
                $dataDate=getMonthAndYear($idReporte, $tipoImg);
                $year=date('Y', strtotime($dataDate["fechaCreacion"]));
                $month=date('m', strtotime($dataDate["fechaCreacion"]));
                $size=$_FILES["PostImage"]["size"];
                $carpetaVenta="";
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
                //buscamos la foto que queremos cambiar dependiendo el tipo que hayan querido cambiar
                switch ($tipoImg) {
                    case 'tuberia':
                        $imageName="tuberia";
                        break;
                    case 'pie':
                        $imageName="pie_derecho";
                        break;
                    case 'comprobante':
                        $imageName="comprobante";
                        break;
                    case 'identificacion':
                        $imageName="identificacion";
                        break;
                    case 'solicitud':
                        $imageName="solicitud";
                        break;
                    case 'aviso':
                        $imageName="aviso";
                        break;
                    case 'contrato':
                        $imageName="contrato";
                        break;
                    case 'pagare':
                        $imageName="pagare";
                        break;
                }
                $imageName = $imageName  . "_File_" . generateRandomString() . "." . $file_extension;
                $sourcePath = $_FILES['PostImage']['tmp_name'];
                
            }elseif($tipoImg == "cuadro"){
                //generamos la ruta para la carpeta en proceso
                $dataDate=getMonthAndYear($idReporte, $tipoImg);
                $year=date('Y', strtotime($dataDate["fechaCreacion"]));
                $month=date('m', strtotime($dataDate["fechaCreacion"]));
                $size=$_FILES["PostImage"]["size"];
                $carpetaVenta="";
                if ($statusInstalacion == 51 || $statusInstalacion == 54) {
                    $carpeta=$CARPETA_TERMINADOS;
                }else{
                    $carpeta=$CARPETA_EN_PROCESO;
                }
                $rutaImagenes = '../../'.$CARPETA_IMAGENES."/".$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
                $base_url2= $base_url2.$CARPETA_IMAGENES."/".$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
                //buscamos la foto que queremos cambiar dependiendo el tipo que hayan querido cambiar
                switch ($tipoImg) {
                    case 'cuadro':
                        $imageName="foto_cuadro";
                        break;
                }
                $imageName = $imageName  . "_File_" . generateRandomString() . "." . $file_extension;
                $sourcePath = $_FILES['PostImage']['tmp_name'];
            }
            if (is_writable($sourcePath)) {
                if (!file_exists($rutaImagenes)) {
                    mkdir($rutaImagenes, 0777, true);
                }
                if (file_exists($rutaImagenes)) {
                    $rutaActual=getcwd();
                    chdir('../../'.$CARPETA_IMAGENES);
                    $carpetaVenta="";
                    if ($financialServiceVal != "" && $financialServiceVal == 1) {
                        $carpetaVenta=$CARPETA_AYOPSA;
                    }elseif ($financialServiceVal != "" && $financialServiceVal == 0) {
                        $carpetaVenta=$CARPETA_CMG;
                    }
                    if ($tipoImg == "tuberia" || $tipoImg == "pie") {
                        $rutaModificada=getcwd().'/'.$CARPETA_PLOMERIA."/".$CARPETA_EN_PROCESO."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
                    }elseif ($tipoImg == "comprobante" ||
                             $tipoImg == "identificacion" || 
                             $tipoImg == "solicitud" || 
                             $tipoImg == "pagare" ||
                             $tipoImg == "aviso" || 
                             $tipoImg == "contrato") {
                        $rutaModificada=getcwd().'/'.$CARPETA_VENTAS."/".$CARPETA_EN_PROCESO."/".$carpetaVenta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
                    }elseif ($tipoImg == "cuadro") {
                        if ($statusInstalacion == 51 || $statusInstalacion == 54) {
                            $carpeta=$CARPETA_TERMINADOS;
                        }else{
                            $carpeta=$CARPETA_EN_PROCESO;
                        }
                        $rutaModificada=getcwd().'/'.$CARPETA_INSTALACION."/".$carpeta."/".$year."/".$month."/".$getDatosStatus['numContrato']."/";
                    }
                    $targetPath = $rutaModificada . $imageName;
                    if (move_uploaded_file($sourcePath, $targetPath)) {
                        //actualizamos los datos de las tablas de multimedia_plumber y multimedia cuando ya aseguremos que se cargo correctamente la imagen
                        if ($imageName != "") {
                            $DB = new DAO();
                            $conn = $DB->getConnect();
                            $stmtInsertFoto="INSERT INTO multimedia(name ,extension ,size,updated_at,created_at)VALUES(?,?,?,NOW(),NOW());";
                            if ($insertFoto = $conn->prepare($stmtInsertFoto)) {
                                $insertFoto->bind_param("sss",$imageName, $file_extension, $size);
                                if ($insertFoto->execute()) {
                                    $idMultimedia = $insertFoto->insert_id;//idMultimedia
                                    if ($tipoImg == "tuberia" || $tipoImg == "pie") {
                                        $idFormPlumber=getIdForm($idReporte, $tipoImg);
                                        $id=$idFormPlumber["idForm"];
                                        $stmtInsertFotoM="INSERT INTO form_plumber_multimedia(idFormPlumber,idMultimedia,created_at,updated_at)VALUES(?,?,NOW(),NOW());";
                                    }elseif ($tipoImg == "comprobante" ||
                                             $tipoImg == "identificacion" || 
                                             $tipoImg == "solicitud" || 
                                             $tipoImg == "pagare" ||
                                             $tipoImg == "aviso" || 
                                             $tipoImg == "contrato") {
                                        $idFormSell=getIdForm($idReporte, $tipoImg);
                                        $id=$idFormSell["idForm"];
                                        $stmtInsertFotoM="INSERT INTO form_sells_multimedia(idSell,idMultimedia,created_at,updated_at)VALUES(?,?,NOW(),NOW());";
                                    }elseif ($tipoImg == "cuadro") {
                                        $idFormInstall=getIdForm($idReporte, $tipoImg);
                                        $id=$idFormInstall["idForm"];
                                        $stmtInsertFotoM="INSERT INTO form_installation_multimedia(idFormInstallation,idMultimedia,created_at,updated_at)VALUES(?,?,NOW(),NOW());";
                                    }
                                    if ($insertFotoM = $conn->prepare($stmtInsertFotoM)) {
                                        $insertFotoM->bind_param("ii",$id, $idMultimedia);
                                        if ($insertFotoM->execute()) {
                                            $response["status"] = "EXITO";
                                            $response["code"] = "200";
                                            $response["responseImg"] = getImgs($idReporte, $tipoImg);
                                            $response["responseRuta"] = $base_url2;
                                            $response["response"] = "La imagen se cargo con exito";
                                            echo json_encode($response);
                                            chdir('../');
                                        }
                                    }else{
                                        $response["status"] = "ERROR";
                                        $response["code"] = "500";
                                        $response["response"] = "error / ".$conn->error;
                                        echo json_encode($response);
                                        chdir($rutaActual);
                                    }
                                }else{
                                    $response["status"] = "ERROR";
                                    $response["code"] = "500";
                                    $response["response"] = "error / ".$conn->error;
                                    echo json_encode($response);
                                }
                            }else{
                                $response["status"] = "ERROR";
                                $response["code"] = "500";
                                $response["response"] = "error / ".$conn->error;
                                echo json_encode($response);
                                chdir($rutaActual);
                            }
                        }else{
                            $response["status"] = "ERROR";
                            $response["code"] = "500";
                            $response["response"] = "error / ".$imageName;
                            echo json_encode($response);
                        }
                    }else{
                        //mandamos error 500
                        $response["status"] = "ERROR";
                        $response["code"] = "500";
                        $response["response"] = "Hubo un problema al cargar la imagen";
                        echo json_encode($response);
                        chdir($rutaActual);
                    }
                } else {
                    $response["status"] = "ERROR";
                    $response["code"] = "500";
                    $response["rutaActual"] = getcwd();
                    $response["rutaImagenes"] = $rutaImagenes;
                    $response["response"] = "No se encontro la ruta para almacenar la imagen";
                    echo json_encode($response);
                }
            } else {
                $response["status"] = "ERROR";
                $response["code"] = "500";
                $response["response"] = "The file is not writable";
                echo json_encode($response);
            }
        }
    } else {
        $response["status"] = "ERROR";
        $response["code"] = "500";
        $response["response"] = "Formato no permitido..";
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
                       and a.id = $idReporte;";
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
                                    and b.idReport=$idReporte;";
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
        }elseif ($tipoImg == "cuadro") {
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
                                    WHERE RP.idReportType=2 AND RP.idReport =$idReporte and (MUL.name LIKE 'identificacion%' OR MUL.name LIKE 'indentificacion%');";
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
        }elseif ($tipoImg == "cuadro") {
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