<?php
include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();
$idReport=$_POST['idReport'];
if (isset($idReport)) {
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
    WHERE rth.idReportType=4 and rth.idReport = $idReport";
    
    $result = $conn->query($getDataInstalacion);
    //echo 'query getDataInstalacion '.$getDataInstalacion;
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
            $res['measurement']=intval($row[14]);
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
    $response["status"] = "EXITO";
    $response["code"] = "200";
    $response["response"] = $res;
    //echo json_encode($response);
    sendInstalacion($res);
}else{
    $response["status"] = "ERROR";
    $response["code"] = "500";
    $response["response"] = "Faltan datos";
    echo json_encode($response);
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
            while($rowMaterial = $result->fetch_array()) {
                $material=ltrim($rowMaterial[1]);
                $res[$cont]['material']=$material;
                $res[$cont]['cantidad']=$rowMaterial[0];
                $cont++;
            }
        }
        $conn->close();
    }
    return $res;
}

function sendInstalacion($post)
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
    //$inst['t_Anomalia']=$post['abnormalities'];
    $inst['t_Anomalia']=0;
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
        error_log('material '.$value['material']);
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
            error_log('entre');
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
            case 'Adaptador Macho 1/2':
            $inst['t_adaptador_macho1-2']=$value['cantidad'];
            break;
        }
    }
    $returnData['it_instalacionesRow'] = $inst;
    error_log(json_encode($returnData));
    $returnData['it_instalacionesRow'] = $inst;
    $returnData = json_encode($returnData);

    $ip_cia_id = 1;
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "http://siscomcmg.com:8080/v1/api/WsSiscomInstalacion.php");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('p_cia' => $ip_cia_id, 
                                                                  'p_usr_id' => "migesa2", 
                                                                  'it_instalaciones' => $returnData,
                                                                  'jsonItSolicitud' => $returnData));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    }
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