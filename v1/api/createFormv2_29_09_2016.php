<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
/**
 * Created by PhpStorm.
 * User: RJUAREZR
 * Date: 03/08/2016
 * Time: 10:38 AM
 */
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/



/**CONSTANTES A UTILIZAR **/
$STATUS_EXITO = "OK";
$STATUS_FALLO = "ERROR";
$CODE_EXITOSO = "200";
$CODE_FALLO = "500";

$directorioImagenesSubida = "../../uploads/";
$extensionPng = ".png";
$tipoDeMIME = "image/png";

/***CONSTANTES TIPOS DE FORMULARIO****/

$FORMULARIO_TIPO_CENSO = 0;
$FORMULARIO_TIPO_VENTA = 1;
$FORMULARIO_TIPO_PLOMERO = 2;
$FORMULARIO_TIPO_INSTALACION = 3;
$FORMULARIO_TIPO_SEGUNDA_VENTA = 4;


if (isset($_POST["token"]) && isset($_POST["solicitud"])) {
	$DB = new DAO();
	$conn = $DB->getConnect();

	$tokenBase64 = $_POST["token"];
	$solicitudBase64 = $_POST["solicitud"];
	$token = base64_decode($tokenBase64);

	list($idUsuario, $nickname, $email, $profile, $nicknameAgency, $rol, $value, $idDevice) = explode("&", $token);
	/**PRIMERO CORROBORAMOS QUE EL ID EN EL TOKEN QUE ENVIA EL USUARIO ESTE REGISTRADO EN EL SISTEMA****/
	$responseToken = compararUsuarioToken($conn, $idUsuario);

	/**SI FUE EXITOSO LA BUSQUEDA DEL ID DEL TOKEN CONTINUAMOS CON DESCIFRAR LA SOLICITUD PARA HACERLA UN OBJETO LEEIBLE**/
	if ($responseToken["code"] == $CODE_EXITOSO) {
		$solicitudJson = base64_decode($solicitudBase64);
		$solicitud = (array)json_decode($solicitudJson);

		grabarLog($solicitudJson);

	$idSolicitudMovil = $solicitud["id"];
		$idAsignacion = $solicitud["idAsignacion"];
		$fechaDeAsignacion = $solicitud["fecha_asignacion"];
		$fechaAgendada = $solicitud["fecha_agendada"];
		$historico = $solicitud["historico"];
		$estatus = $solicitud["estatus"];
		$estatusCompletoDesdeMovil = $solicitud["estatus_concluido"];

		$nombreDelCliente = $solicitud["nombre_cliente"];
		$direcciones = $solicitud["dirección"];
		$observaciones = $solicitud["observaciones"];
		$tipoDePerfil = $solicitud["tipo_perfil"];
		$tipoDeEstatus = $solicitud["tipo_estatus"];
		$tipoDeFormulario = $solicitud["idTypeForm"];
		$idFormulario = $solicitud["idFormulario"];
		$latitud = (double)$solicitud["latitud"];
                
                
                
		if (!is_numeric($latitud)) {
			$latitud = 0.00;
		}
                
		$longitud = (double)$solicitud["longitud"];
		if (!is_numeric($longitud)) {
			$longitud = 0.00;
		}

		/**FORMULARIO QUE ES EL ***/
		$formularioArray = (array)$solicitud["formulario"];

		/**CALLEJERO QUE ES EL ***/
		$callejeroArray = (array)$solicitud["callejero"];


		/***SI EL CALLEJERO VIENE COMPLETO ENTONCES OBTENEMOS SU INFORMACION***/
		if (isset($callejeroArray) && $callejeroArray != null) {
			$idMunicipioCallejero = $callejeroArray["municipio"];
			$idSolicitudCallejero = $callejeroArray["idSolicitud"];
			$coloniaCallejero = $callejeroArray["colonia"];
			$streetCallejero = $callejeroArray["calle"];
			$betweenStreetsCallejero = $callejeroArray["entrecalles"];
			$innerNumberCallejero = $callejeroArray["numero"];
			if ($innerNumberCallejero == NULL) {
				$innerNumberCallejero = 0;
			}
			$nseCallejero = $callejeroArray["nse"];
			$newStreetCallejero = $callejeroArray["direccionNueva"];
			$streetsCallejero = $callejeroArray["entrecallesNueva"];
			$coloniaTypeCallejero = $callejeroArray["exclusividad"];
			$marketTypeCallejero = $callejeroArray["tipoMercado"];
			$latitudeCallejero = (double)$callejeroArray["latitud"];
			if (!is_numeric($latitudeCallejero)) {
				$latitudeCallejero = 0.00;
			}
			$longitudeCallejero = (double)$callejeroArray["longitud"];
			if (!is_numeric($longitudeCallejero)) {
				$longitudeCallejero = 0.00;
			}
		}

		//grabarLog(json_encode($solicitud));
		/**TOMAMOS EL ESTATUS QUE NOS ENVIA EL MOVIL, PARA TRANSFORMARLO EN UN CODIGO DE ESTATUS A INSERTAR
		 * EN LA BASE DE DATOS DEPENDIENDO PARA EL WORKFLOW***/
		$estatusWorkFlow = validarEstatusWorkFlow($estatusCompletoDesdeMovil);
		$tipoDeReporte = validarReportTypeDependiendoDelTipoDeFormulario($tipoDeFormulario);

		/**AHORA DEPENDIENDO DEL TIPO DE FORMULARIO ES COMO EJECUTAMOS LA RUTINA DE CADA UNO***/
		if ($tipoDeFormulario == $FORMULARIO_TIPO_CENSO) {

			/**OBTENEMOS LOS VALORES DEL FORMULARIO DE CENSO EN CASO DE QUE VENGA***/
			$lote_type = $formularioArray["tipo_lote"];
			$requestNum = $formularioArray["id"];
			$homeLevel = $formularioArray["nivel_vivienda"];
			$socialLevel = $formularioArray["nivel_socioeconomico"];
			$area = $formularioArray["giro"];
			$acometida = $formularioArray["acometida"];
			$arrayFotografias = $formularioArray["fotos"];
			$comments = $formularioArray["observaciones"];
			$color = $formularioArray["color_tapon"];
			$measurer = $formularioArray["medidor"];
			$measurerType = $formularioArray["tipo_medidor"];
			$measurerSerialNumber = $formularioArray["num_serie"];
			$measurerBrand = $formularioArray["marca"];
			$niple = $formularioArray["niple_decorte"];

			$responseArrayInsertarFormularioTipoCenso=array();
			$responseArrayInsertarFormularioTipoCenso = insertarFormularioTipoCenso($conn,
				$idUsuario, $idAsignacion, $estatusWorkFlow, $coloniaCallejero, $streetCallejero,
				$betweenStreetsCallejero, $innerNumberCallejero, $newStreetCallejero, $nseCallejero,$coloniaTypeCallejero,
				$marketTypeCallejero, 1, 1, $idMunicipioCallejero, 0,
				$tipoDeReporte, $idUsuario, $idFormulario, $latitud,
				$longitudeCallejero,
				$lote_type, $homeLevel, $socialLevel, $area, $acometida,
				$comments, $color, $measurer, $measurerBrand, $measurerType,
				$measurerSerialNumber, $niple
			);

			if ($responseArrayInsertarFormularioTipoCenso["code"] == $CODE_EXITOSO) {
				/**SACAMOS EL ID REPORTE QUE GENERO LA CONSULTA**/
				$idFormCensusGenerado= $responseArrayInsertarFormularioTipoCenso["idFormCensusGenerado"];
				$ReporteGenerado=$responseArrayInsertarFormularioTipoCenso["ReporteGenerado"];
				/***AHORA DEBEMOS INSERTAR LA RELACION DE LAS FOTOGRAFIAS CON EL CENSO ESTO
				 * RECORRIENDO EL ARREGLO DE FOTOGRAFIAS***/
				$DB = new DAO();
				$connNueva = $DB->getConnect();
				$stmtInsertFirst="INSERT INTO `reportHistory`(`idReport`,`idFormSell`,`idReportType`,`idUserAssigned`,`idStatusReport`,`idSolicitud`,`idFormulario`,`updated_at`,`created_at`)VALUES(?,?,?,?,?,?,?,NOW(),NOW());";
	            if ($insertHistory = $connNueva->prepare($stmtInsertFirst)) {
	            	$censo=1;
	            	$insertHistory->bind_param("iiiiiii",$ReporteGenerado, $idFormCensusGenerado, $censo, $idUsuario, $estatusWorkFlow,$idSolicitudMovil,$inIdFormulario);
	                $insertHistory->execute();
	            }else{
	            	error_log($connNueva->error);
	            }
	            $connNueva->close();
				$sizeFotografias = sizeof($arrayFotografias);
				$i = 0;
				for ($i = 0; $i < $sizeFotografias; $i++) {

					$fotografia = $arrayFotografias[$i];
					$responseFotografia = insertarRelacionFotografiasDelCenso($conn, $idFormCensusGenerado, $fotografia,$i,$sizeFotografias);
				}
				/**SI LAS FOTOGRAFIAS SE INSERTARON CON EXITO ENTONCES IMPRIMIMOS LA RESPUESTA DE LA INSERCION
				 * SATISFACTORIA DEL CENSO***/
				if ($responseFotografia["code"] == $CODE_EXITOSO) {
					echo json_encode($responseArrayInsertarFormularioTipoCenso);
				} else {
					/**SI ALGO OCURRIO MAL, ENTONCES DE CUALQUIER MANERA REALIZAMOS LA MUESTRA
					 * DE LA INSERCION DEL FORMULARIO TIPO CENSO**/
					echo json_encode($responseArrayInsertarFormularioTipoCenso);
				}
			} else {
				echo json_encode($responseArrayInsertarFormularioTipoCenso);
			}
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_VENTA) {

			/**OBTENEMOS LOS VALORES DEL FORMULARIO DE VENTA EN CASO DE QUE VENGA***/
			//$lote_type = $formularioArray["fecha_solicitud"];
			$inUninteresting = $formularioArray["esta_interesado"];
			$inCommentsUninteresting = $formularioArray["motivo_desinteres"];
			$inComments = $formularioArray["comentarios"];
			$inOwner = $formularioArray["titular_encontrado"];
			$consecutive = $formularioArray["consecutivo"];
			$inClientName = $formularioArray["nombre"];
			$inClientLastName1 = $formularioArray["apellido_paterno"];
			$inClientLastName2 = $formularioArray["apellido_materno"];
			$inPayment = $formularioArray["forma_pago"];
			$inFinancialService = $formularioArray["financiera"];
			$inRequestNumber = $formularioArray["num_solicitud"];
			$arrayFotografias = $formularioArray["fotos"];
			$inAgreementNumber = $solicitud["num_contrato"];
		   // $niple = $formularioArray["id"];

			if($inAgreementNumber==null){
				$inAgreementNumber="-";
			}

			if($inUninteresting==false){
				$inUninteresting='false';
			}else if($inUninteresting==true){
				$inUninteresting='true';
			}
			/**CUANDO TENEMOS EL MOTIVO DE DESINTERES EN TRUE, DEBEMOS TOMAR LOS COMENTARIOS
			 ***/
			$responseArrayInsertarFormularioTipoVentaUno=array();
			$responseArrayInsertarFormularioTipoVentaUno = insertarFormularioTipoVentaUno(
                                $conn,
				$idUsuario,
                                $idAsignacion,
                                $estatusWorkFlow,
                                $tipoDeReporte,
				$inAgreementNumber, 
                                $inClientName, 
                                $inClientLastName1, 
                                $inClientLastName2, 
                                $coloniaCallejero,
				$streetCallejero,
                                $betweenStreetsCallejero,
                                
                                $innerNumberCallejero, 
                                $innerNumberCallejero, 
                                $nseCallejero,
				$newStreetCallejero,
                                $streetsCallejero,
                                $coloniaTypeCallejero,
                                $marketTypeCallejero,
                                1,
				1, 
                                $idMunicipioCallejero, 
                                0,
                                
                                $idFormulario,
                                $latitud,
				$longitud,
				1, 
                                $inUninteresting,
                                $inCommentsUninteresting, 
                                $inComments,
                                $inOwner,
                                
                                $inClientName,
				$inClientLastName1, 
                                $inClientLastName2, 
                                $inPayment,
                                $inFinancialService, 
                                $inRequestNumber,
				1,
                                $idSolicitudMovil
			);

			if ($responseArrayInsertarFormularioTipoVentaUno["code"] == $CODE_EXITOSO) {
                            
                            
                            
				/**SACAMOS EL ID REPORTE QUE GENERO LA CONSULTA**/
				$idFormSellsGenerado= $responseArrayInsertarFormularioTipoVentaUno["idFormSellsGenerado"];
				/***AHORA DEBEMOS INSERTAR LA RELACION DE LAS FOTOGRAFIAS CON LA VENTA ESTO
				 * RECORRIENDO EL ARREGLO DE FOTOGRAFIAS***/
				$sizeFotografias = sizeof($arrayFotografias);
				$i = 0;
				for ($i = 0; $i < $sizeFotografias; $i++) {
					$fotografia = $arrayFotografias[$i];
					$responseFotografia = insertarRelacionFotografiasDeLaVenta($conn, $idFormSellsGenerado, $fotografia,$i,$sizeFotografias);
				}

				/**SI LAS FOTOGRAFIAS SE INSERTARON CON EXITO ENTONCES IMPRIMIMOS LA RESPUESTA DE LA INSERCION
				 * SATISFACTORIA DEL CENSO***/
				if ($responseFotografia["code"] == $CODE_EXITOSO) {
					echo json_encode($responseArrayInsertarFormularioTipoVentaUno);                    
				} else {

					/**SI ALGO OCURRIO MAL, ENTONCES DE CUALQUIER MANERA REALIZAMOS LA MUESTRA
					 * DE LA INSERCION DEL FORMULARIO TIPO CENSO**/
					echo json_encode($responseArrayInsertarFormularioTipoVentaUno);
				}
			} else {
				echo json_encode($responseArrayInsertarFormularioTipoVentaUno);
			}
			error_log(json_encode($responseArrayInsertarFormularioTipoVentaUno));
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_PLOMERO) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			$name = $formularioArray["nombre"];
			$lastName = $formularioArray["apellido_paterno"];
			$request = $formularioArray["num_solicitud"];
			$documentNumber = $formularioArray["num_solicitud"];
			if ($ri == null || $ri == 0 || $ri == "") {
				$ri = 0;
			} else {
				$ri = $formularioArray["ri"];
			}
			$tapon = $formularioArray["color_tapon"];
			if ($tapon == true) {
				$tapon = 1;
			} else {
				$tapon = 0;
			}
			$dictamen = $formularioArray["num_dictamen"];
			$preassureFalls = $formularioArray["calculos"];
			$diagrama = $formularioArray["diagrama"];
			$comments = $formularioArray["observaciones"];
			$newPipe = $formularioArray["serequiere_tuberia"];
			if ($newPipe == true) {
				$newPipe = 1;
			} else {
				$newPipe = 0;
			}
			$pipesCount = $formularioArray["num_tomas"];
			$ph = $formularioArray["resultado_ph"];
			$fotos = $formularioArray["fotos"];
			$diagram = $formularioArray["diagrama"];
			$latitude = $solicitud["latitud"];
			$longitude = $solicitud["longitud"];
			$reportID=intval($solicitud['idAsignacion']);
			$id=intval($solicitud['idUsuario']);
			$IDSTatus=intval($solicitud['id_estatus']);
			$consecutivo=intval($solicitud['consecutivo']);
			$sizeFotografias = sizeof($arrayFotografias);
			$i = 0;
			$createPlumberFormSQL ="INSERT INTO `form_plumber`(`consecutive`,`name`, `lastName`, `request`, `tapon`, `documentNumber`, `diagram`, `comments`, `newPipe`, `ph`, `pipesCount`, `idStatus`,`latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?, NOW(), NOW(), 1);";
			if ($stmt = $conn->prepare($createPlumberFormSQL)) {
				$stmt->bind_param("isssisssiiiddd", $consecutivo, $name, $lastName, $request, $tapon, $dictamen, $diagram, $comments, $newPipe, $ph, $pipesCount, $IDSTatus,$latitude, $longitude);
				if ($stmt->execute()) {
					$idPlumberForm = $stmt->insert_id;//id del formulario plomero
					if(count($fotos) > 0){
						foreach ($fotos as $imgID) {
							if ($imgID != null && $imgID != 'null' && $imgID != "" && $imgID && 'undefined') {
								$insertPlumberPipesPhoto ="INSERT INTO `form_plumber_multimedia`(`idFormPlumber`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());";
								if ($stmtFotos = $conn->prepare($insertPlumberPipesPhoto)) {
									$stmtFotos->bind_param("ii", $idPlumberForm, $imgID);
									if (!$stmtFotos->execute()) {
										$response["status"] = "ERROR";
										$response["code"] = "500";
										$response["response"] = "Falla en el enlace de la Imagen de Tuberias con el Reporte de Plomero: " . $conn->error;
										echo json_encode($response);
									}
								}else{
									$res='Error en insert de fotos Plomero '.$conn->error;
								}   
							}
						}
					}
					if (count($preassureFalls) > 0) {
						foreach ($preassureFalls as $key) {
							$data = (array)$key;
							$createPlumberDetails = "INSERT INTO `form_plumber_details`(`path`, `distance`, `pipe`, `fall`, `idFormPlumber`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
							if ($stmtDetails = $conn->prepare($createPlumberDetails)) {
								$stmtDetails->bind_param("ssssi", $data["tramo"], $data["distancia"], $data["tuberia"], $data["porcentaje"], $idPlumberForm);
								if (!$stmtDetails->execute()) {
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Falla en el registro de material: " . $conn->error;
									echo json_encode($response);
								}   
							}else{
								$res='Error en insert de detalles Plomero '.$conn->error;
							}
						}
					}
					$reportEmployeeForm = "INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
					if ($reportEmployeeFormSTMT = $conn->prepare($reportEmployeeForm)) {
						$reportEmployeeFormSTMT->bind_param("iii", $reportID, $id, $idPlumberForm);
						$reportEmployeeFormSTMT->execute();
						$idWorkflow = 1;
						if ($statusType == "Completo") {
							$idStatus = 3;
						} else if ($statusType == "Prendiente Reagendado") {
							$idStatus = 7;
						} else {
							$idStatus = 4;
						}
					}else{
						$res='Error en insert de report_employee_form Plomero '.$conn->error;
					}
					$searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned FROM report AS RP WHERE RP.`id` = ?;");
					$searchReports->bind_param("i", $reportID);
					if ($searchReports->execute()) {
						$searchReports->store_result();
						$searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned);
						$searchReports->fetch();
						$insertStatusReport = "INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);";
						if ($insertStatusReportSTMT = $conn->prepare($insertStatusReport)) {
							$insertStatusReportSTMT->bind_param("iii", $idWorkflow, $idStatus, $reportID);
							$insertStatusReportSTMT->execute();
							$response["status"] = "OK";
							$response["code"] = "200";
							$response["response"] = "Creacion de Reporte para Plomero de Manera Exitosa.";
							$response["reportId"] = $idPlumberForm;
							echo json_encode($response);
						}

						$idStatusPH = 31;
						$updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
						$updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $reportID);
						$updateEstatusContrato->execute();
						$conn->close;
						$idStatus=3;
						$updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
						$updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);

						if($updateReportAssignedStatus->execute()) {

						}
					}
				}
			}else{
				$conn->error;
			}
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_SEGUNDA_VENTA) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			error_log(json_encode($solicitud));
			$agencia=obteneridAgencia($formularioArray["agencia"]);//la vamos a obtener con una funcion
			$pagare=$formularioArray["pagare"];
			$contrato=$formularioArray["contrato"];
			$fecha_solicitud=date_create($formularioArray["fecha_solicitud"]);
			$fecha_solicitud= date_format($fecha_solicitud,"Y-m-d");
			$apellido_paterno=$formularioArray["apellido_paterno"];
			$apellido_materno=$formularioArray["apellido_materno"];
			$nombre=$formularioArray["nombre"];
			$rfc=$formularioArray["rfc"];
			$curp=$formularioArray["curp"];
			$correo=$formularioArray["correo"];
			$estado_civil=$formularioArray["estado_civil"];
			$sexo=$formularioArray["sexo"];
			$num_identificacion=$formularioArray["num_identificacion"];
			$tipo_identificacion=$formularioArray["tipo_identificacion"];
			$fecha_nacimiento=date_create($formularioArray["fecha_nacimiento"]);
			$fecha_nacimiento= date_format($fecha_nacimiento,"Y-m-d");
			$pais_nacimiento=$formularioArray["pais_nacimiento"];
			$estado=$formularioArray["estado"];
			$municipio=$formularioArray["municipio"];
			$colonia=$formularioArray["colonia"];
			$calle=$formularioArray["calle"];
			$vive_encasa=$formularioArray["vive_encasa"];
			$tel_casa=$formularioArray["tel_casa"];
			$tel_celular=$formularioArray["tel_celular"];
			$tipo_contrato=$formularioArray["tipo_contrato"];
			$idArt=$formularioArray["idArt"];
			$precio=ereg_replace("[^A-Za-z0-9]", "", $formularioArray["precio"]);
			$plazo=$formularioArray["plazo"];
			$mensualidad=ereg_replace("[^A-Za-z0-9]", "", $formularioArray["mensualidad"]);
			$ri=$formularioArray["ri"];
			$fecha_ri=date_create($formularioArray["fecha_ri"]);
			$fecha_ri= date_format($fecha_ri,"Y-m-d");
			$empresa=$formularioArray["empresa"];
			$direccion=$formularioArray["direccion"];
			$puesto=$formularioArray["puesto"];
			$area=$formularioArray["area"];
			$tel_empresa=$formularioArray["tel_empresa"];
			$createSecondStepSellSQL = "INSERT INTO agreement(idAgency, payment, 
															  idReport, requestDate,
															  clientlastName, clientlastName2, 
															  clientName,clientRFC, clientCURP, 
															  clientEmail, clientRelationship,
															  clientgender, clientIdNumber, 
															  identificationType,clientBirthDate,
															  clientBirthCountry,idState,idCity, 
															  idColonia, street, inHome, homeTelephone, 
															  celullarTelephone, agreementType, price,
															  idArt,agreementMonthlyPayment,agreementExpires,
															  agreementRi,agreementRiDate,clientJobEnterprise,
															  clientJobLocation, clientJobRange,
															  clientJobActivity, clientJobTelephone, 
															  latitude,longitude,created_at, updated_at) 
													VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
														   ?, ?, ?, ?, ?, ?, ?,?,?, ?, ?, ?, ?,?,?, ?, ?, ?, NOW(), NOW());";
			//cachamos errores en MySQL
			if ($createSecondStepSell = $conn->prepare($createSecondStepSellSQL)) {
				//generamos las demas acciones correspondientes
				$createSecondStepSell->bind_param("idisssssssssssssssssssssdididssssssdd", 
					$agencia, $pagare, $solicitud["idAsignacion"], 
					$fecha_solicitud, $apellido_paterno,$apellido_materno, $nombre,
					$rfc,$curp, $correo,$estado_civil, $sexo,$num_identificacion,
					$tipo_identificacion, $fecha_nacimiento,$pais_nacimiento,
					$estado, $municipio,
					$colonia,$calle,$vive_encasa,
					$tel_casa,$tel_celular,
					$tipo_contrato,$precio,$idArt, 
					$mensualidad, $plazo,
					$ri,$fecha_ri,$empresa,
					$direccion, $puesto,
					$area,$tel_empresa,
					$solicitud["latitud"], $solicitud["longitud"]);
				if ($createSecondStepSell->execute()) {
					$secondSell = $createSecondStepSell->insert_id;
					foreach ($formularioArray["referencias_array"] as $referencias) {
						$data = (array)$referencias;
						if($data["referencia"] != "" ||
						   $data["telefono"] != "" ||
						   $data["trabajo"] != "" ||
						   $data["extencion"] != ""){
							//almacenamos las referencias
							$createReferenceSQL="INSERT INTO agreement_reference(name, telephone, jobTelephone, ext, idAgreement, created_at, updated_at) VALUES(?, ?, ?, ?, ?, NOW(), NOW());";
							if ($createReference = $conn->prepare($createReferenceSQL)) {
								$createReference->bind_param("ssssi", $data["referencia"], 
																	  $data["telefono"], 
																	  $data["trabajo"], 
																	  $data["extencion"],
																	  $secondSell);
								if (!$createReference->execute()) {
									$response = null;

									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
									error_log(json_encode($response));
								}
							}else{
								$response["status"] = "ERROR";
								$response["code"] = "500";
								$response["response"] = "Error en la creación de imagen de referencia" . $createReference->error;
								error_log(json_encode($response));
							}
						}
					}
					$createAgreeReport = $conn->prepare("INSERT INTO agreement_employee_report(idAgreement, idEmployee, idReport, created_at, updated_at) VALUES(?, ?, ?, NOW(), NOW());");
					$createAgreeReport->bind_param("iii", $secondSell, $idUsuario, $solicitud["idAsignacion"]);

					if ($createAgreeReport->execute()) {
						$response = null;

						$idWorkflow = 2;
						$idStatus = 3;

						$idAgreegment = $createSecondStepSell->insert_id;

						$createAgreementStatus = $conn->prepare("INSERT INTO workflow_status_agreement(idWorkflow, idStatus, idAgreement, created_at, updated_at, active) VALUES(?, ?, ?, NOW(), NOW(), 1);");
						$createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
						$response = null;

						if ($createAgreementStatus->execute()) {
							$response["status"] = "OK";
							$response["code"] = "200";
							$response["response"] = "Contrato Creado Exitosamente!"; //.$createAgreementStatus->insert_id;
							echo json_encode($response);
						} else {
							$response["status"] = "ERROR";
							$response["code"] = "500";
							$response["response"] = "Error en la Creacion del status de Contrato: " . $conn->error;
							echo json_encode($response);
						}
					} else {
						$response["status"] = "ERROR";
						$response["code"] = "500";
						$response["response"] = "Error en la Creacion de Formulario Contrato: " . $conn->error;
						error_log(json_encode($response));
					}
				}
			}else{
				$response["status"] = "ERROR";
				$response["code"] = "500";
				$response["response"] = "Error en la Creacion de Contrato2: " . $conn->error;
				$response["post"]=$solicitud;
				error_log(json_encode($response));
			}
			
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_INSTALACION) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			$name = $formularioArray["nombre"];
			$lastName = $formularioArray["apellido_paterno"];
			$request = intval($solicitud['id']);
			$documentNumber = intval($solicitud['id']);
			$comments = $solicitud["observaciones"];
			$fotos = $formularioArray["fotos"];
			$latitude = $solicitud["latitud"];
			$longitude = $solicitud["longitud"];
			$reportID=intval($solicitud['idAsignacion']);
			$id=intval($solicitud['idUsuario']);
			$idStatus=intval($solicitud['id_estatus']);
			$consecutivo=intval($solicitud['consecutivo']);
			$anomalias=$formularioArray["catalogo_anomalias"];
			$agencyNumber=$formularioArray["num_agencia"];
			$installation=$formularioArray["se_proecede"];
			$brand=$formularioArray["marca_medidor"];
			$type=$formularioArray["tipo_medidor"];
			$serialNumber=$formularioArray["num_medidor"];
			$measurement=$formularioArray["lectura_medidor"];
			$i = 0;

			$createInstallationFormSQL ="INSERT INTO `form_installation` (`consecutive`,`name`, `lastName`, `request`, `phLabel`, `agencyPh`, `agencyNumber`, `installation`, `abnormalities`, `comments`, `brand`, `type`,`serialNumber`, `measurement`, `idStatus`, `latitude`,`longitude`,`created_at`,`updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?, NOW(), NOW(), 1);";
			if ($stmt = $conn->prepare($createInstallationFormSQL)) {
				$stmt->bind_param("isssssiissssssidd", $consecutivo, $name, $lastName, $request, $phLabel, $agencyPh, $agencyNumber, $installation, $anomalias, $comments, $brand, $type,$serialNumber, $measurement,$idStatus,$latitude,$longitude);
				if ($stmt->execute()) {
					$idInstallationForm = $stmt->insert_id;//id del formulario installation
					if (count($fotos) > 0) {
						foreach ($fotos as $imgID) {
							if ($imgID != null && $imgID != 'null' && $imgID != "" && $imgID && 'undefined') {
								$insertInstallationPipesPhoto = "INSERT INTO `form_installation_multimedia`(`idFormInstallation`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());";
								if ($stmtFotos = $conn->prepare($insertInstallationPipesPhoto)) {
									$stmtFotos->bind_param("ii", $idInstallationForm, $imgID);
									if (!$stmtFotos->execute()) {
										$response["status"] = "ERROR";
										$response["code"] = "500";
										$response["response"] = "Falla en el enlace de la Imagen con el Reporte de Instalacion: " . $conn->error;
										echo json_encode($response);
									}
								} else {
									$res = 'Error en insert de fotos Instalacion ' . $conn->error;
								}
							}
						}
					}

					$materiales = (array)$formularioArray["materiales"];

					if (count($materiales) > 0) {
						foreach ($materiales as $key) {
							$data = (array)$key;
							$createInstallationDetails = "INSERT INTO `form_installation_details` (`qty`, `idFormInstallation`,`material`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
							if ($stmtDetails = $conn->prepare($createInstallationDetails)) {
								$stmtDetails->bind_param("iis", $data["cantidad"], $idInstallationForm, $data["material"]);
								if (!$stmtDetails->execute()) {
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Falla en el registro de material: " . $conn->error;
									echo json_encode($response);
								}
							}else{
								$res='Error en insert de detalles Plomero '.$conn->error;
							}
						}
					}

					$reportEmployeeForm = "INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
					if ($reportEmployeeFormSTMT = $conn->prepare($reportEmployeeForm)) {
						$reportEmployeeFormSTMT->bind_param("iii", $reportID, $id, $idInstallationForm);
						$reportEmployeeFormSTMT->execute();
						$idWorkflow = 1;
						if ($statusType == "Completo") {
							$idStatus = 3;
						} else if ($statusType == "Prendiente Reagendado") {
							$idStatus = 7;
						} else {
							$idStatus = 4;
						}
					}else{
						$res='Error en insert de report_employee_form Installation '.$conn->error;
					}



					$searchReports = $conn->prepare("SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned FROM report AS RP WHERE RP.`id` = ?;");
					$searchReports->bind_param("i", $reportID);
					if ($searchReports->execute()) {
						$searchReports->store_result();
						$searchReports->bind_result($idReport, $employee, $creator, $reportType, $employeeAssigned);
						$searchReports->fetch();
						$insertStatusReport = "INSERT INTO `workflow_status_report`(`idWorkflow`, `idStatus`, `idReport`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, NOW(), NOW(), 1);";
						if ($insertStatusReportSTMT = $conn->prepare($insertStatusReport)) {
							$insertStatusReportSTMT->bind_param("iii", $idWorkflow, $idStatus, $reportID);
							$insertStatusReportSTMT->execute();
							$response["status"] = "OK";
							$response["code"] = "200";
							$response["response"] = "Creacion de Reporte para Instalacion de Manera Exitosa.";
							$response["reportId"] = $idPlumberForm;
							echo json_encode($response);
						}

						$idStatusIns = 51;
						$updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `estatusAsignacionInstalacion` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
						$updateEstatusContrato->bind_param("iii", $idStatusIns, $reportID);
						$updateEstatusContrato->execute();
						$conn->close;
						$idStatus=3;
						$updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
						$updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);

						if($updateReportAssignedStatus->execute()) {

						}
					}


				}



		}

			else {
				echo json_encode($responseToken);
			}
	}
}

 else {
	$response["status"] = $GLOBALS[STATUS_FALLO];
	$response["code"] = $GLOBALS[CODE_FALLO];
	$response["response"] = "DATOS INGRESADOS ERRONEAMENTE";
	echo json_encode($response);
}
}
else {

	echo "Error";
}

