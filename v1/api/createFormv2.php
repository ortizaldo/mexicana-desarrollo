<?php include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
require_once '../../dataLayer/classes/estructuraCarpetas.php';
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
error_log('entre a mexicana des');
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
		error_log('jsonSolicitudo '.json_encode($solicitud));
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
		$IDUser = $solicitud["idUsuario"];
		$latitud = $solicitud["latitud"];     
                
                
		/*if (!is_numeric($latitud)) {
			$latitud = 0.00;
		}*/
                
		$longitud = $solicitud["longitud"];
		/*if (!is_numeric($longitud)) {
			$longitud = 0.00;
		}*/

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
			$latitudeCallejero = $callejeroArray["latitud"];
			/*if (!is_numeric($latitudeCallejero)) {
				$latitudeCallejero = 0.00;
			}*/
			$longitudeCallejero = $callejeroArray["longitud"];
			error_log('message longitude '.$longitud);
			/*if (!is_numeric($longitudeCallejero)) {
				$longitudeCallejero = 0.00;
			}*/
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
				$longitud,
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
				$censo=1;
				$resIDHist=getIdReportHist($ReporteGenerado);
				//validamos que history se haya insertado solo una vez

				if ($resIDHist != "") {
					$stmtUpdateFirst="UPDATE reportHistory SET idReport=?,
																 idFormSell=?,
																 idReportType=?,
																 idUserAssigned=?,
																 idStatusReport=?,
																 idSolicitud=?,
																 idFormulario=?,
																 updated_at=NOW(),
										WHERE idReportHistory=?;";
		            if ($insertHistory = $connNueva->prepare($stmtUpdateFirst)) {
		            	$insertHistory->bind_param("iiiiiii",$ReporteGenerado, $idFormCensusGenerado, $censo, $idUsuario, $estatusWorkFlow,$idSolicitudMovil,$inIdFormulario);
		                $insertHistory->execute();
		            }else{
		            	error_log($connNueva->error);
		            }
				}else{
					$stmtInsertFirst="INSERT INTO `reportHistory`(`idReport`,`idFormSell`,`idReportType`,`idUserAssigned`,`idStatusReport`,`idSolicitud`,`idFormulario`,`updated_at`,`created_at`)VALUES(?,?,?,?,?,?,?,NOW(),NOW());";
		            if ($insertHistory = $connNueva->prepare($stmtInsertFirst)) {
		            	$insertHistory->bind_param("iiiiiii",$ReporteGenerado, $idFormCensusGenerado, $censo, $idUsuario, $estatusWorkFlow,$idSolicitudMovil,$inIdFormulario);
		                $insertHistory->execute();
		            }else{
		            	error_log($connNueva->error);
		            }
				}
	            $connNueva->close();
				$sizeFotografias = sizeof($arrayFotografias);
				$i = 0;
				for ($i = 0; $i < $sizeFotografias; $i++) {

					$fotografia = $arrayFotografias[$i];
					$responseFotografia = insertarRelacionFotografiasDelCenso($conn, $idFormCensusGenerado, $fotografia,$i,$sizeFotografias);
				}
                                
                                $oEstructuraCarpetas = new EstructuraCarpetas();
                                $oEstructuraCarpetas->setIdReporte($ReporteGenerado);
                                $oEstructuraCarpetas->crearCarpetaCenso();
                                $oEstructuraCarpetas->moverTemporalCenso();
                                
                                
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
		   	if($inAgreementNumber==null){
				$inAgreementNumber="-";
			}

			if($inUninteresting==false){
				$inUninteresting='false';
			}else if($inUninteresting==true){
				$inUninteresting='true';
			}
			error_log('inFinancialService '.$inFinancialService);
			/*if($inFinancialService == false || $inFinancialService == 'false'){
				$inFinancialService=0;
			}else if($inFinancialService == true || $inFinancialService == 'true'){
				$inFinancialService=1;
			}*/

			error_log('inFinancialService2 '.$inFinancialService);

			$responseArrayInsertarFormularioTipoVentaUno=array();

			$resGetStatusReport=getStatusReport($idAsignacion);
			
			$responseArrayInsertarFormularioTipoVentaUno = insertarFormularioTipoVentaUno(
				$conn,$idUsuario,$idAsignacion,$estatusWorkFlow,$tipoDeReporte,$inAgreementNumber, 
				$inClientName, $inClientLastName1, $inClientLastName2, $coloniaCallejero,$streetCallejero,
				$betweenStreetsCallejero,$innerNumberCallejero, $innerNumberCallejero, $nseCallejero,
				$newStreetCallejero,$streetsCallejero,$coloniaTypeCallejero,$marketTypeCallejero,1,1,
				$idMunicipioCallejero, 0,$idFormulario,$latitud,$longitud,1, $inUninteresting,
				$inCommentsUninteresting, $inComments,$inOwner,$inClientName,$inClientLastName1,
				$inClientLastName2, $inPayment,$inFinancialService, $inRequestNumber,1,$idSolicitudMovil, $IDUser
			);
			error_log('formvtauno '.json_encode($responseArrayInsertarFormularioTipoVentaUno));
			if ($responseArrayInsertarFormularioTipoVentaUno["code"] == $CODE_EXITOSO) {
				error_log('message entramos a exitoso');
				/**SACAMOS EL ID REPORTE QUE GENERO LA CONSULTA**/
				$idFormSellsGenerado= $responseArrayInsertarFormularioTipoVentaUno["idFormSellsGenerado"];
                /**  BUG IMAGEN  */
                error_log('message status '.$resGetStatusReport[0]["estatusVenta"]);
                if (intval($resGetStatusReport[0]["estatusVenta"]) == 9) {
                	error_log('message entre a estatus rehazado');
                	borrarImagenFormSell($idFormSellsGenerado, $idAsignacion, $estatus);
                }
                //borrarImagenFormSell($idFormSellsGenerado, $idAsignacion, $estatus);
                
                /**  BUG IMAGEN  */
                /***AHORA DEBEMOS INSERTAR LA RELACION DE LAS FOTOGRAFIAS CON LA VENTA ESTO
				 * RECORRIENDO EL ARREGLO DE FOTOGRAFIAS***/
				$sizeFotografias = sizeof($arrayFotografias);
				$i = 0;
				for ($i = 0; $i < $sizeFotografias; $i++) {
					$fotografia = $arrayFotografias[$i];
					$responseFotografia = insertarRelacionFotografiasDeLaVenta($conn, $idFormSellsGenerado, $fotografia,$i,$sizeFotografias);
				}
                $oEstructuraCarpetas = new EstructuraCarpetas();
                $oEstructuraCarpetas->setIdReporte($responseArrayInsertarFormularioTipoVentaUno["ReporteGenerado"]);
                $oEstructuraCarpetas->crearCarpetaVenta();
                $oEstructuraCarpetas->moverTemporalVentas();
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
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_PLOMERO) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			$name = $formularioArray["nombre"];
			$lastName = $formularioArray["apellido_paterno"];
			$request = $formularioArray["num_solicitud"];
			$documentNumber = $formularioArray["num_solicitud"];
			$ri = $formularioArray["esmenor"];
			if ($ri == true) {
				$ri = 1;
			} else {
				$ri = 0;
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
			$comments = $formularioArray["observaciones"].' - '.$solicitud["observaciones"];
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
			$createPlumberFormSQL ="INSERT INTO `form_plumber`(`consecutive`,`name`, `lastName`, `request`, `tapon`, `documentNumber`, `diagram`, `comments`, `newPipe`, `ph`, `pipesCount`, `idStatus`,`latitude`, `longitude`, `ri`,`created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?, ?,NOW(), NOW(), 1);";
			if ($stmt = $conn->prepare($createPlumberFormSQL)) {
				$stmt->bind_param("isssisssiiidddi", $consecutivo, $name, $lastName, $request, $tapon, $dictamen, $diagram, $comments, $newPipe, $ph, $pipesCount, $IDSTatus,$latitude, $longitude, $ri);
				if ($stmt->execute()) {
					$idPlumberForm = $stmt->insert_id;//id del formulario plomero
					$idRepHist=getIdReportHistPlom($reportID);
					if ($idRepHist != '') {
						//actualizamos report history con el idForm -> idFormPlumber
						$stmtUpdateFirst="UPDATE reportHistory SET idReport=?,idStatusReport=?,idFormulario=?,updated_at=NOW() WHERE idReportHistory=?;";
			            if ($insertHistory = $conn->prepare($stmtUpdateFirst)) {
			            	$idRepHist=intval($idRepHist);
			            	$reportID=intval($reportID);
							$estatusWorkFlow=intval($estatusWorkFlow);
							$idPlumberForm=intval($idPlumberForm);
			            	$insertHistory->bind_param("iiii",$reportID, $estatusWorkFlow,$idPlumberForm, $idRepHist);
			                $insertHistory->execute();
			            }else{
			            	error_log($conn->error);
			            }
					}
					$getIdRpTVTA=getReportTVTAID($reportID);
					if ($getIdRpTVTA != "") {
                    	if (intval($estatusWorkFlow) == 7) {
                    		$updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaInicioAnomPH = NOW() WHERE idReporte = ?;";
                    	}elseif(intval($estatusWorkFlow) == 3){
                    		$selecteportTVTASQL = "SELECT fechaInicioAnomPH FROM reportTiempoVentas WHERE id = $getIdRpTVTA;";
                    		$result = $conn->query($selecteportTVTASQL);
					        $res="";
					        if ($result->num_rows > 0) {
					            while($row = $result->fetch_array()) {
					            	if ($row[0] != "" || !is_null($row[0]) ) {
					            		$updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechafINAnomPH = NOW(), fechaFinRealizoPH = NOW() WHERE idReporte = ?;";
					            	}else{
					            		$updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaFinRealizoPH = NOW() WHERE idReporte = ?;";
					            	}
					            }
					        }
                    	}
                        if ($updateReportTVTA= $conn->prepare($updateReportTVTASQL)) {
                            $updateReportTVTA->bind_param('i',$reportID);
                            if($updateReportTVTA->execute()){
                                error_log("Se actualizo la tabla reporte de tiempos");
                            }
                        }
                    }
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



					if(count($fotos) > 0){
						$oEstructuraCarpetas = new EstructuraCarpetas();
                        $oEstructuraCarpetas->setIdReporte($reportID);
                        $oEstructuraCarpetas->crearCarpetaPloemro();
                        $oEstructuraCarpetas->moverTemporalPlomeria();
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
							//$response['resultUpdTrack']=actualizarTrack($idReport);
							$response["reportId"] = $idPlumberForm;
							echo json_encode($response);
						}
						$idStatusPH = validarEstatusContrato($estatusWorkFlow);
                        // $idStatusPH = 31;
						$updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `phEstatus` = ?, `idEmpleadoPhAsignado` = ?, `fechaMod` = NOW() WHERE `idReporte` = ?;");
						$updateEstatusContrato->bind_param("iii", $idStatusPH, $employee, $reportID);
						$updateEstatusContrato->execute();
						$idStatus=3;
						$updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
						$updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);
						$updateReportAssignedStatus->execute();
					}
				}
			}else{
				$conn->error;
			}
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_SEGUNDA_VENTA) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			//error_log(json_encode($solicitud));
			$agencia=obteneridAgencia($formularioArray["agencia"]);//la vamos a obtener con una funcion
			$pagare=$formularioArray["pagare"];
			$contrato=$formularioArray["contrato"];
			$fecha_solicitud=$formularioArray["fecha_solicitud"];
			//error_log('fecha_solicitud Antes '.$formularioArray["fecha_solicitud"]);
			$fechaSolFormat = str_replace('/', '-', $formularioArray["fecha_solicitud"]);
			$fechaSolFormat=date('Y-m-d', strtotime($fechaSolFormat));
			//error_log('fecha_solicitud despues '.$fechaSolFormat);
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
			//error_log('fecha_nacimiento Antes '.$formularioArray["fecha_nacimiento"]);
			//$fechaNacFormat=date_create($formularioArray["fecha_nacimiento"]);
			$fechaNacFormat = str_replace('/', '-', $formularioArray["fecha_nacimiento"]);
			$fechaNacFormat=date('Y-m-d', strtotime($fechaNacFormat));
			//error_log('fecha_nacimiento despues '.$fechaNacFormat);
			$fecha_nacimiento=$formularioArray["fecha_nacimiento"];
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
			//error_log('fechaRiFormat antes '.$formularioArray["fecha_ri"]);
			$fechaRiFormat = str_replace('/', '-', $formularioArray["fecha_ri"]);
			$fechaRiFormat=date('Y-m-d', strtotime($fechaRiFormat));
			//error_log('fechaRiFormat despues '.$fechaRiFormat);
			$fecha_ri=$formularioArray["fecha_ri"];
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
					$fechaSolFormat, $apellido_paterno,$apellido_materno, $nombre,
					$rfc,$curp, $correo,$estado_civil, $sexo,$num_identificacion,
					$tipo_identificacion, $fechaNacFormat,$pais_nacimiento,
					$estado, $municipio,
					$colonia,$calle,$vive_encasa,
					$tel_casa,$tel_celular,
					$tipo_contrato,$precio,$idArt, 
					$mensualidad, $plazo,
					$ri,$fechaRiFormat,$empresa,
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

						$idRpHist=getIdReportHistSegVta($solicitud["idAsignacion"]);
						error_log('message idRpHist '.$idRpHist);
						if ($idRpHist != '') {
							//actualizamos el estatus en history
							error_log('message estatusWorkFlow1 '.$estatusWorkFlow);
							$stmtUpdateFirst="UPDATE reportHistory SET idReport=?,
																	   idStatusReport=?,
																	   updated_at=NOW()
												WHERE idReportHistory=?;";
				            if ($stmtUpdate = $conn->prepare($stmtUpdateFirst)) {
				            	error_log('entre');
				            	$stmtUpdate->bind_param("iii",$solicitud["idAsignacion"], $estatusWorkFlow,$idRpHist);
				                //$insertHistory->execute();
				                error_log(json_encode($stmtUpdate));
				                if ($stmtUpdate->execute()) {
				                	error_log('no fallo');
				                }else{
				                	error_log('fallo');
				                }
				            }else{
				            	error_log('error '.$conn->error);
				            }
						}

						$idAgreegment = $createSecondStepSell->insert_id;
						$createAgreementStatus = $conn->prepare("INSERT INTO workflow_status_agreement(idWorkflow, idStatus, idAgreement, created_at, updated_at, active) VALUES(?, ?, ?, NOW(), NOW(), 1);");
						$createAgreementStatus->bind_param("iii", $idWorkflow, $idStatus, $idAgreegment);
						$response = null;

						$statusSegundaVenta = 41;
                        $dbSegVta = new DAO();
                        $connSegVta = $dbSegVta->getConnect();
                        $stmtTEstatus = "UPDATE tEstatusContrato SET validacionSegundaVenta = ?,idEmpleadoSegundaVenta = ? WHERE idReporte = ?;";
                        if ($estatusCrontratoReport = $connSegVta->prepare($stmtTEstatus)) {
                            $estatusCrontratoReport->bind_param("iii", $statusSegundaVenta, $IDUser, $solicitud["idAsignacion"]);
                            //;
                            if ($estatusCrontratoReport->execute()) {
                            	error_log('maldito error fuck'.$connSegVta->error);
                            }
                        }else{
                            error_log('maldito error '.$connSegVta->error);
                        }
                        $connSegVta->close();

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
					}
				}
			}else{
				$response["status"] = "ERROR";
				$response["code"] = "500";
				$response["response"] = "Error en la Creacion de Contrato2: " . $conn->error;
				$response["post"]=$solicitud;
			}
		} else if ($tipoDeFormulario == $FORMULARIO_TIPO_INSTALACION) {
			$DB = new DAO();
			$conn = $DB->getConnect();
			$name = $formularioArray["nombre"];
			$lastName = $formularioArray["apellido_paterno"];
			//$request = intval($solicitud['id']);
			$documentNumber = intval($solicitud['id']);
			$comments = $solicitud["observaciones"];
			$fotos = $formularioArray["fotos"];
			$latitude = $solicitud["latitud"];
			$longitude = $solicitud["longitud"];
			$reportID=intval($solicitud['idAsignacion']);
			$request = getIDCliente($reportID);
			$id=intval($solicitud['idUsuario']);
			$idStatus=intval($solicitud['id_estatus']);
			$statusType=$solicitud['estatus_concluido'];
			$consecutivo=intval($solicitud['consecutivo']);
			$anomalias=$formularioArray["catalogo_anomalias"];
			$agencyNumber=$formularioArray["num_agencia"];
			$installation=$formularioArray["se_proecede"];
			$brand=$formularioArray["marca_medidor"];
			$type=$formularioArray["tipo_medidor"];
			$serialNumber=$formularioArray["num_medidor"];
			$measurement=$formularioArray["lectura_medidor"];
			$phLabel=$formularioArray["color_etiqueta"];
			$i = 0;

			//validamos si existe el formulario en dado caso de existir solo actualizamos
			$existFormInst = getIdFormInst($consecutivo);
			error_log('existFormInst '.$existFormInst);
			if (intval($existFormInst) > 0) {
				$createInstallationFormSQL ="UPDATE form_installation SET `consecutive`=?,`name`=?, `lastName`=?, `request`=?, `phLabel`=?, `agencyPh`=?, `agencyNumber`=?, `installation`=?, `abnormalities`=?, `comments`=?, `brand`=?, `type`=?,`serialNumber`=?, `measurement`=?, `idStatus`=?, `latitude`=?,`longitude`=?,`updated_at`=NOW(), `active`=1 WHERE id=?;";
			}else{
				$createInstallationFormSQL ="INSERT INTO `form_installation` (`consecutive`,`name`, `lastName`, `request`, `phLabel`, `agencyPh`, `agencyNumber`, `installation`, `abnormalities`, `comments`, `brand`, `type`,`serialNumber`, `measurement`, `idStatus`, `latitude`,`longitude`,`created_at`,`updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?, NOW(), NOW(), 1);";
			}
			if ($stmt = $conn->prepare($createInstallationFormSQL)) {
				if (intval($existFormInst) > 0) {
					$stmt->bind_param("isssssiissssssiddi", $consecutivo, $name, $lastName, $request, $phLabel, $agencyPh, $agencyNumber, $installation, $anomalias, $comments, $brand, $type,$serialNumber, $measurement,$idStatus,$latitude,$longitude,$existFormInst);
				}else{
					$stmt->bind_param("isssssiissssssidd", $consecutivo, $name, $lastName, $request, $phLabel, $agencyPh, $agencyNumber, $installation, $anomalias, $comments, $brand, $type,$serialNumber, $measurement,$idStatus,$latitude,$longitude);
				}
				if ($stmt->execute()) {
					$idInstallationForm = $stmt->insert_id;//id del formulario installation
					if (intval($idInstallationForm) == 0 ) {
						$idInstallationForm=intval($existFormInst);
					}
					error_log('message idInstallationForm '.$idInstallationForm);
					if (count($fotos) > 0) {
						if (intval($existFormInst) > 0) {
							$deleteInstallationPipesPhoto = "DELETE FROM `form_installation_multimedia` WHERE idFormInstallation=?";
							if ($stmtDFotos = $conn->prepare($deleteInstallationPipesPhoto)) {
								$stmtDFotos->bind_param("i", $existFormInst);
								if (!$stmtDFotos->execute()) {
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Falla en el enlace de la Imagen con el Reporte de Instalacion: " . $conn->error;
									echo json_encode($response);
								}
							} else {
								$res = 'Error en delete de fotos Instalacion ' . $conn->error;
							}
						}
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
						if (intval($existFormInst) > 0) {
							$deleteInstallationDetails = "DELETE FROM `form_installation_details` WHERE idFormInstallation=?";
							if ($stmtDDFotos = $conn->prepare($deleteInstallationDetails)) {
								$stmtDDFotos->bind_param("i", $existFormInst);
								if (!$stmtDDFotos->execute()) {
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Falla en el enlace de la Imagen con el Reporte de Instalacion: " . $conn->error;
									echo json_encode($response);
								}
							} else {
								$res = 'Error en delete de fotos Instalacion ' . $conn->error;
							}
						}
						foreach ($materiales as $key) {
							$data = (array)$key;
							$createInstallationDetails = "INSERT INTO `form_installation_details` (`qty`, `idFormInstallation`,`material`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
							if ($stmtDetails = $conn->prepare($createInstallationDetails)) {
								$stmtDetails->bind_param("sis", $data["cantidad"], $idInstallationForm, $data["material"]);
								if (!$stmtDetails->execute()) {
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] = "Falla en el registro de material: " . $conn->error;
									echo json_encode($response);
								}
							}else{
								$res='Error en insert de detalles Instalacion '.$conn->error;
							}
						}
					}

					$reportEmployeeForm = "INSERT INTO `report_employee_form`(`idReport`, `idEmployee`, `idForm`, `created_at`, `updated_at`) VALUES(?, ?, ?, NOW(), NOW());";
					if ($reportEmployeeFormSTMT = $conn->prepare($reportEmployeeForm)) {
						$reportEmployeeFormSTMT->bind_param("iii", $reportID, $id, $idInstallationForm);
						$reportEmployeeFormSTMT->execute();

					}else{
						$res='Error en insert de report_employee_form Installation '.$conn->error;
					}

					$idWorkflow = 1;
					switch ($statusType) {
						case "Completado":
							$idStatus = 3;
							$idStatusContrato=51;
							break;
						case "Pendiente Reagendado":
							$idStatus = 7;
							break;
						default:
							error_log('ninguna de las anteriores');
							$idStatus = 4;
							break;
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
							error_log('message statusType '. $statusType);
							$response["status"] = "OK";
							$response["code"] = "200";
							$response["response"] = "Creacion de Reporte para Instalacion de Manera Exitosa.";
							$response["reportId"] = $idInstallationForm;
							echo json_encode($response);
						}
						$idStatusIns = validarEstatusInstalacion($idStatus);
						$updateEstatusContrato = $conn->prepare("UPDATE tEstatusContrato SET `estatusAsignacionInstalacion` = ?, 
																`fechaMod` = NOW() WHERE `idReporte` = ?;");
						$updateEstatusContrato->bind_param("ii",$idStatusIns, $reportID);
						$updateEstatusContrato->execute();
						$updateReportAssignedStatus = $conn->prepare("UPDATE `report_AssignedStatus` SET `idStatus` = ?, `updated_at` = NOW() WHERE `idReport` = ?;");
						$updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);
						$updateReportAssignedStatus->execute();
						$updateReportAssignedStatus = $conn->prepare("UPDATE `reportHistory` SET `idStatusReport` = ?, `updated_at` = NOW() WHERE `idReport` = ? and `idReportType`=4 ;");
						$updateReportAssignedStatus->bind_param('ii', $idStatus, $reportID);
						$updateReportAssignedStatus->execute();
						if ($statusType == "Completado") {
							$updateTiempoVenta = $conn->prepare("UPDATE reportTiempoVentas SET `fechaFinRealInst` = NOW(),`fechaFinAnomInst` = NOW() WHERE `idReporte` = ?;");
							$updateTiempoVenta->bind_param("i", $reportID);
							$updateTiempoVenta->execute();
							$oEstructuraCarpetas = new EstructuraCarpetas();
                            $oEstructuraCarpetas->setIdReporte($reportID);
                            $oEstructuraCarpetas->crearCarpetaVenta();
                            $oEstructuraCarpetas->moverProcesoTerminadoVenta();
                            $oEstructuraCarpetas->crearCarpetaPloemro();
                            $oEstructuraCarpetas->moverProcesoTerminadoPlomero();
                            $oEstructuraCarpetas->crearCarpetaInstalacion();
                            $oEstructuraCarpetas->moverTemporalInstalacion();
						} else if ($statusType == "Pendiente Reagendado") {
							$updateTiempoVenta = $conn->prepare("UPDATE reportTiempoVentas SET `fechaInicioAnomInst` = NOW() WHERE `idReporte` = ?;");
							$updateTiempoVenta->bind_param("i", $reportID);
							$updateTiempoVenta->execute();
                            $oEstructuraCarpetas = new EstructuraCarpetas();
                            $oEstructuraCarpetas->setIdReporte($reportID);
                            $oEstructuraCarpetas->crearCarpetaInstalacion();
                            $oEstructuraCarpetas->moverTemporalInstalacion();
						}


					}
					$conn->close;

				}

		}else {
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



function getIdFormInst($consecutive)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($consecutive != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT id FROM form_installation WHERE consecutive = $consecutive;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
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
	mysqli_stmt_bind_param($stmtinsertarFormularioTipoCenso, 'iiissssssssiisiiisssssssisiisssi',
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
	$stmtinsertarRelacionFotografiasDelCenso->close();

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

function insertarFormularioTipoVentaUno($conn,$idUsuario,$idAsignacion,$estatusWorkFlow,$inReportType,
										$inAgreementNumber,$inClientName, $inClientLastName1,$inClientLastName2, 
										$inColonia,$inStreet, $inBetweenStreets,$innerNumber,$inOutterNumber,
										$inNse,$inNewStreet, $inStreets, $inColoniaType,$inMarketType,$inIdCountry,
										$inIdState, $inIdCity,$inCp,$inIdFormulario,$inDot_latitude,$inDot_longitude,
										$inProspect,$inUninteresting,$inCommentsUninteresting,$inComments,$inOwner,
										$inName,$inLastName,$inLastNameOp, $inPayment,$inFinancialService, 
										$inRequestNumber,$inMmeeting,$idSolicitudMovil, $IDUser)
{
	/****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
	 * LISTAS EN MYSQL*/
	$responseArray = array();
	$status = "";
	$code = "";
	$response = "";
	$idFormSellsGenerado="";
	$idReporteGenerado = "";
	error_log('idAsignacion '.$idAsignacion);
	$stmtinsertarFormularioTipoVentaUnoSQL = "call spInsertarFormularioVentaUno(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
	if ($stmtinsertarFormularioTipoVentaUno = $conn->prepare($stmtinsertarFormularioTipoVentaUnoSQL)) {
		mysqli_stmt_bind_param($stmtinsertarFormularioTipoVentaUno, 'iiiissssssssssssssiisiiddisssssssssssi',
			$IDUser,$idAsignacion,$estatusWorkFlow,$inReportType,
			$inAgreementNumber, $inClientName, $inClientLastName1, $inClientLastName2, $inColonia,
			$inStreet, $inBetweenStreets, $innerNumber, $inOutterNumber, $inNse,
			$inNewStreet, $inStreets, $inColoniaType, $inMarketType, $inIdCountry,
			$inIdState, $inIdCity, $inCp, $inIdFormulario,$inDot_latitude,$inDot_longitude,
			$inProspect, $inUninteresting,$inCommentsUninteresting, $inComments, $inOwner, $inName,
			$inLastName, $inLastNameOp, $inPayment, $inFinancialService,  $inRequestNumber,
			$inMmeeting,$idSolicitudMovil
		);
		if ($stmtinsertarFormularioTipoVentaUno->execute()) {
			$stmtinsertarFormularioTipoVentaUno->store_result();
			$stmtinsertarFormularioTipoVentaUno->bind_result($status, $code, $response,$idFormSellsGenerado,$idReporteGenerado);
			if ($stmtinsertarFormularioTipoVentaUno->fetch()) {            
                validarVentaHistory($idReporteGenerado,$idFormSellsGenerado, $IDUser, $estatusWorkFlow, $idSolicitudMovil, $inIdFormulario);
                actualizarReportTiempos($idReporteGenerado, $estatusWorkFlow);
				$responseArray["status"] = $status;
				$responseArray["code"] = $code;
				$responseArray["response"] = $response;
				$responseArray["idFormSellsGenerado"] = $idFormSellsGenerado;
				$responseArray["ReporteGenerado"] = $idReporteGenerado;
				$responseArray["RespuestaActPlom"] = validaEmpleadoTipoPlomero($IDUser, $idReporteGenerado,
																			   $inAgreementNumber, $inIdFormulario, 
																			   $idFormSellsGenerado, $idSolicitudMovil, 
																			   $inUninteresting);
				error_log("result ".json_encode($responseArray));
			} else {
	                    //grabarLog("entro en  elsesesese");
				$responseArray["status"] = $GLOBALS[STATUS_FALLO];
				$responseArray["code"] = $GLOBALS[CODE_FALLO];
				$responseArray["response"] = "Ocurrio un problema al momento de obtener el token";
				$responseArray["idFormSellsGenerado"] = "";
				$responseArray["ReporteGenerado"] = "";
			}
		}else{
			error_log('error codigo '.$stmtinsertarFormularioTipoVentaUno->error);
		}
	}else{
		error_log('error bd '.$conn->error);
	}
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
	$desinteres = ($inUninteresting == "true") ? 1: 0;
	if ($desinteres == 1) {
		//creamos proceso de plomeria
		$DB = new DAO();
		$conn = $DB->getConnect();
		if ($userID != "" && $idReporte != "") {
			//validamos que el reporte tipo plomero no exista para que entre a crear el reporte
			$resGetExistPlumber=getExistPlumber($idReporte);
			if ($resGetExistPlumber != "") {
				error_log('message ya existe un plomero');
			}else{
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
								if ($stmtUpdate = $conn->prepare($querySmt)) {
									$stmtUpdate->bind_param("ii", $inAgreementNumber, $idReporte);
									if ($stmtUpdate->execute()) {
										$resIDHist=getIdReportHistPlom($idReporte);
										if($resIDHist == ''){
											$stmtInsHistory = "INSERT INTO reportHistory(idReport,idFormSell,idFormulario,idReportType,idUserAssigned,idStatusReport,idSolicitud,updated_at,created_at)VALUES(?,?,?,?,?,?,?,NOW(),NOW())";
											if ($stmtInsert = $conn->prepare($stmtInsHistory)) {
												$stmtInsert->bind_param("iiiiiii", $idReporte, $idFormSellsGenerado,$idFormulario, $plumber, $userID, $status, $idSolicitudMovil);
												$stmtInsert->execute();
											}else{
												error_log($conn->error);
											}
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
									$error=$conn->error;
								}
							}else{
								//$error="El Vendedor no tiene perfil Plomero";
								$getIdRpTVTA=getReportTVTAID($idReporte);
	                            if ($getIdRpTVTA != "") {
	                                $updateReportTVTASQL = "UPDATE reportTiempoVentas SET fechaInicioAsigPH = NOW() WHERE idReporte = ?;";
	                                if ($updateReportTVTA= $conn->prepare($updateReportTVTASQL)) {
	                                    $updateReportTVTA->bind_param('i',$idReporte);
	                                    if($updateReportTVTA->execute()){
	                                        error_log("Se actualizo la tabla reporte de tiempos");
	                                    }
	                                }
	                            }
							}
						}
					}
				}else{
					$error=$conn->error;
				}
			}
		}
		$conn->close();
		//validamos si el empleado asignado al formulario tipoVenta es plomero si no es plomero asignamos un plomero
	}else{
		$error='Cuando hay desinteres no se registra la plomeria automatica';
	}
	return $error;
}

function actualizarWorkFlow($idUsuario,$idReporte, $statusWorkflow)
{
	$DB = new DAO();
	$conn = $DB->getConnect();
	if ($statusWorkflow != "" && $idReporte != "") {
		$updateEstatusContratoSQL = "UPDATE tEstatusContrato SET idEmpleadoParaVenta=?, estatusVenta=?,fechaMod = NOW() WHERE idReporte = ?;";
		if ($updateEstatusContrato = $conn->prepare($updateEstatusContratoSQL)) {
			$updateEstatusContrato->bind_param("iii", $idUsuario, $statusWorkflow, $idReporte);
			if ($updateEstatusContrato->execute()) {
				$sqlUpdStatusWorkFlow='UPDATE workflow_status_report 
									   SET 
									   idStatus = ?,
									   updated_at = NOW()
									   WHERE
									   idReport = ?';
				if ($updateEstatusWorkFlow = $conn->prepare($sqlUpdStatusWorkFlow)) {
					$updateEstatusWorkFlow->bind_param("ii", $statusWorkflow,$idReporte);
					if ($updateEstatusWorkFlow->execute()) {
						$result["status"] = "OK";
						$result["code"] = "200";
						$result["result"] = "WorkFlowActualizado Correctamente";
						$error=$result;
					}
				}
			}
		}
	}
	$conn->close();
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
	/**CERRAMOS EL RESULT DE insertarRelacionFotografiasDelCenso***/
	$stmtinsertarRelacionFotografiasDeLaVenta->close();
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
function getExistPlumber($idReport)
{
	//generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport AND idReportType=3;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getIdReportHist($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport AND idReportType=1;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getIdReportHistPlom($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport AND idReportType=3;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}

function getIdReportHistSegVta($idReport)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReportHistory FROM reportHistory WHERE idReport = $idReport AND idReportType=5;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}
function actualizarTrack($idReport)
{
	//generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdReportHistSQL = "SELECT idReporte, id, fechaInicioAsigPH FROM reportTiempoVentas WHERE idReporte = $idReport;";
        $result = $conn->query($getIdReportHistSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $idReportTiempoVentas=$row[1];
                $fechaInicioAsigPH=$row[2];
                if ($fechaInicioAsigPH != '' || $fechaInicioAsigPH != null || $fechaInicioAsigPH != 'null') {
                	# code...
                	$stmtUpdateRepVtsTSQL="UPDATE reportTiempoVentas SET  fechaFinAsigPH = NOW(), fechaFinRealizoPH= NOW() WHERE idReportHistory = ?;";
		            if ($updateRepVtsT = $conn->prepare($stmtUpdateRepVtsTSQL)){
		                $updateRepVtsT->bind_param("iiiii", $idReportTiempoVentas);
		                $updateRepVtsT->execute();
		            }
                }
            }
        }else{
        	$stmtInsertTrack="INSERT INTO reportTiempoVentas (idReporte) VALUES (?)";
            if ($InsertTrack = $conn->prepare($stmtInsertTrack)) 
            {
                $InsertTrack->bind_param("i", $idReport);
                if ($InsertTrack->execute()) {
                	$result["status"] = "OK";
					$result["code"] = "200";
					$result["result"] = "Track insertado Correctamente";
					$error=$result;
                }
            }
        }
        $conn->close();
    }
    return $error;
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
            $stmtUpdate="UPDATE reportHistory SET  idUserAssigned = ?, idStatusReport = ?,idSolicitud=?, idFormulario=?, updated_at = NOW() WHERE idReportHistory = ?;";
            if ($update = $conn->prepare($stmtUpdate)) 
            {
                $update->bind_param("iiiii", $idUsuario, $estatusWorkFlow,$idSolicitudMovil, $inIdFormulario, $idReportHistory);
                $update->execute();
            }
        }
        else
        {
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
    }
    
   $conn->close();   
}
        
        
        
