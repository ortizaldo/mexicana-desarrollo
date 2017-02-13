<?php include_once '../DAO.php';
			include_once '../libs/utils.php';

    session_start();

    /*if( !isset($_SESSION["nickname"]) ) {
			
		}*/

		$DB = new DAO();
		$conn = $DB->getConnect();

    $user = array();

    $id;
		$nickname="";
    $name="";
    $lastname="";
    $lastnameOp="";
		$email="";
    $phoneNumber="";
    $birthdate="";
    $gender="";
    $avatar="";

		$userID = $_POST["id"];

		//$password = $_POST["password"];
		//$password = md5($password);

		$getUserData = $conn->prepare("SELECT US.id, US.nickname, US.name, US.lastName, US.lastNameOp, US.email, US.birthdate, US.gender, US.avatar, US.phoneNumber FROM user AS US WHERE US.id = ? AND US.active = 1;");
		//print_r($getUserData);
		$getUserData->bind_param('i', $userID);

		if($getUserData->execute()) {
				$getUserData->store_result();
				$getUserData->bind_result($id, $nickname, $name, $lastname, $lastnameOp, $email, $birthdate, $gender, $avatar, $phoneNumber);
      
				if($getUserData->fetch()) {
						$user["id"] = $id;
            $user["nickname"] = $nickname;
            $user["name"] = $name;
            $user["lastName"] = $lastname;
            $user["lastNameOp"] = $lastnameOp;
            $user["email"] = $email;
            $user["birthdate"] = $birthdate;
            $user["gender"] = $gender;
            $user["avatar"] = $avatar;
            $user["phoneNumber"] = $phoneNumber;
          
            $userData[]=$user;
          
            echo json_encode($userData);
				} else {
					printf("Comment statement error: %s\n", $getUserData->error);
				}
		}
?>