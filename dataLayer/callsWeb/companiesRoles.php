<?php

	include_once "../DAO.php";
	
	$DB = new DAO();
	$conn = $DB->getConnect();

	//if (isset($_POST['seed']) && isset($_POST['company'])) {
	if (isset($_POST['company'])) {
	    $idCompany = $_POST['company'];
	
	    $returnData = [];
	    $reports = [];
	
        $formTypeQuery = $conn->prepare("SELECT * FROM report_employee_form AS REF LEFT JOIN form_census AS FC ON FC.id = REF.idForm LEFT JOIN form_census_multimedia AS FCM ON FCM.idFormCensus = FC.id LEFT JOIN multimedia AS Mul ON Mul.id = FCM.idMultimedia WHERE REF.idReport = ?;");
        $formTypeQuery->bind_param("i", $formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $lote, $houseStatus, $nivel, $fileContent, $fileName, $giro, $acometida, $observacion, $tapon, $medidor, $marca, $tipo, $NoSerie, $niple);
            while ($formTypeQuery->fetch()) {
                $returnData['ID'] = $id;
                $returnData['lote'] = $lote;
                $returnData['houseStatus'] = $houseStatus;
                $returnData['nivel'] = $nivel;
                $returnData['fileContent'] = $fileContent;
                $returnData['fileName'] = $fileName;
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
	}