function validarEstatusContrato($estatusWorkFlow)
{
    $estatus = "";
    /***CONSTANTES PARA WORKFLOW CODIGOS*****/
    $WORKFLOW_POR_ASIGNAR = 1;
    $WORKFLOW_PENDIENTE = 2;
    $WORKFLOW_COMPLETO = 3;
    $WORKFLOW_EN_PROCESO = 4;
    $WORKFLOW_REEAGENDADO = 7;
    error_log('message estatusWorkFlow'.$estatusWorkFlow);
    /**ESTATUS DE PH**/
    $ESTATUS_PH_EN_PROCESO = 30;
    $ESTATUS_PH_COMPLETO = 31;
    $ESTATUS_PH_REAGENDADO = 32;
    
    if ($estatusWorkFlow == $WORKFLOW_EN_PROCESO) {
		$estatus = $ESTATUS_PH_EN_PROCESO;
    } else if($estatusWorkFlow == $WORKFLOW_COMPLETO) {
        $estatus = $ESTATUS_PH_COMPLETO;
    } else if($estatusWorkFlow == $WORKFLOW_REEAGENDADO) {
        $estatus = $ESTATUS_PH_REAGENDADO;
    } else  {
        $estatus = $ESTATUS_PH_EN_PROCESO;
    }
    
    return $estatus;
}
        
        
function validarEstatusSegundaVenta($estatusWorkFlow)
{
    $estatus = "";
    $WORKFLOW_POR_ASIGNAR = 1;
    $WORKFLOW_PENDIENTE = 2;
    $WORKFLOW_COMPLETO = 3;
    $WORKFLOW_EN_PROCESO = 4;
    $WORKFLOW_REEAGENDADO = 7;
    
    $ESTATUS_SEGUNDA_VENTA_EN_PROCESO = 40;
    $ESTATUS_SEGUNDA_VENTA_COMPLETA = 41;
    $ESTATUS_SEGUNDA_VENTA_CANCELADA = 42;
    
    if ($estatusWorkFlow == $WORKFLOW_EN_PROCESO) {
		$estatus = $ESTATUS_SEGUNDA_VENTA_EN_PROCESO;
    } else if($estatusWorkFlow == $WORKFLOW_COMPLETO) {
        $estatus = $ESTATUS_SEGUNDA_VENTA_COMPLETA;
    } else  {
        $estatus = $ESTATUS_SEGUNDA_VENTA_EN_PROCESO;
    }
    
    return $estatus;
}

