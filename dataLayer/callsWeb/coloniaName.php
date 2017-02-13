<?php include_once "../DAO.php";
    include_once "../libs/utils.php";

    session_start();

    $idCity;
    $param;

    $DB = new DAO();
    $conn = $DB->getConnect();

    $id;
    $name;
    $cityId;

    if( isset($_POST['city']) && isset($_POST['param']) ) {
        //Descifrar param y comparar contra contenido de session con nickname, en caso de ser igual set idCity caso contrario regresar error
        $idCity = $_POST['city'];
        //$param = base64_decode($_POST['param']);
        $param = $_POST['param'];

        /*if($param == $_SESSION['nickname']) {

        }*/

        $returnData = array();
        $colonias = array();

        /*$returnData['city'] = $idCity;
        $returnData['param'] = $param;

        echo json_encode($returnData);*/

        $getColonias = $conn->prepare("SELECT `col`.`id` , `col`.`name` , `city`.`id` FROM `Colonia` AS `col` LEFT JOIN `city_Colonia` AS `cityCol` ON `cityCol`.`idColonia` = `col`.`id` LEFT JOIN `city` ON `city`.`id` = `cityCol`.`idCity` WHERE `city`.`id` = ?;");
        $getColonias->bind_param('i', $idCity);

        if($getColonias->execute()) {
            $getColonias->store_result();
            $getColonias->bind_result($id, $name, $cityId);

            while( $getColonias->fetch() ) {
                $colonias['id'] = $id;
                $colonias['name'] = $name;
                $colonias['idCity'] = $cityId;
                $returnData[]=$colonias;
            }
            echo json_encode($returnData);
        } else {
            $returnData["statusCode"] = 404;
            $returnData["message"] = "Colonia Request Fails";
            $returnData["response"] = "No Colonias to show";

            echo json_encode($returnData);
        }
    }
?>