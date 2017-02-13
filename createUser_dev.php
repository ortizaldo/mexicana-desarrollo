<?php
  include_once "DAO.php";
	include_once "libs/utils.php";

	session_start();

  $DB = new DAO();
  $conn = $DB->getConnect();

  //print_r($conn);
  //exit;

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

  $activationToken="sdafasdf";

  if( isset($_POST['nickname']) || ($_POST['nickname']) && $_POST['nickname'] != NULL || $_POST['nickname'] != '' ) {

  
      $stmnUser = 'INSERT INTO user(nickname, name, lastName, lastNameOp, email, password,
        birthdate, gender, avatar, phoneNumber, activation_token, active, created_at, updated_at, photoUrl, photoName)
  		 VALUES ("'. $nickname .'", "'. $name .'", "'. $lastName .'", "'. $lastNameOp .'", "'. $email .'", "'. $password .'",
       "'. $birthdate .'", "'. $gender .'", "'. $dir.$fileName .'" , "'. $phoneNumber .'", "'. $activationToken .'", 1,
       NOW(), NOW(), "' . $dir.$fileName . '", "'. $fileName .'");';

      $conn->query($stmnUser);

      printf ("New Record User has id %d.\n", $conn->insert_id);

      $stmnRolUser = 'INSERT INTO user_rol(idUser, idRol, created_at, updated_at, active)
        VALUES ("'. $conn->insert_id .'", 1, NOW(), NOW(), 1);';

      //$stmnLocation = 'INSERT INTO .`locationUser` ( `idUser`, ``, ``, `created_at`, `updated_at`)
      //VALUES ("'. $conn->insert_id .'", 90.7890867, -100.6789097, "'. date('Y-m-d H:i:s') .'", "'. date('Y-m-d H:i:s') .'");';

      $conn->query($stmnRolUser);

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
				<div id="">

          <p>Nickname</p>
					<input type="text" name="nickname" placeholder="Nickname" required></input>
					<p>Nombre</p>
					<input type="text" name="name" placeholder="Name" required></input>
          <p>Apellido Paterno</p>
					<input type="text" name="lastName" placeholder="Last Name" required></input>
          <p>Apellido Materno</p>
					<input type="text" name="lastNameOp" placeholder="Last Name" required></input>
          <p>Email</p>
					<input type="email" name="email" placeholder="Email" required></input>
          <p>Password</p>
					<input type="password" name="password" placeholder="Password" required></input>
          <p>Repetir Password</p>
					<input type="password" name="passwordRetype" placeholder="Repetir Password" required></input>
          <p>Fecha de Nacimiento</p>
					<input type="date" name="birthdate" placeholder="Birthdate" required></input>
          <p>Gender</p>
					<input type="text" name="gender" placeholder="Gender" required></input>
          <p>Avatar</p>
					<input type="file" name="avatar" placeholder="Avatar" required></input>
          <p>Phone Number</p>
					<input type="text" name="phoneNumber" placeholder="Phone Number" required></input>

				</div>

				<br/><br/>
				<input type="submit" value="Insert"></input>
			</form>
	</body>
</html>
