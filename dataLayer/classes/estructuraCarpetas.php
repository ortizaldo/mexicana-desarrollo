<?php


class EstructuraCarpetas {
    
    const CARPETA_NOMBRE_PROYECTO = "mexicana";
    const CARPETA_IMAGENES_TEMPORAL = "uploads";
    const CARPETA_IMAGENES = "files_img";
    const CARPETA_VENTAS = "Venta";
    const CARPETA_PLOMERIA = "Plomeria";
    const CARPETA_INSTALACION = "Instalacion";
    const CARPETA_CENSO = "Censo";
    const CARPETA_EMPLEADOS = "Empleados";
    const CARPETA_TERMINADOS = "Terminados";
    const CARPETA_EN_PROCESO = "Proceso";
    const CARPETA_AYOPSA = "AYOPSA";
    const CARPETA_CMG = "CMG";
    
    const REPORTE_TIPO_CENSO = 1;
    const REPORTE_TIPO_VENTA = 2;
    const REPORTE_TIPO_PLOMERO= 3;
    const REPORTE_TIPO_INSTALACION = 4;
    const REPORTE_TIPO_SEGUNDA_VENTA = 5;
    
    const EMPLEADO_PROFILE_ID_CENSO = 1;
    const EMPLEADO_PROFILE_ID_VENTA =2;
    const EMPLEADO_PROFILE_ID_PLOMERO =3;
    const EMPLEADO_PROFILE_ID_INSTALACION =4;
    const EMPLEADO_PROFILE_ID_CENSO_VENTA =5;
    const EMPLEADO_PROFILE_ID_PLOMERO_VENTA =6;
    const EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO =7;
    const EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO_INTALACION =8;
    
    const EMPLEADO_PROFILE_CENSO ="Censo";
    const EMPLEADO_PROFILE_VENTA ="Venta";
    const EMPLEADO_PROFILE_PLOMERO ="Plomero";
    const EMPLEADO_PROFILE_INSTALACION ="Instalacion";
    const EMPLEADO_PROFILE_CENSO_VENTA ="censo_venta";
    const EMPLEADO_PROFILE_PLOMERO_VENTA ="plomero_venta";
    const EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO ="plomero_venta_censo";
    const EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO_INTALACION ="plomero_venta_censo_instalacion";
    
    const ESTATUS_INSTALACION_COMPLETADA = 51;
    
    private $numeroContrato;
    private $fecha;
    private $financialService;
    private $concecutivo;
    private $tipoEmpleado;
    private $tipoReporte;
    private $idReporte;
    private $estatusInstalacion;
    private $idFormulario;
    
    private $perfil;
    
    private $dirVentas;
    private $dirPlomeria;
    private $dirInstalacion;
    private $dirCenso;
    private $dirEmpleado;
    
    
    protected $conn;


