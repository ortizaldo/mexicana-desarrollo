<?php include_once "../DAO.php";
 session_start();

$DB = new DAO();
$conn = $DB->getConnect();

$returnData = []; $agency = [];
$profile1 = 2; $profile2 = 5; $profile3 = 6; $profile4 = 7; $profile5 = 8;

$nickName = $_SESSION["nickname"];

//var_dump($nickName);exit();
//$type = "Venta";
/*
 * Busca Agencia encargada de venta.
 */
/***************************VENTA************************************/
$searchAgency = $conn->prepare("
    SELECT a.id, a.nickname
    FROM user a, user_rol b, rol c
    WHERE 0=0
    and a.id=b.idUser
    and c.id=b.idRol
    and c.id in (1,3)
    and a.nickname != ? and a.nickname not in ('AYOPSA', 'callcenter');
");
$searchAgency->bind_param("s",$nickName);
if ($searchAgency->execute()) {
    $searchAgency->store_result();
    $searchAgency->bind_result($userId, $nickname);
    while ($searchAgency->fetch()) {
        $agency['idUser'] = $userId;
        $agency['agency'] = $nickname;
        $returnData[] = $agency;
    }
}
$searchAgency->free_result();
echo json_encode($returnData);