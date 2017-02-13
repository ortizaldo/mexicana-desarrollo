<?php 
include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
session_start();
$agency=strtoupper($_SESSION["nickname"]);
$angencyLike = '%' . $agency . '%';
$venta = '%venta%';
if ($angencyLike != "") {
    $stmtUsuarios = "SELECT 
                                a.nickname, a.id
                            FROM
                                user a,
                                profile b,
                                employee c,
                                agency_employee d,
                                agency e
                            WHERE
                                a.id = c.idUser AND b.id = c.idProfile
                                    AND c.id = d.idemployee
                                    AND e.id = d.idAgency
                                    AND b.name LIKE ?
                                    AND d.idAgency IN (SELECT 
                                        f.id
                                    FROM
                                        agency f,
                                        user g
                                    WHERE
                                        g.id = f.idUser
                                            AND g.nickname LIKE ?);";

    if ($connUs = $conn->prepare($stmtUsuarios)) {
        $connUs->bind_param("ss",$venta, $angencyLike);
        //devolvemos la respuesta
        if ($connUs->execute()) {
            $connUs->store_result();
            $connUs->bind_result($nickname,$id);
            $cont=0;
            while ($connUs->fetch()) {
                $requests[$cont]["nickname"] = $nickname;
                $requests[$cont]["id"] = $id;
                $cont++;
            }
            $info["respuesta"] = $requests;
            $requests = null;
            $response["status"] = "OK";
            $response["code"] = "200";
            $response["empleados"] = $info;
        }
    }
    echo json_encode($response);
    $conn->close();
}