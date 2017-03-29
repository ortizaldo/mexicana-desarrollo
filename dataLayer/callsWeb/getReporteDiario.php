<?php
session_start();
include_once dirname(dirname(dirname(__FILE__))) . "/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
if (isset($_GET["startMonth"]) && isset($_GET["ayer"]) && isset($_GET["hoy"])) {
    $startMonth = $_GET["startMonth"];
    $ayer = $_GET["ayer"];

    $hoyMañana = $_GET["hoy"].' 00:00:00';
    $hoyTarde = $_GET["hoy"].' 23:59:59';

    $total = "total";
    $response["total"]["contratos"] = getTotalMes($total, $startMonth, $ayer);
    $response["total"]["medidores"] = getNumInstaladosTodos($startMonth, $ayer);
    $total = "";
    $response["desglose"]["agencias"] = getTotalMes($total,$startMonth, $ayer);
    $response["desglose"]["sucursal"] = getTotalMesSucursales($total, $startMonth, $ayer);
    $total = "financieras";
    $response["desglose"]["financieras"] = getTotalMesSucursales($total, $startMonth, $ayer);
    $response["desglose"]["desgloseFinanciamiento"] = getTotalDesgloseFinancieras($startMonth, $ayer);
    $total = "totalInst";
    $response["desglose"]["agenciaInst"] = getTotalMes($total, $startMonth, $ayer);


    $total = "total";
    $response["hoy"]["total"]["contratos"] = getTotalMesActual($total, $startMonth, $hoyMañana, $hoyTarde);
    $response["hoy"]["total"]["medidores"] = getNumInstaladosTodosActual($startMonth, $hoyMañana, $hoyTarde);
    $total = "";
    $response["hoy"]["desglose"]["agencias"] = getTotalMesActual($total,$startMonth, $hoyMañana, $hoyTarde);
    $response["hoy"]["desglose"]["sucursal"] = getTotalMesSucursalesActual($total, $startMonth, $hoyMañana, $hoyTarde);
    $total = "financieras";
    $response["hoy"]["desglose"]["financieras"] = getTotalMesSucursalesActual($total, $startMonth, $hoyMañana, $hoyTarde);
    $response["hoy"]["desglose"]["desgloseFinanciamiento"] = getTotalDesgloseFinancierasActual($startMonth, $hoyMañana, $hoyTarde);
    $total = "totalInst";
    $response["hoy"]["desglose"]["agenciaInst"] = getTotalMesActual($total, $startMonth, $hoyMañana, $hoyTarde);
    echo json_encode($response);
}

function getTotalMes($total, $startMonth, $ayer)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    if ($total == "total") {
        $getTotalContratosAgencias = "SELECT 
                                            count(c.id)
                                        FROM
                                            tEstatusContrato a,
                                            reportTiempoVentas b,
                                            report c,
                                            reportHistory rh
                                        WHERE
                                        0 = 0 
                                        AND a.idReporte = b.idReporte
                                        AND c.id = a.idReporte
                                        AND c.id = b.idReporte
                                        AND c.id = rh.idReport
                                        AND rh.idReportType = 2
                                        AND a.validacionSegundaVenta = 42
                                        AND a.idClienteGenerado <> ''
                                        AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer';";
    }elseif ($total == "totalInst") {
        $getTotalContratosAgencias = "SELECT 
                                        count(c.id),
                                        (SELECT 
                                                tu.nickname
                                            FROM
                                                user AS tu
                                                    INNER JOIN
                                                agency AS ta ON ta.idUser = tu.id
                                                    INNER JOIN
                                                agency_employee AS trae ON trae.idAgency = ta.id
                                                    INNER JOIN
                                                employee AS te ON te.id = trae.idEmployee
                                            WHERE
                                                te.id = a.idEmpleadoInstalacion) AS nicknameAgencia
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        form_installation e
                                    WHERE
                                    0 = 0 
                                    AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND e.consecutive = c.idFormulario
                                    AND a.estatusAsignacionInstalacion = 54
                                    AND numInstalacionGen <> ''
                                    AND b.fechaFinRealInst BETWEEN '$startMonth' AND '$ayer'
                                    group by nicknameAgencia;";
    }else{
        $getTotalContratosAgencias = "SELECT 
                                            count(c.id),
                                            (SELECT 
                                                    tu.nickname
                                                FROM
                                                    user AS tu
                                                        INNER JOIN
                                                    agency AS ta ON ta.idUser = tu.id
                                                        INNER JOIN
                                                    agency_employee AS trae ON trae.idAgency = ta.id
                                                        INNER JOIN
                                                    employee AS te ON te.id = trae.idEmployee
                                                WHERE
                                                    te.idUser = rh.idUserAssigned) AS nicknameAgencia
                                        FROM
                                            tEstatusContrato a,
                                            reportTiempoVentas b,
                                            report c,
                                            reportHistory rh
                                        WHERE
                                        0 = 0 
                                        AND a.idReporte = b.idReporte
                                        AND c.id = a.idReporte
                                        AND c.id = b.idReporte
                                        AND c.id = rh.idReport
                                        AND rh.idReportType = 2
                                        AND a.validacionSegundaVenta = 42
                                        AND a.idClienteGenerado <> ''
                                        AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer'
                                        group by nicknameAgencia
                                        ORDER BY nicknameAgencia ASC;";
    }
        
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        while($row = $result->fetch_array()) {
            if ($total == "total") {
                $res = $row[0];
            }else{
                $res[$cont]["numReportes"]= $row[0];
                $res[$cont]["agencia"]= $row[1];
            }
            $cont++;
        }
    }
    $conn->close();
    $total = "";
    return $res;
}