function validarEstatusInstalacion($estatusWorkFlow)
{
    $estatus = "";
    $WORKFLOW_POR_ASIGNAR = 1;
    $WORKFLOW_PENDIENTE = 2;
    $WORKFLOW_COMPLETO = 3;
    $WORKFLOW_EN_PROCESO = 4;
    $WORKFLOW_REEAGENDADO = 7;
    
    $ESTATUS_INSTALACION_EN_PROCESO = 50;
    $ESTATUS_INSTALACION_COMPLETA = 51;
    $ESTATUS_INSTALACION_REAGENDADA = 52;
    $ESTATUS_INSTALACION_CANCELADA = 53;
    
    
    if($estatusWorkFlow == $WORKFLOW_EN_PROCESO){
        
        $estatus = $ESTATUS_INSTALACION_EN_PROCESO;
        
    } else if($estatusWorkFlow == $WORKFLOW_COMPLETO){
        $estatus = $ESTATUS_INSTALACION_COMPLETA;
    } else if($estatusWorkFlow == $WORKFLOW_REEAGENDADO){
        $estatus = $ESTATUS_INSTALACION_REAGENDADA;
    }else  {
        $estatus = $ESTATUS_INSTALACION_EN_PROCESO;
    }
    
    return $estatus;
    
}

