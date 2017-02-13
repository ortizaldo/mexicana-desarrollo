<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$reportID=$_GET['reportID'];
if ($reportID != '') {
	$returnData = []; $reports = [];
	$getReportStatus = "SELECT DISTINCT FP.id, RP.employeesAssigned, RP.idUserCreator, RP.id 
						FROM report_employee_form AS REF 
						INNER JOIN form_plumber AS FP ON REF.idForm = FP.id 
						INNER JOIN report AS RP ON REF.idReport = RP.id 
						WHERE RP.id =$reportID";
	
	$getReportStatus;
	$result = $conn->query($getReportStatus);

	while( $row = $result->fetch_array() ) {
	    $returnData['id'] = $row[0];
	    $returnData['employeesAssigned'] = $row[1];
	    $returnData['idUserCreator']=$row[2];
	    $returnData['id']=$row[3];
	    $reports[] = $returnData;
	}
	$result->free_result();
	echo json_encode($reports);	
}