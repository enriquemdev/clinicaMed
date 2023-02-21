<?php
        if($peticionAjax){
            require_once "../modelos/dashboardModelo.php";
        }else{
            require_once "./modelos/dashboardModelo.php";
        }
    class dashboardControlador extends dashboardModelo {
        public function dashboard($cargo){
            if($cargo ==1 ){//Si es gerente
                $tabla="";

                //Obtener datos
                //Cantidad ganada mensual
    
                    $consulta="SELECT COUNT(*) as registros FROM tblconsulta WHERE (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(FechaYHora , ' ', 1), ' ', -1)) BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW();"; //Se obtiene consultas de aquí a un año
                    $Consultademes="SELECT COUNT(*) as registros FROM tblconsulta WHERE (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(FechaYHora , ' ', 1), ' ', -1)) BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW();"; //Se obtiene consultas del mes
                    $Consultadehoy="SELECT COUNT(*) as registros FROM tblconsulta WHERE CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(FechaYHora , ' ', 1), ' ', -1))"; //Se obtiene consultas de hoy
                    $consulta_de_ganancias="SELECT Monto from tbldetpagoservicios as a 
                    INNER JOIN tblrecibosventa as b on a.NumeroRecibo = b.idRecibo Where CURRENT_DATE = (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(b.FyHRegistro , ' ', 1), ' ', -1))";
                    $consulta_de_ganancias_del_mes = "SELECT Monto from tbldetpagoservicios as a INNER JOIN tblrecibosventa as b on a.NumeroRecibo = b.idRecibo Where (SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(b.FyHRegistro , ' ', 1), ' ', -1))BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
                    

                /*Se establece la conexión con la bd */
                $conexion= mainModel::conectar();
                $datos = $conexion->query($Consultademes);
                $consultames = $datos->fetchAll();
                /*$datos = $conexion->query($consulta);
                $consultaanual = $datos->fetchAll();*/
                $datos = $conexion->query($Consultadehoy);
                $consultashoy = $datos->fetchAll();
                //Ganancias del día
                $datos = $conexion->query($consulta_de_ganancias);
                $GananciasDeHoy = $datos->fetchAll();
                $total = 0;
                foreach($GananciasDeHoy as $ganancia){
                    $total = $total + $ganancia['Monto'];
                }
                //Ganancias del mes
                $datos = $conexion->query($consulta_de_ganancias_del_mes);
                $GananciasDelMes = $datos->fetchAll();
                $total2 = 0;
                foreach($GananciasDelMes as $ganancia){
                    $total2 = $total2 + $ganancia['Monto'];
                }


                foreach($consultames as $row){
                    $consultasdadasmes=$row['registros']; //cantidad de consultas al mes
                    $gananciasmes=$consultasdadasmes*100; //ganado al mes
                }
                /*foreach($consultaanual as $row){
                    $consultasdadasano=$row['registros']; //cantidad de consultas al año
                    $gananciasano=$consultasdadasano*100; //Ganado al año
                    
                }*/
                foreach($consultashoy as $row){
                    $consultasdadashoy=$row['registros']; //cantidad de consultas de hoy
                    
                }
                $tabla.='
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
    
                       
    
                        <!-- Content Row -->
                        <div class="row">
    
                        <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Ganancias (Hoy)</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">'.$total.'$</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
    
                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Ganancias (Mensuales)</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">'.$total2.'$</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Consultas del mes</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">'.$consultasdadasmes.'</div>
                                            </div>
                                            <div class="col-auto">
                                            <i class="fa-solid fa-calendar-plus fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Consultas de hoy</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">'.$consultasdadashoy.'</div>
                                            </div>
                                            <div class="col-auto">
                                            <i class="fa-solid fa-calendar-day fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Content Row -->

                        <canvas id="myChart" style="width:100%;max-width:50vw; max-height: 40vh; margin:auto;"></canvas> 
                            
                        </div>
    
                       
    
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->
                ';
                return $tabla;

            } else if ($cargo == 2){//Si es doctor
                //DATOS DE CONSULTAS POR HACER
                $consulta="SELECT  COUNT(*) as registros, a.Codigo as Codigo_consulta,a.Estado as estadocons, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                ApellidoPaciente, c.Apellidos as ApellidoDoc,e.Telefono as TelefonoPac, e.Email as EmailPac 
                from tblconsulta as a
                inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                inner join tblpersona as c on (b.CodPersona=c.Codigo)
                inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                    WHERE a.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "') AND (a.Estado=1 OR a.Estado=2 OR a.Estado=3 ) 
                    ORDER BY a.Estado DESC,a.Codigo ASC"; //Se obtiene consultas del dr
                $conexion= mainModel::conectar();
                $datos = $conexion->query($consulta);
                $datos = $datos->fetch();
                //LA INFO QUE SE LE MUESTRA AL DR EN ESTE ANCHOR ES DE LAS CONSULTAS DE PRIORIDAD, ASIGNADAS Y EN ESPERA.
                //DATOS DE CONSULTAS REALIZADAS EN UN MES
                $consulta="SELECT COUNT(*) as registros , a.Codigo as Codigo_consulta,a.Estado as estadocons, c.Nombres as NombresDoctor, e.Nombres as NombresPaciente,e.Apellidos as 
                ApellidoPaciente, c.Apellidos as ApellidoDoc, e.Telefono as TelefonoPac, e.Email as EmailPac  
                from tblconsulta as a
                inner join tblempleado as b ON (a.CodMedico=b.Codigo)
                inner join tblpersona as c on (b.CodPersona=c.Codigo)
                inner join tblpaciente as d on (a.CodPaciente=d.CodigoP)
                inner join tblpersona as e on(d.CodPersona=e.Codigo) 
                WHERE a.CodMedico = (SELECT Codigo FROM tblempleado WHERE CodPersona = '" . $_SESSION['codPersona_spm'] . "') AND (a.Estado=5) AND ((SELECT SUBSTRING_INDEX(SUBSTRING_INDEX(FechaYHora , ' ', 1), ' ', -1)) BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()) ORDER BY a.Codigo";
                $datos2 = $conexion->query($consulta);
                $datos2 = $datos2->fetch();
                $url = SERVERURL ;
                $tabla='
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
    
                        <!-- Page Heading -->
                        
                        <a href= "'.$url.'solicitud-consultadr-list/" class="tile" title="Lista de consultas">
                            <div class="tile-tittle" style="font-size: 75%;">CONSULTAS NUEVAS</div>
                            <div class="tile-icon">
                                <i class=" fas fa-regular fa-hand-holding-medical"> '.$datos[0].'</i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'paciente-search//" class="tile" title="Buscador Expedientes">
                            <div class="tile-tittle" style="font-size: 75%;">CONSULTAR EXPEDIENTE</div>
                            <div class="tile-icon">
                                <i class="fa-solid fa-file-medical"></i>
                                
                            </div>
					    </a>
                        <div>
                            <div class="tile" title="Consultas dadas en los últimos 7 días">
                                <div class="tile-tittle" style="font-size: 75%;">CONSULTAS DADAS</div>
                                <div class="tile-icon">
                                    <i class="fa-solid fa-suitcase-medical"> '.$datos2[0].'</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->';
                return $tabla;
            }else if ($cargo == 3){//Si es recepcionista

                /*
                $consulta=""; 
                $conexion= mainModel::conectar();
                $datos = $conexion->query($consulta);
                $datos = $datos->fetch();
                */
                $url = SERVERURL ;
                $tabla='
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
    
                        
                        <a href= "'.$url.'paciente-new/" class="tile" title="Agregar paciente">
                            <div class="tile-tittle" style="font-size: 75%;">AGREGAR PACIENTE</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-hospital-user"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'paciente-search/" class="tile" title="Buscar paciente">
                            <div class="tile-tittle" style="font-size: 75%;">BUSCAR PACIENTE</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'solicitudConsulta-new/" class="tile" title="Asignar consulta">
                            <div class="tile-tittle" style="font-size: 75%;">ASIGNAR CONSULTA</div>
                            <div class="tile-icon">
                                <i class=" fas fa-regular fa-hand-holding-medical"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'cita-new/" class="tile" title="Agregar cita">
                            <div class="tile-tittle" style="font-size: 75%;">AGREGAR CITA</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-calendar-check"></i>
                                
                            </div>
					    </a>
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->';
                return $tabla;
            }else if ($cargo == 4){//Si es cajero

                /*
                $consulta=""; 
                $conexion= mainModel::conectar();
                $datos = $conexion->query($consulta);
                $datos = $datos->fetch();
                */
                $url = SERVERURL ;
                $tabla='
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
    
                        
                        <a href= "'.$url.'gestion-caja/" class="tile" title="Gestiones caja">
                            <div class="tile-tittle" style="font-size: 75%;">CAJA</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-money-check-dollar"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'consulta-search/" class="tile" title="Buscar consulta">
                            <div class="tile-tittle" style="font-size: 75%;">BUSCAR CONSULTA</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <i class="fa-solid fa-file-medical"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'paciente-search/" class="tile" title="Buscar paciente">
                            <div class="tile-tittle" style="font-size: 75%;">BUSCAR PACIENTE</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <i class="fa-solid fa-hospital-user"></i>
                                
                            </div>
					    </a>
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->';
                return $tabla;
            }else if ($cargo == 5){//Si es administrador

                /*
                $consulta=""; 
                $conexion= mainModel::conectar();
                $datos = $conexion->query($consulta);
                $datos = $datos->fetch();
                */
                $url = SERVERURL ;
                $tabla='
                <!-- Main Content -->
                <div id="content">
    
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
    
                        
                        <a href= "'.$url.'user-list/" class="tile" title="Gestiones usuarios">
                            <div class="tile-tittle" style="font-size: 75%;">USUARIOS</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-users"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'respaldo-new/" class="tile" title="Realizar backup">
                            <div class="tile-tittle" style="font-size: 75%;">BACKUP</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-download"></i>
                                
                            </div>
					    </a>
                        <a href= "'.$url.'historial-de-cargo-solicitud/" class="tile" title="Cargos">
                            <div class="tile-tittle" style="font-size: 75%;">CARGOS</div>
                            <div class="tile-icon">
                            <i class="fa-solid fa-user-doctor"></i>
                                
                            </div>
					    </a>
                    </div>
                    <!-- /.container-fluid -->
    
                </div>
                <!-- End of Main Content -->';
                return $tabla;
            }

        } //Termina controlador
    }