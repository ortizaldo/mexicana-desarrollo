<?php include_once "../DAO.php";
//echo json_encode($_POST);
ini_set("memory_limit","1024M");
if (count($_POST['collection']) > 0) {
    $reportsAll = array();

    $DB = new DAO();
    $conn = $DB->getConnect();
    /*$idForm = intval($_POST['form']);
    $type = $_POST['type'];
    $idUsuario = intval($_POST['idUsuario']);*/
    
    //$reports = GetForm($conn, $type, $idForm, $idUsuario);
    //var_dump($_POST["collection"]);
    $arrayFormularios = array();
    foreach ($_POST["collection"] as $key => $value) {
        $idForm = intval($value['id']);
        $type = $value['name'];
        $idUsuario = intval($value['idUserAssigned']);
        //echo 'idForm '.$idForm." type ".$type." idUsuario ".$idUsuario;
        $reports = GetForm($type, $idForm, $idUsuario);
        array_push($arrayFormularios, $reports);
        $options['data'][$key]=array_merge($reports, $_POST['collection'][$key]);
    }
    //$arrayFormularios['datosGrales']=$_POST['collection'];
    echo json_encode($options['data']);
    //echo $type;

}

function GetForm($type, $idForm, $idUsuario)
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
    $DB = new DAO();
    $conn = $DB->getConnect();
    $stmtGetForm = $conn->prepare("call spGetform(?,?,?);");
    //$stmtGetForm = $conn->prepare("call spGetform('Venta',2071,347);");
    //call spGetform('Venta',2071,347);
    mysqli_stmt_bind_param($stmtGetForm, 'sii',
        $type, $idForm, $idUsuario
    );
    
    if ($stmtGetForm->execute()) {
        $stmtGetForm->store_result();
        if ($type == "Venta") {
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
            }
        } elseif ($type == "Instalacion") {
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
            }
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
                $responseArray["References"] = getReferences($idAgreement, $conn);
            }
        }
    }else{
        echo "error ".$conn->error;
    }
    //print_r($stmtGetForm);
    $stmtGetForm->free_result();
    /**CERRAMOS EL RESULT DE CompararUsuarioToken***/
    $stmtGetForm->close();
    //$conn->close();
    return $responseArrayGetForm;
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