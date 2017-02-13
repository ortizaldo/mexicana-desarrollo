<?php include_once 'header.php';
	include_once 'dataLayer/DAO.php';

	$DB = new DAO();
	$conn = $DB->getConnect();

	$user = array();

	$id;
	$agency="";
	$rol="";
	$nickname="";
	$name="";
	$lastname="";
	$lastnameOp="";
    $email="";
    $birthdate="";
    $gender="";
    $avatar="";
    $phoneNumber="";
    $photoUrl="";

	if( isset($_GET["id"]) ) {

		$userID = $_GET["id"];

		$getUserData = $conn->prepare("SELECT US.id, US.nickname, US.name, US.lastName, US.lastNameOp, US.email, US.birthdate, US.gender, US.avatar, US.phoneNumber, US.photoUrl, PF.id AS idRol, PF.name AS rol, USAG.id AS idAgency, USAG.nickname AS Agency FROM user AS US LEFT JOIN employee AS EM ON EM.id = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id LEFT JOIN agency AS AG ON AG.idUser = AGEM.idAgency INNER JOIN profile AS PF ON PF.id = EM.idProfile INNER JOIN user AS USAG ON USAG.id = AG.idUser WHERE US.id = ? AND US.active = 1;");
        $getUserData->bind_param('i', $userID);

		if($getUserData->execute()) {
			$getUserData->store_result();
			$getUserData->bind_result($id, $nickname, $name, $lastname, $lastnameOp, $email, $birthdate, $gender, $avatar, $phoneNumber, $photoUrl, $idRol, $rol, $idAgency, $agency);

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
                $user["phone"] = $phoneNumber;
                $user["photoUrl"] = $photoUrl;
                $user["idRol"] = $idRol;
                $user["rol"] = $rol;
                $user["idAgency"] = $idAgency;
                $user["agency"] = $agency;
			} else {
				printf("Comment statement error: %s\n", $getUserData->error);
			}
		}

		$agencies = array();
		$returnData = array();

        $getAdmins = "SELECT US.id, US.nickname FROM user AS US LEFT JOIN agency AS AG ON AG.idUser = US.id;";
		$result = $conn->query($getAdmins);
			
        while( $row = $result->fetch_array() ) {
          $agencies['id'] = $row[0];
          $agencies['agency'] = $row[1];
          $returnData[]=$agencies;
        }
        $result = null;

        $roles = array();

        $getRoles = "SELECT id, type FROM rol WHERE active = 1;";
        $result = $conn->query($getRoles);

	} else if ( isset($_POST["id"]) ) {
		
		$user = array();

        $id;
        $agency="";
        $rol="";
        $nickname="";
        $name="";
        $lastname="";
        $lastnameOp="";
        $email="";
        $phoneNumber="";
        $birthdate="";
        $gender="";
        $avatar="";
        $photoUrl="";
        $status;

		if( isset($_POST['id']) && isset($_POST['nickname']) && isset($_POST['name']) && isset($_POST['lastName']) && isset($_POST['email']) ) {

            $id = $_POST['id'];
            $agency = $_POST['agency'];
            $rol = $_POST['rol'];
            $nickname = $_POST['nickname'];
            $name = $_POST['name'];
            $lastName = $_POST['lastName'];
            $lastNameOp = $_POST['lastNameOp'];
            $email = $_POST['email'];
            $phoneNumber = $_POST['phoneNumber'];
            $birthdate = $_POST['birthdate'];
            $gender = $_POST['gender'];
            $status = $_POST['status'];

            $fileName=$_FILES["avatar"]["name"];

            $x = 6; // Amount of digits
            $min = pow(2,$x);
            $max = pow(8,($x+1)-1);
            $value = rand($min, $max);

            $file_name = $value.'-'.str_replace("", "_", $file_name);

            $ext = $_FILES["avatar"]["type"];
            $error = $_FILES["avatar"]["error"];
            $upload = $_FILES["avatar"]["tmp_name"];
            $size = $_FILES["avatar"]["size"];

            move_uploaded_file($upload, $dir.$fileName);

            /*$updateUser = $conn->prepare('UPDATE user SET nickname = "'. $nickname .'", name = "'. $name .'", lastName = "'. $lastName .'",
            lastNameOp = "'. $lastNameOp .'", avatar = "'. $dir.$fileName .'", email = "'. $email .'", phoneNumber = "'. $phoneNumber .'",
            gender = "'. $gender .'", active = "'. $status .'" WHERE id=?;');*/

            $updateUser = $conn->prepare('UPDATE user SET nickname = "'. $nickname .'", name = "'. $name .'", lastName = "'. $lastName .'", 
                lastNameOp = "'. $lastNameOp .'", email = "'. $email .'", birthdate = "'. $birthdate .'", gender = "'. $gender .'", avatar = "'. $dir.$fileName .'",
                phoneNumber = "'. $phoneNumber .'", active = "'. $status .'", updated_at = NOW(), photoUrl = "'. $dir.$fileName .'", photoName = "'. $fileName .'" WHERE id = ?');
            $updateUser->bind_param('i', $id);

            if($updateUser->execute()) {
                $stmnAgency_Employee = 'INSERT INTO agency_employee(idAgency, idemployee, created_at, updated_at) VALUES("'. $agency .'", "'. $id .'", NOW(), NOW());';
                $conn->query($stmnAgency_Employee);
                movePage(200, "user.php?id=".$id);
            }
		}
	}
?>
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">Usuario</header>
			<div class="panel-body">
				<form class="form-horizontal tasi-form" method="POST" action="<?= $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
					<fieldset disabled>
						<div class="form-group">
							<label class="col-sm-2 col-sm-2 control-label">ID</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="id" value="<?= $user["id"]; ?>">
							</div>
						</div>
					</fieldset>
                    <div class="form-group"></div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Avatar</label>
                        <div class="col-sm-10">
                            <img src="<?= $user["avatar"]; ?>" width="240" height="240">
                            <br/><br/>
                            <input type="file" name="avatar" class="btn btn-round btn-danger" value="Nueva imagen">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Agencia</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b-10" name="agency">
                                <?php
                                    echo "<option value=". $user["idAgency"] .">". $user['agency'] ."</option>";
                                    foreach( $returnData as $key ) {
                                        echo "<option value=". $key['id'] .">". $key['agency'] ."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Rol</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b-10" name="rol">
                                <?php
                                    echo "<option value=". $user["idRol"] .">". $user["rol"] ."</option>";
                                    while( $row = $result->fetch_array() ) {
                                        if( $row[0] ==  $user["idRol"] ) {
                                            echo "<option value=". $row[0] .">". $row[1] ."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Nickname</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nickname" value="<?= $user["nickname"]; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Nombre</label>
                            <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" value="<?= $user["name"]; ?>">
                            </div>
                    </div>
                    <div class="form-group">
                            <label class="col-sm-2 col-sm-2 control-label">Apellido Paterno</label>
                            <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lastName" value="<?= $user["lastName"]; ?>">
                            </div>
                            <div class="form-group"></div>
                            <label class="col-sm-2 col-sm-2 control-label">Apellido Materno</label>
                            <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lastNameOp" value="<?= $user["lastNameOp"]; ?>">
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="email" value="<?= $user["email"]; ?>">
                        </div>
                        <div class="form-group"></div>
                        <label class="col-sm-2 col-sm-2 control-label">N&uacute;mero Telef&oacute;nico</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="phone" value="<?= $user["phone"]; ?>">
                        </div>
                        <div class="form-group"></div>
                        <label class="col-sm-2 col-sm-2 control-label">Género</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="gender" value="<?= $user["gender"]; ?>">
                        </div>
                        <div class="form-group"></div>
                        <label class="col-sm-2 col-sm-2 control-label">Fecha de Nacimiento</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="birthdate" value="<?= $user["birthdate"]; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">Estado</label>
                        <div class="col-sm-10">
                            <select class="form-control m-b-10" name="status">
                                <option value="1">activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <!--<label class="col-sm-2 col-sm-2 control-label"></label>
                        <div class="col-sm-10">&nbsp;</div>-->
                        <div class="col-sm-12">
                            <div class="col-sm-8">&nbsp;</div>
                            <div class="col-sm-4"><input type="submit" id="addField" class="btn btn-round btn-info" value="Editar"></div>
                        </div>
                    </div>
				</form>
			</div>
		</section>
	</div>
</div>

<?php include("footer.php") ?>

<!--form validation-->
<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
