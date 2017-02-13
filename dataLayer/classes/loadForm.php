<?php include_once "../DAO.php";

class loadForm {
    var $conn = ""; var $type = ""; var $formNumber = 0; $reports = []; $this->report = [];

    function __Construct($type, $formNumber) {
        $DB = new DAO();
        $this->conn = $DB->getConnect();

        $this->type = $type;
        $this->formNumber = $formNumber;
    }

    function getType() {
        return $this->type;
    }

    function getFormNumber() {
        return $this->formNumber;
    }

    function getFormCensus() {
        $stmtGetForm = $this->conn->prepare("call spGetform('Censo', ?);");
        mysqli_stmt_bind_param($stmtGetForm, "i", $this->formNumber);
        if ($stmtGetForm->execute()) {
            $stmtGetForm->store_result();
            $stmtGetForm->bind_result($id, $lote, $houseStatus, $nivel, $content, $name, $giro, $acometida, $observacion, $tapon, $medidor, $marca, $tipo, $NoSerie, $niple);
            while ($stmtGetForm->fetch()) {
                $this->report['id'] = $id;
                $this->report['lote'] = $lote;
                $this->report['houseStatus'] = $houseStatus;
                $this->report['nivel'] = $nivel;
                $this->report['content'] = $content;
                $this->report['name'] = $name;
                $this->report['giro'] = $giro;
                $this->report['acometida'] = $acometida;
                $this->report['observacion'] = $observacion;
                $this->report['tapon'] = $tapon;
                $this->report['medidor'] = $medidor;
                $this->report['marca'] = $marca;
                $this->report['tipo'] = $tipo;
                $this->report['NoSerie'] = $NoSerie;
                $this->report['niple'] = $niple;
                $this->reports[] = $this->report;
            }
        }
        return $this->reports;
    }

    function getFormSell() {
        $stmtGetForm = $this->conn->prepare("call spGetform('Venta', ?);");
        mysqli_stmt_bind_param($stmtGetForm, "i", $this->formNumber);
        if ($stmtGetForm->execute()) {
            $stmtGetForm->store_result();
            $stmtGetForm->bind_result($id, $prospect, $uninteresting, $comments, $owner, $consecutive, $name, $lastName, $lastNameOp, $payment, $financialService, $requestNumber, $meeting, $content, $nameImg,$estatusVentaUno);
            while ($stmtGetForm->fetch()) {
                $this->report['ID'] = $id;
                $this->report['prospect'] = $prospect;
                $this->report['uninteresting'] = $uninteresting;
                $this->report['comments'] = $comments;
                $this->report['owner'] = $owner;
                $this->report['consecutive'] = $consecutive;
                $this->report['userName'] = $name;
                $this->report['lastName'] = $lastName;
                $this->report['lastNameOp'] = $lastNameOp;
                $this->report['payment'] = $payment;
                $this->report['financialService'] = $financialService;
                $this->report['requestNumber'] = $requestNumber;
                $this->report['meeting'] = $meeting;
                $this->report['content'] = $content;
                $this->report['nameImg'] = $nameImg;
                $this->report['estatusVentaUno'] = $estatusVentaUno;
                $this->reports[] = $this->report;

            }
        }
        return $this->reports;
    }

    function getFormPH() {
        $stmtGetForm = $this->conn->prepare("call spGetform('Plomero', ?);");
        mysqli_stmt_bind_param($stmtGetForm, "i", $this->formNumber);
        if ($stmtGetForm->execute()) {
            $stmtGetForm->store_result();
            $stmtGetForm->bind_result($id, $name, $lastName, $lastNameM, $request, $documentNumber, $tapon, $ri, $observations, $newPipe, $content, $name, $ph, $pipesCount, $path, $distance, $pipe, $fall);
            while ($stmtGetForm->fetch()) {
                $this->report['ID'] = $id;
                $this->report['name'] = $name;
                $this->report['lastName'] = $lastName;
                $this->report['lastNameM'] = $lastNameM;
                $this->report['request'] = $request;
                $this->report['documentNumber'] = $documentNumber;
                $this->report['tapon'] = $tapon;
                $this->report['ri'] = $ri;
                $this->report['observations'] = $observations;
                $this->report['newPipe'] = $newPipe;
                $this->report['content'] = $content;
                $this->report['name'] = $name;
                $this->report['ph'] = $ph;
                $this->report['pipesCount'] = $pipesCount;
                $this->report['path'] = $path;
                $this->report['distance'] = $distance;
                $this->report['pipe'] = $pipe;
                $this->report['fall'] = $fall;
                $this->reports[] = $this->report;
            }
        }
        
        return $this->reports;
    }

