<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
require(dirname(dirname(dirname(__FILE__)))."/dataLayer/libs/curl-easy-fast/curl.class.php");

if ( isset($_POST["token"]) )
{
    $DB = new DAO();
    $conn = $DB->getConnect();

    $token = $_POST["token"];
    $token = base64_decode($token);

    list($id, $username, $password, $value, $idDevice) = explode("&", $token);

    $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
    $searchToken->bind_param('i', $id);

    if( $searchToken->execute() ) {
        $searchToken->store_result();
        $searchToken->bind_result($userToken);

        if ($searchToken->fetch()) {

            if ($_POST["token"] == $userToken) {
                if (isset($_POST["census"])) {
                    $data = $_POST["census"];
                    $data = base64_decode($data);

                    //list($city, $colonia, $street, $roads, $number, $class, $newLocation, $newLocationRoads, $private, $market, $latitude, $longitude) = explode("&", $data);
                    list($terrain, $homeStatus, $NSE, $use, $acometida, $photo, $comments, $color, $measurer, $measurerPhoto, $measurerBrand, $measurerType, $measurerSerialNumber, $cut, $latitude, $longitude) = explode("&", $data);

                    /*$options = array(
                        "url" => "http://preview.u4ehqxlaogodpldi3aquh6dkp6cg14i0yb4hbse3y1oflxr.box.codeanywhere.com/mexicana/newProjectStructure/v1/api/createNumber.php",
                        "type" => "GET",
                        "return_transfer" => "1"
                    );

                    $obj = new wArLeY_cURL($options);
                    $resp = $obj->Execute();
                    echo $obj->getError();

                    $elems = array();
                    $elems = json_decode($resp);

                    $agregment;

                    $values = (array)$elems;
                    $agregment = $values['response'];


                    if ($agregment == null) {
                        $agregment = "7435852";
                    }*/

                    /*+-------------+------------------+------+-----+---------------------+----------------+
                    | Field       | Type             | Null | Key | Default             | Extra          |
                                            +-------------+------------------+------+-----+---------------------+----------------+
                    | id          | int(10) unsigned | NO   | PRI | NULL                | auto_increment |
                    | lote        | varchar(255)     | YES  |     | NULL                |                |
                    | nivel       | varchar(255)     | YES  |     | NULL                |                |
                    | giro        | varchar(5)       | YES  |     | NULL                |                |
                    | acometida   | tinyint(4)       | YES  |     | NULL                |                |
                    | observacion | longtext         | YES  |     | NULL                |                |
                    | tapon       | tinyint(4)       | YES  |     | NULL                |                |
                    | medidor     | tinyint(4)       | YES  |     | NULL                |                |
                    | marca       | varchar(255)     | YES  |     | NULL                |                |
                    | tipo        | varchar(255)     | YES  |     | NULL                |                |
                    | NoSerie     | varchar(255)     | YES  |     | NULL                |                |
                    | niple       | tinyint(4)       | YES  |     | NULL                |                |
                    | created_at  | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
                    | updated_at  | timestamp        | NO   |     | 0000-00-00 00:00:00 |                |
                    | active      | tinyint(4)       | YES  |     | 1                   |                |
                    +-------------+------------------+------+-----+---------------------+----------------+*/


                    $idCountry = 1;
                    $idState = 1;
                    $idCity = 1;
                    $idEmployee = 1;
                    $idReportType = 1;

                    $insertReport = $conn->prepare("INSERT INTO `report`(`agreementNumber`, `innerNumber`, `outterNumber`, `street`, `idCountry`, `idState`, `idCity`, `idEmployee`, `idReportType`, `idUserCreator`, `dot_latitude`, `dot_longitude`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");
                    $insertReport->bind_param("ssssiiiiiidd", $agregment, $number, $number, $street, $idCountry, $idState, $idCity, $idEmployee, $idReportType, $id, $latitude, $longitude);

                    if ($insertReport->execute()) {

                        $response["status"] = "OK";
                        $response["code"] = "200";
                        $response["response"] = "Report Created";
                        $response["reportId"] = $insertReport->insert_id;
                        echo json_encode($response);
                        $response = null;

                        $idWorkflow = 1;
                        $idStatus = 1;
                        $idReport = $insertReport->insert_id;

                        $insertStatusReport = $conn->prepare("INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                        $insertStatusReport->bind_param("iii", $idWorkflow, $idStatus, $idReport);

                        if ($insertStatusReport->execute()) {
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["workflow_status_report_ID"] = $insertStatusReport->insert_id;
                            echo json_encode($response);
                        }
                    }
                }
            }
        }
    }
    $conn->close();
}