function asignarInstalacion($report){

	$DB2 = new DAO();
	$conn2 = $DB2->getConnect();

	$idReport=0; $idUserCreator=0; $idEmployee=0; $city=0;$reportType=0; $employeeAssigned=""; $idFormulario=0; $idForm=0;$idSolicitudMovil=0;
	$instalation=4;$employeesAssigned="";$agency=0;$id=0;$profileID=0;$profileName="";

	$getEmployeeData = $conn2->prepare("SELECT user.id, employee.id, profile.id , profile.name FROM user INNER JOIN employee ON user.id = employee.idUser INNER JOIN profile ON employee.idProfile = profile.id inner join report on report.idemployee=employee.id WHERE report.id = ?;");
	$getEmployeeData->bind_param('i', $report);
	$getEmployeeData->store_result();
	$getEmployeeData->bind_result($id, $idEmployee, $profileID, $profileName);
	if ($getEmployeeData->execute()) {
		if ($getEmployeeData->fetch()) {
			$instalation = 4;
			if ( $profileID == 4 || $profileID == 8) {
				$searchReportsSQL = "SELECT RP.id, RP.idEmployee, RP.idUserCreator, RP.idReportType, RP.employeesAssigned,
                                                    RP.idFormulario,RP.idForm,RP.idSolicitudMovil
                                             FROM report AS RP WHERE RP.id = ?;";
				if($searchReports = $conn2->prepare($searchReportsSQL)){
					$searchReports->bind_param("i", $report);
					if ($searchReports->execute()) {
						$searchReports->store_result();
						$searchReports->bind_result($idReport, $idEmployee, $creator, $reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
						$searchReports->fetch();

						$employeesAssigned = $idEmployee;
						$reassingReportSQL="call spAsignarInstalacion(?,?,?,?,?,?,?,?);";
						error_log("Ejecutar: call spAsignarInstalacion(".$_POST['agency'].",".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report,$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);
						if ($reassingReport=$conn2->prepare($reassingReportSQL)) {
							mysqli_stmt_bind_param($reassingReport, 'iiisiiii', $_POST['agency'],$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
							if ($reassingReport->execute()) {
								$response["status"] = "COMPLETO";
								$response["code"] = "100";
								$response["response"] = "Asignación Automática de Instalación Satisfactoria";
							}else{
								$response["status"] = "ERROR";
								$response["code"] = "500";
								$response["response"] = 'Error en primer update asignacion inst / '.$conn2->error;
								echo json_encode($response);
							}
						}else{
							$response["status"] = "ERROR";
							$response["code"] = "500";
							$response["response"] = 'Error en primer update asignacion inst / '.$conn2->error;
							echo json_encode($response);
						}

					}

				}
				else
				{
					echo $searchReports->error;
				}



			} else {
				$DB2 = new DAO();
				$conn2 = $DB2->getConnect();
				$employeesAssigned = "";
				//ASIGNAR A AGENCIA QUE PERTENEZCA AL MUNICIPIO DONDE FUE CREADO EL REPORTE
				$getReportCity = $conn2->prepare("SELECT DISTINCT RP.id, RP.idUserCreator, RP.idEmployee, RP.idCity,RP.idReportType, RP.employeesAssigned,
                                                    RP.idFormulario,RP.idForm,RP.idSolicitudMovil  FROM report AS RP WHERE RP.id = ?;");
				$getReportCity->bind_param("i", $report);
				if ($getReportCity->execute()) {
					$getReportCity->store_result();
					$getReportCity->bind_result($idReport, $idUserCreator, $idEmployee, $city,$reportType, $employeeAssigned, $idFormulario, $idForm,$idSolicitudMovil);
					if ($getReportCity->fetch()) {

						$employeesAssigned="";
						//SELECCIONAR AGENCIA QUE TENGA EL MUNICIPIO ASIGNADO
						$idCity=getIdCity($city);
						$getAgencyCitySQL = "SELECT DISTINCT ASCTY.idAgency FROM agency_cities AS ASCTY WHERE ASCTY.idCity = ? ORDER BY RAND() LIMIT 1;";
						if ($getAgencyCity = $conn2->prepare($getAgencyCitySQL)) {
							$getAgencyCity->bind_param("i", $idCity);
							if ($getAgencyCity->execute()) {
								$getAgencyCity->store_result();
								$getAgencyCity->bind_result($agency);
								if ($getAgencyCity->fetch()) {
									$reassingReport=$conn2->prepare("call spAsignarInstalacion(?,?,?,?,?,?,?,?);");
									mysqli_stmt_bind_param($reassingReport, 'iiisiiii', $agency,$idEmployee,$instalation,$employeesAssigned,$report,$idFormulario,$idForm,$idSolicitudMovil);
									if ($reassingReport->execute()) {
										$reassingReport->store_result();
										$reassingReport->bind_result($employee);
										error_log("Ejecutar: call spAsignarInstalacion(".$agency.",".$idEmployee.",".$instalation.",".$employeesAssigned.",".$report.",".$idFormulario.",".$idForm.",".$idSolicitudMovil.");", 0);
										$response["status"] = "COMPLETO";
										$response["code"] = "100";
										$response["response"] = "Asignación Automática de Instalación Satisfactoria";
									}
								}else{
									$response["status"] = "ERROR";
									$response["code"] = "500";
									$response["response"] =$conn2->error;
									echo json_encode($response);
								}
							}
						}else{
							echo $getAgencyCity->error;
						}
					}
				}

			}
		}
	}

}

function getIdCity($city)
{
	require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');

	$clienteSoapMexicana = "http://111.111.111.18:8080/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
	$nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
	$nuSoapClientMexicana->forceEndpoint = "http://111.111.111.18:8080/wsa/wsa1/";
	$nuSoapClientMexicana->soap_defencoding = 'UTF-8';
	$nuSoapClientMexicana->decode_utf8 = false;

	$postData = array();
	$resultWsMunicipios = $nuSoapClientMexicana->call('ws_siscom_municipios', $postData);

	if ($nuSoapClientMexicana->fault) {

	} else {
		$err = $nuSoapClientMexicana->getError();
	}
	if ($err) {
		echo json_encode($err);

	} else {
		$municipios = $resultWsMunicipios ["ot_municipios"]["ot_municipiosRow"];
		foreach ($municipios as $municipio) {
			if ($municipio["nombre"] == $city) {
				return $municipio["idMunicipio"];
			}
		}
	}
}

function borrarImagenFormSell($idFormSellsGenerado, $idReport, $estatus)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    $rechazo = validarSiEsRechazo($idReport);

    $trustedHome = $rechazo[0]["trustedHome"];
    $requestImage = $rechazo[0]["requestImage"];
    $privacyAdvice = $rechazo[0]["privacyAdvice"];
    $identificationImage = $rechazo[0]["identificationImage"];
    $payerImage = $rechazo[0]["payerImage"];
    $agreegmentImage = $rechazo[0]["agreegmentImage"];
    error_log('message payerImage '.$payerImage);
    $querys = [];
    if (intval($trustedHome) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND d.name like 'comprobante%'";
		array_push($querys,$stmtDeletePic);
    }
    if (intval($requestImage) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND d.name like 'solicitud%'";
		array_push($querys,$stmtDeletePic);
    }
    if (intval($privacyAdvice) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND d.name like 'aviso%'";
		array_push($querys,$stmtDeletePic);
    }
    if (intval($identificationImage) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND (d.name like 'identificacion%' OR d.name like 'indentificacion%')";
		array_push($querys,$stmtDeletePic);
    }
    if (intval($payerImage) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND d.name like 'pagare%'";
		array_push($querys,$stmtDeletePic);
    }
    if (intval($agreegmentImage) == 0) {
    	//creamos el statment para eliminar la imagen que se esta rechazando
    	$stmtDeletePic = "SELECT 
						  d.id, d.name
						  FROM
						  reportHistory a,
						  form_sells b,
						  form_sells_multimedia c,
						  multimedia d
						  WHERE
						  0 = 0 
						  AND a.idFormSell = b.id
						  AND b.id = c.idSell
						  AND c.idMultimedia = d.id
						  AND a.idReportType = 2
						  AND a.idReport = $idReport
						  AND d.name like 'contrato%'";
		array_push($querys,$stmtDeletePic);
    }
    foreach ($querys as $key => $stmt) {
    	//recorremos el arreglo de consultas
    	$result = $conn->query($stmt);
	    if ($result->num_rows > 0) {
	    	while($row = $result->fetch_array()) {
	        	if (intval($row[0]) > 0) {
	        		error_log('message entre a borrar las imagenes');
	        		$delSQL = "DELETE FROM form_sells_multimedia WHERE idMultimedia = ?;";
				    if ($smtDel = $conn->prepare($delSQL)) {
				    	$idImagen = intval($row[0]);
				    	$smtDel->bind_param("i", $row[0]);
				    	if ($smtDel->execute()) {
				    		error_log('message borramos la imagen '.$row[1]);
				    	}
				    }
	        	}
	        }
	    }
    }
    $conn->close(); 
}

function getStatusReport($idReport)
{
	if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT 
						    a.idReport,
						    b.estatusVenta
						FROM
						    reportHistory a,
						    tEstatusContrato b
						WHERE
						    0 = 0 
						    AND a.idFormSell = b.idReporte
						    AND a.idReportType = 2
						    AND a.idReport = $idReport";
        $result = $conn->query($getIdRepHSQL);
        $res=[];
        if ($result->num_rows > 0) {
        	$cont = 0;
            while($row = $result->fetch_array()) {
            	$res[$cont]["idReport"]=$row[0];
            	$res[$cont]["estatusVenta"]=$row[1];
                $cont++;
            }
        }
        $conn->close();
        return $res;
    }

}


function validarSiEsRechazo($idReport)
{
	if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT 
						    a.idReport,
						    a.idStatusReport,
						    a.rechazado,
						    c.requestImage,
						    c.privacyAdvice,
						    c.identificationImage,
						    c.payerImage,
						    c.agreegmentImage,
						    c.trustedHome
						FROM
						    reportHistory a,
						    form_sells b,
						    form_sells_validation c
						WHERE
						    0 = 0 
						    AND a.idFormSell = b.id
						    AND b.id = c.idFormSell
						    AND a.idReportType = 2
						    AND a.idReport = $idReport";
        $result = $conn->query($getIdRepHSQL);
        $res=[];
        if ($result->num_rows > 0) {
        	$cont = 0;
            while($row = $result->fetch_array()) {
            	$res[$cont]["idReport"]=$row[0];
            	$res[$cont]["idStatusReport"]=$row[1];
                $res[$cont]["rechazado"]=$row[2];
                $res[$cont]["requestImage"]=$row[3];
                $res[$cont]["privacyAdvice"]=$row[4];
                $res[$cont]["identificationImage"]=$row[5];
                $res[$cont]["payerImage"]=$row[6];
                $res[$cont]["agreegmentImage"]=$row[7];
                $res[$cont]["trustedHome"]=$row[8];
                $cont++;
            }
        }
        $conn->close();
        return $res;
    }

}

