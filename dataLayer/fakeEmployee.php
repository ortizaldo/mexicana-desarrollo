<?php require "libs/faker_vendor/autoload.php";
      include_once 'DAO.php';

  $faker = Faker\Factory::create();

  $DB = new DAO();
  $conn = $DB->getConnect();

  for( $i = 1; $i <= 8; $i++ ) {
      //echo "<p>" . $faker->name . "</p>";
      //echo "<p>" . $faker->address . "</p>";
    
    $x = 4; // Amount of digits
    $min = pow(10,$x);
    $max = pow(10,($x+1)-1);
    $value = rand($min, $max);
    
    $activationToken = time().$value;
    
    $width=768; $height=512;
    
    $stmnUser = 'INSERT INTO user(nickname, name, lastName, lastNameOp, email, password,
        birthdate, gender, avatar, phoneNumber, activation_token, active, created_at, updated_at, photoUrl, photoName)
  		 VALUES ("'. $faker->name .'", "'. $faker->firstName .'", "'. $faker->lastName .'", "'. $faker->lastName .'", 
       "'. $faker->companyEmail .'", "'. $faker->password .'", "'. $faker->date($format = 'Y-m-d', $max = 'now') .'", 
       "'. $faker->titleFemale .'", "'. $faker->imageUrl($width, $height, 'cats', true, $value) .'" , 
       "'. $faker->phoneNumber .'", "'. $activationToken .'", 1,
       NOW(), NOW(), "' . $faker->imageUrl($width, $height, 'cats', true, $value) . '", "'. $faker->imageUrl($width, $height, 'cats', true, $value) .'");';

    $conn->query($stmnUser);
    //printf ("New Record User has id %d.\n", $conn->insert_id);
    $idUserResult = $conn->insert_id;
    
    $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active) VALUES ("'. $idUserResult .'", "'. $i .'", NOW(), NOW(), 1);';
    $conn->query($stmnRolUser);
      
    $stmnEmployee = 'INSERT INTO employee(idUser, idProfile, created_at, updated_at, active)
      VALUES("'. $idUserResult .'", "'. $i .'", NOW(), NOW(), 1);';
    $conn->query($stmnEmployee);
    $stmnEmployee = $conn->insert_id;

    $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at)
          VALUES(2, "'. $stmnEmployee .'", NOW(), NOW());';
    $conn->query($stmnAgency_Employee);

    printf ("New Record agency_employee has id %d.\n", $conn->insert_id);
  }   