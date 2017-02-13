<?php require "libs/faker_vendor/autoload.php";
      include_once 'DAO.php';

$faker = Faker\Factory::create();

  session_start();

    /*if( !isset($_SESSION["nickname"]) ) {
			
		}*/

  $DB = new DAO();
  $conn = $DB->getConnect();

  /*$agency = $_POST["agency"];
  $nickname = $_POST["nickname"];
  $name = $_POST["name"];
  $lastName = $_POST["lastname"];
  $lastNameOp = $_POST["lastnameOp"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $passwordRetype = $_POST["passwordretype"];
  $birthdate = $_POST["birthdate"];
  $gender = $_POST["gender"];
  $phoneNumber = $_POST["phoneNumber"];*/

  /*$x = 4; // Amount of digits
  $min = pow(10,$x);
  $max = pow(10,($x+1)-1);
  $value = rand($min, $max);

  $activationToken = time().$value;*/

  // generate data by accessing properties
  for ($i = 0; $i < 599; $i++) {
      //echo "<p>" . $faker->name . "</p>";
      //echo "<p>" . $faker->address . "</p>";
    
    $x = 4; // Amount of digits
    $min = pow(10,$x);
    $max = pow(10,($x+1)-1);
    $value = rand($min, $max);
    
    $activationToken = time().$value;
    
    $width=768; $height=512;
    
    $stmnUser = 'INSERT INTO user(nickname, name, lastName, email, password, avatar, phoneNumber, activation_token, active, created_at, updated_at, photoUrl, photoName)
  		 VALUES ("'. $faker->company .'", "'. $faker->catchPhrase .'", "'. $faker->bs .'", 
       "'. $faker->companyEmail .'", "'. $faker->password .'", "'. $faker->imageUrl($width, $height, 'cats', true, $value) .'" , 
       "'. $faker->phoneNumber .'", "'. $activationToken .'", 1,
       NOW(), NOW(), "' . $faker->imageUrl($width, $height, 'cats', true, $value) . '", "'. $faker->imageUrl($width, $height, 'cats', true, $value) .'");';

      $conn->query($stmnUser);

      printf ("New Record User has id %d.\n", $conn->insert_id);
      $idUserResult = $conn->insert_id;
    
      $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 4, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);
      printf ("New Record user_rol has id %d.\n", $conn->insert_id);
    
      if( $i < 150 ) {
        $profile = "Cencista";
      } else if ( $i > 149 && $i < 300 ) {
        $profile = "Cambaceo";
      } else if ( $i > 299 && $i < 450 ) {
        $profile = "Plomero";
      } else if ( $i > 449 && $i <= 600 ) {
        $profile = "Instalador";
      }
    
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
    
      $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
						VALUES("'. rand(10, 115) .'", "'. $stmnEmployee .'", NOW(), NOW());';
      $conn->query($stmnAgency_Employee);

      printf ("New Record agency_employee has id %d.\n", $conn->insert_id);
		
  }   