function actualizarReportTiempos($idReporteGenerado, $estatusWorkFlow)
{ 
    
    $DB = new DAO();
    $conn = $DB->getConnect();
    
    $smtReportTiempo = NULL;
    $idReportTiempo = NULL;
    $fechaInicioVenta = NULL;
    $idReportType = 2; 
    
    $ESTATUS_VENTA_COMPLETA = 3;
    $ESTATUS_VENTA_RECHAZADO = 2;
    
    $reportTiempoSQL = "SELECT id FROM reportTiempoVentas WHERE idReporte = ?";
    if($smtTiempo = $conn->prepare($reportTiempoSQL))
    {
        error_log("entro en primer if");
        $smtTiempo->bind_param("i",$idReporteGenerado);
        if($smtTiempo->execute())
        {
            error_log("entro en segundo if");
            $smtTiempo->store_result();
            $smtTiempo->bind_result($idReportTiempo);
            if($smtTiempo->fetch())
            {
                error_log("entro en la parte donde si hay id");
                if($estatusWorkFlow == $ESTATUS_VENTA_COMPLETA){
                    $reportActTiempoSQL = "UPDATE reportTiempoVentas SET fechaFinRechazo = NOW() WHERE idReporte = ?;";
                    $smtReportActTiempo = $conn->prepare($reportActTiempoSQL);
                    $smtReportActTiempo->bind_param("i",$idReporteGenerado);
                    $smtReportActTiempo->execute();
                }
            }
            else
            {
                error_log("entro en la parte donde no hay id y tiene que validar si inserta solo sii esta completado");
                error_log(json_encode(array("status" => "entra en  el else", "estatusWorkflow" => $estatusWorkFlow, "idReporteGenerado" => $idReporteGenerado, "reportHistory" => $reportHistory)));

                // PARA REGISTRARSE POR PRIMERA VEZ TIENE QUE ESTAR EN ESTATUS DE COMPLETADA
                if($estatusWorkFlow == $ESTATUS_VENTA_COMPLETA)
                {
                    $reportActTiempoSQL = "INSERT INTO reportTiempoVentas (idReporte,fechaInicioVenta,fechaFinVenta,fechaInicioFinanciera,fechaFinFinanciera,fechaInicioRechazo,fechaFinRechazo,fechaPrimeraCaptura,fechaSegundaCaptura,fechaInicioAsigPH,fechaFinAsigPH,fechaInicioRealizoPH,fechaFinRealizoPH,fechaInicioAnomPH,fechaFinAnomPH,fechaInicioAsigInst,fechaFinAsigInst,fechaInicioRealInst,fechaFinRealInst,fechaInicioAnomInst,fechaFinAnomInst)VALUES(
                                                    ?,NOW(),NULL,NULL,NULL,NULL,NULL,NOW(),NULL,NOW(),NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL
                                                );"; 

                    if($smtReportActTiempo = $conn->prepare($reportActTiempoSQL))
                    {
                        $smtReportActTiempo->bind_param("i",$idReporteGenerado);

                        if($smtReportActTiempo->execute())
                        {
                            error_log("inserto");
                        }
                        else
                        {
                            error_log("No isnerto " . $conn->error);
                        }
                    }
                    else
                    {
                        error_log("entra en  el else donde no pudo insertar" . $conn->error);
                    }
                }
            }
        }
        else
        {
             error_log("error al recuperar reporttiempos" . $conn->error);
        }
    }
    else
    {
        error_log("error al recuperar reporttiemposssss" . $conn->error);
    }
    $conn->close(); 
}

