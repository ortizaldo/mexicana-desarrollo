<?php include_once "../DAO.php";
include_once "../libs/utils.php";
session_start();

$DB = new DAO();
$conn = $DB->getConnect();


    $idAgencia = $_POST['idAgencia'];


$users=array();
$returnEmployees = array();
$returnData=array();

$id;
$nickname;
$name;
$lastName;
$lastNameOp;
$email;
$idRol;
$tipo;

$queryEmpleadosPorAgencia = "SELECT tu.id,tu.nickname, tu.name,tu.lastName,tu.lastNameOp, tu.email,trur.idRol,ta.tipo
FROM user AS tu
INNER JOIN user_rol AS trur ON trur.idUser=tu.id
INNER JOIN employee AS te ON te.idUser=tu.id
INNER JOIN agency_employee AS trae ON trae.idemployee=te.id
INNER JOIN agency AS ta ON ta.id=trae.idAgency
WHERE ta.idUser=?
AND trur.idRol=4;";

$resultEmployees = $conn->prepare($queryEmpleadosPorAgencia);
$resultEmployees->bind_param("i", $idAgencia);

$resultEmployees->store_result();
$resultEmployees->bind_result($id, $nickname, $name, $lastName,$lastNameOp,$email,$idRol,$tipo);

if( $resultEmployees->execute() ) {
    while( $resultEmployees->fetch() ) {
        $users['id'] = $id;
        $users['nickname'] =$nickname;
        $users['name'] = $name;
        $users['lastName'] = $lastName;
        $users['lastNameOp'] =$lastNameOp;
        $users['email'] = $email;
        $users['idRol'] = $idRol;
        $users['tipo'] = $tipo;
        $returnEmployees[] = $users;
    }

}
echo json_encode($returnEmployees);


function grabarLog($logInfo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = "showUsers.txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

}