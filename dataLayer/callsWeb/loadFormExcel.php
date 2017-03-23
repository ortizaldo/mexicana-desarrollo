<?php include_once "../DAO.php";
//echo json_encode($_POST);
ini_set("memory_limit","1024M");
/*if (count($_POST['collection']) > 0) {
    $reportsAll = array();

    $DB = new DAO();
    $conn = $DB->getConnect();
    $arrayFormularios = array();
    //var_dump($_POST["collection"]);
    $cont = 0;
    foreach ($_POST["collection"] as $key => $value) {
        //echo "idForm ".$idForm." type ".$type." idUsuario ".$idUsuario."\n";
        $idForm = intval($value['id']);
        $type = $value['name'];
        $idUsuario = intval($value['idUserAssigned']);
        $reports = GetForm($type, $idForm, $idUsuario);
        //array_push($arrayFormularios, $reports, $value);
        switch ($type) {
            case 'Censo':
                $type = "censo";
            break;
            case 'Venta':
                $type = "venta";
            break;
            case 'Plomero':
                $type = "plomero";
            break;
            case 'Instalacion':
                $type = "installation";
            break;
            case 'Segunda Venta':
                $type = "formSegVta";
            break;
        }
        $arrayFormularios[$cont] = array('datosGrales' => $value, 
                                         $type => $reports);
        $cont++;
    }
    echo json_encode($arrayFormularios);
    //echo $type;
}*/