    public function __construct() 
    {
        
    }
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Getters and Setters">
    
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = (string)$numeroContrato;
    }
    
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }
    
    public function setFinanciado($financialService)
    {
        $this->financialService = $financialService;
    }
    
    public function setConcecutivo($concecutivo)
    {
        $this->concecutivo = $concecutivo;
    }
    
    public function setTipoEmpleado($tipoEmpleado)
    {
        $this->tipoEmpleado = $tipoEmpleado;
    }
    
    public function setTipoReporte($tipoReporte)
    {
        $this->tipoReporte = $tipoReporte;
    }
    
    public function setIdReporte($idReporte)
    {
        $this->idReporte = $idReporte;
    }
    
    public function setEstatusInstalacion($estatusInstalacion)
    {
        $this->estatusInstalacion = $estatusInstalacion;
    }
    
    public function setFormulario($idFormulario)
    {
        $this->idFormulario = $idFormulario;
    }
    
    public function setPerfil($idPerfil)
    {
        $this->perfil = $idPerfil;
    }
    
    public function  getEstatusInstalacion()
    {
        return self::ESTATUS_INSTALACION_COMPLETADA;
    }
    
    public function getNumeroContrato()
    {
        return (string)$this->numeroContrato;
    }
    
    public function getFecha()
    {
        return $this->fecha;
    }
    
    public function getFinanciado()
    {
        return $this->financialService;
    }
    
    public function getConcecutivo()
    {
        return $this->concecutivo;
    }
    
    public function getTipoEmpleado()
    {
        return $this->tipoEmpleado;
    }
    
    public function getTipoReporte()
    {
        return $this->tipoReporte;
    }
    
    public function getIdReporte()
    {
        return $this->idReporte;
    }
    
    public function getPerfil()
    {
        return $this->perfil;
    }
    
    public function getDirEmpleado()
    {
        return $this->dirEmpleado;
    }
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Creacion de Carpetas">
    
    
    public function crearCarpetaEmpleado()
    {
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        
        error_log($dir);
        $this->crearCarpeta($dir);
        
        $dir .= self::CARPETA_EMPLEADOS . "/";
        $this->crearCarpeta($dir);
        
        error_log($dir);
        switch ($this->perfil)
        {
            case self::EMPLEADO_PROFILE_ID_CENSO:
                $dir .= self::EMPLEADO_PROFILE_CENSO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_VENTA:
                $dir .= self::EMPLEADO_PROFILE_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_INSTALACION:
                $dir .= self::EMPLEADO_PROFILE_INSTALACION . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_CENSO_VENTA:
                $dir .= self::EMPLEADO_PROFILE_CENSO_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO_INTALACION:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO_INTALACION . "/";
                break;
        }
        
        error_log($dir);
        $this->crearCarpeta($dir);
        $this->dirEmpleado = $dir;
    }
    
    
    public function getDirectorioEmpleado()
    {
        $dir = $this->getBaseUrl() . self::CARPETA_IMAGENES . "/";
        
        $dir .= self::CARPETA_EMPLEADOS . "/";
        
        switch ($this->perfil)
        {
            case self::EMPLEADO_PROFILE_ID_CENSO:
                $dir .= self::EMPLEADO_PROFILE_CENSO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_VENTA:
                $dir .= self::EMPLEADO_PROFILE_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_INSTALACION:
                $dir .= self::EMPLEADO_PROFILE_INSTALACION . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_CENSO_VENTA:
                $dir .= self::EMPLEADO_PROFILE_CENSO_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO . "/";
                break;
            case self::EMPLEADO_PROFILE_ID_PLOMERO_VENTA_CENSO_INTALACION:
                $dir .= self::EMPLEADO_PROFILE_PLOMERO_VENTA_CENSO_INTALACION . "/";
                break;
        }
        
        return  $dir;
    }
    
    /**
     * Mtodo que se encarga  de crear la estrucutra de carpetas para cuando es un  censo
     * 
     */
    public function crearCarpetaCenso()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_CENSO); 
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        // creamos la carpeta imagenes si no existe
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $this->crearCarpeta($dir);
        
        $dir .= self::CARPETA_CENSO . "/";
        $this->crearCarpeta($dir);
        
        if(!is_null($this->idReporte))
        {
            // año
            $dir .= $ano . "/";
            $this->crearCarpeta($dir);
            
            // mes
            $dir .= $mes . "/";
            $this->crearCarpeta($dir);
            
            $dir .= $this->idReporte . "/";
            $this->crearCarpeta($dir);
            
            error_log($dir);
            $this->dirCenso = $dir;
        }
    }
    
    /**
     * Metodo que se encarga de crear la estrucutura de carpetas cuando es una venta
     */
    public function crearCarpetaVenta()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_VENTA); 
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        // creamos la carpeta imagenes si no existe
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $this->crearCarpeta($dir);
        
        // creamos la carpeta de ventas
        $dir .= self::CARPETA_VENTAS . "/";
        $this->crearCarpeta($dir);
        
        
        if(!is_null($this->idReporte))
        {
            // validamos si el reporte se encuentra terminado o en proceso            
            $dir .= ($this->estatusInstalacion == self::ESTATUS_INSTALACION_COMPLETADA) ? self::CARPETA_TERMINADOS . "/" : self::CARPETA_EN_PROCESO . "/";
            $this->crearCarpeta($dir);
            
            // validamos si el reporte es financialService o no 
            $dir .= ($this->financialService == 1) ? self::CARPETA_AYOPSA . "/" : self::CARPETA_CMG . "/";
            $this->crearCarpeta($dir);
            
            // año
            $dir .= $ano . "/";
            $this->crearCarpeta($dir);
            
            // mes
            $dir .= $mes . "/";
            $this->crearCarpeta($dir);
            
            // numero de contrato
            $dir .= (string)$this->numeroContrato . "/";
            $this->crearCarpeta($dir);
            
            error_log($dir);
            $this->dirVentas = $dir;
        }
    }
    
    /**
     * Metodo que se encarga de crear la estructura de carpetas cuando es un 
     */
    public function crearCarpetaPloemro()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_PLOMERO); 
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        // creamos la carpeta imagenes si no existe
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $this->crearCarpeta($dir);
        
        $dir .= self::CARPETA_PLOMERIA . "/";
        $this->crearCarpeta($dir);
        
        if(!is_null($this->idReporte))
        {
            // validamos si el reporte se encuentra terminado o en proceso            
            $dir .= ($this->estatusInstalacion == self::ESTATUS_INSTALACION_COMPLETADA) ? self::CARPETA_TERMINADOS . "/" : self::CARPETA_EN_PROCESO . "/";
            $this->crearCarpeta($dir);
            
            // año
            $dir .= $ano . "/";
            $this->crearCarpeta($dir);
            
            // mes
            $dir .= $mes . "/";
            $this->crearCarpeta($dir);
            
            // numero de contrato
            $dir .= (string)$this->numeroContrato . "/";
            $this->crearCarpeta($dir);
            
            error_log($dir);
            $this->dirPlomeria = $dir;
        }
    }
    
    /**
     * Metodo que se encarga de generar la estructura de carpetas cuando oes una instalacion
     */
    public function crearCarpetaInstalacion()
    {
        error_log('message entre a crearCarpetaInstalacion');
        $this->reporte($this->idReporte, self::REPORTE_TIPO_INSTALACION); 
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        // creamos la carpeta imagenes si no existe
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $this->crearCarpeta($dir);
        
        $dir .= self::CARPETA_INSTALACION . "/";
        $this->crearCarpeta($dir);
        error_log("message idReporte".$this->idReporte);
        error_log(print_r($this, true));
        if(!is_null($this->idReporte))
        {
            error_log("message estatus".$this->estatusInstalacion);
            // validamos si el reporte se encuentra terminado o en proceso            
            $dir .= ($this->estatusInstalacion == self::ESTATUS_INSTALACION_COMPLETADA) ? self::CARPETA_TERMINADOS . "/" : self::CARPETA_EN_PROCESO . "/";
            $this->crearCarpeta($dir);
            error_log("message dir".$dir);
            // año
            $dir .= $ano . "/";
            $this->crearCarpeta($dir);
            
            // mes
            $dir .= $mes . "/";
            $this->crearCarpeta($dir);
            
            // numero de contrato
            $dir .= (string)$this->numeroContrato . "/";
            $this->crearCarpeta($dir);
            
            error_log($dir);
            $this->dirInstalacion = $dir;
        }
    }
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Mover Temporal a Estructura">
    
    /**
     * Metodo que se encarga de mover de la carpeta tempra
     */
    public function moverTemporalCenso()
    {
        $dirTemporal = $this->getDocumentRoot() . self::CARPETA_IMAGENES_TEMPORAL . "/";
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_CENSO);
        error_log(json_encode($aImagenes));
        $this->moverImagenes($dirTemporal, $this->dirCenso, $aImagenes);
    }
    
    /**
     * Metodo que se encarga de mover de la carpeta tempra
     */
    public function moverTemporalVentas()
    {
        $dirTemporal = $this->getDocumentRoot() . self::CARPETA_IMAGENES_TEMPORAL . "/";
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_VENTA);
        error_log(json_encode($aImagenes));
        $this->moverImagenes($dirTemporal, $this->dirVentas, $aImagenes);
    }
    
    /**
     * Metodo que se encarga de mover de la carpeta tempra
     */
    public function moverTemporalPlomeria()
    {
        $dirTemporal = $this->getDocumentRoot() . self::CARPETA_IMAGENES_TEMPORAL . "/";
        $aImagenes = $this->getImagenes($this->idFormulario,  self::REPORTE_TIPO_PLOMERO);
        error_log(json_encode($aImagenes));
        $this->moverImagenes($dirTemporal, $this->dirPlomeria, $aImagenes);
    }
    
    /**
     * Metodo que se encarga de mover de la carpeta tempra
     */
    public function moverTemporalInstalacion()
    {
        $dirTemporal = $this->getDocumentRoot() . self::CARPETA_IMAGENES_TEMPORAL . "/";
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_INSTALACION);
        error_log(json_encode($aImagenes));
        $this->moverImagenes($dirTemporal, $this->dirInstalacion, $aImagenes);
    }
    
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Mover de Proceso a Terminado">

    public function moverProcesoTerminadoCenso()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_INSTALACION); 
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $dir .= self::CARPETA_CENSO . "/";
        $dir .= $ano . "/";
        $dir .= $mes . "/";
        $dir .= $this->idReporte . "/";
        
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_CENSO);
        $this->moverImagenes($dir, $this->dirCenso, $aImagenes);
        if($this->moverImagenes($dir, $this->dirCenso, $aImagenes))
        {
            @unlink($dir);
        }
    }
    
    public function moverProcesoTerminadoVenta()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_VENTA);  
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $dir .= self::CARPETA_VENTAS . "/";
        
        $dir .= self::CARPETA_EN_PROCESO . "/";
        $dir .= ($this->financialService == 1) ? self::CARPETA_AYOPSA . "/" : self::CARPETA_CMG . "/";
        
        $dir .= $ano . "/";
        $dir .= $mes . "/";
        $dir .= (string)$this->numeroContrato . "/";
        
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_VENTA);
        if($this->moverImagenes($dir, $this->dirVentas, $aImagenes))
        {
            @unlink($dir);
        }
    }
    
    public function moverProcesoTerminadoPlomero()
    {
        $this->reporte($this->idReporte, self::REPORTE_TIPO_PLOMERO);  
        
        $ano = date("Y", strtotime($this->fecha));
        $mes = date("m", strtotime($this->fecha));
        
        $dir = $this->getDocumentRoot() . self::CARPETA_IMAGENES . "/";
        $dir .= self::CARPETA_PLOMERIA . "/";
        
        $dir .= self::CARPETA_EN_PROCESO . "/";
        
        $dir .= $ano . "/";
        $dir .= $mes . "/";
        $dir .= (string)$this->numeroContrato . "/";
        
        $aImagenes = $this->getImagenes($this->idFormulario, self::REPORTE_TIPO_PLOMERO);
        if($this->moverImagenes($dir, $this->dirPlomeria, $aImagenes))
        {
            @unlink($dir);
        }
    }
    
    // </editor-fold>
        
    // <editor-fold defaultstate="collapsed" desc="Metodos Para Recuperar Datos">
    
    /**
     * Metodo  que se encarga de recuperar la informaion del report History
     * @param type $idReporteBuscar
     * @param type $idReportType
     */
    public function reporte($idReporteBuscar, $idReportType)
    {
        error_log("-------- reporte  ------");
        error_log("idReportType->" . $idReportType);
        error_log("idReporteBuscar->".$idReporteBuscar);
        
        $DBb = new DAO();
        $c = $DBb->getConnect();
        
        $reporteSQL = "";
        switch($idReportType)
        {
            case self::REPORTE_TIPO_CENSO:
                $reporteSQL = "SELECT r.id AS idReporte, r.agreementNumber, NULL AS concecutivo, rh.idFormSell AS idFormulario, NULL AS financialService,ec.estatusAsignacionInstalacion, rh.created_at  FROM report AS r INNER JOIN tEstatusContrato AS ec ON r.id = ec.idReporte INNER JOIN reportHistory AS rh ON r.id = rh.idReport WHERE rh.idReportType = ". $idReportType . " AND r.id = ".$idReporteBuscar.";";
                break;
            case self::REPORTE_TIPO_VENTA:
                $reporteSQL = "SELECT r.id AS idReporte,r.agreementNumber,NULL AS concecutivo, fs.id AS idFormulario,fs.financialService,ec.estatusAsignacionInstalacion, rh.created_at FROM report AS r INNER JOIN tEstatusContrato AS ec ON r.id = ec.idReporte INNER JOIN reportHistory AS rh ON r.id = rh.idReport AND rh.idReportType = ". $idReportType . " INNER JOIN form_sells AS fs ON rh.idFormSell = fs.id WHERE r.id = ".$idReporteBuscar.";";
                break;
            case self::REPORTE_TIPO_PLOMERO:
                $reporteSQL = "SELECT r.id AS idReporte, r.agreementNumber,NULL AS concecutivo, fp.id AS idFormulario, 0 AS financialService,ec.estatusAsignacionInstalacion, rh.created_at FROM report AS r INNER JOIN tEstatusContrato AS ec ON r.id = ec.idReporte INNER JOIN reportHistory AS rh ON r.id = rh.idReport INNER JOIN form_plumber AS fp ON rh.idFormulario = fp.id WHERE rh.idReportType = ". $idReportType . " AND r.id = ".$idReporteBuscar." AND (rh.idStatusReport = 3 OR rh.idStatusReport = 7) ; ";
                break;
            case self::REPORTE_TIPO_INSTALACION:
                $reporteSQL = "SELECT r.id AS idReporte, r.agreementNumber,fi.consecutive AS concecutivo, fi.id AS idFormulario, NULL AS financialService,ec.estatusAsignacionInstalacion, rh.created_at FROM report AS r INNER JOIN tEstatusContrato AS ec ON r.id = ec.idReporte INNER JOIN reportHistory AS rh ON r.id = rh.idReport INNER JOIN report_employee_form AS ref ON rh.idReport = ref.idReport INNER JOIN form_installation AS fi ON ref.idForm = fi.id WHERE rh.idReportType = ". $idReportType . " AND r.id = ".$idReporteBuscar.";  ";
                break;
        }
         
        error_log("consulta de sql reporote");
        error_log($reporteSQL);
        error_log("consulta de sql reporte");
       
        if($smtReporte = $c->query($reporteSQL))
        {
            error_log(json_encode($c));
            error_log(json_encode($smtReporte));
            
            if($smtReporte->num_rows > 0)
            {   
                while($row = $smtReporte->fetch_array())
                {
                    error_log(json_encode($row));
                    $this->idReporte = $row["idReporte"];
                    $this->numeroContrato = (string)$row["agreementNumber"];
                    $this->idFormulario = $row["idFormulario"];
                    $this->financialService = $row["financialService"];
                    $this->estatusInstalacion = $row["estatusAsignacionInstalacion"];
                    $this->fecha = $row["created_at"];
                    $this->concecutivo = $row["concecutivo"];
                }
            }
            else
            {
               error_log("NO HAY ROWS"); 
            }
        }
        else
        {
            error_log("-------------     ERROR AL PREPARAR LA CONSULTA     --------------");
            error_log(json_encode($smtReporte));
            error_log("-------------     ERROR AL PREPARAR LA CONSULTA     --------------");
        }
        
        error_log("-------- reporte  ------");
        $c->close();
    }
    
    /**
     * Metodo que se encarga de recuperar las imagenes del censo
     * @return type
     */
    public function getImagenes($idFormulario, $idReportType)
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $aImagenes = array();
        
        $imagenSQL = "";
        switch($idReportType)
        {
            case self::REPORTE_TIPO_CENSO:
                $imagenSQL = "SELECT m.name AS imagen FROM form_census AS fs INNER JOIN form_census_multimedia AS fcm ON fs.id = fcm.idFormCensus INNER JOIN multimedia AS m ON fcm.idMultimedia = m.id WHERE fs.id = ".$idFormulario."";
                break;
            case self::REPORTE_TIPO_VENTA:
                $imagenSQL = "SELECT m.name AS imagen FROM form_sells AS fs INNER JOIN form_sells_multimedia AS fsm ON fs.id = fsm.idSell INNER JOIN multimedia AS m ON fsm.idMultimedia = m.id WHERE fs.id = ".$idFormulario."";
                break;
            case self::REPORTE_TIPO_PLOMERO:
                $imagenSQL = "SELECT m.name AS imagen FROM form_plumber AS fs INNER JOIN form_plumber_multimedia AS fcm ON fs.id = fcm.idFormPlumber INNER JOIN multimedia AS m ON fcm.idMultimedia = m.id WHERE fs.id = ".$idFormulario."";
                break;
            case self::REPORTE_TIPO_INSTALACION:
                $imagenSQL = "SELECT m.name AS imagen FROM form_sells AS fs INNER JOIN form_installation_multimedia AS fcm ON fs.id = fcm.idFormInstallation INNER JOIN multimedia AS m ON fcm.idMultimedia = m.id WHERE fs.id = ".$idFormulario."";
                break;
        }
        
        $smtImagen = $conn->query($imagenSQL);
        
        if($smtImagen->num_rows > 0)
        {
            while($row = $smtImagen->fetch_array())
            {
                $aImagenes[] = $row["imagen"];
            }
        }
        
        $conn->close();
        return $aImagenes;
    }
    
    
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Metodos Utils">
    
    /**
     * Metodo que se encarga de crear una carpeta en una ruta especificada, antes de crear valida si dicha carpeta existe
     * @param string $rutaCarpeta
     */
    public function crearCarpeta($rutaCarpeta)
    {
        if(!@file_exists($rutaCarpeta))
        {
            mkdir($rutaCarpeta);
        }
    }
    
    /**
     * Metodo que se encarga de mover la imagen de la carpeta temporal a la carpeta destino
     * 
     * @param string $dir Directorio destino al cual  se copiara la imagen
     * @param array $aImagenes Arreglo que contiene las imagenes a copiar
     */
    public function moverImagenes($dirOrigen, $dirDestino, $aImagenes)
    {
    	error_log("-------  MOVER IMAGENES  -----");
        $return = true;
        $i = 0;
        $total = count($aImagenes);
        
        foreach($aImagenes AS $imagen)
        {
        	error_log("--- VERIFICAR SI IMAGEN EXISTE -> " . $imagen);
            if(@file_exists($dirOrigen . $imagen))
            {
            	error_log("SI EXISTE IMAGEN" . $imagen);
            	error_log("COPIAR IMAGEN DE -> " . $dirOrigen . $imagen . "  A " . $dirDestino . $imagen);
                if(@copy($dirOrigen . $imagen, $dirDestino . $imagen))
                {
                    @unlink($dirOrigen . $imagen);
                    $i++;
                }
                else
                {
                	error_log("NO COPIAR IMAGEN DE -> " . $dirOrigen . $imagen . "  A " . $dirDestino . $imagen);
                }
            }
            else
            {
            	error_log("NO EXISTE IMAGEN" . $imagen);
            }
        }

        error_log("-------  MOVER IMAGENES  -----"); 
        
        return ($i == $total);
    }
    
    /**
     * Metodo que se encarga de recuperar el docuent root de la aplicacion
     * @return string
     */
    public function getDocumentRoot()
    {
        $dir  = (substr($_SERVER["DOCUMENT_ROOT"], -1) == "/") ? $_SERVER["DOCUMENT_ROOT"] : $_SERVER["DOCUMENT_ROOT"] . "/";
       // $dir.= self::CARPETA_NOMBRE_PROYECTO . "/";
        $dir = str_replace("\\", "/", $dir);
        return  $dir;
    }
    
    public function getBaseUrl()
    {
        error_log('entre a getBaseUrl');
        $base = "http://siscomcmg.com:8080/";
        //$base = "http://localhost/mexicana-de-gas-backoffice/";
        
        return $base;
    }
    
    // </editor-fold>
            
}

