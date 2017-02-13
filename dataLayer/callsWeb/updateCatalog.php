<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

if (isset($_POST['catalog']) && isset($_POST['reasons'])) {
    $catalog = $_POST['catalog'];
    $reasons = json_decode($_POST['reasons']);

    foreach ($reasons as $key) {
        $rejectedReason = $conn->prepare("INSERT INTO rejected_reason(reason, created_at, updated_at) VALUES(?, NOW(), NOW());");
        $rejectedReason->bind_param("s", $key);
        $rejectedReason->execute();

        //$rejectedReason->close();
    }
}