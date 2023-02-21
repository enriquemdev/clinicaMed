<?php
        if($peticionAjax){
            require_once "../modelos/citaModelo.php";
        }else{
            require_once "./modelos/citaModelo.php";
        }
    class citaControlador extends citaModelo {
        public function datos_item1_controlador(){return citaModelo::datos_item1_modelo();}/*Fin de controlador */
        /*-----------------Controlador para agregar usuario-----------------*/        
        public function agregar_cita_controlador(){
            $pacienteCita=mainModel::limpiar_cadena($_POST['cita_paciente_reg']);
            $doctorCita=mainModel::limpiar_cadena($_POST['cita_doctor_reg']);
            $consultorioCita=mainModel::limpiar_cadena($_POST['cita_consultorio_reg']);
            $fechaCita=mainModel::limpiar_cadena($_POST['cita_fecha_reg']);
            $horaInicio=mainModel::limpiar_cadena($_POST['cita_hora_inicio_reg']);
            $horaFin=mainModel::limpiar_cadena($_POST['cita_hora_fin_reg']);



            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($pacienteCita=="" || $doctorCita=="" || $consultorioCita=="" || $fechaCita=="" || $horaInicio=="" || $horaFin==""){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Ingrese todos los campos requeridos",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $hora=explode(":",$horaInicio,2);
            if($hora[0] < 8 || $hora[0] > 17){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione hora inicio dentro horario laboral 8AM-5PM",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $hora=explode(":",$horaFin,2);
            if($hora[0] < 8 || $hora[0] > 17){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione hora inicio dentro horario laboral 8AM-5PM",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $fecha=explode("-",$fechaCita,3);//y-m-d
            $fechaactual=explode("-",date("y-m-d"),3);

            if($fecha[0]< $fechaactual[0]){//si es año antes que el actual
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una fecha válida",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
                
            }
            if($fecha[1]< $fechaactual[1]){//si es mes antes que el actual
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una fecha válida",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
                
            }
            if($fecha[2]< $fechaactual[2]){//si es día antes que el actual
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una fecha válida",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $check_Codconsl=mainModel::ejecutar_consulta_simple("SELECT ID FROM catconsultorio WHERE ID='$consultorioCita'");
            if($check_Codconsl->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo del doctor no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            ///////////////////////////////////////////////////////////////////////////////////////
            
            //buscador codigo Paciente textBox

            $parametrosPaciente=explode('-', $pacienteCita);//Nombre_Apellido_codPaciente


            $datos_Paciente = [
                "NombrePaciente"=>$parametrosPaciente[0],
                "ApellidoPaciente"=>$parametrosPaciente[1],
                "CodigoPaciente"=>$parametrosPaciente[2]
            ];

                
            $CodigoPaciente=citaModelo::buscarCodPaciente($datos_Paciente);
            $row3=$CodigoPaciente->fetch();/*ti*/
            $CodigoPaciente=$row3['CodigoP'];
            ///////////////////////////////////////////////////////////////////////////////////////

            /*DATOS POR ENVIAR */
            $datos_cita_reg = [
                "pacienteCita"=>$CodigoPaciente,//1
                "fechaCita"=>$fechaCita
            ];
            

            $agregar_cita=citaModelo::agregar_cita_modelo($datos_cita_reg);

            $cita=citaModelo::obtener_cita_modelo();
            $row2=$cita->fetch();/*ti*/
            $codigoCita=$row2['IDCita'];

            //////////////////////////////////////////////////////////////////////////////////////
            //buscador de empleado(doctor) para textbox

            $parametrosEmpleado=explode('-', $doctorCita);//Nombre_Apellido_NombreCargo_UltimoCargo_codEmpleado


            $datos_empleado = [
                "NombreEmpleado"=>$parametrosEmpleado[0],
                "ApellidoEmpleado"=>$parametrosEmpleado[1],
                "UltimoCargo"=>$parametrosEmpleado[2],
                "CodigoEmpleado"=>$parametrosEmpleado[3]
            ];

                
            $CodigoEmpleado=citaModelo::buscarCodEmpleado($datos_empleado);
            $row2=$CodigoEmpleado->fetch();//ti
            $CodigoEmpleado=$row2['cod_empleado'];//primary key tabla empleado
            ///////////////////////////////////////////////////////////////////////////////////////
            
            $datos_detCita_reg = [
                "IdCita"=>$codigoCita,
                "HoraInicio"=>$horaInicio,
                "HoraFin"=>$horaFin,
                "IdConsultorio"=>$consultorioCita,//2
                "CodDoctor"=>$CodigoEmpleado,//ya con buscador
                "Estado"=>1
            ];
            $agregar_detCita=citaModelo::agregar_detCita_modelo($datos_detCita_reg);

            if($agregar_cita->rowCount()==1 && $agregar_detCita->rowCount()==1){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cita registrada",
                    "Texto"=>"Cita registrada correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            
                

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró añadir el usuario",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }
        }
        public function paginador_cita_controlador($pagina,$registros,$privilegio,$id,$url,$fecha1,$fecha2){
            $pagina=mainModel::limpiar_cadena($pagina);
            $registros=mainModel::limpiar_cadena($registros);
            $privilegio=mainModel::limpiar_cadena($privilegio);
            $id=mainModel::limpiar_cadena($id);
            $url=mainModel::limpiar_cadena($url);
            $url=SERVERURL.$url."/";

            //Identificador: 000
            $EsConsulta=false;
            //Termino identificador: 000

            //Identificador: 111
            $fecha1=mainModel::limpiar_cadena($fecha1);
            $fecha2=mainModel::limpiar_cadena($fecha2);
            //Termino identificador: 111
            $tabla="";
            $pagina= (isset($pagina) && $pagina>0) ?(int)$pagina :1 ;
            $inicio= ($pagina>0) ?(($pagina*$registros)-$registros) : 0; 

            //Identificador: 222
            if(isset($fecha1) && $fecha1!="" && isset($fecha2) && $fecha2!=""){
                /*if(mainModel::verificar_fecha($fecha1) || mainModel::verificar_fecha($fecha1)){
                    return '<div class="alert alert-danger text-center role="alert">
                    <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                    <h4 class="alert-heading"> El formato de la fecha es incorrecto!!</h4>
                    <p class="mb-0"> Lo sentimos no podemos mostrar la información solicitada
                    debido a un errror. </p>
                    </div> ';
                    exit();
                }*/
                $EsConsulta=true;
                $consulta="SELECT SQL_CALC_FOUND_ROWS * ,a.Estado as estadocita
                FROM tbldetallesdecita as a
                INNER JOIN tblcita ON a.IdCita = tblcita.IDCita 
                INNER JOIN tblpaciente ON tblcita.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                WHERE tblcita.fechaProgramada BETWEEN '$fecha1' AND '$fecha2'
                ORDER BY tblcita.fechaProgramada DESC LIMIT $inicio,$registros";
            }else{
                $EsConsulta=false;
                $consulta="SELECT SQL_CALC_FOUND_ROWS *,a.Estado as estadocita
                FROM tbldetallesdecita as a
                INNER JOIN tblcita ON a.IdCita = tblcita.IDCita 
                INNER JOIN tblpaciente ON tblcita.CodPaciente = tblpaciente.CodigoP 
                INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
                ORDER BY tblcita.fechaProgramada DESC LIMIT $inicio,$registros";
            }
            //Identificador: 222

            /*Se establece la conexión con la bd */
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();
            
            $Npaginas=ceil($total/5);
            $tabla.='
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                    <tr class="text-center" >
                        <th>ID CITA</th>
                        <th>NOMBRE PACIENTE</th>
                        <th>FECHA PROGRAMADA</th>
                        <th>HORA DE CITA</th>
                        <th>ESTADO</th>
                        <th>Reporte</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                foreach($datos as $rows){
                    if($rows['estadocita']==1){
                        $estado="Activa";
                    }else{$estado="Inactiva";}
                        $tabla.='
                    <tr class="text-center" >
                        <th>'.$rows['IDCita'].'</th>
                        <th>'.$rows['Nombres']. ' '.$rows['Apellidos'].'</th>
                        <th>'.$rows['fechaProgramada'].'</th>
                        <th>'.$rows['HoraInicio'].'</th>
                        <th> <span class="badge badge-info">'.$estado.'</span></th>
                        <td>
                        <a href="'.SERVERURL.'Reportes/reporte-u-cita.php?idCita='
                        .mainModel::encryption($rows['IDCita']).'" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>   
                        ';
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
        
        public function datos_cita_controlador($id){
            $id=mainModel::decryption($id);
            $id=mainModel::limpiar_cadena($id);

            return citaModelo::datos_cita_modelo($id);
        }
    }