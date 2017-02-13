<?php include_once "../DAO.php";
    //include_once "../libs/utils.php";

    $DB = new DAO();
    $conn = $DB->getConnect();

    /*if( isset($_POST['']) ) {
      
    }*/

    $returnData = array();
    $reports = array();

    $getReports = "SELECT * FROM `report` WHERE active = 1;";
    $result = $conn->query($getReports);

    while( $row = $result->fetch_array() ) {
        $cities['1'] = $row[0];
        $cities['2'] = $row[1];
        $cities['3'] = $row[2];
        $cities['4'] = $row[3];
        $cities['5'] = $row[4];
        $cities['6'] = $row[5];
        $cities['7'] = $row[6];
        $cities['8'] = $row[7];
        $cities['9'] = $row[8];
        $returnData[]=$cities;
    }
    echo json_encode($returnData);