<?php include_once dirname(dirname(dirname(__FILE__)))."/dataLayer/DAO.php";
$DB = new DAO();
$conn = $DB->getConnect();
$catInstSQL = "SELECT idCatInst,idMedidor, materialnstalacion FROM catInstalacion;";
if ($catInst = $conn->prepare($catInstSQL)) {
    //devolvemos la respuesta
    if ($catInst->execute()) {
        $catInst->store_result();
        $catInst->bind_result($idCatInst, $idMedidor, $materialInstalacion);
        //var_dump($catInst);
        $cont=0;
        $positionArrayMaterial = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24, 25, 26, 27);
        while ($catInst->fetch()) {
            switch ($materialInstalacion) {
                case 'Tue.Union 3/4 G':
                    $positionArrayMaterial[0] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    //var_dump($positionArrayMaterial);
                    break;
                case 'Red.Camp.3/4-1/2 G':
                    $positionArrayMaterial[1] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Codo 3/4x90 150# G':
                    $positionArrayMaterial[2] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Codo Nip.3/4 150# G':
                    $positionArrayMaterial[3] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Tee 3/4 G':
                    $positionArrayMaterial[4] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Cople 3/4 150# G':
                    $positionArrayMaterial[5] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Tapon M.3/4 Neg':
                    $positionArrayMaterial[6] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Conex.p/medid.dom':
                    $positionArrayMaterial[7] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Val.esfer.3/4 125lbs Br.':
                    $positionArrayMaterial[8] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Reg.Ameri.CR-4000 3/4':
                    $positionArrayMaterial[9] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 RC G':
                    $positionArrayMaterial[10] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x2 G':
                    $positionArrayMaterial[11] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x3 G':
                    $positionArrayMaterial[12] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x4 G':
                    $positionArrayMaterial[13] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x5 G':
                    $positionArrayMaterial[14] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x6 G':
                    $positionArrayMaterial[15] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x7 G':
                    $positionArrayMaterial[16] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x8 G':
                    $positionArrayMaterial[17] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x9 G':
                    $positionArrayMaterial[18] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 3/4 x10 G':
                    $positionArrayMaterial[19] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Tue.Union 1Std.Neg':
                    $positionArrayMaterial[20] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Red.Camp.1x3/4 G':
                    $positionArrayMaterial[21] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Red.Bush.1x3/4 G':
                    $positionArrayMaterial[22] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Val.esfer.1 125lbs Br.':
                    $positionArrayMaterial[23] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Niple 1/2 x 2 G':
                    $positionArrayMaterial[24] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Red.Bush.3/4x1/2':
                    $positionArrayMaterial[25] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Sell.Bco.Plomo':
                    $positionArrayMaterial[26] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
                case 'Cta.Teflon 3/4x260pl L':
                    $positionArrayMaterial[27] = array(
                        'id' => $idCatInst,
                        'desc' => $materialInstalacion
                    );
                    break;
            }
        }
        $info["respuesta"] = $positionArrayMaterial;
        $positionArrayMaterial = null;
        $response["status"] = "OK";
        $response["code"] = "200";
        $response["response"] = $info;
    }
}
echo json_encode($response);