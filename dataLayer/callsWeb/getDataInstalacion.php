<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$reportID=$_GET['id'];
$returnData = []; $reports = [];

/*$getReportStatus = "SELECT E.idReporte, E.idAgenciaInstalacion,USAG.nickname  from tEstatusContrato
					FROM user AS USAG
					INNER JOIN agency AS AG ON USAG.id = AG.idUser
					INNER JOIN tEstatusControl as E on idAgenciaInstalacion=AG.id
					WHERE E.idReporte=$reportID";*/


$getReportStatus = "SELECT USEMP.id as useIdEmp,
USEMP.nickname,
4 as idReportType,
AG.id as idAgencia,
(SELECT us.nickname from user as us WHERE us.id = AG.idUser) as descAgencia,
(SELECT us.nickname from user as us WHERE us.id = rpH.idUserAssigned) as nickname2
FROM user AS USAG
INNER JOIN agency AS AG ON USAG.id = AG.idUser
LEFT JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
LEFT JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
LEFT JOIN profile AS PRF ON EMP.idProfile = PRF.id
LEFT JOIN user AS USEMP ON EMP.idUser = USEMP.id
INNER JOIN tEstatusContrato as repH on repH.idAgenciaInstalacion=AG.id
LEFT JOIN reportHistory AS rpH ON rpH.idReport = repH.idReporte
WHERE EMP.idProfile in (4,8,9) and USEMP.active=1 and repH.idReporte=$reportID and rpH.idReportType=4";



$result = $conn->query($getReportStatus);
while( $row = $result->fetch_array() ) {
	$returnData['IDEmp'] = $row[0];
	$returnData['nicknameEmp'] = $row[1];
	$returnData['tipo']=$row[2];
	$returnData['idAgencia']=$row[3];
	$returnData['descAgencia']=$row[4];
	$returnData['nickname2']=$row[5];
	$reports[] = $returnData;
}
$result->free_result();
echo json_encode($reports);