<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$reasonsData = [];

$reasonsQuery = "SELECT reason FROM rejected_reason;";
$result = $conn->query($reasonsQuery);

while ($row = $result->fetch_assoc()) {
    $returnData['reason'] = $row['reason'];
}

$result->free_result();

echo json_encode($returnData);