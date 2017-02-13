<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$reportID=intval($_GET['id']);
if ($reportID != '') {
	$returnData = []; $reports = [];
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
						-- INNER JOIN report_employee_form as RPEMP on repH.idReport=RPEMP.idReport
						INNER JOIN form_sells AS FRMSELL ON repH.idFormSell = FRMSELL.id
						WHERE 0=0
						AND repH.idReportType=5
						AND repH.idReport=$reportID;";
	$result = $conn->query($getReportStatus);
	if($result){
		while( $row = $result->fetch_array() ) {
		    $returnData['IDEmp'] = $row[0];
		    $returnData['nicknameEmp'] = $row[1];
		    $returnData['idAgencia']=$row[3];
		    $returnData['descAgencia']=$row[4];
		    $reports[] = $returnData;
		}
		$result->free_result();
		echo json_encode($reports);
	}else{
		$returnData['error']=$result->error;
		$reports[] = $returnData;
		echo json_encode($returnData);
	}
}