<?php include_once "../DAO.php";

    $DB = new DAO();
    $conn = $DB->getConnect();

    $returnData = [];
    $reasons = [];

    $getReasons = "SELECT id, reason FROM rejected_reason;";
    $result = $conn->query($getReasons);

    while( $row = $result->fetch_array() ) {
    	$reasons['id'] = $row[0];
    	$reasons['reason'] = $row[1];
        $returnData[] = $reasons;
    }

    $result->free_result();
    echo json_encode($returnData);
?>