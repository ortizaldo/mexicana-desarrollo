<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = []; $reports = [];

$getReportStatus = "SELECT id, name, description FROM status WHERE name < 10 liMit 10;";

$result = $conn->query($getReportStatus);

while( $row = $result->fetch_array() ) {
    $returnData['id'] = $row[0];
    $returnData['name'] = $row[1];
    $returnData['description'] = $row[2];
    $reports[] = $returnData;
}

$result->free_result();
echo json_encode($reports);