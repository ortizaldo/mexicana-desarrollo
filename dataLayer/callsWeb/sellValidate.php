<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$response = [];

$formId = $_POST['formId'];

$stmtGetSellInfoValues = $conn->prepare("call spGetFormSellValidation(?);");
mysqli_stmt_bind_param($stmtGetSellInfoValues, 'i', $fromId);

if ($stmtGetSellInfoValues->execute()) {
    $stmtGetSellInfoValues->store_result();
    $stmtGetSellInfoValues->bind_result($trustedHome, $requestImage, $privacyAdvice, $identificationImage, $payerImage, $agreegmentImage, $validate, $idUser, $created_at);
    if ($stmtGetSellInfoValues->fetch()) {
        $response["comprobante"] = $trustedHome;
        $response["solicitud"] = $requestImage;
        $response["avisoPrivacidad"] = $privacyAdvice;
        $response["identificacion"] = $identificationImage;
        $response["pagare"] = $payerImage;
        $response["contrato"] = $agreegmentImage;
        $response["validado"] = $validate;
        $response["idUser"] = $idUser;
        $response["creacion"] = $created_at;
        $returnData[] = $response;
    }
    $stmtGetSellInfoValues->free_result();
    echo json_encode($returnData);
}
?>