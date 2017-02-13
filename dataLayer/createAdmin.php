<?php include_once "DAO.php";
	include_once "libs/utils.php";

	session_start();

    $DB = new DAO();
    $conn = $DB->getConnect();

    $dir="../uploads/";
    $fileName="";

    $agency = $_POST["agency"];
    $nickname = $_POST["nickname"];
    $name = $_POST["name"];
    $lastName = $_POST["lastname"];
    $lastNameOp = $_POST["lastnameOp"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRetype = $_POST["passwordretype"];
    $birthdate = $_POST["birthdate"];
    $gender = $_POST["gender"];
    $phoneNumber = $_POST["phoneNumber"];

    $activationToken="";

    $x = 7; // Amount of digits
    $min = pow(10,$x);
    $max = pow(10,($x+1)-1);
    $activationToken = rand($min, $max);

    $profile = "admin";

  if( isset($_POST['nickname']) || ($_POST['nickname']) && $_POST['nickname'] != NULL || $_POST['nickname'] != '' 
		&&  $password == $passwordRetype ) {

    /*echo "<pre>";
        print_r($_FILES["avatar"]);
    echo "</pre>";*/

    if( !file_exists($dir) ) {
      mkdir($dir, 0777, true);
    }

    if( isset($_FILES["avatar"]) ) {
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

      /*$stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

      printf ("New Record Location has id %d.\n", $conn->insert_id);*/
      
      
      $idUserResult = $conn->insert_id;

      $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

      printf ("New Record user_rol has id %d.\n", $conn->insert_id);
      
      $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
        VALUES("'. $profile .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnProfile);

      printf ("New Record profile has id %d.\n", $conn->insert_id);
        
      $idProfileResult = $conn->insert_id;
      
      $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
        VALUES("'. $idUserResult .'", "'. $idProfileResult .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnEmployee);

      printf ("New Record employee has id %d.\n", $conn->insert_id);
        
      $stmnEmployee = $conn->insert_id;
    } else if( isset($_POST["avatar"]) ) {
      //$file = read_file($_FILES['avatar']['tmp_name']);
      //$name = basename($_FILES['avatar']['name']);
			
			$base64_string_img = $_POST["avatar"];
			
			$filename_path = md5(time().uniqid()).".jpg";
			$decoded=base64_decode($base64_string_img); 
			file_put_contents("../uploads/".$filename_path, $decoded);
			
			$uri="../uploads/".$filename_path;
			
			$x = 5; // Amount of digits
			$min = pow(10,$x);
			$max = pow(10,($x+1)-1);
			$value = rand($min, $max);
		
			//$file_name = time().$value.'-'.str_replace("", "_", $filename_path);

      $stmnUser = 'INSERT INTO user(nickname, name, lastName, lastNameOp, email, password,
        birthdate, gender, avatar, phoneNumber, activation_token, active, created_at, updated_at, photoUrl, photoName)
  		 VALUES ("'. $nickname .'", "'. $name .'", "'. $lastName .'", "'. $lastNameOp .'", "'. $email .'", "'. $password .'",
       "'. $birthdate .'", "'. $gender .'", "'. $uri .'" , "'. $phoneNumber .'", "'. $activationToken .'", 1,
       NOW(), NOW(), "' . $uri. '", "'. $filename_path .'");';

      $conn->query($stmnUser);

      printf ("New Record User has id %d.\n", $conn->insert_id);

      /*$stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

      printf ("New Record Location has id %d.\n", $conn->insert_id);*/
      
      
      $idUserResult = $conn->insert_id;

      $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

      printf ("New Record user_rol has id %d.\n", $conn->insert_id);
      
      $stmnProfile = 'INSERT INTO profile(name, created_at, updated_at, active)
        VALUES("'. $profile .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnProfile);

      printf ("New Record profile has id %d.\n", $conn->insert_id);
        
      $idProfileResult = $conn->insert_id;
      
      $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
        VALUES("'. $idUserResult .'", "'. $idProfileResult .'", NOW(), NOW(), 1);';
      
      $conn->query($stmnEmployee);

      printf ("New Record employee has id %d.\n", $conn->insert_id);
        
      $stmnEmployee = $conn->insert_id;
			
			$getIDs = $conn->prepare("SELECT US.id, AG.id FROM user AS US LEFT JOIN agency AS AG ON US.id = AG.id WHERE US.nickname = ? AND US.active = 1;");
			//print_r($getIDs);
			$getIDs->bind_param('s', $agency);

			if( $getIDs->execute() ) {
				$getIDs->store_result();
				$getIDs->bind_result($userId, $agencyId);

				if( $getIDs->fetch() ) {

					$stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
						VALUES("'. $userId .'", "'. $agencyId .'", NOW(), NOW());';
					$conn->query($stmnAgency_Employee);

					printf ("New Record employee has id %d.\n", $conn->insert_id);

					$stmnIdEmployee = $conn->insert_id;

				} else {
					printf("Comment statement error: %s\n", $getIDs->error);
				}
			}
			
    } else {
			$elem["status"]="Bad Request";
			$elem["code"]="403";
			
			$dataReturn[]=$elem;
			
			echo json_encode($dataReturn);
		}

  }

?>

<!DOCTYPE>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <h1>Insert User</h1>
        <form method="POST" action='<?=$_SERVER['PHP_SELF']?>' enctype="multipart/form-data"> 
            <div id=""><p>Nickname</p> <input type="text" name="nickname" placeholder="Nickname" required/>
                <p>Nombre</p> <input type="text" name="name" placeholder="Name" required/> 
                <p>Apellido Paterno</p> <input type="text" name="lastName" placeholder="Last Name" required/> 
                <p>Apellido Materno</p> <input type="text" name="lastNameOp" placeholder="Last Name"/>
                <p>Email</p> <input type="email" name="email" placeholder="Email" required/> 
                <p>Password</p> <input type="password" name="password" placeholder="Password" required/>
                <p>Repetir Password</p> <input type="password" name="passwordRetype" placeholder="Repetir Password" required/> 
                <p>Fecha de Nacimiento</p> <input type="date" name="birthdate" placeholder="Birthdate"/>
                <p>Gender</p> <input type="text" name="gender" placeholder="Gender"/>
                <p>Avatar</p> <input type="file" name="avatar" placeholder="Avatar"/>
                <p>Phone Number</p> <input type="text" name="phoneNumber" placeholder="Phone Number"/>
            </div>
            <br/><br/>
            <input type="submit" value="Insert"/>
        </form>
    </body>
</html>