function getReportTVTAID($idReport)
{
    //generamos una consulta para obtener id
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT id, fechaInicioAsigPH  FROM reportTiempoVentas WHERE idReporte = $idReport";
        $result = $conn->query($getIdRepHSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}
function sendDataInstalacion($reportID)
{
	//generamos una consulta para obtener id
    if ($reportID != '') {
    	$DB = new DAO();
        $conn = $DB->getConnect();
        $getDataInstalacion = "SELECT 
						    FI.id,
						    FI.consecutive,
						    FI.name,
						    FI.lastName,
						    FI.request,
						    FI.phLabel,
						    FI.agencyPh,
						    FI.agencyNumber,
						    FI.installation,
						    FI.abnormalities,
						    FI.comments,
						    FI.brand,
						    FI.type,
						    FI.serialNumber,
						    FI.measurement,
						    FI.latitude,
						    FI.longitude,
						    FI.created_at,
						    te.estatusAsignacionInstalacion,
						    RP.agreementNumber,
						    agg.clientName,
						    agg.clientlastName,
						    agg.clientlastName2,
						    agg.clientBirthCountry,
						    agg.idState,
						    agg.idColonia,
						    agg.street,
						    agg.homeTelephone,
						    te.idClienteGenerado,
						    rth.idUserAssigned,
						    agg.idCity
						FROM
						    report AS RP
							INNER JOIN reportHistory as rth on rth.idReport=RP.id
							INNER JOIN form_installation AS FI ON FI.consecutive = rth.idFormSell
							INNER JOIN tEstatusContrato AS te ON te.idReporte = RP.id
							LEFT JOIN agreement as agg on agg.idReport=rth.idReport
							WHERE rth.idReportType=4 and rth.idReport = $reportID";
        $result = $conn->query($getDataInstalacion);
        error_log('query getDataInstalacion '.$getDataInstalacion);
        $res=[];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res['id']=$row[0];
				$res['consecutive']=$row[1];
				$res['name']=$row[2];
				$res['lastName']=$row[3];
				$res['request']=$row[4];
				$res['phLabel']=$row[5];
				$res['agencyPh']=$row[6];
				$res['agencyNumber']=$row[7];
				$res['installation']=$row[8];
				$res['abnormalities']=$row[9];
				$res['comments']=$row[10];
				$res['brand']=ltrim($row[11]);
				$res['type']=$row[12];
				$res['serialNumber']=$row[13];
				$res['measurement']=$row[14];
				$res['latitude']=$row[15];
				$res['longitude']=$row[16];
				$res['created_at']=$row[17];
				$res['estatusAsignacionInstalacion']=$row[18];
				$res['agreementNumber']=$row[19];
				$res['clientName']=$row[20];
				$res['clientlastName']=$row[21];
				$res['clientlastName2']=$row[22];
				$res['clientBirthCountry']=$row[23];
				$res['idState']=$row[24];
				$res['idColonia']=$row[25];
				$res['street']=$row[26];
				$res['homeTelephone']=$row[27];
				$res['idClienteGenerado']=$row[28];
				//$res['idCity']=$row[29];
				$res['idUserAssigned']=$row[29];
				$res['idCity']=$row[30];
				//obtenemos los materiales
				$res['materialesInstalacion']=getMaterialesInstalacion($row[0]);
            }
        }
        $conn->close();
    }
    return $res;
}
function getMaterialesInstalacion($idInstalacion)
{
	//generamos una consulta para obtener id
    if ($idInstalacion != '') {
    	$DB = new DAO();
        $conn = $DB->getConnect();
        $getIdRepHSQL = "SELECT qty, material  FROM form_installation_details WHERE idFormInstallation = $idInstalacion";
        $result = $conn->query($getIdRepHSQL);
        error_log('query getMaterialesInstalacion '.$getIdRepHSQL);
        $res=[];
        $cont=0;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
            	$material=ltrim($row[1]);
                $res[$cont]['material']=$material;
                $res[$cont]['cantidad']=$row[0];
                $cont++;
            }
        }
        $conn->close();
    }
    return $res;
}

