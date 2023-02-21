<?php
        if($peticionAjax){
            require_once "../modelos/aperturaCajaModelo.php";
        }else{
            require_once "./modelos/aperturaCajaModelo.php";
        }
    class AperturaCajaControlador extends AperturaCajaModelo {
        public function datos_item1_controlador(){return AperturaCajaModelo::datos_item1_modelo();}/*Fin de controlador */


        /*-----------------Controlador para agregar apertura-----------------*/        
        public function agregar_apertura_controlador(){
            $Caja=mainModel::limpiar_cadena($_POST['codCajaApertura']);
            $MontoInicial=mainModel::limpiar_cadena($_POST['montoInicial']);

            session_start(['name'=>'SPM']);
            $empleadoCaja=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '".$_SESSION['codPersona_spm']."'");
            $empleadoCaja=$empleadoCaja->fetch();
            $empleadoCaja=$empleadoCaja['Codigo'];

            $direccionMAC = exec('getmac');
            $direccionMAC = strtok($direccionMAC, ' ');


            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($Caja=="" || $MontoInicial==""){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            /*DATOS POR ENVIAR DIAGNOSTICO*/
            $datos_apertura_reg = [
                "Caja"=>$Caja,
                "MontoInicial"=>$MontoInicial,
                "EmpleadoCaja"=>$empleadoCaja,//Variable modificada por el buscador en tiempo real
                "direccionMAC"=>$direccionMAC
            ];

            $estadoCaja=mainModel::ejecutar_consulta_simple("UPDATE catcaja SET EstadoCaja = '1' WHERE idCaja = '".$Caja."'");
            $agregar_apertura=AperturaCajaModelo::agregar_apertura_modelo($datos_apertura_reg);

            if($agregar_apertura->rowCount()==1){
                // $alerta=[
                //     "Alerta"=>"recargar",
                //     "Titulo"=>"Caja Aperturada",
                //     "Texto"=>"Caja Aperturada correctamente",
                //     "Tipo"=>"success"
                // ];
                // echo json_encode($alerta);

                $alerta=[
                    "Alerta"=>"redireccion_violenta",
                    "Titulo"=>"CAJA APERTURADA",
                    "Texto"=>"Caja aperturada con éxito.",
                    "Tipo"=>"success",
                    "URL"=>SERVERURL."gestion-caja/"
                ];
                echo json_encode($alerta);
            

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró aperturar caja",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }
        }

        public function verificar_apertura_controlador()
        {
            //session_start(['name'=>'SPM']);
            $empleadoCaja=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado WHERE CodPersona = '".$_SESSION['codPersona_spm']."'");
            $empleadoCaja=$empleadoCaja->fetch();
            $empleadoCaja=$empleadoCaja['Codigo'];

            // $datosCajaEmpleado=mainModel::ejecutar_consulta_simple("SELECT MAX(idApertura), EstadoCaja, nombreCaja FROM catcaja as a
            // INNER JOIN tblaperturacaja as b ON a.idCaja = b.Caja
            // WHERE EmpleadoCaja = '".$empleadoCaja."'");

            //ANTES HABIA INNER PROBANDO LEFT PARA CUANDO NO HAYA APERTURAS REALIZADAS DE ESA CAJA
            $datosCajaEmpleado=mainModel::ejecutar_consulta_simple("SELECT idApertura, EstadoCaja, nombreCaja FROM catcaja as a
            LEFT JOIN tblaperturacaja as b ON a.idCaja = b.Caja
            WHERE (idApertura = (SELECT MAX(idApertura) from tblaperturacaja WHERE EmpleadoCaja = '".$empleadoCaja."'))
            ");
            $datosCajaEmpleado=$datosCajaEmpleado->fetch();
            //$datosCajaEmpleado=$datosCajaEmpleado['datosCajaEmpleado'];

            return $datosCajaEmpleado;
        }

        public function cerrarCaja_controlador()
        {
            $idCaja=mainModel::limpiar_cadena($_POST['cerrarCajaReg']);

            // $datos_cierre_reg = [
            //     "idCaja"=>$idCaja
            // ];
            $fechaCierre=mainModel::ejecutar_consulta_simple("UPDATE tblaperturacaja SET FyHCierre = CURRENT_TIMESTAMP
             WHERE (idApertura = (SELECT MAX(idApertura) FROM tblaperturacaja WHERE Caja = '".$idCaja."')) ");
            $cerrarCaja=mainModel::ejecutar_consulta_simple("UPDATE catcaja SET EstadoCaja = '2' WHERE idCaja = '".$idCaja."'");
            
            header("Location: ".SERVERURL."gestion-caja/");
            exit();
            
            //echo "<script>window.history.back()</script>";

            // if($cerrarCaja->rowCount()==1){
            //     $alerta=[
            //         "Alerta"=>"redireccionar",
            //         "URL"=>SERVERURL."gestion-caja"
            //     ];
            //     echo json_encode($alerta);
            

            // }else{
            //     $alerta=[
            //         "Alerta"=>"simple",
            //         "Titulo"=>"Ocurrió un error inesperado",
            //         "Texto"=>"No se logró cerrar caja",
            //         "Tipo"=>"error"
            //     ];
            //     echo json_encode($alerta);
            // exit();
                
            // }
        }
        
        public function paginador_diagnostico_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
            $pagina=mainModel::limpiar_cadena($pagina);
            $registros=mainModel::limpiar_cadena($registros);
            $privilegio=mainModel::limpiar_cadena($privilegio);
            $id=mainModel::limpiar_cadena($id);
            $url=mainModel::limpiar_cadena($url);
            $url=SERVERURL.$url."/";

            //Identificador: 333
            $EsConsulta=false;
            //Termino identificador: 333

            $busqueda=mainModel::limpiar_cadena($busqueda);
            $tabla="";
            $pagina= (isset($pagina) && $pagina>0) ?(int)$pagina :1 ;
            $inicio= ($pagina>0) ?(($pagina*$registros)-$registros) : 0; 
            
            if(isset($busqueda) && $busqueda!=""){
                $EsConsulta=true;
                $consulta="SELECT *,A.Codigo as coddiagnostico,A.CodConsulta as CodigoConsulta 
                FROM tbldiagnosticoconsulta AS A
                INNER JOIN tblconsulta AS B ON A.CodConsulta = B.Codigo 
                INNER JOIN tblpaciente as C ON B.CodPaciente = C.CodigoP 
                INNER JOIN tblpersona as D ON C.CodPersona = D.Codigo 
                INNER JOIN catenfermedades as E ON A.IdEnfermedad = E.ID 
                WHERE (CONCAT(D.Nombres,' ',D.Apellidos) LIKE '%$busqueda%')
                OR (D.Codigo LIKE '$busqueda') 
                OR (C.INSS LIKE '$busqueda') 
                OR (D.Cedula LIKE '$busqueda') 
                OR (D.Telefono LIKE '$busqueda') 
                OR (D.Email LIKE '$busqueda') 
                OR (D.Nombres LIKE '%$busqueda%') 
                OR (D.Apellidos LIKE '%$busqueda%') 
                ORDER BY coddiagnostico DESC
                LIMIT $inicio,$registros";
            }else{
                $EsConsulta=false;
                $consulta="SELECT *,tbldiagnosticoconsulta.Codigo as coddiagnostico,tbldiagnosticoconsulta.CodConsulta as CodigoConsulta FROM tbldiagnosticoconsulta INNER JOIN tblconsulta 
                ON tbldiagnosticoconsulta.CodConsulta = tblconsulta.Codigo 
                INNER JOIN tblpaciente ON tblconsulta.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                INNER JOIN catenfermedades ON tbldiagnosticoconsulta.IdEnfermedad = catenfermedades.ID 
                ORDER BY coddiagnostico DESC
                LIMIT $inicio,$registros";
            }
            
            
            /*Se establece la conexión con la bd */
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();
            
            $Npaginas=ceil($total/15);
            $tabla.='
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                    <tr class="text-center roboto-medium">		
                        <th>#</th>
                        <th>NOMBRE PACIENTE</th>
                        <th>ENFERMEDAD</th>
                        <th>DESCRIPCIÓN</th>
                        <th>GENERAR</th>
                        <th>DETALLES</th>
                </tr>
                    </thead>
                    <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;
                foreach($datos as $rows){
                        $tabla.='
                        <tr class="text-center" >
                                <td>'.$rows['coddiagnostico'].'</td>
                                <th>'.$rows['Nombres'].' '.$rows['Apellidos'].'</th>
                                <th>'.$rows['NombreEnfermedad'].'</th>
                                <th>'.$rows['Descripcion'].'</th>
                                <!--Button to display details -->
                                
                                <th>
                                
                                <a href="'.SERVERURL.'receta-medica-auto/'.mainModel::encryption($rows['CodigoConsulta']).'"  data-toggle="tooltip" title="Generar Receta!" style="margin-right:20px; text-decoration:none" >
                                <i class="fa-solid fa-notes-medical hover-shadow" style="font-size: 30px; color:#2B8288; " ></i>
                                </a>
                                <a href="'.SERVERURL.'receta-examen-auto/'.mainModel::encryption($rows['CodigoConsulta']).'"data-toggle="tooltip" title="Generar orden exámen!" style="text-decoration:none">
                                <i class="fa-solid fa-file-medical hover-shadow" style="font-size: 30px; color:#36475C"></i>
                                </a>
                                </span>
                                <a href="'.SERVERURL.'constancia-auto/'.mainModel::encryption($rows['coddiagnostico']).'"data-toggle="tooltip" title="Generar constancia!" style="margin-left:20px; text-decoration:none"">
                                <i class="fa-solid fa-bed-pulse hover-shadow" style="font-size: 30px; color: #264963 "></i>
                                </a>
                                </th>
                                <td>
                                    <button style="font-size: 28px; color: #0384fc " class ="btn btn-info p-0" onclick="loadData(this.getAttribute(`data-id`));" data-id="'.$rows['coddiagnostico'].'">
                                    <i class="fa-solid fa-list"></i>
                                    </button>
                                </td>
                    ';$contador++;
                        }
                    
                
            }
            else{
                if($total>=1){
                    $tabla.='<tr class="text-center"><td colspan="9">
                    <a href="'.$url.'" class="btn btn-raised btn-primary btn-sm">Haga click acá para recargar lista</a>
                    
                    </td></tr>';
                }else{
                    $tabla.='<tr class="text-center"><td colspan="9">No hay registros en el sistema</td></tr>';
                }
                

            }

            $tabla.='</tbody></table></div>';
            if($total>=1 && $pagina<=$Npaginas){
                $tabla.=mainModel::paginador_tablas($pagina,$Npaginas,$url,7);

            }
            return $tabla;
        }//Termina controlador
        
        
    }