<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$id=$_GET['idAgencia'];
if ($id != '') {
	$returnData = []; $reports = [];
	$getAgency = "SELECT id,tipo,idUser from agency where idUser=$id";

	$result = $conn->query($getAgency);

	while( $row = $result->fetch_array() ) {
	    $returnData['id'] = $row[0];
	    $returnData['tipo'] = $row[1];
	    $returnData['idUser']=$row[2];
	    $reports[] = $returnData;
	}
	$result->free_result();
	echo json_encode($reports);	
}