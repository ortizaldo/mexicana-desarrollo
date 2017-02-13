<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";

    ini_set('memory_limit', '512M');

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
                    if( isset($_POST["plumber"]) ) {
                        $data = $_POST["plumber"];
                        $data = base64_decode($data);

                        $dir = "../../uploads/";

                        $typeExt = ".png";
                        $MIMEtype = "image/png";

                        list($name, $lastName, $request, $tapon, $documentNumber, $ri, $preassureFalls, $diagrama, $observations, $newPipe, $pipesInstallation, $pipesCount, $ph, $latitude, $longitude, $rightFootBrand) = explode("&", $data);

                        /*$diagram = (array) json_decode($diagrama);
                        print_r($diagram);

                        foreach( $diagram["diagram"] as $value ) {
                            $dataDiagram = (array) $value;

                            var_dump($dataDiagram);

                            foreach ($dataDiagram as $insValue) {
                                var_dump($insValue);
                            }
                        }*/

                        $createPlumberReport = $conn->prepare("INSERT INTO `form_plumber`(`name`, `lastName`, `request`, `tapon`, `documentNumber`, `ri`, `observations`, `newPipe`, `ph`, `pipesCount`, `meeting`, `latitude`, `longitude`, `created_at`, `updated_at`, `active`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW(), NOW(), 1);");
                        $createPlumberReport->bind_param("sssisisiiidd", $name, $lastName, $request, $tapon, $documentNumber, $ri, $observations, $newPipe, $ph, $pipesCount, $latitude, $longitude);

                        if( $createPlumberReport->execute() ) {
                            $idPlumberReport = $createPlumberReport->insert_id;

                            //$pipesInstallation //photo about pipes installation
                            //$rightFootBrand //Information

                            var_dump($pipesInstallation);

                            var_dump($rightFootBrand);

                            $imagePipes = base64_decode($pipesInstallation);
                            $sourcePipesPhoto = imagecreatefromstring($imagePipes);
                            $imagePipesName = "pipesPhoto_reportPlumber_".$idPlumberReport.".png";
                            $imagePipesSize = filesize($imagePipes);
                            $urlPipesPhoto = $dir.$imagePipes;
                            $imageSave = imagejpeg($sourcePipesPhoto, $imagePipes, 100);
                            imagedestroy($sourcePipesPhoto);

                            $insertmultimediaPlumberPipes = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertmultimediaPlumberPipes->bind_param("sssss", $urlPipesPhoto, $imagePipesName, $typeExt, $MIMEtype, $imagePipesSize);

                            $insertmultimediaPlumberPipes->execute();

                            $insertmultimediaPlumberPipes->error;

                            $pipesImg = $insertmultimediaPlumberPipes->insert_id;

                            $insertPlumberPipesPhoto = $conn->prepare("INSERT INTO `form_plumber_multimedia`(`idFormPlumber`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                            $insertPlumberPipesPhoto->bind_param("ii",  $idPlumberReport, $pipesImg);

                            $insertPlumberPipesPhoto->excute();

                            var_dump($insertPlumberPipesPhoto);

                            $fallsMeasurement = (array) json_decode($preassureFalls);
                            $diagram = (array) json_decode($diagrama);

                            var_dump($fallsMeasurement);

                            foreach( $fallsMeasurement["medidas"] as $key ) {
                                $data = (array) $key;

                                print_r($data);

                                $createPlumberDetails = $conn->prepare("INSERT INTO `form_plumber_details`(`path`, `distance`, `pipe`, `fall`, `idFormPlumber`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");

                                var_dump($data["tramo"]);var_dump($data["distancia"]);var_dump($data["tuberia"]);var_dump($data["caida"]);var_dump($idPlumberReport);

                                $createPlumberDetails->bind_param("ssssi", $data["tramo"], $data["distancia"], $data["tuberia"], $data["caida"], $idPlumberReport);

                                $createPlumberDetails->excute();
                                exit;
                                /*$response = null;

                                $response["status"] = "OK";
                                $response["code"] = "200";
                                $response["reference Response"] = $createPlumberDetails->insert_id;
                                echo json_encode($response);*/
                            }



                            foreach( $diagram["diagram"] as $value ) {
                                $dataDiagram = (array) $value;

                                var_dump($dataDiagram);

                                foreach ( $dataDiagram as $insValue ) {
                                    var_dump($insValue);
                                }

                                /*$dataDiagram["photo1"]
                                $dataDiagram["photo2"]
                                $dataDiagram["photo3"]
                                $dataDiagram["photo4"]
                                $dataDiagram["photo5"]
                                $dataDiagram["photo6"]*/

                                /*desc form_plumber_multimedia;
                                +---------------+---------------------+------+-----+---------+----------------+
                                | Field         | Type                | Null | Key | Default | Extra          |
                                +---------------+---------------------+------+-----+---------+----------------+
                                | id            | int(10) unsigned    | NO   | PRI | NULL    | auto_increment |
                                | idFormPlumber | int(10) unsigned    | NO   | MUL | NULL    |                |
                                | idMultimedia  | bigint(20) unsigned | NO   | MUL | NULL    |                |
                                | created_at    | datetime            | YES  |     | NULL    |                |
                                | updated_at    | datetime            | YES  |     | NULL    |                |
                                +---------------+---------------------+------+-----+---------+----------------+*/

                                /*$createPlumberDetails = $conn->prepare("INSERT INTO `form_plumber_multimedia`(`idFormPlumber`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createPlumberDetails->bind_param("ii",  $->insert_id], $->insert_id);

                                $createPlumberDetails->excute();*/
                            }

                            /*$imageDataHome = base64_decode($homeDocs);
                            $sourceHomePhoto = imagecreatefromstring($imageDataHome);
                            $imageHomeName = "comprobanteDomicilio_sell_".$idSell.".png";
                            $imageHomeSize = filesize($imageHomeName);
                            $urlPhotoHome = $dir.$imageHomeName;
                            $imageSave = imagejpeg($sourceHomePhoto, $imageHomeName, 100);
                            imagedestroy($sourceHomePhoto);

                            $imageDataIdentification = base64_decode($identification);
                            $sourceIdentification = imagecreatefromstring($imageDataIdentification);
                            $imageNameIdentification = "identification_sell_".$idSell.".png";
                            $imageIdentificationSize = filesize($imageNameIdentification);
                            $urlPhotoIdentification = $dir.$imageNameIdentification;
                            $imageSave = imagejpeg($sourceIdentification, $imageNameIdentification, 100);
                            imagedestroy($sourceIdentification);

                            $imageDataRequest = base64_decode(requestPaper);
                            $sourceRequest = imagecreatefromstring($imageDataRequest);
                            $imageNameRequest = "request_sell_".$idSell.".png";
                            $imageRequestSize = filesize($imageNameRequest);
                            $urlPhotoRequest = $dir.$imageNameRequest;
                            $imageSave = imagejpeg($sourceRequest, $imageNameRequest, 100);
                            imagedestroy($source);

                            $imageDataPayer = base64_decode($payerSheet);
                            $sourcePayer = imagecreatefromstring($imageDataPayer);
                            $imageNamePayer = "payer_sell_".$idSell.".png";
                            $imagePayerSize = filesize($imageNamePayer);
                            $urlPhotoPayer = $dir.$imageNamePayer;
                            $imageSave = imagejpeg($sourcePayer, $imageNamePayer, 100);
                            imagedestroy($sourcePayer);

                            $imageDataPrivacy = base64_decode($privacyAgreement);
                            $sourcePrivacy = imagecreatefromstring($imageDataPrivacy);
                            $imageNamePrivacy = "privacy_sell_".$idSell.".png";
                            $imagePrivacySize = filesize($imageNamePrivacy);
                            $urlPhotoPrivacy = $dir.$imageNamePrivacy;
                            $imageSave = imagejpeg($imageNamePrivacy, $imageNamePrivacy, 100);
                            imagedestroy($sourcePrivacy);

                            $imageDataAgreement = base64_decode($agreement);
                            $sourceAgreement = imagecreatefromstring($imageDataAgreement);
                            $imageNameAgreement = "agreement_sell_".$idSell.".png";
                            $imageAgreementSize = filesize($imageNameAgreement);
                            $urlPhotoAgreement = $dir.$imageNameAgreement;
                            $imageSave = imagejpeg($sourceAgreement, $imageNameAgreement, 100);
                            imagedestroy($sourceAgreement);

                            $insertMultimediaHomeDocs = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaHomeDocs->bind_param("sssss", $urlPhotoHome, $imageHomeName, $typeExt, $MIMEtype, $imageHomeSize);

                            $insertMultimediaIdentification = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaIdentification->bind_param("sssss", $urlPhotoIdentification, $imageNameIdentification, $typeExt, $MIMEtype, $imageIdentificationSize);

                            $insertMultimediaRequest = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaRequest->bind_param("sssss", $urlPhotoRequest, $imageNameRequest, $typeExt, $MIMEtype, $imageRequestSize);

                            $insertMultimediaPayer = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaPayer->bind_param("sssss", $urlPhotoPayer, $imageNamePayer, $typeExt, $MIMEtype, $imagePayerSize);

                            $insertMultimediaPrivacy = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaPrivacy->bind_param("sssss", $urlPhotoPrivacy, $imageNamePrivacy, $typeExt, $MIMEtype, $imagePrivacySize);

                            $insertMultimediaAgreement = $conn->prepare("INSERT INTO `multimedia`(`content`, `name`, `extension`, `type`, `size`, `created_at`, `updated_at`) VALUES(?, ?, ?, ?, ?, NOW(), NOW());");
                            $insertMultimediaAgreement->bind_param("sssss", $urlPhotoAgreement, $imageNameAgreement, $typeExt, $MIMEtype, $imageAgreementSize);

                            if( $insertMultimediaHomeDocs->execute() && $insertMultimediaIdentification->execute() && $insertMultimediaRequest->execute()
                                && $insertMultimediaPayer->execute() && $insertMultimediaPrivacy->execute() && $insertMultimediaAgreement->execute() ) {

                                $createSellHomeMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellHomeMultimedia->bind_param("ii", $idSell, $insertMultimediaHomeDocs->insert_id);

                                $createSellHomeMultimedia->execute();

                                $createSellIdentificationMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellIdentificationMultimedia->bind_param("ii", $idSell, $insertMultimediaIdentification->insert_id);

                                $createSellIdentificationMultimedia->execute();

                                $createSellRequestMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellRequestMultimedia->bind_param("ii", $idSell, $insertMultimediaRequest->insert_id);

                                $createSellRequestMultimedia->execute();

                                $createSellPayerMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellPayerMultimedia->bind_param("ii", $idSell, $insertMultimediaPayer->insert_id);

                                $createSellPayerMultimedia->execute();

                                $createSellPrivacyMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellPrivacyMultimedia->bind_param("ii", $idSell, $insertMultimediaPrivacy->insert_id);

                                $createSellPrivacyMultimedia->execute();

                                $createSellAgreementMultimedia = $conn->prepare("INSERT INTO `form_sells_multimedia`(`idSell`, `idMultimedia`, `created_at`, `updated_at`) VALUES(?, ?, NOW(), NOW());");
                                $createSellAgreementMultimedia->bind_param("ii", $idSell, $insertMultimediaAgreement->insert_id);

                                $createSellAgreementMultimedia->execute();

                                if( $createSellHomeMultimedia->execute() && $createSellIdentificationMultimedia->execute() ) {

                                    $response["status"] = "OK";
                                    $response["code"] = "200";
                                    $response["response"] = "Report sell created";
                                    $response["reportId"] = $idSell;
                                    echo json_encode($response);

                                }
                            }*/
                        }
                    }
                }
            }
        }
        $conn->close();
    }