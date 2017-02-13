<?php include_once "../DAO.php";

$DB = new DAO();
$conn = $DB->getConnect();

$employee = 0;

if(isset($_POST['employee']) && isset($_POST['date'])) {
   $employee = $_POST['employee'];
   $date = $_POST['date'];
   $returnData = [];
    $track = "SELECT track.id, track.start_latitude, track.created_at, track.start_longitude, USEMP.nickname, track.idEmployee
            FROM track 
            LEFT JOIN employee AS EMP ON EMP.idUser = track.idEmployee 
            LEFT JOIN user AS USEMP ON USEMP.id = EMP.idUser 
            WHERE 0=0 
            AND track.idEmployee = $employee
            AND track.created_at LIKE '".$date."%'";
    $result = $conn->query($track);
    $res="";
    if ($result->num_rows > 0) {
        while($row = $result->fetch_array()) {
            $trackData["id"] = $row[0];
            $trackData["latitude"] = $row[1];
            $trackData["created"] = $row[2];
            $trackData["longitude"] = $row[3];
            $trackData["nickname"] = $row[4];
            $returnData[] = $trackData;
        }
        echo json_encode($returnData);
    }
}