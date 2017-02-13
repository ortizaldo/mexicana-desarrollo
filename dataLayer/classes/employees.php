<?php include_once "../DAO.php";

class employees {
    var $conn = "";

    function __Construct() {
        $DB = new DAO();
        $this->conn = $DB->getConnect();
    }

    /*public function employee() {

    }

    public function employees() {

    }*/

    public function getEmployeeByUser($userID) {
        $employees = [];

        $conn = $this->conn;
        $getEmployeeData = $conn->prepare("SELECT `user`.`id`, `employee`.`id`, `profile`.`id` , `profile`.`name` FROM `user` INNER JOIN `employee` ON `user`.`id` = `employee`.`idUser` INNER JOIN `profile` ON `employee`.`idProfile` = `profile`.`id` WHERE `user`.`id` = ? LIMIT 1;");
        $getEmployeeData->bind_param('i', intval($userID));
        if ($getEmployeeData->execute()) {
            $getEmployeeData->store_result();
            $getEmployeeData->bind_result($idUser, $employeeID, $profileID, $profileName);
            if ($getEmployeeData->fetch()) {
                $employee["idUser"] = $idUser;
                $employee["employeeID"] = $employeeID;
                $employee["profileID"] = $profileID;
                $employee["profileName"] = $profileName;
                $employees[] = $employee;
            }
        }
        return $employees;
    }
}