<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";

    if ( isset($_POST["token"]) )
    {
        $DB = new DAO();
        $conn = $DB->getConnect();

        $token = $_POST["token"];
        $token = base64_decode($token);

        list($id, $username, $password, $value, $idDevice) = explode("&", $token);

        $searchToken = $conn->prepare("SELECT `user`.`token` FROM `user` WHERE `user`.`id` = ? AND `user`.`active` = 1;");
        $searchToken->bind_param('i', $id);

        if( $searchToken->execute() ) {

            $searchToken->store_result();
            $searchToken->bind_result($userToken);

            if( $searchToken->fetch() ) {
                if ( $_POST["token"] == $userToken ) {
                    if( isset($_POST["census"]) ) {
                        $data = $_POST["census"];
                        $data = base64_decode($data);

                        list($terrain, $homeStatus, $NSE, $use, $acometida, $photoAcometida, $comments, $color,
                            $measurer, $measurerPhoto, $measurerBrand, $measurerType,
                            $measurerSerialNumber, $cut) = explode("&", $data);

                        $createCensus = $conn->prepare("INSERT INTO `form_census`(`lote`, `houseStatus`, `nivel`, `giro`, `acometida`, `observacion`,`tapon`, `medidor`, `marca`, `tipo`, `NoSerie`, `niple`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), 1);");
                        $createCensus->bind_param("ssssisiisssi", $terrain, $homeStatus, $NSE, $use, $acometida, $comments, $color, $measurer, $measurerBrand, $measurerType, $measurerSerialNumber, $cut);

                        if( $createCensus->execute() ) {
                            $idCenso = $createCensus->insert_id;

                            $dir = "../../uploads/";

                            $typeExt = ".png";
                            $MIMEtype = "image/png";

                            $imageData = base64_decode($photoAcometida);
                            $source = imagecreatefromstring($imageData);
                            $imageName = "acometida_censo_".$idCenso.".png";
                            $imageAcometidaSize = filesize($imageName);
                            $urlPhotoAcometida = $dir.$imageName;
                            $imageSave = imagejpeg($source, $imageName, 100);
                            imagedestroy($source);

                            $imageDataMeasurer = base64_decode($measurerPhoto);
                            $sourceMeasurer = imagecreatefromstring($imageDataMeasurer);
                            $imageNameMeasurer = "measurer_censo_".$idCenso.".png";
                            $imageMeasurerSize = filesize($imageNameMeasurer);
                            $urlPhotoMeasurer = $dir.$imageNameMeasurer;
                            $imageSave = imagejpeg($sourceMeasurer, $imageNameMeasurer, 100);
                            imagedestroy($sourceMeasurer);

                            $insertMultimediaAcometida = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaAcometida->bind_param("sssss", $urlPhotoAcometida, $imageName, $typeExt, $MIMEtype, $imageAcometidaSize);

                            $insertMultimediaMeasurer = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaMeasurer->bind_param("sssss", $urlPhotoMeasurer, $imageNameMeasurer, $typeExt, $MIMEtype, $imageMeasurerSize);

                            if( $insertMultimediaAcometida->execute() && $insertMultimediaMeasurer->execute() ) {

                                $createCensusAcometidaMultimedia = $conn->prepare("INSERT INTO `form_census_multimedia`(`idFormCensus`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createCensusAcometidaMultimedia->bind_param("ii", $createCensus->insert_id, $insertMultimediaAcometida->insert_id);

                                if( $createCensusAcometidaMultimedia->execute() ) {
                                    $createCensusMeasurerMultimedia = $conn->prepare("INSERT INTO `form_census_multimedia`(`idFormCensus`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                    $createCensusMeasurerMultimedia->bind_param("ii", $createCensus->insert_id, $insertMultimediaMeasurer->insert_id);

                                    if( $createCensusMeasurerMultimedia->execute() ) {
                                        $response["status"] = "OK";
                                        $response["code"] = "200";
                                        $response["response"] = "Report census created";
                                        $response["reportId"] = $createCensus->insert_id;
                                        echo json_encode($response);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $conn->close();
    }