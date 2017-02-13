<?php
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$idReport=$_POST["idReport"];
$Consecutive=$_POST["Consecutive"];
$Agency=$_POST["Agency"];
$NextSellPayment=$_POST["NextSellPayment"];
$Agreement=$_POST["Agreement"];
$LastName1=$_POST["LastName1"];
$Name=$_POST["Name"];
$CURP=$_POST["CURP"];
$Engagment=$_POST["Engagment"];
$IdCard=$_POST["IdCard"];
$NextSellAgency=$_POST["NextSellAgency"];
$RequestDate=$_POST["RequestDate"];
$LastName2=$_POST["LastName2"];
$RFC=$_POST["RFC"];
$Email=$_POST["Email"];
$NextSellGender=$_POST["NextSellGender"];
$NextSellIdentification=$_POST["NextSellIdentification"];
$NextSellBirthdate=$_POST["NextSellBirthdate"];
$NextStepSaleState=$_POST["NextStepSaleState"];
$NextStepSaleColonia=$_POST["NextStepSaleColonia"];
$NextStepSaleInHome=$_POST["NextStepSaleInHome"];
$NextSellCellularPhone=$_POST["NextSellCellularPhone"];
$NextSellEnterprise=$_POST["NextSellEnterprise"];
$NextSellPosition=$_POST["NextSellPosition"];
$NextSellJobTelephone=$_POST["NextSellJobTelephone"];
$NextSellCountry=$_POST["NextSellCountry"];
$NextStepSaleCity=$_POST["NextStepSaleCity"];
$NextStepSaleStreet=$_POST["NextStepSaleStreet"];
$NextSellPhone=$_POST["NextSellPhone"];
$NextSellJobLocation=$_POST["NextSellJobLocation"];
$NextSellJobActivity=$_POST["NextSellJobActivity"];
$NextStepSaleAgreegmentType=$_POST["NextStepSaleAgreegmentType"];
$NextSellPrice=$_POST["NextSellPrice"];
$NextSellPaymentTime=$_POST["NextSellPaymentTime"];
$NextSellMonthlyCost=$_POST["NextSellMonthlyCost"];
$NextSellRI=$_POST["NextSellRI"];
$NextSellDateRI=$_POST["NextSellDateRI"];



$DB = new DAO();
$conn = $DB->getConnect();


insertarSegundaVenta($conn, $idReport, $Consecutive, $Agency,
    $NextSellPayment, $Agreement, $LastName1, $Name, $CURP, $Engagment, $IdCard,
    $NextSellAgency,$RequestDate,$LastName2,$RFC,$Email,$NextSellGender,$NextSellIdentification,
    $NextSellBirthdate,$NextStepSaleState,$NextStepSaleColonia,$NextStepSaleInHome,$NextSellCellularPhone,$NextSellEnterprise,
    $NextSellPosition,$NextSellJobTelephone,$NextSellCountry,$NextStepSaleCity,$NextStepSaleStreet,$NextSellPhone,$NextSellJobLocation,
    $NextSellJobActivity,$NextStepSaleAgreegmentType,$NextSellPrice,$NextSellPaymentTime,$NextSellMonthlyCost,$NextSellRI,
    $NextSellDateRI);



function insertarSegundaVenta($conn, $idReport, $Consecutive, $Agency,
                              $NextSellPayment, $Agreement, $LastName1, $Name, $CURP, $Engagment, $IdCard,
                              $NextSellAgency,$RequestDate,$LastName2,$RFC,$Email,$NextSellGender,$NextSellIdentification,
                              $NextSellBirthdate,$NextStepSaleState,$NextStepSaleColonia,$NextStepSaleInHome,$NextSellCellularPhone,$NextSellEnterprise,
                              $NextSellPosition,$NextSellJobTelephone,$NextSellCountry,$NextStepSaleCity,$NextStepSaleStreet,$NextSellPhone,$NextSellJobLocation,
                              $NextSellJobActivity,$NextStepSaleAgreegmentType,$NextSellPrice,$NextSellPaymentTime,$NextSellMonthlyCost,$NextSellRI,
                              $NextSellDateRI)
{
    /****PRIMERO SETEAMOS LAS VARIABLES DE ENTRADA DEL STOREPROCEDURE PARA QUE ESTEN
     * LISTAS EN MYSQL*/
    $responseArray=array();
    $status = "";
    $code = "";
    $response="";
    $idSegundaVenta="";

    /***UNA VEZ SETEADAS ENTONCES PROCEDEMOS A PASARLAS Y PREPARAR LA LLAMADA AL STOREPROCEDURE**/
    $stmtinsertarSegundaVenta = $conn->prepare("call spInsertarSegundaVenta(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmtinsertarSegundaVenta, 'iiidssssssssssssssssssssssssssssissds',
        $idReport, $Consecutive, $Agency, $NextSellPayment,
        $Agreement, $LastName1, $Name, $CURP, $Engagment,
        $IdCard,$NextSellAgency,$RequestDate,$LastName2,$RFC,
        $Email,$NextSellGender,$NextSellIdentification, $NextSellBirthdate,$NextStepSaleState,
        $NextStepSaleColonia,$NextStepSaleInHome,$NextSellCellularPhone,$NextSellEnterprise,  $NextSellPosition,
        $NextSellJobTelephone,$NextSellCountry,$NextStepSaleCity,$NextStepSaleStreet,$NextSellPhone,
        $NextSellJobLocation, $NextSellJobActivity,$NextStepSaleAgreegmentType,$NextSellPrice,$NextSellPaymentTime,
        $NextSellMonthlyCost,$NextSellRI,$NextSellDateRI);
    //grabarLog(json_encode($stmtinsertarSegundaVenta),"InsertarSegundaVentaStatement");
    if ($stmtinsertarSegundaVenta->execute()) {
        $stmtinsertarSegundaVenta->store_result();
        $stmtinsertarSegundaVenta->bind_result($status, $code,$response,$idSegundaVenta);
        if ($stmtinsertarSegundaVenta->fetch()) {
            $responseArray["status"] = $status;
            $responseArray["code"] = $code;
            $responseArray["response"] = $response;
            $responseArray["idSegundaVenta"] = $idSegundaVenta;
        }
        //grabarLog(json_encode($stmtinsertarSegundaVenta),"InsertarSegundaVentaStatementResponse");
        $conn->close();
        echo json_encode($responseArray);

    }
}


function grabarLog($logInfo, $nombreArchivo)
{
    /***TODO LOGS PARA EL SERVICIO**/
    $dir = "../../logs/";
    $fileName = $nombreArchivo . ".txt";
    $fileData = fopen($dir . $fileName, "w");
    fwrite($fileData, $logInfo);
    fclose($fileData);

}
