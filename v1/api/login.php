<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
  if ( isset($_POST["nickname"]) && isset($_POST["password"]) && isset($_POST["idDevice"]) )
  {
      /*$username = base64_decode($_POST["nickname"]);
      $password = base64_decode($_POST["password"]);
      $idDevice = base64_decode($_POST["idDevice"]);*/
      $username = $_POST["nickname"];
      $password = $_POST["password"];
      $idDevice = $_POST["idDevice"];

      $DB = new DAO();
      $conn = $DB->getConnect();
      $getUserInfoSQL = "SELECT `user`.`id`, `user`.`nickname`, `user`.`email`, `profile`.`name`, USAG.`nickname`, `rol`.`type` FROM `profile` LEFT JOIN `employee` ON `employee`.`idProfile` = `profile`.`id` LEFT JOIN `user` ON `user`.`id` = `employee`.`idUser` LEFT JOIN `user_rol` ON `user_rol`.`idUser` = `user`.`id` LEFT JOIN `rol` ON `rol`.`id` = `user_rol`.`idRol` AND `user_rol`.`idUser` = `user`.`id` INNER JOIN `agency_employee` ON `employee`.`id` = `agency_employee`.`idemployee` LEFT JOIN `agency` ON `agency`.`id` = `agency_employee`.`idAgency` INNER JOIN `user` AS USAG ON `agency`.`idUser` = USAG.id WHERE `user`.`email` = ? AND `user`.`password` = ? OR `user`.`nickname` = ? AND `user`.`password` = ? AND `user`.`active` = 1;";
      if ($getUserInfo = $conn->prepare($getUserInfoSQL)) {
            $getUserInfo->bind_param("ssss", $username, $password, $username, $password);
            if( $getUserInfo->execute() ) {
                  $getUserInfo->store_result();
                  $getUserInfo->bind_result($id, $nickname, $email, $profile, $agency, $rol);

                  if( $getUserInfo->fetch() ) {
                      $value = rand(0, 1000000);

                      if($profile == 1) {
                          $profile = "Censo";
                      } else if($profile == 2) {
                          $profile = "Venta";
                      } else if($profile == 3) {
                          $profile = "Plomero";
                      } else if($profile == 4) {
                          $profile = "Instalacion";
                      } else if($profile == 5) {
                          $profile = "censo_venta";
                      } else if($profile == 6) {
                          $profile = "plomero_venta";
                      } else if($profile == 7) {
                          $profile = "plomero_venta_censo";
                      } else if($profile == 8) {
                          $profile = "plomero_venta_censo_instalacion";
                      } else if($profile == 9) {
                          $profile = "plomero_instalacion";
                      }
                      $token = base64_encode($id."&".$nickname."&".$email."&".$profile."&".$agency."&".$rol."&".$value."&".$idDevice);

                      $getUserInfo = $conn->prepare("UPDATE `user` SET `user`.`token` = ? WHERE `user`.`id` = ?");
                      $getUserInfo->bind_param('si', $token, $id);
                      $getUserInfo->execute();

                      $response["status"]="OK";
                      $response["code"]="200";
                      $response["response"]="Session started";
                      $response["token"]=$token;
                      echo json_encode($response);

                  } else {
                      $response["status"]="ERROR";
                      $response["code"]="404";
                      $response["response"]="Usuario no encontrado".$getUserInfo->error;
                      echo json_encode($response);
                  }
            }
      }

      $conn->close();
  } else {
      $response["status"]="ERROR";
      $response["code"]="404";
      $response["response"]="Datos Ingresados Erroneamente.";
      echo json_encode($response);
  }