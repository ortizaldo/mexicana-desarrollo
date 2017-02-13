<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['form']) && isset($_POST['id'])) {
    $formNumber = $_POST['form'];
    $formNumber = intval($formNumber);
    $userNickname = $_POST['id'];
    $reason = json_decode($_POST['reasons']);

    /*var_dump($formNumber);
    var_dump($userNickname);
    var_dump($reason);*/

    $trustedHome = $_POST['trustedHome'];
    $requestImage = $_POST['requestImage'];
    $privacyAdvice = $_POST['privacyAdvice'];
    $identificationImage = $_POST['identificationImage'];
    $payerImage = $_POST['payerImage'];
    $agreegmentImage = $_POST['agreegmentImage'];

    $returnData = [];

    $searchIdEmployee = $conn->prepare("SELECT id FROM user WHERE nickname = ?;");
    $searchIdEmployee->bind_param("s", $userNickname);
    if ($searchIdEmployee->execute()) {
        $searchIdEmployee->store_result();
        $searchIdEmployee->bind_result($userId);
        if ($searchIdEmployee->fetch()) {
            $userId;

            /*var_dump($userId);

            var_dump($trustedHome);
            var_dump($requestImage);
            var_dump($privacyAdvice);
            var_dump($identificationImage);
            var_dump($payerImage);
            var_dump($agreegmentImage);*/

            if ($trustedHome == "true") {
                $trustedHome = 1;
            } else {
                $trustedHome = 0;
            }
            if ($requestImage == "true") {
                $requestImage = 1;
            } else {
                $requestImage = 0;
            }
            if ($privacyAdvice == "true") {
                $privacyAdvice = 1;
            } else {
                $privacyAdvice = 0;
            }
            if ($identificationImage == "true") {
                $identificationImage = 1;
            } else {
                $identificationImage = 0;
            }
            if ($payerImage == "true") {
                $payerImage = 1;
            } else {
                $payerImage = 0;
            }
            if ($agreegmentImage == "true") {
                $agreegmentImage = 1;
            } else {
                $agreegmentImage = 0;
            }

            if ($trustedHome == "true" || $requestImage == "true" || $privacyAdvice == "true" || $identificationImage == "true" || $payerImage == "true" || $agreegmentImage == "true") {
                $validate = 1;
            } else {
                $validate = 0;
            }
            /*var_dump($trustedHome);
            var_dump($requestImage);
            var_dump($privacyAdvice);
            var_dump($identificationImage);
            var_dump($payerImage);
            var_dump($agreegmentImage);
            var_dump($validate);
            var_dump($formNumber);*/
            $searchIdEmployee->close();
            $conn->next_result();
            //print_r($conn);
            $imagesStatus = $conn->prepare("INSERT INTO form_sells_validation (trustedHome, requestImage, privacyAdvice, identificationImage, payerImage, agreegmentImage, validate, idFormSell, created_at, updated_at, active) VALUES (?, ?, ?, ?, ? ,?, ?, ?, NOW(), NOW(), 1);");
            //print_r($imagesStatus);
            $imagesStatus->bind_param("iiiiiiii", $trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate, $formNumber);
            $imagesStatus->execute();
            $imagesStatus->close();
            $conn->next_result();
            if ($reason != "" || $reason != null) {
                foreach ($reason as $key) {
                    $key = (array)$key;
                    //var_dump($key);

                    //var_dump($key['Text']);
                    //var_dump($key['Val']);
                    $val = intval($key['Val']);
                    $validate = 0;
                    //print_r($conn);
                    $rejectedReson = $conn->prepare("INSERT INTO form_sells_rejected_reason(idSell, idRejectedReason, valid, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
                    //print_r($rejectedReson);
                    $rejectedReson->bind_param("iii", $formNumber, $val, $validate);
                    //print_r($rejectedReson);
                    $rejectedReson->execute();

                    $rejectedReson->close();
                    $conn->next_result();
                    //var_dump("Entering to idReport Search");  
                }
            }
            
            $idReport = $conn->prepare("SELECT idReport FROM report_employee_form WHERE idForm = ?;");
            //print_r($idReport);
            $idReport->bind_param("i", $formNumber);
            if ($idReport->execute()) {
                $idReport->store_result();
                $idReport->bind_result($reportID);
                if ($idReport->fetch()) {
                    $idReport->close();
                    //var_dump($reportID);
                    $conn->next_result();
                    $status = 2;
                    $statusReport = $conn->prepare("UPDATE workflow_status_report SET idStatus = ? WHERE idReport = ?;");
                    $statusReport->bind_param("ii", $status, $reportID);
                    $statusReport->execute();

                    $response["status"] = "OK";
                    $response["code"] = "200";
                    $response["response"] = "Venta Reachazada Exitosamente";

                    echo json_encode($response);
                }
            }
        }
    }
}
?>