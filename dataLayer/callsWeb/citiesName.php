<?php include_once "../DAO.php";

    $DB = new DAO();
    $conn = $DB->getConnect();

    $returnData = array();
    $cities = array();

    $getCities = "SELECT id, name, idState FROM city WHERE active = 1;";
    $result = $conn->query($getCities);

    while( $row = $result->fetch_array() ) {
        $cities['id'] = $row[0];
        $cities['agency'] = $row[1];
        $cities['idState'] = $row[2];
        $returnData[]=$cities;
    }
    $result->free_result();
    echo json_encode($returnData);