function obteneridAgencia($agencia)
{
	if ($agencia != '') {
		$DB = new DAO();
		$conn = $DB->getConnect();
		$queryEncontrarPlomero="SELECT b.idAgency
								FROM user as a, agency_employee as b
								WHERE 0=0 AND
								a.id=b.idemployee
								and a.nickname=?";
		$res='';
		if ($stmt = $conn->prepare($queryEncontrarPlomero)) {
			$stmt->bind_param("i", $agencia);
			if ($stmt->execute()) {
				$stmt->store_result();
				$stmt->bind_result($idAgency);
				if ($stmt->fetch()) {
					if ($idAgency != '') {
						$res=$idAgency;
					}
				}
			}
		}
		return $res;
		$conn->close();
	}
	//validamos si el empleado asignado al formulario tipoVenta es plomero si no es plomero asignamos un plomero
}
function validarEstatusWorkFlow($estatusCompletoDesdeMovil)
{
	/***CONSTANTES PARA WORKFLOW CODIGOS TEXTO*****/
	$WORKFLOW_POR_ASIGNAR_TEXTO = "Por asignar ";
	$WORKFLOW_PENDIENTE_TEXTO = "Pendiente";
	$WORKFLOW_COMPLETO_TEXTO = "Completado";
	$WORKFLOW_EN_PROCESO_TEXTO = "En proceso";
	$WORKFLOW_REEAGENDADO_TEXTO = "Pendiente Reagendado";


	/***CONSTANTES PARA WORKFLOW CODIGOS*****/
	$WORKFLOW_POR_ASIGNAR = 1;
	$WORKFLOW_PENDIENTE = 2;
	$WORKFLOW_COMPLETO = 3;
	$WORKFLOW_EN_PROCESO = 4;
	$WORKFLOW_REEAGENDADO = 7;

	$workflowCode = "";

	if ($estatusCompletoDesdeMovil == $WORKFLOW_POR_ASIGNAR_TEXTO) {
		$workflowCode = $WORKFLOW_POR_ASIGNAR;
	} else if ($estatusCompletoDesdeMovil == $WORKFLOW_PENDIENTE_TEXTO) {
		$workflowCode = $WORKFLOW_PENDIENTE;
	} else if ($estatusCompletoDesdeMovil == $WORKFLOW_COMPLETO_TEXTO) {
		$workflowCode = $WORKFLOW_COMPLETO;
	} else if ($estatusCompletoDesdeMovil == $WORKFLOW_EN_PROCESO_TEXTO) {
		$workflowCode = $WORKFLOW_EN_PROCESO;
	} else if ($estatusCompletoDesdeMovil == $WORKFLOW_REEAGENDADO_TEXTO) {
		$workflowCode = $WORKFLOW_REEAGENDADO;
	}

	return $workflowCode;
}
function validarReportTypeDependiendoDelTipoDeFormulario($tipoDeFormulario)
{
	if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_CENSO]) {
		$reportType = 1;
	} else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_VENTA]) {
		$reportType = 2;
	} else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_PLOMERO]) {
		$reportType = 3;
	} else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_INSTALACION]) {
		$reportType = 4;
	} else if ($tipoDeFormulario == $GLOBALS[FORMULARIO_TIPO_SEGUNDA_VENTA]) {
		$reportType = 5;
	}
	return $reportType;
}

