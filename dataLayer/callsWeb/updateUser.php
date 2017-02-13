<?php include_once '../DAO.php';
			include_once '../libs/utils.php';

  $DB = new DAO();
  $conn = $DB->getConnect();

  $user = array();

  $id;
  $agency;
  $nickname;
  $name;
  $lastName;
	//$lastNameOp;
  $email;
  $phoneNumber;
	//$rol;
	$status;
	//$gender;

	if( isset($_POST['id']) && isset($_POST['nickname']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['agency']) ) {
		
      $id = $_POST['id'];
      $nickname = $_POST['nickname'];
      $email = $_POST['email'];
			$pass = $_POST['pass'];
			$img = $_POST['img'];
			$roles[] = $_POST['roles'];
			$agency = $_POST['agency'];
		
			//Modificar Usuario con base a la información ingresada por el usuario
		
			$updateUser = $conn->prepare('UPDATE user SET nickname = "'. $nickname .'", name = "'. $name .'", lastName = "'. $lastName .'", 
			lastNameOp = "'. $lastNameOp .'", avatar = "'. $dir.$fileName .'", email = "'. $email .'", phoneNumber = "'. $phoneNumber .'",
			gender = "'. $gender .'", active = "'. $status .'" WHERE id=?;');
			
			$updateUser->bind_param('i', $id);

			if($updateUser->execute()) {
				
				$stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
						VALUES("'. $agency .'", "'. $id .'", NOW(), NOW());';
				$conn->query($stmnAgency_Employee);
				//printf ("New Record agency_employee has id %d.\n", $conn->insert_id);

				$_GET["id"] = $id;
				$user["status"] = "OK";
				$user["code"] = "200";
				$user["id"] = $_GET["id"];
			}
		
			echo json_encode($user);
	}
?>