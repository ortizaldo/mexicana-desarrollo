<?php include_once "../DAO.php";
include_once "../libs/utils.php";
session_start();

$DB = new DAO();
$conn = $DB->getConnect();

if( isset($_POST['userId']) && isset($_POST['status']) ) {
    $id = $_POST['userId'];
    $status = $_POST['status'];

    $updateUser = $conn->prepare("UPDATE user SET active = ? WHERE id = ?;");
    $updateUser->bind_param('ii', $status, $id);

    if( $updateUser->execute() ) {
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = "User Data Refreshed";
        echo json_encode($response);
    }
    $updateUser->close();
}