function getTotalMesSucursales($total, $startMonth, $ayer)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    $getTotalContratosAgencias = "SELECT 
                                        c.id,
                                        c.agreementNumber,
                                        b.fechaSegundaCaptura,
                                        a.idClienteGenerado,
                                        (SELECT 
                                                tu.nickname
                                            FROM
                                                user AS tu
                                                    INNER JOIN
                                                agency AS ta ON ta.idUser = tu.id
                                                    INNER JOIN
                                                agency_employee AS trae ON trae.idAgency = ta.id
                                                    INNER JOIN
                                                employee AS te ON te.id = trae.idEmployee
                                            WHERE
                                                te.idUser = rh.idUserAssigned) AS nicknameAgencia,
                                        fs.payment,
                                        fs.financialService
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs
                                    WHERE
                                    0 = 0 
                                    AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer'
                                    ORDER BY nicknameAgencia ASC;";
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        $contMex = 0;
        $contAyo = 0;
        while($row = $result->fetch_array()) {
            if ($total == "financieras") {
                if (intval($row[5]) == 0 && intval($row[6]) == 0 ) {
                    $contMex++;
                }elseif (intval($row[5]) == 1 && intval($row[6]) == 1 ) {
                    $contAyo++;
                }
            }else{
                if ($row[4] == "SUCURSALES") {
                    if (intval($row[5]) == 0 && intval($row[6]) == 0 ) {
                        $contMex++;
                    }elseif (intval($row[5]) == 1 && intval($row[6]) == 1 ) {
                        $contAyo++;
                    }
                    $cont++;
                }
            }
        }

        if ($total == "financieras") {
            $res["agencia"]= "FINANCIERAS";
            $res["numContratosMex"]= $contMex;
            $res["numContratosAyo"]= $contAyo;
        }else{
            $res["agencia"]= "SUCURSALES";
            $res["numContratosMex"]= $contMex;
            $res["numContratosAyo"]= $contAyo;
        }
    }
    $conn->close();
    $total = "";
    return $res;
}

function getTotalDesgloseFinancieras($startMonth, $ayer)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    $getTotalContratosAgencias = "SELECT 
                                        count(c.id) as desgloseFinanciera
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Expansión Financiado%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer'
                                    UNION
                                    SELECT 
                                        count(c.id) as countZM
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Zona Madura%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer'
                                    UNION
                                    SELECT 
                                        count(c.id) as countLA
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Lote Actual%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$startMonth' AND '$ayer';";
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        $contMex = 0;
        $contAyoEXP = 0;
        $contAyoLA = 0;
        $contAyoZM = 0;
        while($row = $result->fetch_array()) {
            //var_dump($row);
            if ($cont == 0) {
                $res["financieras"]["Exp"]= $row[0];
            }elseif ($cont == 1) {
                $res["financieras"]["zonaMadura"]= $row[0];
            }elseif ($cont == 2) {
                $res["financieras"]["loteActual"]= $row[0];
            }
            $cont++;
        }
    }
    $conn->close();
    return $res;
}

