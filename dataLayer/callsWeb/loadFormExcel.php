<?php include_once "../DAO.php";
//echo json_encode($_POST);
ini_set("memory_limit","1024M");
if (isset($_POST['form']) && isset($_POST['type']) && isset($_POST['idUsuario'])) {
    $reports = array();

    $DB = new DAO();
    $conn = $DB->getConnect();

    $idForm = $_POST['form'];
    $type = $_POST['type'];
    $idUsuario = $_POST['idUsuario'];
    
    $reports = GetForm($conn, $type, $idForm, $idUsuario);
    $reports['datosGrales']=$_POST['collection'];
    echo json_encode($reports);
    //echo $type;

}

function GetForm($conn, $type, $idForm, $idUsuario)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray = array();
    $responseArrayGetForm = array();
    $id = "";
    $prospect = "";
    $comments = "";
    $uninteresting = "";
    $motivosDesinteres = "";
    $owner = "";
    $name = "";
    $lastName = "";
    $lastNameOp = "";
    $payment = "";
    $financialService = "";
    $requestNumber = "";
    $meeting = "";
    $content = "";
    $imageName = "";
    $estatus = "";
    $puedeValidar = "";
    $created_at="";
    $agenciaVendedor="";

    /**CENSO**/
    $lote="";
    $houseStatus="";
    $nivel="";
    $giro="";
    $acometida="";
    $observacion="";
    $tapon="";
    $medidor="";
    $marca="";
    $tipo="";
    $NoSerie="";
    $niple="";
    $estatusAsignacionInstalacion = "";

    $stmtGetForm = $conn->prepare("call spGetform(?,?,?);");
    mysqli_stmt_bind_param($stmtGetForm, 'sii',
        $type, $idForm, $idUsuario
    );
    
    $stmtGetForm->execute();
    $stmtGetForm->store_result();

        if ($type == "Venta") {

            $stmtGetForm->bind_result($id, $prospect, $uninteresting, $motivosDesinteres, $comments, $owner, $id, $name, $lastName, $lastNameOp, $payment, $financialService, $requestNumber, $meeting, $content, $imageName, $estatus, $puedeValidar,$created_at,$agenciaVendedor, $estatusAsignacionInstalacion, $idClienteGenerado);

            while ($stmtGetForm->fetch()) {
                $responseArray["id"] = $id;
                $responseArray["prospect"] = $prospect;
                $responseArray["uninteresting"] = $uninteresting;
                $responseArray["motivosDesinteres"] = $motivosDesinteres;
                $responseArray["comments"] = $comments;
                $responseArray["owner"] = $owner;
                $responseArray["name"] = $name;
                $responseArray["lastName"] = $lastName;
                $responseArray["lastNameOp"] = $lastNameOp;
                $responseArray["payment"] = $payment;
                $responseArray["financialService"] = $financialService;
                $responseArray["requestNumber"] = $requestNumber;
                $responseArray["meeting"] = $meeting;
                $responseArray["content"] = $content;
                $responseArray["imageName"] = $imageName;
                $responseArray["estatus"] = $estatus;
                $responseArray["puedeValidar"] = $puedeValidar;
                $responseArray["created_at"] = $created_at;
                $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                $responseArray["idClienteGenerado"] = $idClienteGenerado;
                $responseArrayGetForm['venta'] = $responseArray;
            }
        } elseif ($type == "Censo") {
            $stmtGetForm->bind_result($id, $lote, $houseStatus, $nivel, $content, $name, $giro, $acometida, $observacion, $tapon, $medidor, $marca, $tipo, $NoSerie, $niple, $created_at);

            while ($stmtGetForm->fetch()) {
                $responseArray["id"] = $id;
                $responseArray["lote"] = $lote;
                $responseArray["houseStatus"] = $houseStatus;
                $responseArray["nivel"] = $nivel;
                $responseArray["content"] = $content;
                $responseArray["name"] = $name;
                $responseArray["giro"] = $giro;
                $responseArray["acometida"] = $acometida;
                $responseArray["observacion"] = $observacion;
                $responseArray["tapon"] = $tapon;
                $responseArray["medidor"] = $medidor;
                $responseArray["marca"] = $marca;
                $responseArray["tipo"] = $tipo;
                $responseArray["NoSerie"] = $NoSerie;
                $responseArray["niple"] = $niple;
                $responseArray["created_at"] = $created_at;
                $responseArrayGetForm['censo'] = $responseArray;
            }
        } elseif ($type == "Plomero") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $querySmtFrmPlumb = "SELECT FP.id, FP.consecutive,FP.name,FP.lastName, FP.request, 
                                FP.documentNumber, FP.tapon, FP.ri, FP.comments, FP.newPipe, 
                                FP.diagram, FP.pipesCount, FP.ph, RP.created_at, tec.estatusAsignacionInstalacion                                 
                                FROM reportHistory AS RP
                                INNER JOIN report_employee_form AS REF ON RP.idReport = REF.idReport
                                INNER JOIN form_plumber AS FP ON FP.id = REF.idForm
                                INNER JOIN tEstatusContrato AS tec ON tec.idReporte=RP.idReport
                                WHERE RP.idReportType=3 
                                AND REF.idReport =?";
            //var_dump($conn->prepare($querySmtFrmPlumb));
            if ($stmtFrmPlumb = $conn->prepare($querySmtFrmPlumb)) {
                $stmtFrmPlumb->bind_param("i", $idForm);
                if($stmtFrmPlumb->execute()){
                    $stmtFrmPlumb->store_result();
                    $stmtFrmPlumb->bind_result($id,$consecutive,$name,$lastName,$request,
                            $documentNumber,$tapon,$ri,$observations,$newPipe,
                            $diagram,$pipesCount, $resPH,  $created_at, $estatusAsignacionInstalacion);
                    //var_dump($stmtFrmPlumb);
                    if ($stmtFrmPlumb->num_rows > 0) {
                        if($stmtFrmPlumb->fetch()){
                            $responseArray['id'] = $id;
                            $responseArray['consecutive'] = $consecutive;
                            $responseArray['namePerso'] = $name;
                            $responseArray['lastName'] = $lastName;
                            $responseArray['request'] = $request;
                            $responseArray['dictamen'] = $documentNumber;
                            $responseArray['tapon'] = $tapon;
                            $responseArray['ri'] = $ri;
                            $responseArray['observations'] = $observations;
                            $responseArray['newPipe'] = $newPipe;
                            $responseArray['numTomas'] = $pipesCount;
                            $responseArray['diagram'] = $diagram;
                            $responseArray['resultadoPH'] = $resPH;
                            $responseArray["created_at"] = $created_at;
                            $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                        }
                        $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id
                                                FROM reportHistory AS RP
                                                INNER JOIN report_employee_form AS REF ON RP.idReport = REF.idReport
                                                INNER JOIN form_plumber AS FP ON FP.id = RP.idFormulario
                                                LEFT JOIN form_plumber_multimedia AS FPM ON FPM.idFormPlumber = FP.id
                                                LEFT JOIN multimedia AS MUL ON MUL.id = FPM.idMultimedia
                                                WHERE RP.idReportType=3 AND REF.idReport =? LIMIT 2";
                        $arrIMG=array();
                        //echo $idForm;
                        if ($stmtFrmPlumbIMG = $conn->prepare($querySmtFrmPlumbIMG)) {
                            $stmtFrmPlumbIMG->bind_param("i", $idForm);
                            if($stmtFrmPlumbIMG->execute()){
                                $stmtFrmPlumbIMG->store_result();
                                $stmtFrmPlumbIMG->bind_result($contentIMG, $nameIMG, $idIMG);
                                //var_dump($stmtFrmPlumbIMG);
                                $contImg=0;
                                while ($stmtFrmPlumbIMG->fetch()) {
                                    $arrIMG[$contImg]= array('contentIMG' => $contentIMG,
                                                          'nameIMG' => $nameIMG,
                                                          'idIMG' => $idIMG,
                                                          );
                                    $contImg++;
                                }
                                $responseArray['arrIMG']=$arrIMG;
                            }
                        }
                        $querySmtFrmPlumbDet = "SELECT 
                                                FPD.path, FPD.distance, FPD.pipe, FPD.fall, FPD.id
                                                FROM report AS RP
                                                INNER JOIN report_employee_form AS REF ON RP.id = REF.idReport
                                                INNER JOIN form_plumber AS FP ON FP.id = REF.idForm
                                                LEFT JOIN form_plumber_details AS FPD ON FP.id  = FPD.idFormPlumber
                                                WHERE REF.idReport =?";
                        $arrDet=array();
                        if ($stmtFrmPlumbDet = $conn->prepare($querySmtFrmPlumbDet)) {
                            $stmtFrmPlumbDet->bind_param("i", $idForm);
                            if($stmtFrmPlumbDet->execute()){
                                $stmtFrmPlumbDet->store_result();
                                $stmtFrmPlumbDet->bind_result($path, $distance, $pipe, $fall, $idDet);
                                $cont=0;
                                while ($stmtFrmPlumbDet->fetch()) {
                                    $arrDet[$cont]= array('path' => $path,
                                                          'distance' => $distance,
                                                          'pipe' => $pipe,
                                                          'fall' => $fall,
                                                          'idDet' => $idDet 
                                                          );
                                    $cont++;
                                }
                                $responseArray['formPlumbDet']=$arrDet;
                            }
                        }
                    }else{
                        $responseArray["status"] = "BAD";
                        $responseArray["code"] = "500";
                        $responseArray["result"] = "Formulario aun no disponible, todavia no se puede visualizar este formulario hasta completar los procesos anteriores.";
                    }
                    $responseArrayGetForm['plomero'] = $responseArray;
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = $conn->error;
                    $responseArrayGetForm[]=$result;
                }
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = $conn->error;
                $responseArrayGetForm[]=$result;
            }
            //$conn->close();
        } elseif ($type == "Instalacion") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $querySmtFrmPlumb = "SELECT FP.id, FP.consecutive,FP.name,FP.lastName, FP.request,
                                        FP.phLabel, FP.agencyPh, FP.agencyNumber,FP.installation,FP.abnormalities, FP.comments, FP.brand,FP.type, FP.serialNumber, FP.measurement,FP.latitude,FP.longitude,FP.created_at, te.estatusAsignacionInstalacion ,RP.agreementNumber
                                             FROM report AS RP
                                             INNER JOIN reportHistory AS REF ON RP.id = REF.idReport
                                             INNER JOIN form_installation AS FP ON FP.consecutive = REF.idFormSell
                                             INNER JOIN tEstatusContrato AS te ON te.idReporte = RP.id
                                             WHERE REF.idReport =?
                                             and REF.idReportType=4;";
            //var_dump($conn->prepare($querySmtFrmPlumb));
            if ($stmtFrmPlumb = $conn->prepare($querySmtFrmPlumb)) {
                $stmtFrmPlumb->bind_param("i", $idForm);
                if($stmtFrmPlumb->execute()){
                    $stmtFrmPlumb->store_result();
                    $stmtFrmPlumb->bind_result($id,$consecutive, $name,$lastName, $request, $phLabel,$agencyPh,$agencyNumber,$installation,
                        $abnormalities,$comments, $brand,$type, $serialNumber,$measurement,$latitude,$longitude,$created_at, $estatusAsignacionInstalacion, $agreementNumber);
                    if($stmtFrmPlumb->fetch()){
                        $responseArray['id'] = $id;
                        $responseArray['consecutive'] = $consecutive;
                        $responseArray['name'] = $name;
                        $responseArray['lastName'] = $lastName;
                        $responseArray['request'] = $request;
                        $responseArray['phLabel'] = $phLabel;
                        $responseArray['agencyPh'] = $agencyPh;
                        $responseArray['agencyNumber'] = $agencyNumber;
                        $responseArray['installation'] = $installation;
                        $responseArray['abnormalities'] = $abnormalities;
                        $responseArray['comments'] = $comments;
                        $responseArray['brand'] = $brand;
                        $responseArray['type'] = $type;
                        $responseArray['serialNumber'] = $serialNumber;
                        $responseArray['measurement'] = $measurement;
                        $responseArray['latitude'] = $latitude;
                        $responseArray['longitude'] = $longitude;
                        $responseArray['created_at'] = $created_at;
                        $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                        $responseArray["agreementNumber"] = $agreementNumber;

                    }
                    $querySmtFrmPlumbIMG = "SELECT MUL.content, MUL.name, MUL.id
                                            FROM report AS RP
                                            INNER JOIN reportHistory AS REF ON RP.id = REF.idReport
                                            INNER JOIN form_installation AS FP ON FP.consecutive = REF.idFormSell
                                            LEFT JOIN form_installation_multimedia AS FPM ON FPM.idFormInstallation = FP.id
                                            LEFT JOIN multimedia AS MUL ON MUL.id = FPM.idMultimedia
                                            WHERE REF.idReport =?
                                            and REF.idReportType=4;";
                    $arrIMG=array();
                    //echo $idForm;
                    if ($stmtFrmPlumbIMG = $conn->prepare($querySmtFrmPlumbIMG)) {
                        $stmtFrmPlumbIMG->bind_param("i", $idForm);
                        if($stmtFrmPlumbIMG->execute()){
                            $stmtFrmPlumbIMG->store_result();
                            $stmtFrmPlumbIMG->bind_result($contentIMG, $nameIMG, $idIMG);
                            //var_dump($stmtFrmPlumbIMG);
                            $contImg=0;
                            while ($stmtFrmPlumbIMG->fetch()) {
                                $arrIMG[$contImg]= array('contentIMG' => $contentIMG,
                                    'nameIMG' => $nameIMG,
                                    'idIMG' => $idIMG,
                                );
                                $contImg++;
                            }
                            $responseArray['arrIMG']=$arrIMG;
                        }
                    }
                    $querySmtFrmPlumbDet = "SELECT
                                            FPD.qty, FPD.material, FPD.id
                                            FROM report AS RP
                                            -- INNER JOIN report_employee_form AS REF ON RP.id = REF.idReport
                                            INNER JOIN reportHistory AS REF ON RP.id = REF.idReport
                                            INNER JOIN form_installation AS FP ON FP.consecutive = REF.idFormSell
                                            LEFT JOIN form_installation_details AS FPD ON FP.id  = FPD.idFormInstallation
                                            WHERE REF.idReport =?
                                            and REF.idReportType=4;";
                    $arrDet=array();
                    if ($stmtFrmPlumbDet = $conn->prepare($querySmtFrmPlumbDet)) {
                        $stmtFrmPlumbDet->bind_param("i", $idForm);
                        if($stmtFrmPlumbDet->execute()){
                            $stmtFrmPlumbDet->store_result();
                            $stmtFrmPlumbDet->bind_result($qty, $material, $id);
                            $cont=0;
                            while ($stmtFrmPlumbDet->fetch()) {
                                $material=ltrim($material);
                                $arrDet[$cont]= array('qty' => $qty,
                                    'material' => $material,
                                    'id' => $id
                                );
                                $cont++;
                            }
                            $responseArray['formInstDet']=$arrDet;
                        }
                    }
                    $responseArrayGetForm['installation'] = $responseArray;
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = $conn->error;
                    $responseArrayGetForm[]=$result;
                }
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = $conn->error;
                $responseArrayGetForm[]=$result;
            }
            //$conn->close();
        } elseif ($type == "SegundaVenta") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $querySmtFrmSecondSell = "SELECT rep.id as repCons, rep.agreementNumber, rep.clientName as nomCliente, 
                                        rep.clientLastName1 as apPat, rep.clientLastName2 as apMat,
                                        rep.colonia, rep.street, rep.betweenStreets,rep.outterNumber, rep.idCountry, 
                                        rep.idState, rep.idCity, rep.idFormulario, frmVUno.payment, 
                                        frmVUno.financialService, frmVUno.created_at as fechaSol                               
                                        from report rep, form_sells frmVUno
                                        where 0=0
                                        and rep.idFormulario=frmVUno.id 
                                        and rep.id=?";
            //var_dump($stmtFrmSecondSell);
            if ($stmtFrmSecondSell = $conn->prepare($querySmtFrmSecondSell)) {
                $stmtFrmSecondSell->bind_param("i", $idForm);
                if($stmtFrmSecondSell->execute()){
                    $stmtFrmSecondSell->store_result();
                    $stmtFrmSecondSell->bind_result($repCons,$agreementNumber,$nomCliente,$apPat,$apMat,
                                               $colonia,$street,$betweenStreets,$outterNumber,$idCountry,$idState,
                                               $idCity,$idFormulario,$payment,$financialService,$fechaSol
                    );
                    //var_dump($stmtFrmSecondSell);
                    if($stmtFrmSecondSell->fetch()){
                        $responseArray['repCons']=$repCons;
                        $responseArray['agreementNumber']=$agreementNumber;
                        $responseArray['nomCliente']=$nomCliente;
                        $responseArray['apPat']=$apPat;
                        $responseArray['apMat']=$apMat;
                        $responseArray['colonia']=$colonia;
                        $responseArray['street']=$street;
                        $responseArray['betweenStreets']=$betweenStreets;
                        $responseArray['outterNumber']=$outterNumber;
                        $responseArray['idCountry']=$idCountry;
                        $responseArray['idState']=$idState;
                        $responseArray['idCity']=$idCity;
                        $responseArray['idFormulario']=$idFormulario;
                        $responseArray['payment']=$payment;
                        $responseArray['financialService']=$financialService;
                        $responseArray['fechaSol']=$fechaSol;
                        $getReportStatus = "SELECT USEMP.id as useIdEmp,
                                                   USEMP.nickname,
                                                   rep.idReportType,
                                                   AG.id as idAgencia,
                                                   (SELECT us.nickname from user as us WHERE us.id = AG.idUser) as descAgencia
                                            FROM user AS USAG
                                            INNER JOIN agency AS AG ON USAG.id = AG.idUser
                                            INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
                                            INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
                                            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
                                            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
                                            INNER JOIN report as rep on rep.idUserCreator=USEMP.id
                                            INNER JOIN report_employee_form as RPEMP on rep.id=RPEMP.idReport
                                            INNER JOIN form_sells AS FRMSELL ON RPEMP.idForm = FRMSELL.id 
                                            WHERE 0=0
                                            AND rep.id=$idForm";
                        
                        $getReportStatus;
                        $result = $conn->query($getReportStatus);

                        while( $row = $result->fetch_array() ) {
                            $responseArray['IDEmp'] = $row[0];
                            $responseArray['nicknameEmp'] = $row[1];
                            $responseArray['idAgencia']=$row[3];
                            $responseArray['descAgencia']=$row[4];
                            //$reports[] = $returnData;
                        }
                    }
                    $responseArrayGetForm[] = $responseArray;
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = $conn->error;
                    $responseArrayGetForm[]=$result;
                }
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = $conn->error;
                $responseArrayGetForm[]=$result;
            }
            //$conn->close();
        } elseif ($type == "Segunda Venta") {
            $DB = new DAO();
            $conn = $DB->getConnect();
            $querySmtFrmSecondSell = "SELECT 
                                            rep.id as repCons, rep.agreementNumber, frmVUno.name as nomCliente, 
                                            frmVUno.lastName as apPat, frmVUno.lastNameOp as apMat,
                                        rep.colonia, rep.street, rep.betweenStreets,rep.outterNumber, rep.idCountry, 
                                        rep.idState, rep.idCity, reph.idFormSell, frmVUno.payment, 
                                        frmVUno.financialService, frmVUno.created_at as   fechaSol, reph.idUserAssigned,
                                        ec.estatusAsignacionInstalacion
                                    FROM report rep
                                            INNER JOIN form_sells AS frmVUno ON rep.idFormulario = frmVUno.id 
                                        INNER JOIN reportHistory AS reph ON rep.id = reph.idReport
                                            INNER JOIN tEstatusContrato AS ec ON rep.id = ec.idReporte 
                                    WHERE 0=0
                                            AND reph.idReportType=2
                                            AND reph.idReport=?";
            //var_dump($stmtFrmSecondSell);
            //var_dump($idForm);
            if ($stmtFrmSecondSell = $conn->prepare($querySmtFrmSecondSell)) {
                $stmtFrmSecondSell->bind_param("i", $idForm);
                if($stmtFrmSecondSell->execute()){
                    $stmtFrmSecondSell->store_result();
                    $stmtFrmSecondSell->bind_result($repCons,$agreementNumber,$nomCliente,$apPat,$apMat,
                                               $colonia,$street,$betweenStreets,$outterNumber,$idCountry,$idState,
                                               $idCity,$idFormulario,$payment,$financialService,$fechaSol, $idUserAssigned, $estatusAsignacionInstalacion
                    );
                    //var_dump($stmtFrmSecondSell);
                    if($stmtFrmSecondSell->fetch()){
                        $responseArray['repCons']=$repCons;
                        $responseArray['agreementNumber']=$agreementNumber;
                        $responseArray['nomCliente']=$nomCliente;
                        $responseArray['apPat']=$apPat;
                        $responseArray['apMat']=$apMat;
                        $responseArray['colonia']=$colonia;
                        $responseArray['street']=$street;
                        $responseArray['betweenStreets']=$betweenStreets;
                        $responseArray['outterNumber']=$outterNumber;
                        $responseArray['idCountry']=$idCountry;
                        $responseArray['idState']=$idState;
                        $responseArray['idCity']=$idCity;
                        $responseArray['idFormulario']=$idFormulario;
                        $responseArray['payment']=$payment;
                        $responseArray['financialService']=$financialService;
                        $responseArray['fechaSol']=$fechaSol;
                        $responseArray['idUserAssigned']=$idUserAssigned;
                        $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                        $getReportStatus = "SELECT USEMP.id as useIdEmp,
                                                   USEMP.nickname,
                                                   repH.idReportType,
                                                   AG.id as idAgencia,
                                                   (SELECT us.nickname from user as us WHERE us.id = AG.idUser) as descAgencia
                                            FROM user AS USAG
                                            INNER JOIN agency AS AG ON USAG.id = AG.idUser
                                            INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
                                            INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
                                            INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
                                            INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
                                            INNER JOIN reportHistory AS repH ON repH.idUserAssigned = USEMP.id
                                            -- INNER JOIN report_employee_form as RPEMP on rep.id=RPEMP.idReport
                                            INNER JOIN form_sells AS FRMSELL ON repH.idFormSell = FRMSELL.id 
                                            WHERE 0=0
                                            -- AND repH.idReportType=5
                                            AND repH.idReport=$idForm";
                        
                        //$getReportStatus;
                        $result = $conn->query($getReportStatus);

                        while( $row = $result->fetch_array() ) {
                            $responseArray['IDEmp'] = $row[0];
                            $responseArray['nicknameEmp'] = $row[1];
                            $responseArray['idAgencia']=$row[3];
                            $responseArray['descAgencia']=$row[4];
                            //$reports[] = $returnData;
                        }
                    }
                    //obtenemos la respuesta de la funcion segunda venta
                    //$responseArrayGetForm['SegundaVenta'] = $responseArray;
                    $resSegundaVentaResults=obtenerSegundaVenta($idForm, $conn);
                    $resGetImgSolicitud=getImgSolicitud($idFormulario, $conn);
                    if ($resSegundaVentaResults != 0) {
                        $responseArrayGetForm["formSegVta"]=$resSegundaVentaResults;
                    }
                    /*if ($resGetImgSolicitud != 0) {
                        $responseArrayGetForm["imgSolicitud"]=$resGetImgSolicitud;
                    }*/
                }else{
                    $result["status"] = "BAD";
                    $result["code"] = "500";
                    $result["result"] = $conn->error;
                    $responseArrayGetForm[]=$result;
                }
            }else{
                $result["status"] = "BAD";
                $result["code"] = "500";
                $result["result"] = $conn->error;
                $responseArrayGetForm[]=$result;
            }
            //$conn->close();
        }
    //print_r($stmtGetForm);
    $stmtGetForm->free_result();
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtGetForm->close();
    //$conn->close();
    return $responseArrayGetForm;
}
function obtenerSegundaVenta($idReport, $conn){
    if ($idReport != '') {
        $getReportStatus = "SELECT id,idAgency,payment,idReport,requestDate,clientlastName,
                            clientlastName2,clientName,clientRFC,clientCURP,clientEmail,
                            clientRelationship,clientgender,clientIdNumber,identificationType,
                            clientBirthDate,clientBirthCountry,idState,idCity,idColonia,
                            street,inHome,homeTelephone,celullarTelephone,agreementType,
                            price,agreementExpires,agreementMonthlyPayment,agreementRi,
                            agreementRiDate,clientJobEnterprise,clientJobLocation,clientJobRange,
                            clientJobActivity,clientJobTelephone,latitude,longitude, idArt, te.idClienteGenerado
                            FROM agreement agr, tEstatusContrato te where agr.idReport=te.idReporte and te.idReporte=$idReport";
        $result = $conn->query($getReportStatus);
        //var_dump($result);
        if ($result->num_rows > 0) {
            while( $row = $result->fetch_array() ) {
                $responseArray['idAgreement_agrr']=$row[0];
                //obtenemos las referencias
                $idAgreement=$row[0];
                $resReference=getReferences($idAgreement, $conn);
                $responseArray['idAgency_agrr']=$row[1];
                $responseArray['payment_agrr']=$row[2];
                $responseArray['idReport_agrr']=$row[3];
                $responseArray['requestDate_agrr']=$row[4];
                $responseArray['clientlastName_agrr']=$row[5];
                $responseArray['clientlastName2_agrr']=$row[6];
                $responseArray['clientName_agrr']=$row[7];
                $responseArray['clientRFC_agrr']=$row[8];
                $responseArray['clientCURP_agrr']=$row[9];
                $responseArray['clientEmail_agrr']=$row[10];
                $responseArray['clientRelationship_agrr']=$row[11];
                $responseArray['clientgender_agrr']=$row[12];
                $responseArray['clientIdNumber_agrr']=$row[13];
                $responseArray['identificationType_agrr']=$row[14];
                $responseArray['clientBirthDate_agrr']=$row[15];
                $responseArray['clientBirthCountry_agrr']=$row[16];
                $responseArray['idState_agrr']=$row[17];
                $responseArray['idCity_agrr']=$row[18];
                $responseArray['idColonia_agrr']=$row[19];
                $responseArray['street_agrr']=$row[20];
                $responseArray['inHome_agrr']=$row[21];
                $responseArray['homeTelephone_agrr']=$row[22];
                $responseArray['celullarTelephone_agrr']=$row[23];
                $responseArray['agreementType_agrr']=$row[24];
                $responseArray['price_agrr']=$row[25];
                $responseArray['agreementExpires_agrr']=$row[26];
                $responseArray['agreementMonthlyPayment_agrr']=$row[27];
                $responseArray['agreementRi_agrr']=$row[28];
                $responseArray['agreementRiDate_agrr']=$row[29];
                $responseArray['clientJobEnterprise_agrr']=$row[30];
                $responseArray['clientJobLocation_agrr']=$row[31];
                $responseArray['clientJobRange_agrr']=$row[32];
                $responseArray['clientJobActivity_agrr']=$row[33];
                $responseArray['clientJobTelephone_agrr']=$row[34];
                $responseArray['latitude_agrr']=$row[35];
                $responseArray['longitude']=$row[36];
                $responseArray['idArt']=$row[37];
                $responseArray['idClienteGenerado']=$row[38];
                if($resReference != 0){
                    $responseArray['referencias']=$resReference;
                }
            }
            $res=$responseArray;
        }else{
            $res=$result->num_rows;
        }
    }
    return $res;
}

function getReferences($idAgreement, $conn){
    if ($idAgreement != '') {
        $getReference = "SELECT id,name,telephone,jobTelephone,ext
                            from agreement_reference where idAgreement=$idAgreement";
        //echo $getReference;
        $result = $conn->query($getReference);
        $cont=0;
        //var_dump($result);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $responseArray[$cont] = array('id' => $row[0], 
                                              'name' => $row[1],
                                              'telephone' => $row[2],
                                              'jobTelephone' => $row[3],
                                              'ext' => $row[4]);
                $cont++;
            }
        }else{
            $responseArray=$result->num_rows;
        }
    }
    return $responseArray;   
}

function getImgSolicitud($idFormSell, $conn){
    if ($idFormSell != '') {
        $getReference = "SELECT name, extension
                         FROM multimedia
                         WHERE id IN (
                         SELECT idMultimedia
                         FROM form_sells_multimedia
                         WHERE idSell=$idFormSell);";
        //echo $getReference;
        $result = $conn->query($getReference);
        $cont=0;
        //var_dump($result);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $responseArray[$cont] = array('name' => $row[0], 
                                              'extension' => $row[1]);
                $cont++;
            }
        }else{
            $responseArray=$result->num_rows;
        }
    }
    return $responseArray;   
}
