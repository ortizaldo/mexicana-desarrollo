<?php include_once "../DAO.php";
include_once "../libs/utils.php";
session_start();

class forms
{

$DB = new DAO();
$conn = $DB->getConnect();

var $forms = [];

    function __Construct()
    {
    }
//Search Form By parameter idForm
/*
 * @Param idForm INT
 *
 * */
public form($idForm)
{

}

public $forms()
{

}

/*--------------------Form Sell-------------------------------*/
//Busqueda del reporte por el parámetro idReport
/*
 * @Param idReport INT
 *
 * */
public getFormSellByReport()
{


}
//Busqueda de la validación para el reporte por el parámetro idForm
/*
 * @Param idForm INT
 *
 * */
public getFormSellValidationStatusByIdForm($idForm)
{
    $getFormSellValidation = $conn->prepare("SELECT id, validate FROM form_sells_validation WHERE idFormSell = ?;");
    $getFormSellValidation->bind_param("i", $idForm);
    if ($getFormSellValidation->execute()) {
        $getFormSellValidation->store_result();
        $getFormSellValidation->bind_result($idFormValidation, $validateStatus);
        if ($getFormSellValidation->fetch()) {
            $form['idFormValidation'] = $idFormValidation;
            $form['validateStatus'] = $validateStatus;

            $forms[] = $form;
        } else {
            $form['idFormValidation'] = "0";
            $form['validateStatus'] = "ValidationPending";

            $forms[] = $form;
        }
    }
    return $forms;
}

//Busqueda de la validación para el reporte por el parámetro idForm
/*
 * @Param idForm INT
 *
 * */
public checkValidationStatus()
{
    //ASIGNAR A ADMINISTRADORES DE MEXICANA
    $rol = 2;
    $getAdmins = $conn->prepare("SELECT US.id, US.nickname, RL.type FROM user AS US INNER JOIN user_rol AS USRL ON US.id = USRL.idUser INNER JOIN rol AS RL ON USRL.idRol = RL.id WHERE RL.id = ?;");
    $getAdmins->bind_param("i", $rol);

    if ($getAdmins->execute()) {
        $getAdmins->store_result();
        $getAdmins->bind_result($idUserAdmin, $nickname, $rol);
        while ($getAdmins->fetch()) {
            $admin["idUserAdmin"] = $idUserAdmin;
            $admin["nickname"] = $nickname;
            $admin["rol"] = $rol;
            $admins[] = $admin;
        }

        $admins = json_encode($admins);

        $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET employeesAssigned = ? WHERE id = ?;");
        $getFinancialAgencyEmployees->bind_param("si", $admins, $reportID);
        $getFinancialAgencyEmployees->execute();
    }

    $secondSell = 5;
    $getFinancialAgencyEmployees = $conn->prepare("UPDATE report SET idReportType = ? WHERE id = ?;");
    $getFinancialAgencyEmployees->bind_param("si", $secondSell, $reportID);
    $getFinancialAgencyEmployees->execute();
    $idStatus = 1;
    $getFinancialAgencyEmployees = $conn->prepare("UPDATE workflow_status_report SET idStatus = ? WHERE idReport = ?;");
    $getFinancialAgencyEmployees->bind_param("si", $idStatus, $reportID);
    $getFinancialAgencyEmployees->execute();
}

/*
 * @Param idForm INT
 *
 * */
public validateSell()
{


}


}