function getNumInstaladosTodos($startMonth, $ayer)
{
    //generamos una consulta para obtener la descripcion del contrato

    $DB = new DAO();
    $conn = $DB->getConnect();
    $getIdRepHSQL = "SELECT 
                        COUNT(*) AS medidoresInstalados
                    FROM
                        tEstatusContrato a,
                        reportTiempoVentas b,
                        report c,
                        form_installation e
                    WHERE
                    0 = 0 
                    AND a.idReporte = b.idReporte
                    AND c.id = a.idReporte
                    AND c.id = b.idReporte
                    AND e.consecutive = c.idFormulario
                    AND a.estatusAsignacionInstalacion = 54
                    AND numInstalacionGen <> ''
                    AND b.fechaFinRealInst BETWEEN '$startMonth' AND '$ayer';";
    $result = $conn->query($getIdRepHSQL);
    $res="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            //echo "row dd ".$row[0];
            $res=$row[0];
        }
    }
    $conn->close();
    return $res;
}


function getTotalMesActual($total, $startMonth, $hoyMañana, $hoyTarde)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    if ($total == "total") {
        $getTotalContratosAgencias = "SELECT 
                                            count(c.id)
                                        FROM
                                            tEstatusContrato a,
                                            reportTiempoVentas b,
                                            report c,
                                            reportHistory rh
                                        WHERE
                                        0 = 0 
                                        AND a.idReporte = b.idReporte
                                        AND c.id = a.idReporte
                                        AND c.id = b.idReporte
                                        AND c.id = rh.idReport
                                        AND rh.idReportType = 2
                                        AND a.validacionSegundaVenta = 42
                                        AND a.idClienteGenerado <> ''
                                        AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde';";
    }elseif ($total == "totalInst") {
        $getTotalContratosAgencias = "SELECT 
                                        count(c.id),
                                        (SELECT 
                                                tu.nickname
                                            FROM
                                                user AS tu
                                                    INNER JOIN
                                                agency AS ta ON ta.idUser = tu.id
                                                    INNER JOIN
                                                agency_employee AS trae ON trae.idAgency = ta.id
                                                    INNER JOIN
                                                employee AS te ON te.id = trae.idEmployee
                                            WHERE
                                                te.id = a.idEmpleadoInstalacion) AS nicknameAgencia
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        form_installation e
                                    WHERE
                                    0 = 0 
                                    AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND e.consecutive = c.idFormulario
                                    AND a.estatusAsignacionInstalacion = 54
                                    AND numInstalacionGen <> ''
                                    AND b.fechaFinRealInst BETWEEN '$hoyMañana' AND '$hoyTarde'
                                    group by nicknameAgencia;";
    }else{
        $getTotalContratosAgencias = "SELECT 
                                            count(c.id),
                                            (SELECT 
                                                    tu.nickname
                                                FROM
                                                    user AS tu
                                                        INNER JOIN
                                                    agency AS ta ON ta.idUser = tu.id
                                                        INNER JOIN
                                                    agency_employee AS trae ON trae.idAgency = ta.id
                                                        INNER JOIN
                                                    employee AS te ON te.id = trae.idEmployee
                                                WHERE
                                                    te.idUser = rh.idUserAssigned) AS nicknameAgencia
                                        FROM
                                            tEstatusContrato a,
                                            reportTiempoVentas b,
                                            report c,
                                            reportHistory rh
                                        WHERE
                                        0 = 0 
                                        AND a.idReporte = b.idReporte
                                        AND c.id = a.idReporte
                                        AND c.id = b.idReporte
                                        AND c.id = rh.idReport
                                        AND rh.idReportType = 2
                                        AND a.validacionSegundaVenta = 42
                                        AND a.idClienteGenerado <> ''
                                        AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde'
                                        group by nicknameAgencia
                                        ORDER BY nicknameAgencia ASC;";
    }
        
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        while($row = $result->fetch_array()) {
            if ($total == "total") {
                $res = $row[0];
            }else{
                $res[$cont]["numReportes"]= $row[0];
                $res[$cont]["agencia"]= $row[1];
            }
            $cont++;
        }
    }
    $conn->close();
    $total = "";
    return $res;
}

