<?php include_once "DAO.php";

  //ini_set('memory_limit', '-1');
  error_reporting(E_ERROR | E_PARSE);

  if ( isset($_POST["idUser"]) ) {
    $DB = new DAO();
    $conn = $DB->getConnect();
    //print_r($conn);
    $token = $_POST["token"];
    $token = base64_decode($token);
    $id =$_POST["idUser"];


  $searchToken = $conn->prepare("SELECT `u`.`id`,`u`.`name`,`u`.`lastNameOp`,`u`.`email`,`t`.`start_latitude`,`t`.`start_longitude` FROM `track` as t JOIN `employee` as e ON `t`.`idEmployee` = `e`.`iduser` JOIN `user` as u ON `u`.`id` = `e`.`id` WHERE `u`.`id`= ?;");

    $searchToken->bind_param('i', $id);
    $cart = array();
    if ( $searchToken->execute() ) {
        $searchToken->store_result();
        $searchToken->bind_result($id,$name,$lastNameOp,$email,$start_latitude,$start_longitude);
        $lisArray = [];
        while ( $searchToken->fetch() ) {
            $lisArray["id"] =$id;
            $lisArray["name"] =$name;
            $lisArray["lastNameOp"] =$lastNameOp;
            $lisArray["email"] =$email;
            $lisArray["start_latitude"] =$start_latitude;
            $lisArray["start_longitude"] =$start_longitude;
            array_push($cart, $lisArray);
            }
            //$resultado = count($lisArray);
           echo json_encode($cart);
        }
}else{
                    $response["status"] = "problem";
                    $response["code"] = "1234";
                    $response["response"] = "Ha ocurrido un problema";
                    echo json_encode($response);
}

 