<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";

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
            if( $searchToken->fetch() ) {

                if ( $_POST["token"] == $userToken ) {

                    if( isset($_POST["secondStepSell"]) ) {
                        $data = $_POST["secondStepSell"];
                        $data = base64_decode($data);

                        //print_r($data);

                        $references = [];

                        /*list($idAgency, $payment, $idReport, $requestDate, $clientlastName, $clientlastName2, $clientName,
                            $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                            $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia,
                            $street, $inHome, $homeTelephone, $celullarTelephone, $agreementType, $price, $agreementExpires,
                            $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation,
                            $clientJobRange, $clientJobActivity, $clientJobTelephone, $latitude, $longitude) = explode("&", $data);*/

                        list($idAgency, $payment, $idReport, $requestDate, $clientlastName, $clientlastName2, $clientName,
                            $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                            $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia,
                            $street, $inHome, $homeTelephone, $celullarTelephone, $agreementType, $price, $agreementExpires,
                            $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $references, $clientJobEnterprise, $clientJobLocation,
                            $clientJobRange, $clientJobActivity, $clientJobTelephone, $latitude, $longitude) = explode("&", $data);

                        /*$response["status"] = "OK";
                        $response["code"] = "200";
                        $response["idAgency"] = $idAgency;
                        $response["payment"] = $payment;
                        $response["idReport"] = $idReport;
                        $response["requestDate"] = $requestDate;
                        $response["clientlastName"] = $clientlastName;
                        $response["clientlastName2"] = $clientlastName2;
                        $response["clientName"] = $clientName;
                        $response["clientRFC"] = $clientRFC;
                        $response["clientCURP"] = $clientCURP;
                        $response["clientEmail"] = $clientEmail;
                        $response["clientRelationship"] = $clientRelationship;
                        $response["clientgender"] = $clientgender;
                        $response["clientIdNumber"] = $clientIdNumber;
                        $response["identificationType"] = $identificationType;
                        $response["clientBirthDate"] = $clientBirthDate;
                        $response["clientBirthCountry"] = $clientBirthCountry;
                        $response["idState"] = $idState;
                        $response["idCity"] = $idCity;
                        $response["idColonia"] = $idColonia;
                        $response["street"] = $street;
                        $response["inHome"] = $inHome;
                        $response["homeTelephone"] = $homeTelephone;
                        $response["celullarTelephone"] = $celullarTelephone;
                        $response["agreementType"] = $agreementType;
                        $response["price"] = $price;
                        $response["agreementExpires"] = $agreementExpires;
                        $response["agreementMonthlyPayment"] = $agreementMonthlyPayment;
                        $response["agreementRi"] = $agreementRi;
                        $response["agreementRiDate"] = $agreementRiDate;
                        $response["references"] = $references;
                        $response["clientJobEnterprise"] = $clientJobEnterprise;
                        $response["clientJobLocation"] = $clientJobLocation;
                        $response["clientJobRange"] = $clientJobRange;
                        $response["clientJobActivity"] = $clientJobActivity;
                        $response["clientJobTelephone"] = $clientJobTelephone;
                        $response["latitude"] = $latitude;
                        $response["longitude"] = $longitude;

                        //echo json_encode($response);
                        exit;*/


                        $createSecondStepSell = $conn->prepare("INSERT INTO `agreement`(`idAgency`, `payment`, `idReport`, `requestDate`, `clientlastName`, `clientlastName2`, `clientName`,
                          `clientRFC`, `clientCURP`, `clientEmail`, `clientRelationship`, `clientgender`, `clientIdNumber`, `identificationType`, `clientBirthDate`, `clientBirthCountry`,
                          `idState`, `idCity`, `idColonia`, `street`, `inHome`, `homeTelephone`, `celullarTelephone`, `agreementType`, `price`, `agreementExpires`, `agreementMonthlyPayment`,
                          `agreementRi`, `agreementRiDate`, `clientJobEnterprise`, `clientJobLocation`, `clientJobRange`, `clientJobActivity`, `clientJobTelephone`, `latitude`, `longitude`, 
                          `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());");

                        //print_r($createSecondStepSell);

                        $createSecondStepSell->bind_param("idisssssssssssssiiisissssisdssssssdd",
                            $idAgency, $payment, $idReport, $requestDate, $clientlastName, $clientlastName2, $clientName,
                            $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $clientgender, $clientIdNumber,
                            $identificationType, $clientBirthDate, $clientBirthCountry, $idState, $idCity, $idColonia,
                            $street, $inHome, $homeTelephone, $celullarTelephone, $agreementType, $price, $agreementExpires,
                            $agreementMonthlyPayment, $agreementRi, $agreementRiDate, $clientJobEnterprise, $clientJobLocation,
                            $clientJobRange, $clientJobActivity, $clientJobTelephone, $latitude, $longitude);

                        //print_r($createSecondStepSell);

                        if ( $createSecondStepSell->execute() ) {
                            $response["status"] = "OK";
                            $response["code"] = "200";
                            $response["response"] = $createSecondStepSell->insert_id;
                            echo json_encode($response);

                            $references =  (array) json_decode($references);
                            //print_r($references["references"]);

                            foreach( $references["references"] as $key ) {
                                $data = (array) $key;
                                /*print_r($data["name"]);
                                print_r($data["tel"]);
                                print_r($data["workTel"]);
                                print_r($data["ext"]);*/

                                $createReference = $conn->prepare("INSERT INTO `agreement_reference`(`name`, `telephone`, `jobTelephone`, `ext`, `idAgreement`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                                $createReference->bind_param("ssssi", $data["name"], $data["tel"], $data["workTel"], $data["ext"], $createSecondStepSell->insert_id);

                                if( $createReference->excute() ) {
                                    $response = null;

                                    $response["status"] = "OK";
                                    $response["code"] = "200";
                                    $response["reference Response"] = $createReference->insert_id;
                                    echo json_encode($response);

                                    $idForm = 1;

                                    $createAgreeForm = $conn->prepare("INSERT INTO `agreement_employee_form`(`idAgreement`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());");
                                    $createAgreeForm->bind_param("iii", $createSecondStepSell->insert_id, $id, $idForm);

                                    if( $createAgreeForm->excute() ) {
                                        $response = null;

                                        $response["status"] = "OK";
                                        $response["code"] = "200";
                                        $response["reference Response"] = $createAgreeForm->insert_id;
                                        echo json_encode($response);

                                        $idWorkflow = 2;
                                        $idStatus = 2;

                                        $createAgreementStatus = $conn->prepare("INSERT INTO `workflow_status_agreement`(`idWorkflow`, `idStatus`, `idAgreement`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);");
                                        $createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $createSecondStepSell->insert_id);
                                        $response = null;

                                        if( $createAgreementStatus->excute() ) {
                                            $response["status"] = "OK";
                                            $response["code"] = "200";
                                            $response["Create Agreement Status"] = $createAgreementStatus->insert_id;
                                            echo json_encode($response);
                                        }
                                    }

                                    /*workflow_status_agreement;
                                    +-------------+------------------+------+-----+---------+----------------+
                                    | Field       | Type             | Null | Key | Default | Extra          |
                                    +-------------+------------------+------+-----+---------+----------------+
                                    | id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
                                    | idWorkflow  | int(10) unsigned | NO   | MUL | NULL    |                |
                                    | idStatus    | int(10) unsigned | NO   | MUL | NULL    |                |
                                    | idAgreement | int(10) unsigned | NO   | MUL | NULL    |                |
                                    | name        | varchar(255)     | YES  |     | NULL    |                |
                                    | description | varchar(255)     | YES  |     | NULL    |                |
                                    | created_at  | datetime         | YES  |     | NULL    |                |
                                    | updated_at  | datetime         | YES  |     | NULL    |                |
                                    | active      | tinyint(4)       | YES  |     | 1       |                |
                                    +-------------+------------------+------+-----+---------+----------------+*/
                                }
                            }
                            exit;

                            /*agreement_employee_form; // idForm = If
                            +-------------+------------------+------+-----+---------+----------------+
                            | Field       | Type             | Null | Key | Default | Extra          |
                            +-------------+------------------+------+-----+---------+----------------+
                            | id          | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
                            | idAgreement | int(10) unsigned | NO   | MUL | NULL    |                |
                            | idEmployee  | int(10) unsigned | NO   | MUL | NULL    |                |
                            | idForm      | int(10) unsigned | NO   | MUL | NULL    |                |
                            | created_at  | datetime         | YES  |     | NULL    |                |
                            | updated_at  | datetime         | YES  |     | NULL    |                |
                            +-------------+------------------+------+-----+---------+----------------+*/

                            /*agreement_reference;
                            +--------------+------------------+------+-----+---------+----------------+
                            | Field        | Type             | Null | Key | Default | Extra          |
                            +--------------+------------------+------+-----+---------+----------------+
                            | id           | int(10) unsigned | NO   | PRI | NULL    | auto_increment |
                            | name         | varchar(200)     | NO   |     | NULL    |                |
                            | telephone    | varchar(50)      | NO   |     | NULL    |                |
                            | jobTelephone | varchar(50)      | NO   |     | NULL    |                |
                            | ext          | varchar(50)      | NO   |     | NULL    |                |
                            | idAgreement  | int(10) unsigned | NO   | MUL | NULL    |                |
                            | created_at   | datetime         | YES  |     | NULL    |                |
                            | updated_at   | datetime         | YES  |     | NULL    |                |
                            +--------------+------------------+------+-----+---------+----------------+*/
                        }

                            /*if ($insertReport->execute()) {

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
                            }*/
                    }
                }
            }
        }
        $conn->close();
    }