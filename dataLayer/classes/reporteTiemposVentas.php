<?php
date_default_timezone_set('America/Monterrey');

class ReporteTiemposVentas {
    
    private $diasFestivos;
    private $diasDescanso;
    
    private $horaEntrada;
    private $horaSalida;
    
    private $decimales = 4;
    
    
    public function __construct() 
    {        
        $this->_setDiasFestivos();
        $this->_setDiasDescanso();
        $this->_setHorarios();
    }
    
    /**
     * Metodo que se encarga de recuperar los dias festivos
     */
    private function _setDiasFestivos()
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $ano  = date("Y");
        
        $diasFestivos = "SELECT dia, mes FROM reporteTiempoVentasDiasFestivos";
        $smtDiasFestivos = $conn->query($diasFestivos);
        
        $this->diasFestivos = array();
        if($smtDiasFestivos->num_rows > 0)
        {
            while($row = $smtDiasFestivos->fetch_array())
            {
                // RECUPERAMOS LOS DIAS FESTIVOS DEL MES PERO EN EL AÑO ACTUAL
                $this->diasFestivos[] = strtotime($ano . "-" . $row["mes"] . "-" . $row["dia"]);
            }
        }
        else
        {
            error_log("error al recuperar los dias festivos");
        }
        
        $conn->close();
    }
    
    /**
     * Metodo que se encarga de recuperar los dias de descanso
     */
    private function _setDiasDescanso()
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $ano  = date("Y");
        $mes = date("m");
        
        $diasDescanso = "SELECT dia FROM reporteTiempoVentasDiasDescanso";
        $smtDiasDescanso = $conn->query($diasDescanso);
        
        $this->diasDescanso = array();
        if($smtDiasDescanso->num_rows > 0)
        {
            while($row = $smtDiasDescanso->fetch_array())
            {
                $this->diasDescanso[] = $row["dia"];
            }
        }
        else
        {
            error_log("error al recuperar los dias descanso");
        }
        
        $conn->close();
    }
    
    /**
     * Metodo que se encarga de recuperar el horario 
     */
    private function  _setHorarios()
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $ano  = date("Y");
        $mes = date("m");
        
        $horarios = "SELECT r.horaEntrada, r.horaSalida FROM reporteTiempoVentasHorario AS r WHERE r.default = 1 LIMIT 1";
        $smtHorarios = $conn->query($horarios);
        
        $this->horaEntrada = NULL;
        $this->horaSalida = NULL;
        if($smtHorarios->num_rows > 0)
        {
            while($row = $smtHorarios->fetch_array())
            {
               $this->horaEntrada = $row["horaEntrada"];
               $this->horaSalida = $row["horaSalida"];
            }
        }
        else
        {
            error_log("error al recuperar los dias descanso");
        }
        
        $conn->close();
    }
    
    /**
     * 
     */
    public function & geTiemposReporte()
    {
        $DB = new DAO();
        $conn = $DB->getConnect();
        
        $fechaInicial = (isset($_POST['fechaInicial']) && $_POST['fechaInicial'] != "") ? $_POST['fechaInicial'] : '';
        $fechaFinal = (isset($_POST['fechaFinal']) && $_POST['fechaFinal'] != "") ? $_POST['fechaFinal'] : '';
        $nickName = (isset($_SESSION["nickname"]) && $_SESSION["nickname"] != "") ? $_SESSION["nickname"] : '';
        
        $id=NULL;
        $idReporte=NULL;
        $idClienteGenerado = NULL;
        $fechaInicioVenta=NULL;
        $fechaFinVenta=NULL;
        $fechaInicioFinanciera=NULL;
        $fechaFinFinanciera=NULL;
        $fechaInicioRechazo=NULL;
        $fechaFinRechazo=NULL;
        $fechaPrimeraCaptura=NULL;
        $fechaSegundaCaptura=NULL;
        $fechaInicioAsigPH=NULL;
        $fechaFinAsigPH=NULL;
        $fechaInicioRealizoPH=NULL;
        $fechaFinRealizoPH=NULL;
        $fechaInicioAnomPH=NULL;
        $fechaFinAnomPH=NULL;
        $fechaInicioAsigInst=NULL;
        $fechaFinAsigInst=NULL;
        $fechaInicioRealInst=NULL;
        $fechaFinRealInst=NULL;
        $fechaInicioAnomInst=NULL;
        $fechaFinAnomInst=NULL;
                
        $reporteSQL = "CALL spReporteTiemposVenta(?,?,?)";
        $smtReporte = $conn->prepare($reporteSQL);
        mysqli_stmt_bind_param($smtReporte, 'sss', $fechaInicial, $fechaFinal, $nickName);
        
        $data = array();
        if ($smtReporte->execute()) 
        {
            $smtReporte->store_result();
            $smtReporte->bind_result($id, $idReporte,$idClienteGenerado, 
                    $fechaInicioVenta, $fechaFinVenta, 
                    $fechaInicioFinanciera, $fechaFinFinanciera, 
                    $fechaInicioRechazo, $fechaFinRechazo, 
                    $fechaPrimeraCaptura,$fechaSegundaCaptura,
                    $fechaInicioAsigPH,$fechaFinAsigPH,
                    $fechaInicioRealizoPH, $fechaFinRealizoPH,
                    $fechaInicioAnomPH,$fechaFinAnomPH,
                    $fechaInicioAsigInst,$fechaFinAsigInst,
                    $fechaInicioRealInst,$fechaFinRealInst,
                    $fechaInicioAnomInst,$fechaFinAnomInst);
            
            while ($smtReporte->fetch()) {
                $row = array(); 
                
                $row["id"] = $id;
                $row["idReporte"] = $idReporte;
                $row["idClienteGenerado"] = $idClienteGenerado;
                $row["tiempoVenta"] = $this->getCalcularHoras($fechaInicioVenta, $fechaFinVenta);
                $row["tiempoFinanciera"] = $this->getCalcularHoras($fechaInicioFinanciera, $fechaFinFinanciera);
                $row["tiempoRechazada"] = $this->getCalcularHoras($fechaInicioRechazo, $fechaFinRechazo);
                $row["tiempoPrimeraSegundaCap"] = $this->getCalcularHoras($fechaPrimeraCaptura, $fechaSegundaCaptura);
                $row["tiempoAsigPH"] = $this->getCalcularHoras($fechaInicioAsigPH, $fechaFinAsigPH);
                $row["tiempooRealizPH"] = $this->getCalcularHoras($fechaInicioRealizoPH, $fechaFinRealizoPH);
                $row["tiempoPHAnomalia"] = $this->getCalcularHoras($fechaInicioAnomPH, $fechaFinAnomPH);
                $row["tiempoAsigInst"] = $this->getCalcularHoras($fechaInicioAsigInst, $fechaFinAsigInst);
                $row["tiempoRealizInst"] = $this->getCalcularHoras($fechaInicioRealInst, $fechaFinRealInst);
                $row["tiempoRealizAnomalia"] = $this->getCalcularHoras($fechaInicioAnomInst, $fechaFinAnomInst);
                
                $data[] = $row;
            }
        }
        else
        {
            error_log("Error al recuperar los datos del reporte -> " . $smtReporte->error);
        }
        
        $conn->close();
        return $data;
    }
    
    /**
     * Metodoo que se encarga de calcular las horas entra las fechas, contemplando  dias de desncaso y dias festivos
     * @param date $fechaInicio
     * @param date $fechaFin
     * @return int
     */
    public function getCalcularHoras($fechaInicio, $fechaFin)
    {
        if((!isset($fechaInicio)) || (!isset($fechaFin)) )
        {
            return NULL;
        }
        
        // recuperamos unicamente el dia sin el time
        $diaInicio = date("Y-m-d", strtotime($fechaInicio));
        $diaFin = date("Y-m-d", strtotime($fechaFin));
        
        // recuperamos el valod time de la fecha 
        $timeInicio = strtotime($diaInicio);
        $timeFin = strtotime($diaFin);
        
        $horaInicio = strtotime(date("H:i:s", strtotime($fechaInicio)));
        $horaFin = strtotime(date("H:i:s", strtotime($fechaFin)));
        
        // recuperamos el valor time del horario
        $timeHoraEntrada = strtotime($this->horaEntrada);
        $timeHoraSalida = strtotime($this->horaSalida);
        
        $horasCalculadas = 0;
        
        $diasEntreFechas = 0;
        
        // PASO 1 VALIDAR SI ES EL MISMO DIA
        if($timeInicio == $timeFin)
        {
            // SI LA HORAS DE LA FECHAS REGISTRADAS ESTAN DENTRO DEL HORARIO DE TRABAJO UNICAMENTE SE CALCULARA LAS HORAS DE DIFERENCIA
            if((($horaInicio >=  $timeHoraEntrada) && ($horaInicio <= $timeHoraSalida))
                    && (($horaFin >  $timeHoraEntrada) && ($horaFin <= $timeHoraSalida)))
            {
                 $horasCalculadas = $this->_calcularHoras($horaInicio, $horaFin);
                 return $horasCalculadas;
            }
            
            // SEGUNDO CASO CUANDO  LAS  DOS HORAS ESTAN FUERA DEL HORARIO PERO DEL MISMO DIA UNICAMENTE SE CALCULAN LAS HORAS DE DIFERENCIA, ES COMO SI CONTARAN AL DIA SIGUIENTE EJEMPLO
            // UN CASO DONDE SEA DE LAS 6PM A LAS 8 PM SON 2 HORAS DE DIFERENCIA QUE DEBEN CONTAR AL DIA SIGUIENTE POR EJEMPLO 8 AM A 10 PM, UNICAMENTE CALCULAMOS LAS HORAS DE DIFERENCIA
            else if(($horaInicio >= $timeHoraSalida) && ($horaFin >= $timeHoraSalida) 
                    && ($horaFin > $horaInicio))
            {
                $horasCalculadas = $this->_calcularHoras($horaInicio, $horaFin);
                return $horasCalculadas;
            }
            
            else 
            {
                return NULL;
            }
        }
        else
        {
            // PASO 1 RECUPERAR FECHA INICIO APARTIR DEL CUAL SE EMPEZARA A CALCULAR LAS HORAS
            
            $horasDiffFechaInicio = 0;
            $horasDiffFechaFin = 0;
            $horasCicloFechas = 0;
            
            // VALIDAMOS SI CUMPLE CON LA HORA
            if(($horaInicio == $timeHoraEntrada) && ($horaFin == $timeHoraEntrada))
            {
                // CALCULAMOS CUANTOS DIAS TIENEN QUE SER ENTRE ESTAS FECHAS SIN SUMAR UN DIA ADICIONAL
                 $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d",strtotime($fechaFin)), FALSE);
            }
            
            // VALIDAMOS SI CORRESPONDE EXACTAMENTE EL HORARIO DE OFICINA TANTO EN FECHA INICIAL COMO EN HORA FINAL
            // AL CUMPLIRSE EL TOTAL DEL HORARIO SE DEBE SUMAR UN DIA PARA QUE CALCULE EL TOTAL DE HORAS POR 
            else if(($horaInicio == $timeHoraEntrada) && ($horaFin == $timeHoraSalida))
            {
                // CALCULAMOS CUANTOS DIAS TIENEN QUE SER ENTRE ESTAS FECHAS
                 $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d",strtotime($fechaFin)), TRUE);
            }
            
            // CUANDO HORA INICIAL ES IGUAL AL HORARIO DE ENTRADA PERO HORA FINAL ES MENOR AL HORARIO DE SALIDA
            else if(($horaInicio == $timeHoraEntrada) && ($horaFin < $timeHoraSalida))
            {
                // CALCULAMOS LAS HORAS DEL DELA FECHA FIN, EN DONDE SE CONTEMPLA EL HORARIO DE ENTRADA Y LA HORA DE LA FECHA FIN
                // POR EJEMPLO SI LA HORA DE ENTRADA SON LAS 8:00 AM Y LA HORA FIN SON LAS 14 HORAS HAY UNA DIFERENCIA DE 6 HORAS
                // NO SE CUMPLE EL DIA COMPLETO 
                $horasDiffFechaFin = $this->_calcularHoras($timeHoraEntrada, $horaFin);
                
                // COMO NO SE CUMPLIO LA JORNADA COMPLETA DE TRABAJO Y YA TENEMOS GUARDADAS LA DIFERENCIA DE LAS HORAS DE LA FECHA FIN
                // RESTAMOS UN DIA A LA FECHA FIN PARA QUE CUANDO SE CALCULEN LAS HORAS EN EL CICLO UNICAMENTE CONTEMPLAR JORNADAS COMPLETAS
                $fechaFin = $this->_restarDia($fechaFin);
                
                // VALIDAMOS QUE LA FECHA RESTADA SEA UN DIA HABIL, SI NO ES SE RESTA UN DIA HASTA QUE ENCUENTRE UN DIA HABIL
                $fechaFin = $this->_getDiaHabil($fechaFin, FALSE);
                
                // CALCULMOS LOS DIAS DE  DIFERENCIA
                $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d",strtotime($fechaFin)), TRUE);
            }
            
            // CUANDO HORA INICIO ES MAYOR AL HORARIO DE ENTRADA Y HORA FINAL ES IGUAL AL HORARIO DE SALIDA
            else if(($horaInicio > $timeHoraEntrada && $horaInicio <= $timeHoraSalida) && ($horaFin == $timeHoraSalida))
            {
                // CALCULAMOS LAS HORAS DEL DELA FECHA INICIO, EN DONDE SE CONTEMPLA EL HORARIO DE ENTRADA Y LA HORA DE LA FECHA INICIO
                // POR EJEMPLO SI LA HORA DE ENTRADA SON LAS 13:00 PM Y LA HORA FIN SON LAS 17 HORAS HAY UNA DIFERENCIA DE 4 HORAS
                // NO SE CUMPLE EL DIA COMPLETO 
                $horasDiffFechaInicio = $this->_calcularHoras($horaInicio, $timeHoraSalida);
                
                // COMO NO SE CUMPLE LA JORNDA COMPLETA Y YA TENEMOS LAS HORAS DE DIFERENCIA DE LA FECHA INICIO UNICAMENTE SUMAMOS UN DIA PARA QUE EMPIEZEN A CONTAR
                // JORNADAS COMPLETAS 
                $fechaInicio = $this->_sumarDia($fechaInicio);                
                
                // VALIDAMOS QUE LA FECHA SEA UN DIA HABILO, SI NO ES SE SUMA UNO HASTA QUE RETORNE UN DIA HABIL
                $fechaInicio = $this->_getDiaHabil($fechaInicio);
                
                // CALCULAMOS LOS DIAS ENTRE FECHAS SUMANDO UNO 
                $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d",strtotime($fechaFin)), TRUE);
            }
            
            else if(($horaInicio > $timeHoraEntrada && $horaInicio <= $timeHoraSalida) 
                    && ($horaFin > $timeHoraEntrada && $horaFin <= $timeHoraSalida))
            {
                // CALCULAMOS LAS HORAS DEL DELA FECHA INICIO, EN DONDE SE CONTEMPLA EL HORARIO DE ENTRADA Y LA HORA DE LA FECHA INICIO
                // POR EJEMPLO SI LA HORA DE ENTRADA SON LAS 13:00 PM Y LA HORA FIN SON LAS 17 HORAS HAY UNA DIFERENCIA DE 4 HORAS
                // NO SE CUMPLE EL DIA COMPLETO 
                $horasDiffFechaInicio = $this->_calcularHoras($horaInicio, $timeHoraSalida);
                
                // CALCULAMOS LAS HORAS DEL DELA FECHA FIN, EN DONDE SE CONTEMPLA EL HORARIO DE ENTRADA Y LA HORA DE LA FECHA FIN
                // POR EJEMPLO SI LA HORA DE ENTRADA SON LAS 8:00 AM Y LA HORA FIN SON LAS 14 HORAS HAY UNA DIFERENCIA DE 6 HORAS
                // NO SE CUMPLE EL DIA COMPLETO 
                $horasDiffFechaFin = $this->_calcularHoras($timeHoraEntrada, $horaFin);
                
                // COMO NO SE CUMPLE LA JORNDA COMPLETA Y YA TENEMOS LAS HORAS DE DIFERENCIA DE LA FECHA INICIO UNICAMENTE SUMAMOS UN DIA PARA QUE EMPIEZEN A CONTAR
                // JORNADAS COMPLETAS 
                $fechaInicio = $this->_sumarDia($fechaInicio);    
                
                // VALIDAMOS QUE LA FECHA SEA UN DIA HABILO, SI NO ES SE SUMA UNO HASTA QUE RETORNE UN DIA HABIL
                $fechaInicio = $this->_getDiaHabil($fechaInicio); 
                
                // COMO NO SE CUMPLIO LA JORNADA COMPLETA DE TRABAJO Y YA TENEMOS GUARDADAS LA DIFERENCIA DE LAS HORAS DE LA FECHA FIN
                // RESTAMOS UN DIA A LA FECHA FIN PARA QUE CUANDO SE CALCULEN LAS HORAS EN EL CICLO UNICAMENTE CONTEMPLAR JORNADAS COMPLETAS
                $fechaFin = $this->_restarDia($fechaFin);
                
                // VALIDAMOS QUE LA FECHA RESTADA SEA UN DIA HABIL, SI NO ES SE RESTA UN DIA HASTA QUE ENCUENTRE UN DIA HABIL
                $fechaFin = $this->_getDiaHabil($fechaFin, FALSE);
                
                // CALCULAMOS LOS DIAS ENTRE FECHAS SUMANDO UNO 
                $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d", strtotime($fechaFin)), TRUE);
            }
            
            // CUANDO EL HORARIO DE ENTRADA ES DESPUES DEL HORARIO DE SALIDA Y HORA FIN ES EXACTAMENTE A LA HORA ENTRADA, ESTE CASO PUEDE DAR COMO RESULTADO
            // QUE EL CALCULO ARROJEN  0 HORAS EN  ALGUNOS CASOS 
            else if(($horaInicio > $timeHoraSalida) && ($horaFin == $timeHoraEntrada))
            {
                // COMO LA HORA DE INICIO ES SUPERIOR A LA HORA SALIDA SUMAMOS UN DIA PARA QUE EL CALCULO DE LAS HORAS CICLO EMPIECE A CONTAR AL DIA SIGUIENTE,
                // CON LA HORA DE ENTRADA
                $fechaInicio = $this->_sumarDia($fechaInicio);    
                
                // VERIFICAMOS QUE SEA UN DIA HABIL
                $fechaInicio = $this->_getDiaHabil($fechaInicio, TRUE);
                
                $sumarDia = NULL;
                // VALIDAMOS QUE SEA MAS DE UN DIA DE DIFERENCIA PARA PODER RESTAR DIAS 
                if(strtotime($fechaInicio) < strtotime(date("Y-m-d", strtotime($fechaFin))))
                {
                    // COMO LA HORA FIN TIENE EL MISMA HORA QUE LA HORA SALIDA LE RESTAMOS UN DIA PARA QUE NO CUENTE LA JORNADA COMPLETA YA QUE EN REALIDAD AL SER LAS 8 AM ES COMO SI NO
                    // HUBIERAN TRABAJADO
                    $fechaFin = $this->_restarDia($fechaFin);
                    
                    // VALIDAMOS QUE LA FECHA RESTADA SEA UN DIA HABIL, SI NO ES SE RESTA UN DIA HASTA QUE ENCUENTRE UN DIA HABIL
                    $fechaFin = $this->_getDiaHabil($fechaFin, FALSE);
                    
                    $sumarDia = TRUE;
                }
                else 
                {
                    $sumarDia = FALSE;
                }
                
                
                // CALCULAMOS LOS DIAS ENTRE FECHAS SUMANDO UNO 
                $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d", strtotime($fechaFin)), $sumarDia);
            }
            
            else if(($horaInicio > $timeHoraSalida) && ($horaFin > $timeHoraEntrada && $horaFin <= $timeHoraSalida))
            {
                // COMO LA HORA DE INICIO ES SUPERIOR A LA HORA SALIDA SUMAMOS UN DIA PARA QUE EL CALCULO DE LAS HORAS CICLO EMPIECE A CONTAR AL DIA SIGUIENTE,
                // CON LA HORA DE ENTRADA
                $fechaInicio = $this->_sumarDia($fechaInicio);    
                
                // VERIFICAMOS QUE SEA UN DIA HABIL
                $fechaInicio = $this->_getDiaHabil($fechaInicio, TRUE);
                
                $horasDiffFechaFin = $this->_calcularHoras($timeHoraEntrada, $horaFin);
                
                if(strtotime($fechaInicio) < strtotime(date("Y-m-d", strtotime($fechaFin))))
                {
                    // COMO LA HORA FIN TIENE EL MISMA HORA QUE LA HORA SALIDA LE RESTAMOS UN DIA PARA QUE NO CUENTE LA JORNADA COMPLETA YA QUE EN REALIDAD AL SER LAS 8 AM ES COMO SI NO
                    // HUBIERAN TRABAJADO
                    $fechaFin = $this->_restarDia($fechaFin);
                    
                    // VALIDAMOS QUE LA FECHA RESTADA SEA UN DIA HABIL, SI NO ES SE RESTA UN DIA HASTA QUE ENCUENTRE UN DIA HABIL
                    $fechaFin = $this->_getDiaHabil($fechaFin, FALSE);
                    
                    $sumarDia = TRUE;
                }
                else 
                {
                    $sumarDia = FALSE;
                }
                
                // CALCULAMOS LOS DIAS ENTRE FECHAS SUMANDO UNO 
                $diasEntreFechas = $this->_calcularDiasDiferencia(date("Y-m-d", strtotime($fechaInicio)), date("Y-m-d", strtotime($fechaFin)), $sumarDia);
            }
           
           // CUANDO HAMBOS SE PASAN DE LA HORA FINAL 
           else if(($horaInicio > $timeHoraSalida) && ($horaFin > $timeHoraEntrada))
           {
               $fechaInicio = $this->_sumarDia($fechaInicio);
               
               $fechaInicio = $this->_getDiaHabil($fechaInicio, TRUE);
               
               $fechaFin = $this->_sumarDia($fechaFin);
               
               $fechaFin = $this->_getDiaHabil($fechaFin);
               
               $diasEntreFechas = $this->_calcularDiasDiferencia($fechaInicio, $fechaFin, FALSE);
           }
            
            // PASO 2 UNA VEZ TENIENDO LA FECHA INICIAL Y LA FECHA FINAL UNICAMENTE CALCULAMOS LAS HORAS ENTRE ESTAS FECHAS
            
            // INICIALIZAMOS APARTTIR DE QUE FECHA SE RECORRERA EN EL CICLO
            $fechaCiclo = $fechaInicio;
            
            for($i = 0; $i<=$diasEntreFechas-1; $i++)
            {
                // VALIDAMOS SI ES UN DIA HABIL
                if(($this->_validarDiaDescanso($fechaCiclo) == FALSE) && ($this->_validarDiaFestivo($fechaCiclo) == FALSE))
                {
                    // SI NO ES UN DIA FESTIVO NI TAMPOCO ES UN DIA DE DESCACNSO, CALCULAMOS LAS HORAS 
                    $horasCicloFechas += $this->_calcularHoras($timeHoraEntrada, $timeHoraSalida);
                }
                
                $fechaCiclo = $this->_sumarDia($fechaCiclo);
            }
            
            // SUMAMOS LAS HORAS DE DIFERENCIIA DE INICIO LAS HORAS CICLO Y HORAS FIN DANDO COMO RESULTADO EL TOTAL DE LAS HORAS CALCULADAS
            $horasCalculadas = ($horasDiffFechaInicio + $horasCicloFechas + $horasDiffFechaFin);
            
            return round($horasCalculadas, $this->decimales);
        }
    }
    
    /**
     * Metodo que se encarga  de calcular las horas de diferencia 
     * @param time $horaInicio
     * @param time $horaFin
     * @return int
     */
    private function _calcularHoras($horaInicio, $horaFin)
    {
        $interval = ($horaFin - $horaInicio);
        $horasCalculadas = ($interval / 3600);
        return round($horasCalculadas, $this->decimales);
    }
    
    /**
     * Metodo que se encarga de calcular los dias de diferencia entre dos fechas, es posible que en algunos casos
     * sea ecesario sumar un dia extra 
     * 
     * @param date $fechaInicio Fecha inicio 
     * @param date $fechaFin Fecha fin
     * @return int Numero de dias de diferencia
     */
    private function _calcularDiasDiferencia($fechaInicio, $fechaFin, $sumarUnDia)
    {
        $timestamp1 = strtotime($fechaInicio);
        $timestamp2 = strtotime($fechaFin);
        
        $segundosDiferenca = $timestamp1 - $timestamp2; 
        $diasDiferencia = $segundosDiferenca / (60 * 60 * 24); 
        $diasDiferencia = abs($diasDiferencia); 
        $diasDiferencia = floor($diasDiferencia); 
        $diasDiferencia = ($sumarUnDia) ? $diasDiferencia + 1 : $diasDiferencia;
        return $diasDiferencia; 
    }
    
    /**
     * 
     * @param type $fecha
     * @return type
     */
    private function _getDiaHabil($fecha, $banderaSumar = true)
    {
        if($this->_validarDiaDescanso($fecha))
        {
            // SI ES UN DIA DE DESCANSO VALIDAMOS SI SE SUMAN O RESTAN DIAS
            $fechaMasUno = ($banderaSumar) ? $this->_sumarDia($fecha) : $this->_restarDia($fecha);
            
            // UNA  VEZ QUE SUMAMOS UN DIA VOLVEMOS A VALIDAR QUE NO SEA UN DIA DE DESCANSO NI FESTIVO MEDIANTE RECURSIVIDAD
            $fecha = $this->_getDiaHabil($fechaMasUno, $banderaSumar);
        }
        else
        {
            if($this->_validarDiaFestivo($fecha))
            {
                // SI ES UN DIA DE DESCANSO VALIDAMOS SI SE SUMAN O RESTAN DIAS
                $fechaMasUno = ($banderaSumar) ? $this->_sumarDia($fecha) : $this->_restarDia($fecha);

                // UNA  VEZ QUE SUMAMOS UN DIA VOLVEMOS A VALIDAR QUE NO SEA UN DIA DE DESCANSO NI FESTIVO MEDIANTE RECURSIVIDAD
                $fecha = $this->_getDiaHabil($fechaMasUno, $banderaSumar);
            }
        }
        
        return $fecha;
    }
    
    /**
     * 
     */
    private function _validarDiaDescanso($fecha)
    {
        $diaISO = date("N", strtotime($fecha));
        $diaDescanso = (in_array($diaISO, $this->diasDescanso));
        return $diaDescanso;
    }
    
    /**
     * 
     * @param type $fecha
     * @return type
     */
    private function _validarDiaFestivo($fecha)
    {
        $fechaTime = strtotime($fecha);
        $diaFestivo = (in_array($fechaTime, $this->diasFestivos));
        return $diaFestivo;
    }
    
    /**
     * Metodo que se encarga de sumar un dia a na fecha dada
     * @param date $fecha
     * @return date
     */
    private function _sumarDia($fecha)
    {
        $oFecha = date_create($fecha);
        date_add($oFecha, date_interval_create_from_date_string("1 days"));
        $fechaMasUno = date_format($oFecha, "Y-m-d");
        return $fechaMasUno;
    }
    
    /**
     * Metodo que se encarga de restar un dia
     */
    private function _restarDia($fecha)
    {
        $oFecha = date_create($fecha);
        date_add($oFecha, date_interval_create_from_date_string("-1 days"));
        $fechaMenosUno = date_format($oFecha, "Y-m-d");
        return $fechaMenosUno;
    }
}
