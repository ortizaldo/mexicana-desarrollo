	<?php include_once "DAO.php";
		include_once "libs/utils.php";

		session_start();

		if( !isset($_SESSION["nickname"]) ) {
				//return error message. There´s no session for that user
		}

		$DB = new DAO();
		$conn = $DB->getConnect();

		//print_r($conn);
		//exit;

		extract($_POST);

    $dir="../uploads/";
    $fileName="";

    $nickname=$_POST["nickname"];
    $name=$_POST["name"];
    $lastName=$_POST["lastName"];
    $lastNameOp=$_POST["lastNameOp"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $passwordRetype=$_POST["passwordRetype"];
    $birthdate=$_POST["birthdate"];
    $gender=$_POST["gender"];
    $phoneNumber=$_POST["phoneNumber"];  
    $typeAgency=$_POST["typeAgency"];
    $credit=$_POST["credit"];
    $profile=$_POST["profile"];

    $activationToken="sdafasdf";
       echo ("antes del nickname");
         echo ($_POST['nickname']);

  if( isset($_POST['nickname']) || ($_POST['nickname']) && $_POST['nickname'] != NULL || $_POST['nickname'] != '' ) {

    /*echo "<pre>";
        print_r($_FILES["avatar"]);
    echo "</pre>";*/

    if( !file_exists($dir) ) {
      mkdir($dir, 0777, true);
    }
	echo ("avatar");
    if( isset($_FILES["avatar"]) ) {
    echo ("noo");
      //$file = read_file($_FILES['avatar']['tmp_name']);
      //$name = basename($_FILES['avatar']['name']);

      $fileName=$_FILES["avatar"]["name"];
			
			$x = 5; // Amount of digits
			$min = pow(10,$x);
			$max = pow(10,($x+1)-1);
			$value = rand($min, $max);
		
			$file_name = time().$value.'-'.str_replace("", "_", $file_name);
			
      $ext=$_FILES["avatar"]["type"];
      $error=$_FILES["avatar"]["error"];
      $upload=$_FILES["avatar"]["tmp_name"];
      $size=$_FILES["avatar"]["size"];

      //var_dump(is_dir('../uploads'));

      //echo $_FILES['avatar']['error'];

      //$dir=getcwd()."/storage/files/";

      //var_dump($_POST['nickname']);

      move_uploaded_file($upload, $dir.$fileName);

      //write_file('../uploads/'.$name, $file);

     //move_uploaded_file($_FILES["avatar"]["tmp_name"], '../uploads/'.$name));
     //echo "The file ". basename( $_FILES["avatar"]["name"]). " has been uploaded.";

      $stmnUser = 'INSERT INTO user(nickname, name, lastName, lastNameOp, email, password,
        birthdate, gender, avatar, phoneNumber, activation_token, active, created_at, updated_at, photoUrl, photoName)
  		 VALUES ("'. $nickname .'", "'. $name .'", "'. $lastName .'", "'. $lastNameOp .'", "'. $email .'", "'. $password .'",
       "'. $birthdate .'", "'. $gender .'", "'. $dir.$fileName .'" , "'. $phoneNumber .'", "'. $activationToken .'", 1,
       NOW(), NOW(), "' . $dir.$fileName . '", "'. $fileName .'");';

      $conn->query($stmnUser);

      printf ("New Record User has id %d.\n", $conn->insert_id);
      
      $idUserResult = $conn->insert_id;

      $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

      printf ("New Record user_rol has id %d.\n", $conn->insert_id);
      
      $stmnAgency = 'INSERT INTO agency(tipo, plazo, idUser, created_at, updated_at, active)
        VALUES("'. $typeAgency .'" , "'. $credit .'" , "'. $idUserResult .'" , NOW(), NOW(), 1);';
        
      $conn->query($stmnAgency);

      printf ("New Record agency has id %d.\n", $conn->insert_id);
        
      $idAgencyResult = $conn->insert_id;
      
      $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
        VALUES("'. $profile .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnProfile);

      printf ("New Record profile has id %d.\n", $conn->insert_id);
        
      $idProfileResult = $conn->insert_id;
      
      $stmnAgencyProfile = 'INSERT INTO agency_profile(name, idAgency, idProfile, created_at, updated_at, active)
        VALUES("'. $profile .'", "'. $idAgencyResult .'", "'. $idProfileResult .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnAgencyProfile);

      printf ("New Record agency_profile has id %d.\n", $conn->insert_id);
        
      $idAgencyProfileResult = $conn->insert_id;
      
    } else {
        echo ("hola");
    }

  }

?>


