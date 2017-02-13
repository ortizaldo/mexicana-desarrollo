<?php include_once '../DAO.php';
include_once '../libs/utils.php';
require_once '../classes/estructuraCarpetas.php';

session_start();

/*if( !isset($_SESSION["nickname"]) ) {

    }*/

$DB = new DAO();
$conn = $DB->getConnect();

$user = array();

$id;
$nickname = "";
$name = "";
$lastname = "";
$lastnameOp = "";
$email = "";
$phoneNumber = "";
$birthdate = "";
$gender = "";
$avatar = "";

$idUser = $_POST["id"];
$idRol = $_POST["rol"];


$getUserData = $conn->prepare("call spObtenerInformacionCompletaDelUsuario(?,?);");
$getUserData->bind_param('ii', $idUser, $idRol);

if ($getUserData->execute()) {
    $getUserData->store_result();
    $getUserData->bind_result($id, $nickname, $name, $lastname, $lastnameOp, $email, $birthdate, $gender, $avatar, $phoneNumber, $idProfile, $idAgency);

    if ($getUserData->fetch()) {
        $user["id"] = $id;
        $user["nickname"] = $nickname;
        $user["name"] = $name;
        $user["lastName"] = $lastname;
        $user["lastNameOp"] = $lastnameOp;
        $user["email"] = $email;
        $user["birthdate"] = $birthdate;
        $user["gender"] = $gender;
        $user["avatar"] = getRutaImagen($idProfile, $avatar);
        $user["phoneNumber"] = $phoneNumber;
        $user["idProfile"] = $idProfile;
        $user["idAgency"] = $idAgency;
        $userData[] = $user;

        echo json_encode($userData);
    } else {
        printf("Comment statement error: %s\n", $getUserData->error);
    }
}


function getRutaImagen($idPerfil, $avatar)
{
    if(isset($avatar))
    {
        $oEstructuraCarpetas = new EstructuraCarpetas();
        $oEstructuraCarpetas->setPerfil($idPerfil);
        $dir = $oEstructuraCarpetas->getDirectorioEmpleado();
    
        $rutaImagen = $dir.$avatar;
    }
    else
    {
        $rutaImagen = "http://siscomcmg.com/assets/img/logoMexicana.png";
    }
    
    return $rutaImagen;
}
?>