<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$reports = [];

$getReportsConfig = "SELECT `id`,`idTimes`,`subIdTimes`, `name` , `from`, `to` FROM times_check_labels";
$result = $conn->query($getReportsConfig);

while( $row = $result->fetch_array() ) {
    $reports['id'] = $row[0];
    $reports['idTimes'] = $row[1];
    $reports['subIdTimes'] = $row[2];
    $reports['labelTitle'] = $row[3];
    $reports['labelFrom'] = $row[4];
    $reports['labelTo'] = $row[5];
    $returnData[] = $reports;
}
$result->free_result();
echo json_encode($returnData);