ini_set('memory_limit', '-1');
if (isset($_POST['form']) && isset($_POST['type']) && isset($_POST['idUsuario'])) {
    $reports = array();

    $DB = new DAO();
    $conn = $DB->getConnect();

    $idForm = intval($_POST['form']);
    $type = $_POST['type'];
    $idUsuario = intval($_POST['idUsuario']);
    
    $reports = GetForm($conn, $type, $idForm, $idUsuario);
    $reports['datosGrales']=$_POST['collection'];
    echo json_encode($reports);
    //echo $type;

}
function GetForm($conn,$type, $idForm, $idUsuario)
{
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
    $DB = new DAO();
    $conn = $DB->getConnect();
    $stmtGetForm = $conn->prepare("call spGetformExcel(?,?,?);");
    mysqli_stmt_bind_param($stmtGetForm, 'sii',
        $type, $idForm, $idUsuario
    );
    
    if ($stmtGetForm->execute()) {
        $stmtGetForm->store_result();
        if ($type == "Venta") {
            $stmtGetForm->bind_result($id,$prospect,$uninteresting,$motivosDesinteres,$comments,$owner,$name,$lastName,$lastNameOp,$payment,$financialService,$requestNumber,$meeting,$estatusVenta,$fechaAlta,$agenciaVendedor,$estatusAsignacionInstalacion);

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
                $responseArray["estatusVenta"] = $estatusVenta;
                $responseArray["fechaAlta"] = $fechaAlta;
                $responseArray["agenciaVendedor"] = $agenciaVendedor;
                $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
            }
            $responseArrayGetForm['venta'] = $responseArray;
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
            $responseArrayGetForm['censo'] = $responseArray;
        } elseif ($type == "Plomero") {
            $stmtGetForm->bind_result($id, $consecutive, $name, $lastName, $request, $documentNumber, $tapon, $ri, $comments, $newPipe, $diagram, $pipesCount, $ph, $created_at, $estatusAsignacionInstalacion);
            while ($stmtGetForm->fetch()) {
                $responseArray["id"] = $id;
                $responseArray["consecutive"] = $consecutive;
                $responseArray["name"] = $name;
                $responseArray["lastName"] = $lastName;
                $responseArray["request"] = $request;
                $responseArray["documentNumber"] = $documentNumber;
                $responseArray["tapon"] = $tapon;
                $responseArray["ri"] = $ri;
                $responseArray["comments"] = $comments;
                $responseArray["newPipe"] = $newPipe;
                $responseArray["diagram"] = $diagram;
                $responseArray["pipesCount"] = $pipesCount;
                $responseArray["ph"] = $ph;
                $responseArray["created_at"] = $created_at;
                $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                $responseArray["formPlumbDet"] = getMaterialPlumber($idForm);
            }
            $responseArrayGetForm['plomero'] = $responseArray;
        } elseif ($type == "Instalacion") {
            $stmtGetForm->bind_result($id,$consecutive,$name,$lastName,$request,$phLabel,$agencyPh,$agencyNumber,$installation,$abnormalities,$comments,$brand,$type,$serialNumber,$measurement,$latitude,$longitude,$created_at,$estatusAsignacionInstalacion ,$agreementNumber);
            while ($stmtGetForm->fetch()) {
                $responseArray["id"] = $id;
                $responseArray["consecutive"] = $consecutive;
                $responseArray["name"] = $name;
                $responseArray["lastName"] = $lastName;
                $responseArray["request"] = $request;
                $responseArray["phLabel"] = $phLabel;
                $responseArray["agencyPh"] = $agencyPh;
                $responseArray["agencyNumber"] = $agencyNumber;
                $responseArray["installation"] = $installation;
                $responseArray["abnormalities"] = $abnormalities;
                $responseArray["comments"] = $comments;
                $responseArray["brand"] = $brand;
                $responseArray["type"] = $type;
                $responseArray["serialNumber"] = $serialNumber;
                $responseArray["measurement"] = $measurement;
                $responseArray["latitude"] = $latitude;
                $responseArray["longitude"] = $longitude;
                $responseArray["created_at"] = $created_at;
                $responseArray["estatusAsignacionInstalacion"] = $estatusAsignacionInstalacion;
                $responseArray["agreementNumber"] = $agreementNumber;
                $responseArray["formInstDet"] = getMaterialInstallation($idForm);
            }
            $responseArrayGetForm['installation'] = $responseArray;
        } elseif ($type == "Segunda Venta") {
            $stmtGetForm->bind_result($id,$idAgency,$payment,$idReport,$requestDate,$clientlastName,$clientlastName2,$clientName,$clientRFC,$clientCURP,$clientEmail,$clientRelationship,$clientgender,$clientIdNumber,$identificationType,$clientBirthDate,$clientBirthCountry,$idState,$idCity,$idColonia,$street,$inHome,$homeTelephone,$celullarTelephone,$agreementType,$price,$agreementExpires,$agreementMonthlyPayment,$agreementRi,$agreementRiDate,$clientJobEnterprise,$clientJobLocation,$clientJobRange,$clientJobActivity,$clientJobTelephone,$latitude,$longitude,$idArt,$idClienteGenerado);
            while ($stmtGetForm->fetch()) {
                $responseArray["id"] = $id;
                $responseArray["idAgency"] = $idAgency;
                $responseArray["payment"] = $payment;
                $responseArray["idReport"] = $idReport;
                $responseArray["requestDate"] = $requestDate;
                $responseArray["clientlastName"] = $clientlastName;
                $responseArray["clientlastName2"] = $clientlastName2;
                $responseArray["clientName"] = $clientName;
                $responseArray["clientRFC"] = $clientRFC;
                $responseArray["clientCURP"] = $clientCURP;
                $responseArray["clientEmail"] = $clientEmail;
                $responseArray["clientRelationship"] = $clientRelationship;
                $responseArray["clientgender"] = $clientgender;
                $responseArray["clientIdNumber"] = $clientIdNumber;
                $responseArray["identificationType"] = $identificationType;
                $responseArray["clientBirthDate"] = $clientBirthDate;
                $responseArray["clientBirthCountry"] = $clientBirthCountry;
                $responseArray["idState"] = $idState;
                $responseArray["idCity"] = $idCity;
                $responseArray["idColonia"] = $idColonia;
                $responseArray["street"] = $street;
                $responseArray["inHome"] = $inHome;
                $responseArray["homeTelephone"] = $homeTelephone;
                $responseArray["celullarTelephone"] = $celullarTelephone;
                $responseArray["agreementType"] = $agreementType;
                $responseArray["price"] = $price;
                $responseArray["agreementExpires"] = $agreementExpires;
                $responseArray["agreementMonthlyPayment"] = $agreementMonthlyPayment;
                $responseArray["agreementRi"] = $agreementRi;
                $responseArray["agreementRiDate"] = $agreementRiDate;
                $responseArray["clientJobEnterprise"] = $clientJobEnterprise;
                $responseArray["clientJobLocation"] = $clientJobLocation;
                $responseArray["clientJobRange"] = $clientJobRange;
                $responseArray["clientJobActivity"] = $clientJobActivity;
                $responseArray["clientJobTelephone"] = $clientJobTelephone;
                $responseArray["latitude"] = $latitude;
                $responseArray["longitude"] = $longitude;
                $responseArray["idArt"] = $idArt;
                $responseArray["idClienteGenerado"] = $idClienteGenerado;
                $responseArray["References"] = getReferences($idForm);
            }
            $responseArrayGetForm["formSegVta"]=$responseArray;
        }
    }else{
        echo "error ".$conn->error;
    }
    $stmtGetForm->free_result();
    $stmtGetForm->close();
    return $responseArrayGetForm;
}

