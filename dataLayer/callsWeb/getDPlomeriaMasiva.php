<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$agencyNickname=$_GET['agencia'];
$returnData = []; $reports = [];

$getReportStatus = "SELECT 
					    a.id, a.nickname
					FROM
					    user a,
					    employee b,
					    agency_employee c,
					    agency d,
					    profile e
					WHERE
					0 = 0 AND a.id = b.idUser
					AND b.id = c.idemployee
					AND c.idAgency = d.id
					AND b.idProfile = e.id
					AND d.id IN (SELECT 
									a.id
								 FROM
									agency a,
									user b
								 WHERE
								 a.idUser = b.id
								 AND b.nickname LIKE '%".$agencyNickname."%')
					AND e.id IN (3 , 6, 7, 8, 9)
					AND a.nickname NOT IN ('Pendiente de Asignar', 'enruta_test', 'enruta_test2');";

$result = $conn->query($getReportStatus);
while( $row = $result->fetch_array() ) {
	$returnData['IDEmp'] = $row[0];
	$returnData['nicknameEmp'] = $row[1];
	$reports[] = $returnData;
}
$result->free_result();
echo json_encode($reports);