    function getFormInstallation() {
        $stmtGetForm = $this->conn->prepare("call spGetform('Instalacion', ?);");
        mysqli_stmt_bind_param($stmtGetForm, "i", $this->formNumber);

        if ($formTypeQuery->execute()) {
            $formTypeQuery->store_result();
            $formTypeQuery->bind_result($id, $name, $lastName, $request, $phLabel, $agencyPh, $agencyNumber, $installation, $abnormalities, $comments, $brand, $type, $serialNuber, $measurement, $latitude, $longitude, $created_at, $content);
            while ($formTypeQuery->fetch()) {
                $this->report['ID'] = $id;
                $this->report['name'] = $name;
                $this->report['lastName'] = $lastName;
                $this->report['request'] = $request;
                $this->report['phLabel'] = $phLabel;
                $this->report['agencyPh'] = $agencyPh;
                $this->report['agencyNumber'] = $agencyNumber;
                $this->report['installation'] = $installation;
                $this->report['abnormalities'] = $abnormalities;
                $this->report['comments'] = $comments;
                $this->report['brand'] = $brand;
                $this->report['type'] = $type;
                $this->report['serialNuber'] = $serialNuber;
                $this->report['measurement'] = $measurement;
                $this->report['latitude'] = $latitude;
                $this->report['longitude'] = $longitude;
                $this->report['created_at'] = $created_at;
                $this->report['content'] = $content;
                $this->reports[] = $this->report;
            }
        }
        return $this->reports;
    }

    function getFormSecondSell() {
        $stmtGetForm = $this->conn->prepare("call spGetform('Venta', ?);");
        mysqli_stmt_bind_param($stmtGetForm, "i", $this->formNumber);
        if ($stmtGetForm->execute()) {
            $stmtGetForm->store_result();
            $stmtGetForm->bind_result($clientName, $clientlastName, $clientlastName2, $clientBirthDate, $clientBirthCountry,
                $clientgender, $clientRFC, $clientCURP, $clientEmail, $clientRelationship, $identificationType, $idState, $idCity,
                $idColonia, $street, $inHome, $homeTelephone, $celullarTelephone, $clientJobEnterprise, $clientJobRange,
                $clientJobActivity, $clientJobTelephone, $clientJobLocation, $consecutive, $agreegment, $agency, $payment,
                $agreementType, $requestDate, $price, $agreementExpires, $agreementMonthlyPayment, $agreementRi, 
                $agreementRiDate, $name, $telephone, $jobTelephone, $ext);
            while ($stmtGetForm->fetch()) {
                $this->report['clientName'] = $clientName;
                $this->report['clientlastName'] = $clientlastName;
                $this->report['clientlastName2'] = $clientlastName2;
                $this->report['clientBirthDate'] = $clientBirthDate;
                $this->report['clientBirthCountry'] = $clientBirthCountry;
                $this->report['clientgender'] = $clientgender;
                $this->report['clientRFC'] = $clientRFC;
                $this->report['clientCURP'] = $clientCURP;
                $this->report['clientEmail'] = $clientEmail;
                $this->report['clientRelationship'] = $clientRelationship;
                $this->report['identificationType'] = $identificationType;
                $this->report['idState'] = $idState;
                $this->report['idCity'] = $idCity;
                $this->report['idColonia'] = $idColonia;
                $this->report['street'] = $street;
                $this->report['inHome'] = $inHome;
                $this->report['homeTelephone'] = $homeTelephone;
                $this->report['celullarTelephone'] = $celullarTelephone;
                $this->report['clientJobEnterprise'] = $clientJobEnterprise;
                $this->report['clientJobRange'] = $clientJobRange;
                $this->report['clientJobActivity'] = $clientJobActivity;
                $this->report['clientJobTelephone'] = $clientJobTelephone;
                $this->report['clientJobLocation'] = $clientJobLocation;
                $this->report['consecutive'] = $consecutive;
                $this->report['agreegment'] = $agreegment;
                $this->report['agency'] = $agency;
                $this->report['payment'] = $payment;
                $this->report['agreementType'] = $agreementType;
                $this->report['requestDate'] = $requestDate;
                $this->report['price'] = $price;
                $this->report['agreementExpires'] = $agreementExpires;
                $this->report['agreementMonthlyPayment'] = $agreementMonthlyPayment;
                $this->report['agreementRi'] = $agreementRi;
                $this->report['agreementRiDate'] = $agreementRiDate;
                $this->report['name'] = $name;
                $this->report['telephone'] = $telephone;
                $this->report['jobTelephone'] = $jobTelephone;
                $this->report['ext'] = $ext;
                $this->reports[] = $this->report;
            }
        }
        return $this->reports;
    }
}