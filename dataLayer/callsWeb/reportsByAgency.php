<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if(isset($_POST['agency']) && isset($_POST['date'])) {
   $agency = $_POST['agency'];
   $date = $_POST['date'];
}

$trackData = [];

$trackSQL = "SELECT EMPUS.id, RP.dot_latitude, RP.dot_longitude, EMPUS.nickname, proemp.name
			FROM report AS RP 
			LEFT JOIN employee AS RPEMP ON RPEMP.id = RP.idEmployee
			LEFT JOIN profile AS proemp ON proemp.id = RPEMP.idProfile
			LEFT JOIN agency_employee AS AGEMP ON AGEMP.idemployee = RPEMP.id LEFT JOIN agency AS AG ON AG.id = AGEMP.idAgency
			LEFT JOIN user AS AGUS ON AGUS.id = AG.idUser 
			INNER JOIN user AS EMPUS ON RPEMP.idUser = EMPUS.id
			WHERE 0=0 AND DATE(RP.created_at) IN ( SELECT MAX(DATE(RP.created_at))  FROM report AS RP WHERE RP.created_at LIKE '".$date."%' GROUP BY RP.id, RP.dot_latitude, RP.dot_longitude) 
			AND AG.id = ".$agency." 
			GROUP BY EMPUS.id
			ORDER BY RP.created_at ASC, RP.dot_latitude, RP.dot_longitude;";
$result = $conn->query($trackSQL);
$res="";
$cont=0;
if ($result->num_rows > 0) {
	while($row = $result->fetch_array()) {
		$trackData[$cont]["id"] = $row[0];
		$trackData[$cont]["latitude"] = $row[1];
		$trackData[$cont]["longitude"] = $row[2];
		$trackData[$cont]["nickname"] = $row[3];
		$trackData[$cont]["perfil"] = $row[4];
		$cont++;
	}
	//$returnData[] = $trackData;
}
echo json_encode($trackData);