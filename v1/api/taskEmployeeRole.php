<?php

include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

if (isset($_POST["token"])) {
    $DB = new DAO();
    $conn = $DB->getConnect();

    $token = $_POST["token"];
    $token = base64_decode($token);

    list($id, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);

    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);

    if ($searchToken->execute()) {

        $searchToken->store_result();
        $searchToken->bind_result($userToken);

        if ($searchToken->fetch()) {
            if ($_POST["token"] == $userToken) {
            	$returnData = [];
	            if (isset($_POST["reportByRole"])) {
		            $role = $_POST["reportByRole"];
		            $role = intval($role);
		            
		            if ($role === 1) {
		            	var_dump($role);
			            $getTasks = $conn->prepare("SELECT TS.id, TS.folio, TS.street, TS.number, TS.colonia, TS.state, TS.annotations, TS.zipCode, TS.dateVisit, TS.clientName, TS.agreementNumber, TS.agendaDate
			             	FROM task AS TS 
			             	LEFT JOIN user AS US ON US.id = TS.idUserAssigned
			             	LEFT JOIN employee AS EM ON EM.idUser = US.id
			            	LEFT JOIN report_employee_form AS REF ON REF.idEmployee = EM.id
			            	LEFT JOIN form_census AS FC ON FC.id = REF.idForm WHERE TS.idUserAssigned = ?;");
						$getTasks->bind_param('i', $id);
    
					    if($getTasks->execute()){
						    $getTasks->store_result();
							$getTasks->bind_result($id, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName, $agreementNumber, $agendaDate);
					    
					    	while($getTasks->fetch()) {
						    	$trackData["id"] = $id;
						    	$trackData["folio"] = $folio;
						    	$trackData["street"] = $street;
						    	$trackData["number"] = $number;
						    	$trackData["colonia"] = $colonia;
						    	$trackData["state"] = $state;
						    	$trackData["annotations"] = $annotations;
						    	$trackData["zipCode"] = $zipCode;
						    	$trackData["dateVisit"] = $dateVisit;
						    	$trackData["clientName"] = $clientName;
						    	$trackData["agreementNumber"] = $agreementNumber;
						    	$trackData["agendaDate"] = $agendaDate;
						    	$returnData[] = $trackData;
					    	}
					    } else {
						    $returnData["ERROR"] = "500: ".$getTasks->error();
					    }
		            } else if ($role === 2) {
			            $getTasks = $conn->prepare("SELECT DISTINCT TS.id, TS.folio, TS.street, TS.number, TS.colonia, TS.state,
			            		TS.annotations, TS.zipCode, TS.dateVisit, TS.clientName, TS.agreementNumber, TS.agendaDate
			             	FROM task AS TS 
			             	LEFT JOIN user AS US ON US.id = TS.idUserAssigned
			             	LEFT JOIN employee AS EM ON EM.idUser = US.id
			            	LEFT JOIN report_employee_form AS REF ON REF.idEmployee = EM.id
			            	LEFT JOIN form_sells AS FS ON FS.id = REF.idForm WHERE TS.idUserAssigned = ?;");
						$getTasks->bind_param('i', $id);
					    if($getTasks->execute()){
						    $getTasks->store_result();
							$getTasks->bind_result($id, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName, $agreementNumber, $agendaDate);
					    
					    	while($getTasks->fetch()) {
						    	$trackData["id"] = $id;
						    	$trackData["folio"] = $folio;
						    	$trackData["street"] = $street;
						    	$trackData["number"] = $number;
						    	$trackData["colonia"] = $colonia;
						    	$trackData["state"] = $state;
						    	$trackData["annotations"] = $annotations;
						    	$trackData["zipCode"] = $zipCode;
						    	$trackData["dateVisit"] = $dateVisit;
						    	$trackData["clientName"] = $clientName;
						    	$trackData["agreementNumber"] = $agreementNumber;
						    	$trackData["agendaDate"] = $agendaDate;
						    	$returnData[] = $trackData;
					    	}
					    } else {
						    $returnData["ERROR"] = "500: ".$getTasks->error();
					    }
		            } else if ($role === 3) {
		            	$getTasks = $conn->prepare("SELECT DISTINCT TS.id, TS.folio, TS.street, TS.number, TS.colonia, TS.state,
			            		TS.annotations, TS.zipCode, TS.dateVisit, TS.clientName, TS.agreementNumber, TS.agendaDate
			             	FROM task AS TS 
			             	LEFT JOIN user AS US ON US.id = TS.idUserAssigned
			             	LEFT JOIN employee AS EM ON EM.idUser = US.id
			            	LEFT JOIN report_employee_form AS REF ON REF.idEmployee = EM.id
			            	LEFT JOIN  form_plumber AS FP ON FP.id = WHERE TS.idUserAssigned = ?;");
						$getTasks->bind_param('i', $id);
					    if($getTasks->execute()){
						    $getTasks->store_result();
							$getTasks->bind_result($id, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName, $agreementNumber, $agendaDate);
					    
					    	while($getTasks->fetch()) {
						    	$trackData["id"] = $id;
						    	$trackData["folio"] = $folio;
						    	$trackData["street"] = $street;
						    	$trackData["number"] = $number;
						    	$trackData["colonia"] = $colonia;
						    	$trackData["state"] = $state;
						    	$trackData["annotations"] = $annotations;
						    	$trackData["zipCode"] = $zipCode;
						    	$trackData["dateVisit"] = $dateVisit;
						    	$trackData["clientName"] = $clientName;
						    	$trackData["agreementNumber"] = $agreementNumber;
						    	$trackData["agendaDate"] = $agendaDate;
						    	$returnData[] = $trackData;
					    	}
					    } else {
						    $returnData["ERROR"] = "500: ".$getTasks->error();
					    }

		            } else if ($role === 4) {
		            	$getTasks = $conn->prepare("SELECT DISTINCT TS.id, TS.folio, TS.street, TS.number, TS.colonia, TS.state,
			            		TS.annotations, TS.zipCode, TS.dateVisit, TS.clientName, TS.agreementNumber, TS.agendaDate
			             	FROM task AS TS 
			             	LEFT JOIN user AS US ON US.id = TS.idUserAssigned
			             	LEFT JOIN employee AS EM ON EM.idUser = US.id
			            	LEFT JOIN report_employee_form AS REF ON REF.idEmployee = EM.id
			            	LEFT JOIN form_installation AS FI ON FI.id = REF.idForm WHERE TS.idUserAssigned = ?;");
						$getTasks->bind_param('i', $id);
					    if($getTasks->execute()){
						    $getTasks->store_result();
							$getTasks->bind_result($id, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName, $agreementNumber, $agendaDate);
					    
					    	while($getTasks->fetch()) {
						    	$trackData["id"] = $id;
						    	$trackData["folio"] = $folio;
						    	$trackData["street"] = $street;
						    	$trackData["number"] = $number;
						    	$trackData["colonia"] = $colonia;
						    	$trackData["state"] = $state;
						    	$trackData["annotations"] = $annotations;
						    	$trackData["zipCode"] = $zipCode;
						    	$trackData["dateVisit"] = $dateVisit;
						    	$trackData["clientName"] = $clientName;
						    	$trackData["agreementNumber"] = $agreementNumber;
						    	$trackData["agendaDate"] = $agendaDate;
						    	$returnData[] = $trackData;
					    	}
					    } else {
						    $returnData["ERROR"] = "500: ".$getTasks->error();
					    }
		            } else if ($role === 5) { 
			            $getTasks = $conn->prepare("SELECT DISTINCT TS.id, TS.folio, TS.street, TS.number, TS.colonia, TS.state,
			            		TS.annotations, TS.zipCode, TS.dateVisit, TS.clientName, TS.agreementNumber, TS.agendaDate
			             	FROM task AS TS 
			             	LEFT JOIN user AS US ON US.id = TS.idUserAssigned
			             	LEFT JOIN employee AS EM ON EM.idUser = US.id
			             	LEFT JOIN agreement_employee_form AS AEF ON AEF.idEmployee = EM.id
			             	LEFT JOIN agreement AS AG ON AG.id = AEF.idAgreement WHERE TS.idUserAssigned = ?;");
						$getTasks->bind_param('i', $id);
					    if($getTasks->execute()){
						    $getTasks->store_result();
							$getTasks->bind_result($id, $folio, $street, $number, $colonia, $state, $annotations, $zipCode, $dateVisit, $clientName, $agreementNumber, $agendaDate);
					    
					    	while($getTasks->fetch()) {
						    	$trackData["id"] = $id;
						    	$trackData["folio"] = $folio;
						    	$trackData["street"] = $street;
						    	$trackData["number"] = $number;
						    	$trackData["colonia"] = $colonia;
						    	$trackData["state"] = $state;
						    	$trackData["annotations"] = $annotations;
						    	$trackData["zipCode"] = $zipCode;
						    	$trackData["dateVisit"] = $dateVisit;
						    	$trackData["clientName"] = $clientName;
						    	$trackData["agreementNumber"] = $agreementNumber;
						    	$trackData["agendaDate"] = $agendaDate;
						    	$returnData[] = $trackData;
					    	}
					    } else {
						    $returnData["ERROR"] = "500: ".$getTasks->error();
					    }
		            }
	            }//End if reportByRole
	            echo json_encode($returnData);
            }//End if token match
        }//Token found
    }//Token search
}//Token set