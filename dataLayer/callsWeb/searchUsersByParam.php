<?php include_once "../DAO.php";
    include_once "../libs/utils.php";

    session_start();

    $DB = new DAO();
    $conn = $DB->getConnect();

		$username; 
		$data = [];

		$users = [];
		$returnData = [];

		$returnAdmins = [];
		$returnAgencies = [];
		$returnEmployees = [];

		if( isset($_POST['value']) ) {
			$username = $_POST['value'];
			$username = base64_decode($username);

			$getUserNickname = $conn->prepare("SELECT `user`.`nickname`, `user`.`email`, `rol`.`type` FROM `user` LEFT JOIN `user_rol` ON `user_rol`.`idUser` = `user`.`id` LEFT JOIN `rol` ON `rol`.`id` = `user_rol`.`idRol` AND `user_rol`.`idUser` = `user`.`id` WHERE `user`.`nickname` = ? AND `user`.`active` = 1;");
			$getUserNickname->bind_param('s', $username);

			if( $getUserNickname->execute() ) {
				$getUserNickname->store_result();
				$getUserNickname->bind_result($nickname, $email, $rol);

				if( $getUserNickname->fetch() ) {

					if( isset($_POST['pivot']) ) {
						$searchFor = $_POST['pivot'];
                        //Busqueda de usuario,
						searchUsers($conn, $searchFor, $nickname, $rol);
					}

				} else {
					printf("Comment statement error: %s\n", $getUserInfo->error);
				}
			}
		}
    
    function searchUsers($conection, $searchParam, $username, $role) {
      
				$response = [];
			
				//$response["connection"] = $conection;
				$response["searchParam"] = $searchParam;
				$response["username"] = $username;
				$response["role"] = $role;
			
				//echo json_encode($response);
			
        if( isset($conection) && isset($searchParam) && isset($username) ) {
          
           if( $role == "SuperAdmin" ) {
             $getAdmins = $conection->prepare("SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, PF.name FROM user AS US LEFT JOIN employee AS EM ON EM.id = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id LEFT JOIN agency AS AG ON AG.idUser = AGEM.idAgency INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.nickname LIKE ? OR US.name LIKE ? OR US.email LIKE ? OR PF.name LIKE ?;");
             $searchParam = '%'.$searchParam.'%';
						 $getAdmins->bind_param('ssss', $searchParam, $searchParam, $searchParam, $searchParam);
						 	
              if( $getAdmins->execute() ) {
                  $getAdmins->store_result();
                  $getAdmins->bind_result($id, $nickname, $name, $lastName, $email, $avatar, $phoneNumber, $profile);
                   while( $getAdmins->fetch() ) {
                      $users['id'] = $id;
                      $users['nickname'] = $nickname;
                      $users['name'] = $name;
                      $users['lastName'] = $lastName;
                      $users['email'] = $email;
                      $users['avatar'] = $avatar;
                      $users['phoneNumber'] = $phoneNumber;	
                      $users['profile'] = $profile;

                      $returnAdmins[]=$users;
                   }
                } else {
									$returnAdmins["admins"]=0;
								}
             
                $users=null;
             
                $getAgencies = $conection->prepare("SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, AG.tipo FROM agency AS AG LEFT JOIN user AS US ON US.id = AG.idUser WHERE US.nickname LIKE ? OR US.name LIKE ? OR US.email LIKE ? OR AG.tipo LIKE ?;");
						 		$searchParam = '%'.$searchParam.'%';
						 		$getAgencies->bind_param('ssss', $searchParam, $searchParam, $searchParam, $searchParam);
             
								if( $getAgencies->execute() ) {
									$getAgencies->store_result();
									$getAgencies->bind_result($id, $nickname, $name, $lastName, $email, $avatar, $phoneNumber, $profile);
									while( $getAgencies->fetch() ) {
										$users['id'] = $id;
										$users['nickname'] = $nickname;
										$users['name'] = $name;
										$users['lastName'] = $lastName;
										$users['email'] = $email;
										$users['avatar'] = $avatar;
										$users['phoneNumber'] = $phoneNumber;	
										$users['profile'] = $profile;

										$returnAgencies[]=$users;
									}
								} else {
									$returnAgencies["agencies"]=0;
								}
                
                 $users=null;

                 $dropTable = "DROP TABLE employeesAssigned";
                 $result = $conection->query($dropTable);
             
                 $crateTempEmployeesTable = "CREATE TEMPORARY TABLE IF NOT EXISTS employeesAssigned AS (SELECT US.id AS id, US.active AS activeEmployee, USAG.nickname AS agency, USAG.active AS activeAgency, US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName, US.email AS email, US.phoneNumber as phone, PF.name as profile 
                              FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
                              LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
                              INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency ORDER BY AG.id);";
						 		 $conection->query($crateTempEmployeesTable);
						 
								 $getEmployeesFinal = $conection->prepare("SELECT id, activeEmployee, agency, activeAgency, employee, employeeName, employeeLastName, email, phone, profile FROM employeesAssigned WHERE agency LIKE ? OR employee LIKE ? OR employeeName LIKE ? OR employeeLastName LIKE ? OR email LIKE ? OR profile LIKE ? ORDER BY id;");
								 $searchParam = '%'.$searchParam.'%';
						 		 $getEmployeesFinal->bind_param('ssssss', $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
						 
						 		 if( $getEmployeesFinal->execute() ) {
                    $getEmployeesFinal->store_result();
                    $getEmployeesFinal->bind_result($id, $activeEmployee, $agency, $activeAgency, $employee, $employeeName, $employeeLastName, $email, $phone, $profile);

                     while( $getEmployeesFinal->fetch() ) {
                        /*$users['id'] = $row[0];
												$users['agency'] = $row[1];
												$users['employee'] = $row[2];
												$users['employeeName'] = $row[3];
												$users['employeeLastName'] = $row[4];
												$users['email'] = $row[5];
												$users['phone'] = $row[6];
												$users['profile'] = $row[7];*/
											 
											  $users['id'] = $id;
												$users['activeEmployee'] = $activeEmployee;
												$users['agency'] = $agency;
												$users['activeAgency'] = $activeAgency;
												$users['employee'] = $employee;
												$users['employeeName'] = $employeeName;
												$users['employeeLastName'] = $employeeLastName;
												$users['email'] = $email;
											  $users['phone'] = $phone;
												$users['profile'] = $profile;

												$returnEmployees[]=$users;
                     }
                 } else {
									$returnEmployees["employees"]=0;
								 }
                
                 $users=null;
              
           } else if ( $role == "Admin" ) {
              
             
             
           } else if ( $role == "Agency" ) {
             
             
             
           } else if ( $role == "Employee" ) {
             
             
             
           }
          
        }
			
        /*$getAdmins = "SELECT US.id, US.nickname, US.name, US.lastName, US.email, US.avatar, US.phoneNumber, US.avatar, PF.name 
            FROM user AS US LEFT JOIN employee AS EM ON EM.id = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
            LEFT JOIN agency AS AG ON AG.idUser = AGEM.idAgency INNER JOIN profile AS PF ON PF.id = EM.idProfile 
            WHERE US.nickname LIKE ?;";

        $result = $conn->query($getAdmins);

        while( $row = $result->fetch_array() ) {
          $users['id'] = $row[0];
          $users['nickname'] = $row[1];
          $users['name'] = $row[2];
          $users['lastName'] = $row[3];
          $users['email'] = $row[4];
          $users['avatar'] = $row[5];
          $users['phoneNumber'] = $row[6];	
          $users['photoUrl'] = $row[7];
          $users['type'] = $row[8];

          $returnAdmins[]=$users;
        }

        $users=null;

        $getAgenciesResult = "SELECT US.id, US.nickname, US.name, US.lastName, US.lastNameOP, US.email, US.avatar, US.phoneNumber, US.created_at, AG.tipo, AG.plazo FROM agency AS AG LEFT JOIN user AS US ON US.id = AG.idUser WHERE US.active=1;";
        $resultAgenciesData = $conn->query($getAgenciesResult);

        while( $row = $resultAgenciesData->fetch_array() ) {
          $users['id'] = $row[0];
          $users['nickname'] = $row[1];
          $users['name'] = $row[2];
          $users['lastName'] = $row[3];
          $users['lastNameOP'] = $row[4];
          $users['email'] = $row[5];
          $users['avatar'] = $row[6];
          $users['phoneNumber'] = $row[7];
          $users['created_at'] = $row[8];
          $users['tipo'] = $row[9];
          $users['plazo'] = $row[10];

          $returnAgencies[]=$users;
        }

        $users=null;

        $dropTable = "DROP TABLE employeesAssigned";
        $result = $conn->query($dropTable);

        $crateTempEmployeesTables = "CREATE TEMPORARY TABLE IF NOT EXISTS employeesAssigned AS (SELECT US.id AS id, USAG.nickname AS agency, 
                              US.nickname AS employee, US.name AS employeeName, US.lastName AS employeeLastName, US.email AS email, US.phoneNumber as phone, PF.name as profile 
                      FROM user AS US LEFT JOIN employee AS EM ON EM.idUser = US.id LEFT JOIN agency_employee AS AGEM ON AGEM.idemployee = EM.id 
                      LEFT JOIN agency AS AG ON AG.id = AGEM.idAgency LEFT JOIN user AS USAG ON USAG.id = AG.idUser
                      INNER JOIN profile AS PF ON PF.id = EM.idProfile WHERE US.id <> AGEM.idAgency ORDER BY AG.id);";
        $result = $conn->query($crateTempEmployeesTables);

        $getEmployeesFinal = "SELECT id, agency, employee, employeeName, employeeLastName, email, phone, profile FROM employeesAssigned ORDER BY id;";
        $resultEmployees = $conn->query($getEmployeesFinal);

        while( $row = $resultEmployees->fetch_array() ) {
          $users['id'] = $row[0];
          $users['agency'] = $row[1];
          $users['employee'] = $row[2];
          $users['employeeName'] = $row[3];
          $users['employeeLastName'] = $row[4];
          $users['email'] = $row[5];
          $users['phone'] = $row[6];
          $users['profile'] = $row[7];

          $returnEmployees[]=$users;
        }

        $users=null;*/

				if( isset($returnAdmins) ) {
					$returnData["Admins"]=$returnAdmins;
				}
				if( isset($returnAgencies) ) {
					$returnData["Agencies"]=$returnAgencies;
				}
				if( isset($returnEmployees) ) {
					$returnData["Employees"]=$returnEmployees;
				}
				if( isset($returnData) ) {
					echo json_encode($returnData);
				} else {
					$status["error"] = "Usuarios no encontrados";
					echo json_encode($status);
				}
        /*echo "<pre>";
          print_r($returnData);
        echo "</pre>";
        exit;*/
      }
?>