function compararUsuarioToken($conn, $inIdUsuario)
{
	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";

	$stmtCompararUsuarioToken = $conn->prepare("call spCompararUsuarioToken(?);");
	mysqli_stmt_bind_param($stmtCompararUsuarioToken, 'i', $inIdUsuario);
	if ($stmtCompararUsuarioToken->execute()) {
		$stmtCompararUsuarioToken->store_result();
		$stmtCompararUsuarioToken->bind_result($status, $code, $response);

		if ($stmtCompararUsuarioToken->fetch()) {
			$responseArray["status"] = $status;
			$responseArray["code"] = $code;
			$responseArray["response"] = $response;

		} else {
			$responseArray["status"] = $GLOBALS[STATUS_FALLO];
			$responseArray["code"] = $GLOBALS[CODE_FALLO];
			$responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
		}
	}
	/**CERRAMOS EL RESULT DE CompararUsuarioToken***/
	$stmtCompararUsuarioToken->close();
	/***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
	 * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
	 * http://stackoverflow.com/a/10745472 ***/
	$conn->next_result();
	return $responseArray;
}

function insertarFormularioTipoCenso($conn,
									 $inIdUsuario, $inIdReporte, $inWorkFlowStatus, $inColonia, $inStreet,
									 $inBetweenStreets, $inInnerNumber, $inNewStreet, $inNse, $inColoniaType,
									 $inMarketType, $inIdCountry, $inIdState, $inIdCity, $inCp,
									 $inIdReportType, $inIdUserCreator, $inIdFormulario, $inDot_latitude,$inDot_longitude,
									 $inLote, $inHouseStatus, $inNivel, $inGiro, $inAcometida,
									 $inObservacion, $inTapon, $inMedidor, $inMarca, $inTipo,
									 $inNoSerie, $inNiple)
{
	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";
	$idFormCensusGenerado="";
	$idReporteGenerado = "";

	$stmtinsertarFormularioTipoCenso = $conn->prepare("call spInsertarFormularioCenso(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
	mysqli_stmt_bind_param($stmtinsertarFormularioTipoCenso, 'iiissssssssiisiiisddssssisiisssi',
		$inIdUsuario, $inIdReporte, $inWorkFlowStatus, $inColonia, $inStreet,
		$inBetweenStreets, $inInnerNumber, $inNewStreet, $inNse, $inColoniaType,
		$inMarketType, $inIdCountry, $inIdState, $inIdCity, $inCp,
		$inIdReportType, $inIdUserCreator, $inIdFormulario, $inDot_latitude,$inDot_longitude,
		$inLote, $inHouseStatus, $inNivel, $inGiro, $inAcometida,
		$inObservacion, $inTapon, $inMedidor, $inMarca, $inTipo,
		$inNoSerie, $inNiple
	);

	if ($stmtinsertarFormularioTipoCenso->execute()) {
		$stmtinsertarFormularioTipoCenso->store_result();
		$stmtinsertarFormularioTipoCenso->bind_result($status, $code, $response,$idFormCensusGenerado,$idReporteGenerado);

		if ($stmtinsertarFormularioTipoCenso->fetch()) {
			$responseArray["status"] = $status;
			$responseArray["code"] = $code;
			$responseArray["response"] = $response;
			$responseArray["idFormCensusGenerado"] = $idFormCensusGenerado;
			$responseArray["ReporteGenerado"] = $idReporteGenerado;

		} else {
			$responseArray["status"] = $GLOBALS[STATUS_FALLO];
			$responseArray["code"] = $GLOBALS[CODE_FALLO];
			$responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
			$responseArray["idFormCensusGenerado"] = "";
			$responseArray["ReporteGenerado"] = "";
		}
	}else{

	}
	//print_r($stmtinsertarFormularioTipoCenso);
	/**CERRAMOS EL RESULT DE CompararUsuarioToken***/
	$stmtinsertarFormularioTipoCenso->close();
	/***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
	 * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
	 * http://stackoverflow.com/a/10745472 ***/
	$conn->next_result();
	return $responseArray;
}

function insertarRelacionFotografiasDelCenso($conn, $idFormCensusGenerado, $idFotografia,$key,$sizeArrayFotografias)
{
	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";



	$stmtinsertarRelacionFotografiasDelCenso = $conn->prepare("call spInsertarRelacionFotografiasDelCenso(?,?);");
	mysqli_stmt_bind_param($stmtinsertarRelacionFotografiasDelCenso, 'ii', $idFormCensusGenerado, $idFotografia);
	if ($stmtinsertarRelacionFotografiasDelCenso->execute()) {
		$stmtinsertarRelacionFotografiasDelCenso->store_result();
		$stmtinsertarRelacionFotografiasDelCenso->bind_result($status, $code, $response);

		if ($stmtinsertarRelacionFotografiasDelCenso->fetch()) {
			$responseArray["status"] = $status;
			$responseArray["code"] = $code;
			$responseArray["response"] = $response;

		} else {
			$responseArray["status"] = $GLOBALS[STATUS_FALLO];
			$responseArray["code"] = $GLOBALS[CODE_FALLO];
			$responseArray["response"] = "Ocurrio un problema al momento de insertar la fotografia " . $idFotografia . "para el censo " . $idFormCensusGenerado;
		}


	}
	//print_r($stmtinsertarRelacionFotografiasDelCenso);
	/**CERRAMOS EL RESULT DE insertarRelacionFotografiasDelCenso***/
	$stmtinsertarRelacionFotografiasDelCenso->close();
   // print_r("idFotografias "+$idFotografia);
   //print_r("key "+$key);
   // print_r("sizeArrayFotografias "+$sizeArrayFotografias);

	/**GENERAMOS ESTA CONDICION PARA QUE HASTA QUE TERMINE EL RECORRIDO DEL FOREACH
	 * ENTONCES SE CIERRE LA CONEXION A LA BASE DE DATOS
	 * MIENTRAS NO SE TERMINE SEGUIMOS LLAMANDO AL SIGUIENTE STOREPROCEDURE***/
	if ($key < $sizeArrayFotografias - 1) {
		/***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
		 * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
		 * http://stackoverflow.com/a/10745472 ***/
		$conn->next_result();
	} else {
		return($response);

		$conn->close();
	}
}

function insertarFormularioTipoVentaUno($conn,
	$idUsuario,
        $idAsignacion,
        $estatusWorkFlow,
        $inReportType,					 
        $inAgreementNumber,
        $inClientName, 
        $inClientLastName1,
        $inClientLastName2, 
        $inColonia,
	$inStreet, 
        $inBetweenStreets,
        
        $innerNumber,
        $inOutterNumber,
        $inNse,
	$inNewStreet, 
        $inStreets, 
        $inColoniaType,
        $inMarketType,
        $inIdCountry,
	$inIdState, 
        $inIdCity,
        $inCp,
        
        $inIdFormulario,
        $inDot_latitude,
	$inDot_longitude,
	$inProspect,
        $inUninteresting,
        $inCommentsUninteresting,
        $inComments,
        $inOwner,
        
        $inName,
	$inLastName,
        $inLastNameOp, 
        $inPayment,
        $inFinancialService, 
        $inRequestNumber,
	$inMmeeting,
        $idSolicitudMovil)
{

	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";
	$idFormSellsGenerado="";
	$idReporteGenerado = "";
	error_log('aqui1');
	$stmtinsertarFormularioTipoVentaUnoSQL = "call spInsertarFormularioVentaUno(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
	error_log('aqui2');
//        grabarLog("entraaqui");
	if ($stmtinsertarFormularioTipoVentaUno = $conn->prepare($stmtinsertarFormularioTipoVentaUnoSQL)) {
		mysqli_stmt_bind_param($stmtinsertarFormularioTipoVentaUno, 'iiiissssssssssssssiisiiddisssssssssssi',
			$idUsuario,$idAsignacion,$estatusWorkFlow,$inReportType,
			$inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $inColonia,
			$inStreet, $inBetweenStreets, $innerNumber, $inOutterNumber, $inNse,
			$inNewStreet, $inStreets, $inColoniaType, $inMarketType, $inIdCountry,
			$inIdState, $inIdCity, $inCp, $inIdFormulario,$inDot_latitude,
			$inDot_longitude,
			$inProspect, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inName,
			$inLastName, $inLastNameOp, $inPayment, $inFinancialService,  $inRequestNumber,
			$inMmeeting,$idSolicitudMovil
		);
		if ($stmtinsertarFormularioTipoVentaUno->execute()) {
			$stmtinsertarFormularioTipoVentaUno->store_result();
			$stmtinsertarFormularioTipoVentaUno->bind_result($status, $code, $response,$idFormSellsGenerado,$idReporteGenerado);
			//error_log(json_encode(var_dump($stmtinsertarFormularioTipoVentaUno)));
			if ($stmtinsertarFormularioTipoVentaUno->fetch()) {

	             validarVentaHistory($idReporteGenerado,$idFormSellsGenerado, $idUsuario, $estatusWorkFlow, $idSolicitudMovil, $inIdFormulario);
	        	
	        	
				$responseArray["status"] = $status;
				$responseArray["code"] = $code;
				$responseArray["response"] = $response;
				$responseArray["idFormSellsGenerado"] = $idFormSellsGenerado;
				$responseArray["ReporteGenerado"] = $idReporteGenerado;
				$responseArray["RespuestaActPlom"] = validaEmpleadoTipoPlomero($idUsuario, $idReporteGenerado, $inAgreementNumber, $inIdFormulario, $idFormSellsGenerado, $idSolicitudMovil, $inUninteresting);
				//$validarPlomero=validaEmpleadoTipoPlomero($conn,$idUsuario, $idReporteGenerado);
	                        
	                        
			} else {
	                    //grabarLog("entro en  elsesesese");
				$responseArray["status"] = $GLOBALS[STATUS_FALLO];
				$responseArray["code"] = $GLOBALS[CODE_FALLO];
				$responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
				$responseArray["idFormSellsGenerado"] = "";
				$responseArray["ReporteGenerado"] = "";
			}
			error_log(json_encode($responseArray));
		}else{
			error_log('la concha de tu madre codigo '.$stmtinsertarFormularioTipoVentaUno->error);
		}
	}else{
		error_log('error bd '.$conn->error);
	}
	//print_r($stmtinsertarFormularioTipoVentaUno);
	////grabarLog(json_encode($responseArray));
	/**CERRAMOS EL RESULT DE CompararUsuarioToken***/
	$stmtinsertarFormularioTipoVentaUno->close();
	/***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
	 * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
	 * http://stackoverflow.com/a/10745472 ***/
	$conn->next_result();
	return $responseArray;
}
function validaEmpleadoTipoPlomero($userID, $idReporte, $inAgreementNumber, $idFormulario,$idFormSellsGenerado, $idSolicitudMovil, $inUninteresting)
{
	error_log('message '.$inUninteresting);
	if ($inUninteresting == 'true' || $inUninteresting == true) {
		//creamos proceso de plomeria
		$DB = new DAO();
		$conn = $DB->getConnect();
		if ($userID != "" && $idReporte != "") {
			$queryEncontrarPlomero="SELECT EMP.id
									FROM user AS USAG
									INNER JOIN agency AS AG ON USAG.id = AG.idUser
									INNER JOIN agency_employee AS AGEMP ON AG.id = AGEMP.idAgency
									INNER JOIN employee AS EMP ON AGEMP.idemployee = EMP.id
									INNER JOIN profile AS PRF ON EMP.idProfile = PRF.id
									INNER JOIN user AS USEMP ON EMP.idUser = USEMP.id
									WHERE 0=0 AND
									((PRF.id = 3) OR 
									(PRF.id = 6) OR 
									(PRF.id = 7) OR 
									(PRF.id = 8)) AND
									USEMP.id=?";
			$plumber=3;
			$employeesAssigned=0;
			$error='';
			$status=4;
			if ($stmt = $conn->prepare($queryEncontrarPlomero)) {
				$stmt->bind_param("i", $userID);
				if ($stmt->execute()) {
					$stmt->store_result();
					$stmt->bind_result($idUser);
					if ($stmt->fetch()) {
						if ($idUser != '') {
							$querySmt="UPDATE report as RP SET RP.agreementNumber=?
									   WHERE RP.`id` = ?";
							//var_dump($conn->prepare($querySmt));
							if ($stmtUpdate = $conn->prepare($querySmt)) {
								//$idUser, $plumber, $employeesAssigned, $idReporte
								$stmtUpdate->bind_param("ii", $inAgreementNumber, $idReporte);
								//$stmtUpdate->execute();
								if ($stmtUpdate->execute()) {
									/*,id,idReport,idStatus,notes,meeting,created_at,updated_at
									*/
									$stmtInsHistory = "INSERT INTO reportHistory(idReport,idFormSell,idFormulario,idReportType,idUserAssigned,idStatusReport,idSolicitud,updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
									if ($stmtInsert = $conn->prepare($stmtInsHistory)) {
										$stmtInsert->bind_param("iiiiiii", $idReporte, $idFormSellsGenerado,$idFormulario, $plumber, $userID, $status, $idSolicitudMovil);
										$stmtInsert->execute();
									}else{
										error_log($conn->error);
									}
									$idStatusPH = 30;
									$updateEstatusContratoSQL = "UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;";
									if ($updateEstatusContrato = $conn->prepare($updateEstatusContratoSQL)) {
										$updateEstatusContrato->bind_param("iii", $idStatusPH, $idUser, $idReporte);
										$updateEstatusContrato->execute();
										$result["status"] = "OK";
										$result["code"] = "200";
										$result["result"] = "Reportes Asignados Exitosamente";
										$error=$result;
									}
								}
							}else{
								//printf("Errormessage: %s\n", $conn->error);
								$error=$conn->error;
							}
						}else{
							$error="El Vendedor no tiene perfil Plomero";
						}
					}
				}
			}else{
				//printf("Errormessage: %s\n", $conn->error);
				$error=$conn->error;
			}
		}
		$conn->close();
		//validamos si el empleado asignado al formulario tipoVenta es plomero si no es plomero asignamos un plomero
	}else{
		$error='Cuando hay desinteres no se registra la plomeria automatica';
	}
	return $error;
}
function insertarRelacionFotografiasDeLaVenta($conn, $idFormSellsGenerado, $idFotografia,$key,$sizeArrayFotografias)
{
	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";

	$stmtinsertarRelacionFotografiasDeLaVenta = $conn->prepare("call spInsertarRelacionFotografiasDeLaVenta(?,?);");
	mysqli_stmt_bind_param($stmtinsertarRelacionFotografiasDeLaVenta, 'ii', $idFormSellsGenerado, $idFotografia);
	if ($stmtinsertarRelacionFotografiasDeLaVenta->execute()) {
		$stmtinsertarRelacionFotografiasDeLaVenta->store_result();
		$stmtinsertarRelacionFotografiasDeLaVenta->bind_result($status, $code, $response);

		if ($stmtinsertarRelacionFotografiasDeLaVenta->fetch()) {
			$responseArray["status"] = $status;
			$responseArray["code"] = $code;
			$responseArray["response"] = $response;

		} else {
			$responseArray["status"] = $GLOBALS[STATUS_FALLO];
			$responseArray["code"] = $GLOBALS[CODE_FALLO];
			$responseArray["response"] = "Ocurrio un problema al momento de insertar la fotografia " . $idFotografia . "para el censo " . $idFormCensusGenerado;
		}


	}
	//print_r($stmtinsertarRelacionFotografiasDeLaVenta);
	/**CERRAMOS EL RESULT DE insertarRelacionFotografiasDelCenso***/
	$stmtinsertarRelacionFotografiasDeLaVenta->close();
	// print_r("idFotografias "+$idFotografia);
	//print_r("key "+$key);
	// print_r("sizeArrayFotografias "+$sizeArrayFotografias);

	/**GENERAMOS ESTA CONDICION PARA QUE HASTA QUE TERMINE EL RECORRIDO DEL FOREACH
	 * ENTONCES SE CIERRE LA CONEXION A LA BASE DE DATOS
	 * MIENTRAS NO SE TERMINE SEGUIMOS LLAMANDO AL SIGUIENTE STOREPROCEDURE***/
	if ($key < $sizeArrayFotografias - 1) {
		/***PARA EJECUTAR OTRO STOREPROCEDURE TENEMOS QUE DECIRLE A NUESTRA BD QUE VAMOS
		 * A CONTINUAR CON RESULTADOS POR LO CUAL SE USA LA SENTENCIA NEXT_RESULT
		 * http://stackoverflow.com/a/10745472 ***/
		$conn->next_result();
	} else {
		return($response);

		$conn->close();
	}
}


	function grabarLog($logInfo)
	{
		/***TODO LOGS PARA EL SERVICIO**/
		$dir = "../../logs/";
		$fileName = "createFormV2.txt";
		$fileData = fopen($dir . $fileName, "w");
		fwrite($fileData, $logInfo);
		fclose($fileData);

	}

        function validarVentaHistory($idReporteGenerado,$idFormSellsGenerado, $idUsuario, $estatusWorkFlow,$idSolicitudMovil, $inIdFormulario)
        {
                $DB = new DAO();
                $conn = $DB->getConnect();
                
                $valor=2;
                
//                grabarLog("estatus que recibe es -> " . $estatusWorkFlow);
                
                $idReportHistory = NULL;
                $sqlGetVenta = "SELECT idReportHistory FROM reportHistory WHERE idReport = ? AND idReportType = ?;";
                $getVenta = $conn->prepare($sqlGetVenta);
                $getVenta->bind_param("ii", $idReporteGenerado, $valor);
                
                if($getVenta->execute())
                {
                    $getVenta->store_result();
                    $getVenta->bind_result($idReportHistory);
                    
                    if($getVenta->fetch())
                    {
//                        grabarLog("entro en la funcion llego if donde si habia uno anterior y tiene que actualziar");
                        $stmtUpdate="UPDATE reportHistory SET  idUserAssigned = ?, idStatusReport = ?,idSolicitud=?, idFormulario=?, updated_at = NOW() WHERE idReportHistory = ?;";
                        if ($update = $conn->prepare($stmtUpdate)) 
                        {
                            $update->bind_param("iiiii", $idUsuario, $estatusWorkFlow,$idSolicitudMovil, $inIdFormulario, $idReportHistory);
                            $update->execute();
                        }
                    }
                    else
                    {
//                        grabarLog("entro en la funcion llego else donde no hay registro de venta y tiene que insertar");
                        $stmtInsertFirst="INSERT INTO `reportHistory`(`idReport`,`idFormSell`,`idReportType`,`idUserAssigned`,`idStatusReport`,`idSolicitud`,`idFormulario`,`updated_at`,`created_at`)VALUES(?,?,?,?,?,?,?,NOW(),NOW());";
                        if ($insertHistory = $conn->prepare($stmtInsertFirst)) 
                        {
                            $insertHistory->bind_param("iiiiiii",$idReporteGenerado, $idFormSellsGenerado, $valor, $idUsuario, $estatusWorkFlow, $idSolicitudMovil,$inIdFormulario);
                            $insertHistory->execute();
                        }
                        else
                        {
                            error_log($conn->error);
                        }
                    }
                }
                else
                {
//                    grabarLog("entro en la funcion llego en else donde no hay nada");
                }
                
               $conn->close();   
        }