function getTotalMesSucursalesActual($total, $startMonth, $hoyMañana, $hoyTarde)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    $getTotalContratosAgencias = "SELECT 
                                        c.id,
                                        c.agreementNumber,
                                        b.fechaSegundaCaptura,
                                        a.idClienteGenerado,
                                        (SELECT 
                                                tu.nickname
                                            FROM
                                                user AS tu
                                                    INNER JOIN
                                                agency AS ta ON ta.idUser = tu.id
                                                    INNER JOIN
                                                agency_employee AS trae ON trae.idAgency = ta.id
                                                    INNER JOIN
                                                employee AS te ON te.id = trae.idEmployee
                                            WHERE
                                                te.idUser = rh.idUserAssigned) AS nicknameAgencia,
                                        fs.payment,
                                        fs.financialService
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs
                                    WHERE
                                    0 = 0 
                                    AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde'
                                    ORDER BY nicknameAgencia ASC;";
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        $contMex = 0;
        $contAyo = 0;
        while($row = $result->fetch_array()) {
            if ($total == "financieras") {
                if (intval($row[5]) == 0 && intval($row[6]) == 0 ) {
                    $contMex++;
                }elseif (intval($row[5]) == 1 && intval($row[6]) == 1 ) {
                    $contAyo++;
                }
            }else{
                if ($row[4] == "SUCURSALES") {
                    if (intval($row[5]) == 0 && intval($row[6]) == 0 ) {
                        $contMex++;
                    }elseif (intval($row[5]) == 1 && intval($row[6]) == 1 ) {
                        $contAyo++;
                    }
                    $cont++;
                }
            }
        }

        if ($total == "financieras") {
            $res["agencia"]= "FINANCIERAS";
            $res["numContratosMex"]= $contMex;
            $res["numContratosAyo"]= $contAyo;
        }else{
            $res["agencia"]= "SUCURSALES";
            $res["numContratosMex"]= $contMex;
            $res["numContratosAyo"]= $contAyo;
        }
    }
    $conn->close();
    $total = "";
    return $res;
}

function getTotalDesgloseFinancierasActual($startMonth, $hoyMañana, $hoyTarde)
{
    $DB = new DAO();
    $conn = $DB->getConnect();
    $getTotalContratosAgencias = "SELECT 
                                        count(c.id) as desgloseFinanciera
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Expansión Financiado%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde'
                                    UNION
                                    SELECT 
                                        count(c.id) as countZM
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Zona Madura%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde'
                                    UNION
                                    SELECT 
                                        count(c.id) as countLA
                                    FROM
                                        tEstatusContrato a,
                                        reportTiempoVentas b,
                                        report c,
                                        reportHistory rh,
                                        form_sells fs,
                                        agreement agg,
                                        catalogoTiposDeContrato cc    
                                    WHERE
                                    0 = 0 AND a.idReporte = b.idReporte
                                    AND c.id = a.idReporte
                                    AND c.id = b.idReporte
                                    AND c.id = rh.idReport
                                    AND fs.id = rh.idFormSell
                                    and agg.idReport = c.id
                                    and agg.idArt=cc.idContrato
                                    AND rh.idReportType = 2
                                    AND a.validacionSegundaVenta = 42
                                    AND a.idClienteGenerado <> ''
                                    and cc.tipoDeContrato like '%Lote Actual%'
                                    and (fs.payment = 1 and fs.financialService = 1)
                                    AND b.fechaSegundaCaptura BETWEEN '$hoyMañana' AND '$hoyTarde';";
    $result = $conn->query($getTotalContratosAgencias);
    $res="";
    if ($result->num_rows > 0) {
        $cont = 0;
        $contMex = 0;
        $contAyoEXP = 0;
        $contAyoLA = 0;
        $contAyoZM = 0;
        while($row = $result->fetch_array()) {
            //var_dump($row);
            if ($cont == 0) {
                $res["financieras"]["Exp"]= $row[0];
            }elseif ($cont == 1) {
                $res["financieras"]["zonaMadura"]= $row[0];
            }elseif ($cont == 2) {
                $res["financieras"]["loteActual"]= $row[0];
            }
            $cont++;
        }
    }
    $conn->close();
    return $res;
}

function getNumInstaladosTodosActual($startMonth, $hoyMañana, $hoyTarde)
{
    //generamos una consulta para obtener la descripcion del contrato

    $DB = new DAO();
    $conn = $DB->getConnect();
    $getIdRepHSQL = "SELECT 
                        COUNT(*) AS medidoresInstalados
                    FROM
                        tEstatusContrato a,
                        reportTiempoVentas b,
                        report c,
                        form_installation e
                    WHERE
                    0 = 0 
                    AND a.idReporte = b.idReporte
                    AND c.id = a.idReporte
                    AND c.id = b.idReporte
                    AND e.consecutive = c.idFormulario
                    AND a.estatusAsignacionInstalacion = 54
                    AND numInstalacionGen <> ''
                    AND b.fechaFinRealInst BETWEEN '$hoyMañana' AND '$hoyTarde';";
    $result = $conn->query($getIdRepHSQL);
    $res="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            //echo "row dd ".$row[0];
            $res=$row[0];
        }
    }
    $conn->close();
    return $res;
}