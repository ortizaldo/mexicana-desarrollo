<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

/*Script para la creaci�n de un nuevo usuario
 * @param nickname string
 * @param email string
 * @param pass string
 * @param img string
 * @param rol string
 * @param agency string
 */

$nickname = $_POST["nickname"];
$email = $_POST["email"];
$password = $_POST["pass"];
$img = $_POST["img"];
$rol = 4;
$profile = $_POST["rol"];
$agency = $_POST["agency"];

function after($this, $inthat)
{
    if (!is_bool(strpos($inthat, $this)))
        return substr($inthat, strpos($inthat, $this) + strlen($this));
}

$activationToken = uniqid();

if (isset($_POST['nickname']) && $_POST['nickname'] != NULL || $_POST['nickname'] != '' && isset($password)) {
    //Dir en donde se almacena la imagen
    $dir = "../../uploads/";
    //Decodifica los datos de la imagen
    $avatar = base64_decode($img);
    //Crea una imagen en base al string generado de los datos de la imagen
    $sourceAvatar = imagecreatefromstring($avatar);
    //Asigna un nombre para la imagen
    $imageAvatarName = "avatar_usuario_" . uniqid() . ".png";
    //Obtiene el tama�o la imagen
    //$imageAvatarSize = filesize($sourceAvatar);
    //Url para almacenamiento de la imagen en el servidor
    $urlPhotoAvatar = $dir.$imageAvatarName;
    //Almacena la imagen
    $imageSave = imagejpeg($sourceAvatar, $imageAvatarName, 100);
    imagedestroy($sourceAvatar);

    //Variable comodin para el almacenamiento de NULL
    $wildcard = NULL;
    //Query para la insercion de la imagen
    $insertAvatar = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
    $insertAvatar->bind_param("sssss", $urlPhotoAvatar, $imageAvatarName, $wildcard, $wildcard, $wildcard);

    $stmnUser = 'INSERT INTO user(nickname, email, password, activation_token, active, created_at, updated_at, photoUrl, photoName)
			VALUES ("' . $nickname . '", "' . $email . '", "' . $password . '", "' . $activationToken . '", 1, NOW(), NOW(), "' . $urlPhotoAvatar . '", "' . $imageAvatarName . '");';
    $conn->query($stmnUser);
    $idUserResult = $conn->insert_id;

    $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
			VALUES ("' . $idUserResult . '", "' . $rol . '",, NOW(), NOW(), 1);';
    $conn->query($stmnRolUser);

    if ($rol == 1) {
        $profile = "SuperAdmin";

        $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
          VALUES("' . $profile . '", NOW(), NOW(), 1);';
        $conn->query($stmnProfile);
        $idProfileResult = $conn->insert_id;

        $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
          VALUES("' . $idUserResult . '", "' . $idProfileResult . '", NOW(), NOW(), 1);';
        $conn->query($stmnEmployee);
        $idEmployeeResult = $conn->insert_id;

        $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
              VALUES(1, "' . $idEmployeeResult . '", NOW(), NOW());';
        $conn->query($stmnAgency_Employee);
    } else if ($rol == 2) {
        $profile = "Admin";

        $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
          VALUES("' . $profile . '", NOW(), NOW(), 1);';
        $conn->query($stmnProfile);
        $idProfileResult = $conn->insert_id;

        $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
          VALUES("' . $idUserResult . '", "' . $idProfileResult . '", NOW(), NOW(), 1);';
        $conn->query($stmnEmployee);
        $idEmployeeResult = $conn->insert_id;

        $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
              VALUES(1, "' . $idEmployeeResult . '", NOW(), NOW());';
        $conn->query($stmnAgency_Employee);
    } else if ($rol == 3) {
        $profile = "Agency";

        $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
          VALUES("' . $profile . '", NOW(), NOW(), 1);';
        $conn->query($stmnProfile);
        $idProfileResult = $conn->insert_id;

        $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
          VALUES("' . $idUserResult . '", "' . $idProfileResult . '", NOW(), NOW(), 1);';
        $conn->query($stmnEmployee);
        $idEmployeeResult = $conn->insert_id;

        $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
              VALUES(1, "' . $idEmployeeResult . '", NOW(), NOW());';
        $conn->query($stmnAgency_Employee);

        $stmnAgency = 'INSERT INTO agency(tipo, plazo, idUser, created_at, updated_at, active)
              VALUES("' . $faker->word . '", "' . $faker->month($max = 'now') . '", "' . $idUserResult . '" ,NOW(), NOW(), 1);';
        $conn->query($stmnAgency);
        $idAgency = $conn->insert_id;

        $stmnAgency_profile = 'INSERT INTO agency_profile(name, idAgency, idProfile, created_at, updated_at, active)
              VALUES("Censista", "' . $idAgency . '", "' . $idProfileResult . '" ,NOW(), NOW(), 1);';
        $conn->query($stmnAgency_profile);

    } else if ($rol == 4) {
        $idRols = [];
        $idEmployeeResult;
        //Search id profiles selected
        foreach ($profile as $key) {
            //var_dump($key);
            $profileID = $conn->prepare("SELECT id FROM profile WHERE name = ? LIMIT 1;");
            $profileID->bind_param("s", $key);

            if ($profileID->execute()) {
                $profileID->store_result();
                $profileID->bind_result($id);
                while ($profileID->fetch()) {
                    //var_dump($id);
                    $idRols[] = $id;
                }
            }
        }
        $profilescount = count($idRols);
        //var_dump($profilescount);
        //print_r($idRols);

        if($profilescount == 1) {
            $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
            VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
            $conn->query($stmnEmployee);
            $idEmployeeResult = $conn->insert_id;
        } else if($profilescount == 2) {
            if ($idRols[0] == 1 && $idRols[1] == 2 ) {
                $idRols = null;
                $idRols[0] = 5;
                $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
                  VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
                $conn->query($stmnEmployee);
                $idEmployeeResult = $conn->insert_id;
            } else if ($idRols[0] == 2 && $idRols[1] == 3){
                $idRols = null;
                $idRols[0] = 6;
                $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
                  VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
                $conn->query($stmnEmployee);
                $idEmployeeResult = $conn->insert_id;
            }
        } else if($profilescount == 3) {
            if ($idRols[0] == 1 && $idRols[1] == 2 && $idRols[2] == 3 ) {
                $idRols = null;
                $idRols[0] = 7;
                $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
                  VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
                $conn->query($stmnEmployee);
                $idEmployeeResult = $conn->insert_id;
            } /*else if ($idRols[0] == 1 && $idRols[1] == 2 && $idRols[2] == 4) {
                $idRols = null;
                $idRols[0] = 7;
                $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
                  VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
                $conn->query($stmnEmployee);
                //printf ("New Record employee has id %d.\n", $conn->insert_id);
                $idEmployeeResult = $conn->insert_id;
            }*/
        } else if($profilescount == 4) {
            if ($idRols[0] == 1 && $idRols[1] == 2 && $idRols[2] == 3 && $idRols[3] == 4){
                $idRols = null;
                $idRols[0] = 8;
                $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
                  VALUES("' . $idUserResult . '", "' . $idRols[0] . '", NOW(), NOW(), 1);';
                $conn->query($stmnEmployee);
                $idEmployeeResult = $conn->insert_id;
            }
        }

        /*$stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
          VALUES("' . $idUserResult . '", "' . $idProfileResult . '", NOW(), NOW(), 1);';
        $conn->query($stmnEmployee);
        //printf ("New Record employee has id %d.\n", $conn->insert_id);
        $idEmployeeResult = $conn->insert_id;
        echo "agency - employee";
        echo $agency;
        echo $idEmployeeResult;*/

        $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
              VALUES("' . $agency . '", "' . $idEmployeeResult . '", NOW(), NOW());';
        $conn->query($stmnAgency_Employee);
    } else {
        printf("Comment statement error: %s\n", $getIDs->error);
    }
    $result["status"] = "OK";
    echo json_encode($result);
}?>
