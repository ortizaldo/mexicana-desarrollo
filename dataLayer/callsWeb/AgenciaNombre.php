<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = [];
$response = array();

if (isset($_POST['idUser'])) {
    $idUser = $_POST['idUser'];
    $getAdmins = $conn->prepare("SELECT AG.id, US.nickname FROM user AS US INNER JOIN agency AS AG ON AG.idUser = US.id WHERE US.id=? limit 1;");
    $getAdmins->bind_param("i", $idUser);

    if ($getAdmins->execute()) {
        $getAdmins->store_result();
        $getAdmins->bind_result($id, $nickname);
        while ($getAdmins->fetch()) {
            $returnData['id'] = $id;
            $returnData['nickname'] = $nickname;
            
            $response[] = $returnData;
        }
    }
    echo json_encode($response);
} else {
    echo "error";
}