function getMaterialPlumber($idReport){
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $querySmtFrmPlumbDet = "SELECT 
                                FPD.path, FPD.distance, FPD.pipe, FPD.fall, FPD.id
                                FROM report AS RP
                                INNER JOIN reportHistory AS REF ON RP.id = REF.idReport and REF.idReportType=3
                                INNER JOIN form_plumber AS FP ON FP.id = REF.idFormulario
                                INNER JOIN form_plumber_details AS FPD ON FP.id  = FPD.idFormPlumber
                                WHERE REF.idReport =?";
        $arrDet=array();
        if ($stmtFrmPlumbDet = $conn->prepare($querySmtFrmPlumbDet)) {
            $stmtFrmPlumbDet->bind_param("i", $idReport);
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
            }
        }
    }
    return $arrDet;   
}

function getMaterialInstallation($idReport){
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $querySmtFrmPlumbDet = "SELECT
                                FPD.qty, FPD.material, FPD.id
                                FROM report AS RP
                                INNER JOIN reportHistory as rth on rth.idReport=RP.id and rth.idReportType=4
                                INNER JOIN form_installation AS FP ON FP.consecutive = rth.idFormSell
                                LEFT JOIN form_installation_details AS FPD ON FP.id  = FPD.idFormInstallation
                                WHERE rth.idReport =?";
        $arrDet=array();
        if ($stmtFrmPlumbDet = $conn->prepare($querySmtFrmPlumbDet)) {
            $stmtFrmPlumbDet->bind_param("i", $idReport);
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
            }
        }
    }
    return $arrDet;   
}

function getReferences($idAgreement){
    if ($idAgreement != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getReference = "SELECT a.id,a.name,a.telephone,a.jobTelephone,a.ext
                         from agreement_reference a, report b, agreement c
                         where 0=0
                         and c.idReport=b.id
                         and c.id = a.idAgreement
                         and b.id=$idAgreement";
        $result = $conn->query($getReference);
        $cont=0;
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

function getAgenciaVendedora($idReporte, $conn)
{
    if ($idReporte != '') {
        $getAgVend = "SELECT 
                            b.nickname AS vendedor,
                            (SELECT 
                                    f.nickname
                                FROM
                                    user f,
                                    agency g
                                WHERE
                                    f.id = g.idUser AND g.id = d.id) AS agenciaVendedora
                        FROM
                            reportHistory a,
                            user b,
                            agency_employee c,
                            agency d,
                            employee e
                        WHERE
                        0 = 0 AND a.idUserAssigned = b.id
                        AND e.idUser = b.id
                        AND e.id = c.idemployee
                        AND d.id = c.idAgency
                        AND a.idReport = $idReporte
                        AND a.idReportType = 2;";
        //echo $getReference;
        $result = $conn->query($getAgVend);
        $cont=0;
        //var_dump($result);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $responseArray[$cont] = array('vendedor' => $row[0], 
                                              'agenciaVendedora' => $row[1]);
                $cont++;
            }
        }else{
            $responseArray=$result->num_rows;
        }
    }
    return $responseArray; 
}