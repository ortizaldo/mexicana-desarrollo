<?php

include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['form']) && isset($_POST['type'])) {
    $formNumber = $_POST['form'];
    $formType = $_POST['type'];

    $returnData = [];
    $reports = [];

    if ($formType == "Censo") {
        $formTypeQuery = $conn->prepare("SELECT FC.id, FC.lote, FC.houseStatus, FC.nivel, Mul.content, Mul.name, FC.giro, FC.acometida, FC.observacion, FC.tapon, FC.medidor, FC.marca, FC.tipo, FC.NoSerie, FC.niple FROM report_employee_form AS REF LEFT JOIN form_census AS FC ON FC.id = REF.idForm LEFT JOIN form_census_multimedia AS FCM ON FCM.idFormCensus = FC.id LEFT JOIN multimedia AS Mul ON Mul.id = FCM.idMultimedia WHERE REF.idReport = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $lote, $houseStatus, $nivel, $content, $name, $giro, $acometida, $observacion, $tapon, $medidor, $marca, $tipo, $NoSerie, $niple);
            while ($formTypeQuery->fetch()) {
                $returnData['id'] = $id;
                $returnData['lote'] = $lote;
                $returnData['houseStatus'] = $houseStatus;
                $returnData['nivel'] = $nivel;
                $returnData['content'] = $content;
                $returnData['name'] = $name;
                $returnData['giro'] = $giro;
                $returnData['acometida'] = $acometida;
                $returnData['observacion'] = $observacion;
                $returnData['tapon'] = $tapon;
                $returnData['medidor'] = $medidor;
                $returnData['marca'] = $marca;
                $returnData['tipo'] = $tipo;
                $returnData['NoSerie'] = $NoSerie;
                $returnData['niple'] = $niple;
                $reports[] = $returnData;
            }
        }
    } else if ($formType == "Plomero") {
        $formTypeQuery = $conn->prepare("SELECT FP.id, FP.name, FP.lastName, FP.request, FP.documentNumber, FP.tapon, FP.ri, FP.observations, FP.newPipe, MUL.content, MUL.name, FP.ph, FP.pipesCount, FPD.path, FPD.distance, FPD.pipe, FPD.fall
        	FROM report_employee_form AS REF
        	LEFT JOIN form_plumber AS FP ON FP.id = REF.idForm
            LEFT JOIN form_plumber_details AS FPD ON FPD.idFormPlumber = FP.id 
            LEFT JOIN form_plumber_multimedia AS FPM ON FPM.idFormPlumber = FPD.idFormPlumber
            LEFT JOIN multimedia AS MUL ON MUL.id = FPM.idMultimedia 
            WHERE REF.idReport = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $name, $lastName, $request, $documentNumber, $tapon, $ri, $observations, $newPipe, $content, $name, $ph, $pipesCount, $path, $distance, $pipe, $fall);
            while ($formTypeQuery->fetch()) {
                $returnData['ID'] = $id;
                $returnData['name'] = $name;
                $returnData['lastName'] = $lastName;
                $returnData['request'] = $request;
                $returnData['documentNumber'] = $documentNumber;
                $returnData['tapon'] = $tapon;
                $returnData['ri'] = $ri;
                $returnData['observations'] = $observations;
                $returnData['newPipe'] = $newPipe;
                $returnData['content'] = $content;
                $returnData['name'] = $name;
                $returnData['ph'] = $ph;
                $returnData['pipesCount'] = $pipesCount;
                $returnData['path'] = $path;
                $returnData['distance'] = $distance;
                $returnData['pipe'] = $pipe;
                $returnData['fall'] = $fall;
                $reports[] = $returnData;
            }
        }
    } else if ($formType == "Venta") {
        $formTypeQuery = $conn->prepare("SELECT FS.id, FS.prospect, FS.uninteresting, FS.comments, FS.owner, FS.consecutive, FS.name, FS.lastName, FS.lastNameOp, FS.payment, FS.financialService, FS.requestNumber, FS.meeting, MUL.content, MUL.name FROM report_employee_form AS REF LEFT JOIN form_sells AS FS ON FS.id = REF.idForm LEFT JOIN form_sells_multimedia AS FSM ON FSM.idSell = FS.id LEFT JOIN multimedia AS Mul ON Mul.id = FSM.idMultimedia WHERE REF.idReport = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $prospect, $uninteresting, $comments, $owner, $consecutive, $name, $lastName, $lastNameOp, $payment, $financialService, $requestNumber, $meeting, $content, $name);
            while ($formTypeQuery->fetch()) {
                $returnData['ID'] = $id;
                $returnData['prospect'] = $prospect;
                $returnData['uninteresting'] = $uninteresting;
                $returnData['comments'] = $comments;
                $returnData['owner'] = $owner;
                $returnData['consecutive'] = $consecutive;
                $returnData['name'] = $name;
                $returnData['lastName'] = $lastName;
                $returnData['lastNameOp'] = $lastNameOp;
                $returnData['payment'] = $payment;
                $returnData['financialService'] = $financialService;
                $returnData['requestNumber'] = $requestNumber;
                $returnData['meeting'] = $meeting;
                $returnData['content'] = $content;
                $returnData['name'] = $name;
                $reports[] = $returnData;
            }
        }
    } else if ($formType == "Instalacion") {
        $formTypeQuery = $conn->prepare("SELECT FI.id, FI.name, FI.lastName, FI.request, FI.phLabel, FI.agencyPh, FI.agencyNumber, FI.installation, FI.abnormalities, FI.comments, FI.brand, FI.type, FI.serialNuber, FI.measurement, FI.latitude, FI.longitude, FI.created_at, Mul.content
        	FROM report_employee_form AS REF 
        	LEFT JOIN form_installation AS FI ON FI.id = REF.idForm 
        	LEFT JOIN form_installation_details AS FID ON FID.idFormInstallation = FI.id
        	LEFT JOIN form_installation_material AS FIM ON FIM.id = FID.idInstallationMaterial 
        	LEFT JOIN form_installation_multimedia AS FIMUL ON FIMUL.idFormInstallation = FI.id
			LEFT JOIN multimedia AS Mul ON Mul.id = FIMUL.idMultimedia WHERE REF.idReport = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $name, $lastName, $request, $phLabel, $agencyPh, $agencyNumber, $installation, $abnormalities, $comments, $brand, $type, $serialNuber, $measurement, $latitude, $longitude, $created_at, $content);
            while ($formTypeQuery->fetch()) {
                $returnData['ID'] = $id;
                $returnData['name'] = $name;
                $returnData['lastName'] = $lastName;
                $returnData['request'] = $request;
                $returnData['phLabel'] = $phLabel;
                $returnData['agencyPh'] = $agencyPh;
                $returnData['agencyNumber'] = $agencyNumber;
                $returnData['installation'] = $installation;
                $returnData['abnormalities'] = $abnormalities;
                $returnData['comments'] = $comments;
                $returnData['brand'] = $brand;
                $returnData['type'] = $type;
                $returnData['serialNuber'] = $serialNuber;
                $returnData['measurement'] = $measurement;
                $returnData['latitude'] = $latitude;
                $returnData['longitude'] = $longitude;
                $returnData['created_at'] = $created_at;
                $returnData['content'] = $content;
                $reports[] = $returnData;
            }
        }
    } else if ($formType == "SegundaVenta") {
    	//Retrieve agreements
        $formTypeQuery = $conn->prepare("SELECT AG.`clientlastName`, AG.`clientlastName`, AG.`clientlastName2`, AG.`clientBirthDate`, AG.`clientBirthCountry`, AG.`clientgender`, AG.`clientRFC`, AG.`clientCURP`, AG.`clientEmail`, AG.`clientRelationship`, AG.`identificationType`, AG.`idState`, AG.`idCity`,
AG.`idColonia`, AG.`street`, AG.`inHome`, AG.`homeTelephone`,  AG.`celullarTelephone`, AG.`clientJobEnterprise`,
AG.`clientJobRange`, AG.`clientJobActivity`, AG.`clientJobTelephone`, AG.`clientJobLocation`, AG.`id`, AG.`id`,
AG.`idAgency` AS changeToAgencyName, AG.`payment`, AG.`agreementType`, AG.`requestDate`, AG.`price`, AG.`agreementExpires`, 
AG.`agreementMonthlyPayment`, AG.`agreementRi`, AG.`agreementRiDate`, AGREF.name, AGREF.telephone, AGREF.jobTelephone, AGREF.ext
FROM agreement_employee_form AS AGEMP 
LEFT JOIN agreement AS AG ON AG.id = AGEMP.idAgreement
LEFT JOIN agreement_reference AS AGREF ON AGREF.idAgreement = AG.id
WHERE AGEMP.idAgreement = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $lote, $houseStatus, $nivel, $content);
            if ($formTypeQuery->fetch()) {
                
            }
        }
    }
    echo json_encode($reports);
}