function sendInstalacion($post, $reportID)
{
    $inst['Municipio']=$post['idCity'];
	$inst['Colonia']=$post['idColonia'];
	$inst['Direccion']=$post['street'];
	$inst['Telefono']=$post['homeTelephone'];
	$inst['t_contrato']=$post['idClienteGenerado'];
	$inst['t_Agencia_id']=$post['agencyNumber'];
	$inst['Instaladora']=getAgencia($post['idUserAssigned']);
	$inst['t_Marca']=$post['brand'];
	$inst['t_Serie_anterior']='';
	$inst['t_Lectura_ant']=0;
	$inst['t_Serie_Medidor']=$post['serialNumber'];
	$inst['t_Lectura']=$post['measurement'];
	$inst['t_Instalador']=258;
	$inst['t_Fecha_Ins']=$post['created_at'];
	$inst['t_Anomalia']=$post['abnormalities'];
	$inst['t_Observaciones']=$post['comments'];
	$inst['t_Union_3-4']=0;
	$inst['t_Red_Camp_3-4_1-2']=0;
	$inst['t_t_Codo3-4x90_150']=0;
	$inst['t_CodoNip_3-4_150']=0;
	$inst['t_Tee3-4']=0;
	$inst['t_Cople3-4_150']=0;
	$inst['t_TaponM_3-4_Neg']=0;
	$inst['t_Conex_p_medid_dom']=0;
	$inst['t_Val_esfer_3-4_125lbs']=0;
	$inst['t_Reg_Ameri_CR-4000_3-4']=0;
	$inst['t_Niple_3-4_RC']=0;
	$inst['t_Niple_3-4_x2']=0;
	$inst['t_Niple_3-4_x3']=0;
	$inst['t_Niple_3-4_x4']=0;
	$inst['t_Niple_3-4_x5']=0;
	$inst['t_Niple_3-4_x6']=0;
	$inst['t_Niple_3-4_x7']=0;
	$inst['t_Niple_3-4_x8']=0;
	$inst['t_Niple_3-4_x9']=0;
	$inst['t_Niple_3-4_x10']=0;
	$inst['t_Union_1Std']=0;
	$inst['t_Red_Camp_1x3-4']=0;
	$inst['t_Red_Bush_1x3-4']=0;
	$inst['t_Val_esfer_1_125lbs']=0;
	$inst['t_Niple_1-2_x_2']=0;
	$inst['t_reduccion']=0;
	$inst['t_blanco_plomo']=0;
	$inst['t_cinta_teflon']=0;
	error_log($post['materialesInstalacion']);
    foreach ($post['materialesInstalacion'] as $key => $value) {
        error_log('material'.$value['material']);
        switch ($value['material']) {
            case 'Tue.Union 3/4 G':
            $inst['t_Union_3-4']=$value['cantidad'];
            break;
            case 'Red.Camp.3/4-1/2 G':
            $inst['t_Red_Camp_3-4_1-2']=$value['cantidad'];
            break;
            case 'Codo 3/4x90 150# G':
            $inst['t_t_Codo3-4x90_150']=$value['cantidad'];
            break;
            case 'Codo Nip.3/4 150# G':
            $inst['t_CodoNip_3-4_150']=$value['cantidad'];
            break;
            case 'Tee 3/4 G':
            $inst['t_Tee3-4']=$value['cantidad'];
            break;
            case 'Cople 3/4 150# G':
            $inst['t_Cople3-4_150']=$value['cantidad'];
            break;
            case 'Tapon M.3/4 Neg':
            $inst['t_TaponM_3-4_Neg']=$value['cantidad'];
            break;
            case 'Conex.p/medid.dom':
            $inst['t_Conex_p_medid_dom']=$value['cantidad'];
            break;
            case 'Val.esfer.3/4 125lbs Br.':
            $inst['t_Val_esfer_3-4_125lbs']=$value['cantidad'];
            break;
            case 'Reg.Ameri.CR-4000 3/4':
            $inst['t_Reg_Ameri_CR-4000_3-4']=$value['cantidad'];
            break;
            case 'Niple 3/4 RC G':
            $inst['t_Niple_3-4_RC']=$value['cantidad'];
            break;
            case 'Niple 3/4 x2 G':
            $inst['t_Niple_3-4_x2']=$value['cantidad'];
            break;
            case 'Niple 3/4 x3 G':
            $inst['t_Niple_3-4_x3']=$value['cantidad'];
            break;
            case 'Niple 3/4 x4 G':
            $inst['t_Niple_3-4_x4']=$value['cantidad'];
            break;
            case 'Niple 3/4 x5 G':
            $inst['t_Niple_3-4_x5']=$value['cantidad'];
            break;
            case 'Niple 3/4 x6 G':
            $inst['t_Niple_3-4_x6']=$value['cantidad'];
            break;
            case 'Niple 3/4 x7 G':
            $inst['t_Niple_3-4_x7']=$value['cantidad'];
            break;
            case 'Niple 3/4 x8 G':
            $inst['t_Niple_3-4_x8']=$value['cantidad'];
            break;
            case 'Niple 3/4 x9 G':
            $inst['t_Niple_3-4_x9']=$value['cantidad'];
            break;
            case 'Niple 3/4 x10 G':
            $inst['t_Niple_3-4_x10']=$value['cantidad'];
            break;
            case 'Tue.Union 1Std.Neg':
            $inst['t_Union_1Std']=$value['cantidad'];
            break;
            case 'Red.Camp.1x3/4 G':
            $inst['t_Red_Camp_1x3-4']=$value['cantidad'];
            break;
            case 'Red.Bush.1x3/4 G':
            $inst['t_Red_Bush_1x3-4']=$value['cantidad'];
            break;
            case 'Val.esfer.1 125lbs Br.':
            $inst['t_Val_esfer_1_125lbs']=$value['cantidad'];
            break;
            case 'Niple 1/2 x 2 G':
            $inst['t_Niple_1-2_x_2']=$value['cantidad'];
            break;
            case 'Red.Bush.3/4x1/2':
            $inst['t_reduccion']=$value['cantidad'];
            break;
            case 'Sell.Bco.Plomo':
            $inst['t_blanco_plomo']=$value['cantidad'];
            break;
            case 'Cta.Teflon 3/4x260pl L':
            $inst['t_cinta_teflon']=$value['cantidad'];
            break;
        }
    }
    
    $returnData['it_instalacionesRow'] = $inst;
    $returnData = json_encode($returnData);

    $ip_cia_id = 1;
    $p_cia=$ip_cia_id;
    $p_usr_id = "migesa2";
	$it_instalaciones = $returnData;
	$jsonItSolicitud = $returnData;
    $rescallWebService=callWebService($p_cia,$p_usr_id,$it_instalaciones,$jsonItSolicitud, $reportID);
}
function callWebService($p_cia,$p_usr_id,$it_instalaciones,$jsonItSolicitud, $reportID)
{
	//ejecutamos el servicio
	if (isset($p_cia) && isset($p_usr_id) && isset($it_instalaciones) && isset($jsonItSolicitud)) {
		require_once('../../dataLayer/libs/nusoap_lib/nusoap.php');
		$DB = new DAO();
	    $conn = $DB->getConnect();

	    $arrayItSolicitud = json_decode($jsonItSolicitud);
	    $it_solicitud = (array)$arrayItSolicitud;
	    $clienteSoapMexicana = "http://111.111.111.3/wsa/wsa1/wsdl?targetURI=urn:com-mexgas-services:siscom";
	    $nuSoapClientMexicana = new nusoap_client($clienteSoapMexicana, true);
	    $nuSoapClientMexicana->forceEndpoint = "http://111.111.111.3/wsa/wsa1/";
	    $nuSoapClientMexicana->soap_defencoding = 'UTF-8';
	    $nuSoapClientMexicana->decode_utf8 = false;
	    $postData = array(
	        'p_cia' => $p_cia,
	        'p_usr_id' => $p_usr_id,
	        'it_instalaciones' => $it_solicitud,
	    );
	    $resultWsColonias = $nuSoapClientMexicana->call('ws_siscom_instala_medidor', $postData);
	    //echo $nuSoapClientMexicana->request;
	    error_log($nuSoapClientMexicana->request);
	    if ($nuSoapClientMexicana->fault) {
	        //print_r($resultWsColonias);
	    } else {
	        $err = $nuSoapClientMexicana->getError();
	    }
	    if ($err) {
	        //echo json_encode($err);
	        error_log(json_encode($err)); 
	    } else {
	        $idFormInst=getFormInst($reportID);
	        if (intval($resultWsColonias['result']) == 0) {
	        	if (isset($idFormInst) && intval($idFormInst) > 0) {
			    	$updInstSQL = "UPDATE  form_installation 
			    					   SET numInstalacionGen =? where id=?";
			    	if ($updInst = $conn->prepare($updInstSQL)) {
			    		$updInst->bind_param("si", $resultWsColonias['p_mensaje'], $idFormInst);
						if ($updInst->execute()) {
							$updateEstatusContratoSQL ="UPDATE tEstatusContrato 
													 	SET estatusAsignacionInstalacion = 54, 
													     fechaMod = NOW() 
													 	WHERE idReporte = ?;";
							if ($updateEstatusContrato = $conn->prepare($updateEstatusContratoSQL)) {
								$updateEstatusContrato->bind_param("i",$reportID);
								$updateEstatusContrato->execute();
								$result["status"] = "OK";
				                $result["code"] = "200";
				                $result["result"] = "Instalacion - #".$resultWsColonias['p_mensaje'];
							    error_log('exito al instalar medidor '.json_encode($result));
							}
						}
			    	}else{
			    		$result["status"] = "ERROR";
			            $result["code"] = "500";
			            $result["result"] = "error - ".$conn->error;
					    error_log('error al actualizar medidor '.json_encode($result));
			    	}
			    }
	        } 
	    }
	}else{
		// "result":"0","p_clasificacion":"0","descripcion_error":"Exito"}
	    $responseJson = array(
	        'result' => 0,
	        'ip_contrato' => 0,
	        'op_message' => 'No se ingreso correctamente los parametros de entrada'
	    );
	    error_log('datos vacios funcion callWebService '.json_encode($responseJson));
	}
	
}
function getFormInst($idReport)
{
	//generamos una consulta para obtener la descripcion del contrato
    if ($idReport != '') {
    	//error_log('message idReport getFormInst '.$idReport);
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT 
					   fi.id
					   FROM
					   form_installation fi,
					   reportHistory rh
					   WHERE
					   0 = 0 AND fi.consecutive = rh.idFormSell
					   AND rh.idReport = $idReport
					   AND rh.idReportType = 4;";
		//error_log('message query getFormInst '.$getNameSQL);
       	$result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}
function getIDCliente($idReport)
{
	//generamos una consulta para obtener la descripcion del contrato
	//error_log('message idReport '.$idReport);
    if ($idReport != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT 
					   idClienteGenerado
					   FROM
					   tEstatusContrato
					   WHERE
					   0 = 0 AND idReporte = $idReport;";
		//error_log('message getNameSQL '.$getNameSQL);
       	$result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[0];
            }
        }
        $conn->close();
    }
    return $res;
}
function getAgencia($idUser)
{
    //generamos una consulta para obtener la descripcion del contrato
    if ($idUser != '') {
        $DB = new DAO();
        $conn = $DB->getConnect();
        $getNameSQL = "SELECT a.id,a.nickname as nickNameEmp, (select nickname from user where id=c.idUser) as nickNameAgency 
                       FROM user a, employee b, agency c, agency_employee d
                       where 0=0
                       and a.id=b.idUser
                       and c.id=d.idAgency
                       and b.id=d.idemployee
                       and a.id = $idUser;";
        $result = $conn->query($getNameSQL);
        $res="";
        if ($result->num_rows > 0) {
            while($row = $result->fetch_array()) {
                $res=$row[2];
            }
        }
        $conn->close();
    }
    return $res;
}