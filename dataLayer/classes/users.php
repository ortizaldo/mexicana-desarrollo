<?php include_once "../DAO.php";

class users {
    var $conn = "";
    
    function __Construct() {
        $DB = new DAO();
        $this->conn = $DB->getConnect();
    }

    //public function user() {}

    public function getAdmins() {
        $admins = [];
        //ASIGNAR A ADMINISTRADORES DE MEXICANA
        $rol = 2;

        $conn = $this->conn;
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

            //$admins = json_encode($admins);

            //Array of Id´s of admins user ID
            return $admins;
        }
    }
}