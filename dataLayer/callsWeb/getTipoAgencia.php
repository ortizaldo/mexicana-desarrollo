<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
//consultamos el catalogo de vivienda
$DB = new DAO();
$conn = $DB->getConnect();
$nickname=$_GET['nicknamAgencia'];
$idReporte=$_GET['idReporte'];
$getTipoAgenciaSQL = "SELECT 
                        b.tipo
                    FROM
                        user a,
                        agency b
                    WHERE
                        0 = 0 
                        AND a.id = b.idUser 
                        AND a.nickname=?";
if ($getTipoAgencia = $conn->prepare($getTipoAgenciaSQL)) {
    //devolvemos la respuesta
    $getTipoAgencia->bind_param("s", $nickname);
    if ($getTipoAgencia->execute()) {
        $getTipoAgencia->store_result();
        $getTipoAgencia->bind_result($tipoAgencia);
        $cont=0;
        while ($getTipoAgencia->fetch()) {
            $requests[$cont]["tipoAgencia"] = $tipoAgencia;
            $cont++;
        }
    }else{
        error_log('message error');
    }
    $response["status"] = "OK";
    $response["code"] = "200";
    $response["response"] = $requests;
    $response["ReportesAsignados"] = getReportesAsignados($idReporte, $conn);
    $requests = null;
}
echo json_encode($response);

function getReportesAsignados($idReporte, $conn)
{
    if ($idReporte != "") {
        $requests = [];
        $stmtReportes = "SELECT 
                            a.id, a.agreementNumber, c.id, c.name
                        FROM
                            report a,
                            reportHistory b,
                            reportType c
                        WHERE
                        0 = 0 AND a.id = b.idReport
                        AND c.id = b.idReportType
                        AND a.id = ?;";

        if ($connReportes = $conn->prepare($stmtReportes)) {
            $connReportes->bind_param("i",$idReporte);
            //devolvemos la respuesta
            if ($connReportes->execute()) {
                $connReportes->store_result();
                $connReportes->bind_result($id, $agreementNumber, $idReportType, $name);
                //var_dump($connReportes);
                $cont=0;
                while ($connReportes->fetch()) {
                    $requests[$cont]["idReporte"] = $id;
                    $requests[$cont]["numeroContrato"] = $agreementNumber;
                    $requests[$cont]["idReportType"] = $idReportType;
                    $requests[$cont]["tipoReporte"] = $name;
                    $cont++;
                }
            }
        }
        return $requests;
    }
}