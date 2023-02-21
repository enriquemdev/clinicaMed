<?php
        if($peticionAjax){
            require_once "../modelos/historialdecargoModelo.php";
        }else{
            require_once "./modelos/historialdecargoModelo.php";
        }
    class historialdecargoControlador extends historialdecargoModelo {

        public function datos_item1_controlador(){return historialdecargoModelo::datos_item1_modelo();}/*Fin de controlador */
        public function datos_item2_controlador(){return historialdecargoModelo::datos_item2_modelo();}/*Fin de controlador */
        /*-----------------Controlador para agregar cargo-----------------*/        
        /*Tabla Persona */
        public function agregar_cargo_controlador(){
            $NomEmpleado=mainModel::limpiar_cadena($_POST['cargo_empleado_reg']);
            $IdCargo=mainModel::limpiar_cadena($_POST['cargo_reg']);
            $FechaAsignacion=mainModel::limpiar_cadena($_POST['asig_cargo_reg']);
            $Salario=mainModel::limpiar_cadena($_POST['salario_cargo_reg']);
            $Estado=mainModel::limpiar_cadena($_POST['estado_cargos_reg']);
            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($NomEmpleado=="" || $IdCargo=="" || $FechaAsignacion==""|| $Salario==""|| $Estado=="" ){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de nombre de empleado -----------------
                
            if(mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,60}",$NomEmpleado)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre del empleado no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

            /*-----------------Comprobando Codigo de empleado-----------------
            $codigoPersona=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblpersona  WHERE Nombres='$NomEmpleado'");
            if ($codigoPersona->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Esta persona no está registrada en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

            /*-----------------Verificando integridad de salario de empleado -----------------*/
                
            if($Salario<0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No es posible asignar el salario solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*
            $Cod1=$codigoPersona->fetch();
            $codigoP=$Cod1['Codigo'];
            $codigoEmpleado=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado  WHERE CodPersona='$codigoP'");
            $Codigo1=$codigoEmpleado->fetch();
            $codigoP=$Codigo1['Codigo'];*/

            /*-----------------Comprobando Codigo de registrado por empleado-----------------
            $codigoP2=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblpersona  WHERE Nombres='$NomEmpleado'");
            if ($codigoP2->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Esta persona no está registrada en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $row2=$codigoP2->fetch();
            $codigoP2=$row2['Codigo'];
            $codigoEmpleado2=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado  WHERE CodPersona='$RegistradoPor'");
            $Codigo2=$codigoEmpleado2->fetch();
            $codigoP2=$Codigo2['Codigo'];


            /* -------------------------------------------registrador por automático-------------------------------------------*/ 
            session_start(['name'=>'SPM']);
            $usuarioActivo=$_SESSION['usuario_spm'];
            $codigoempleadoregistro=mainModel::ejecutar_consulta_simple("SELECT tblempleado.Codigo as CodigoEmpleado FROM tblempleado 
            inner join tblpersona on tblempleado.CodPersona = tblpersona.Codigo
            inner join tblusuarios on tblpersona.Codigo = tblusuarios.CodPersonaU WHERE NombreUsuario='$usuarioActivo'");
            $CodRegistrador=$codigoempleadoregistro->fetch();
            $RegistradoPor=$CodRegistrador['CodigoEmpleado'];
            
            /* -------------------------------------------aprobado por automático-------------------------------------------*/ 
            
            $AprobadoPor=$RegistradoPor; /*COMO LA PERSONA QUE ESTÁ REGISTRANDO ES SOLO EL ASIGNADO DE RRHH APRUEBA TAMBIÉN LA INSERCIÓN*/

            //////////////////////////////////////////////////////////////////////////////////////
            //buscador de empleado para textbox

            $parametrosEmpleado=explode('-', $NomEmpleado);//Nombre_Apellido_NombreCargo__codEmpleado


            $datos_empleado = [
                "NombreEmpleado"=>$parametrosEmpleado[0],
                "ApellidoEmpleado"=>$parametrosEmpleado[1],
                /*"UltimoCargo"=>$parametrosEmpleado[2],*/
                "CodigoEmpleado"=>$parametrosEmpleado[2]
            ];

                
            $CodigoEmpleado=historialdecargoModelo::buscarCodEmpleado($datos_empleado);
            $row2=$CodigoEmpleado->fetch();//ti
            $CodigoEmpleado=$row2['cod_empleado'];//primary key tabla empleado
            ///////////////////////////////////////////////////////////////////////////////////////
            
            
            /*Datos por enviar cargo */
            $datos_cargo_reg = [
                "CodEmpleado"=>$CodigoEmpleado,
                "IdCargo"=>$IdCargo,
                "FechaAsignacion"=>$FechaAsignacion,
                "Salario"=>$Salario,
                "Estado"=>$Estado,
                "RegistradoPor"=>$RegistradoPor,
                "AprobadoPor"=>$AprobadoPor,
            ];
            

            $agregar_cargo=historialdecargoModelo::agregar_cargo_modelo($datos_cargo_reg);
            
            
            if($agregar_cargo->rowCount()==1 ){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cargo registrado",
                    "Texto"=>"Cargo registrado correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró añadir el Cargo",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }
        }//Termina controlador
        public function agregar_solicitudcargo_controlador(){
            $NomEmpleado=mainModel::limpiar_cadena($_POST['cargo_empleado_reg']);
            $IdCargo=mainModel::limpiar_cadena($_POST['cargo_reg']);
            $FechaAsignacion=mainModel::limpiar_cadena($_POST['asig_cargo_reg']);
            $Salario=mainModel::limpiar_cadena($_POST['salario_cargo_reg']);
            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($IdCargo=="" || $FechaAsignacion==""|| $Salario=="" || $NomEmpleado==""){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            //Validación de Fecha By Luis
            $fecha=explode("-",$FechaAsignacion,3);//y-m-d
            $fechaactual=explode("-",date("y-m-d"),3);

            if(($fecha[2] < $fechaactual[2]) ){//si es una fecha antes de este año
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una año valido",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
                
            }
            if(($fecha[1] < $fechaactual[1]) ){//si es una fecha antes de este mes
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una año valido",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
                
            }
            if(($fecha[0] < $fechaactual[0]) ){//si es una fecha antes de hoy
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Por favor seleccione una año valido",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
                
            }
            
            /*-----------------Verificando integridad de nombre de empleado -----------------
            
            if(mainModel::verificar_datos("[1-9a-zA-ZáéíóúÁÉÍÓÚ ]{3,60}",$NomEmpleado)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre del empleado no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }*/

            /*-----------------Comprobando Codigo de empleado-----------------
            $codigoPersona=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblpersona  WHERE Nombres='$NomEmpleado'");
            if ($codigoPersona->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"Esta persona no está registrada en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }

            $Cod1=$codigoPersona->fetch();
            $codigoP=$Cod1['Codigo'];
            $codigoEmpleado=mainModel::ejecutar_consulta_simple("SELECT Codigo FROM tblempleado  WHERE CodPersona='$codigoP'");
            $Codigo1=$codigoEmpleado->fetch();
            $codigoP=$Codigo1['Codigo'];*/

             //////////////////////////////////////////////////////////////////////////////////////
            //buscador de empleado para textbox

            $parametrosEmpleado=explode('-', $NomEmpleado);//Nombre_Apellido_NombreCargo__codEmpleado


            $datos_empleado = [
                "NombreEmpleado"=>$parametrosEmpleado[0],
                "ApellidoEmpleado"=>$parametrosEmpleado[1],
                /*"UltimoCargo"=>$parametrosEmpleado[2],*/
                "CodigoEmpleado"=>$parametrosEmpleado[2]
            ];

                
            $CodigoEmpleado=historialdecargoModelo::buscarCodEmpleado($datos_empleado);
            $row2=$CodigoEmpleado->fetch();//ti
            $CodigoEmpleado=$row2['cod_empleado'];//primary key tabla empleado
            ///////////////////////////////////////////////////////////////////////////////////////


            /*-----------------Verificando integridad de salario de empleado -----------------*/
                
            if($Salario<0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No es posible asignar el salario solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /* -------------------------------------------registrador por automático-------------------------------------------*/ 
            session_start(['name'=>'SPM']);
            $usuarioActivo=$_SESSION['usuario_spm'];
            $codigoempleadoregistro=mainModel::ejecutar_consulta_simple("SELECT tblempleado.Codigo as CodigoEmpleado FROM tblempleado 
            inner join tblpersona on tblempleado.CodPersona = tblpersona.Codigo
            inner join tblusuarios on tblpersona.Codigo = tblusuarios.CodPersonaU WHERE NombreUsuario='$usuarioActivo'");
            $CodRegistrador=$codigoempleadoregistro->fetch();
            $RegistradoPor=$CodRegistrador['CodigoEmpleado'];
            
            
            /*Datos por enviar cargo */
            $datos_cargo_reg = [
                "CodEmpleado"=>$CodigoEmpleado,
                "IdCargo"=>$IdCargo,
                "FechaAsignacion"=>$FechaAsignacion,
                "Salario"=>$Salario,
                "Estado"=>3,
                "RegistradoPor"=>$RegistradoPor,
            ];
            

            $agregar_cargo=historialdecargoModelo::agregar_cargo_modelo($datos_cargo_reg);
            
            
            if($agregar_cargo->rowCount()==1 ){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cargo registrado",
                    "Texto"=>"Cargo solicitado correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró añadir el Cargo",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }
        }//Termina controlador
        public function cambiarestado()
        {
            $id=mainModel::limpiar_cadena($_POST['id']);
            /* Consiguiendo datos de empleado para actualizar sus otros cargos */
            $CodeEmpleado=mainModel::ejecutar_consulta_simple("SELECT CodEmpleado FROM tblhistorialcargos 
            WHERE ID='$id'");
            $CodeEmpleado=$CodeEmpleado->fetch();
            $CodeEmpleado=$CodeEmpleado['CodEmpleado'];

            /*DATOS POR ENVIAR */

            session_start(['name'=>'SPM']);
            $usuarioActivo=$_SESSION['usuario_spm'];
            $codigoempleadoregistro=mainModel::ejecutar_consulta_simple("SELECT tblempleado.Codigo as CodigoEmpleado FROM tblempleado 
            inner join tblpersona on tblempleado.CodPersona = tblpersona.Codigo
            inner join tblusuarios on tblpersona.Codigo = tblusuarios.CodPersonaU WHERE NombreUsuario='$usuarioActivo'");
            $CodRegistrador=$codigoempleadoregistro->fetch();
            $Aceptadopor=$CodRegistrador['CodigoEmpleado'];
            
            $datos_cargo= [
                "id"=>$id,
                "Estado"=>1,
                "CodEmpleado"=>$CodeEmpleado,
                "aceptado"=>$Aceptadopor
                
                
            ];
            

            $actu_cargo=historialdecargoModelo::actues($datos_cargo);
            
           
            if($actu_cargo->rowCount()==1){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cargo aceptado",
                    "Texto"=>"Cargo aceptado correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            
                

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró aceptar el cargo",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }

        }//Termina actualizar estado aceptada

        public function cambiarEstadoRechazado()
        {
            $id=mainModel::limpiar_cadena($_POST['idRechazar']);
            /*DATOS POR ENVIAR */
            session_start(['name'=>'SPM']);
            $usuarioActivo=$_SESSION['usuario_spm'];
            $codigoempleadoregistro=mainModel::ejecutar_consulta_simple("SELECT tblempleado.Codigo as CodigoEmpleado FROM tblempleado 
            inner join tblpersona on tblempleado.CodPersona = tblpersona.Codigo
            inner join tblusuarios on tblpersona.Codigo = tblusuarios.CodPersonaU WHERE NombreUsuario='$usuarioActivo'");
            $CodRegistrador=$codigoempleadoregistro->fetch();
            $Negadopor=$CodRegistrador['CodigoEmpleado'];
            
            $datos_cargo= [
                "id"=>$id,
                "negado"=>$Negadopor
                
            ];

            $actu_cargo=historialdecargoModelo::actualizarEstadoRechazada($datos_cargo);
            
           
            if($actu_cargo->rowCount()==1){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Cargo denegado",
                    "Texto"=>"Cargo denegado correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            
                

            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró negar el cargo",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }

        }//Termina actualizar estado Rechazada
        public function paginador_cargos_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
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
                $consulta="SELECT *,tblhistorialcargos.ID as idHistorialCargo, tblempleado.Codigo as Code, tblhistorialcargos.FechaRegistro as daate, 
                catestado.ID as IDestado 
                FROM tblhistorialcargos 
                INNER JOIN tblempleado ON tblhistorialcargos.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                INNER JOIN catestado ON tblhistorialcargos.Estado = catestado.ID
                WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                OR (tblempleado.Codigo LIKE '$busqueda') 
                OR (tblpersona.Cedula LIKE '$busqueda') 
                OR (tblpersona.Telefono LIKE '$busqueda') 
                OR (tblpersona.Email LIKE '$busqueda') 
                OR (catcargos.Nombre LIKE '$busqueda') 
                OR (Nombres LIKE '%$busqueda%') 
                OR (Apellidos LIKE '%$busqueda%')     
                ORDER BY tblhistorialcargos.ID DESC LIMIT $inicio,$registros";
            }else if ( isset($busqueda) && $busqueda=="TODO")
            {
                $EsConsulta=false;
                $consulta="SELECT *,tblhistorialcargos.ID as idHistorialCargo, tblempleado.Codigo as Code, tblhistorialcargos.FechaRegistro as daate , 
                catestado.ID as IDestado
                FROM tblhistorialcargos 
                INNER JOIN tblempleado ON tblhistorialcargos.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                INNER JOIN catestado ON tblhistorialcargos.Estado = catestado.ID    
                ORDER BY tblhistorialcargos.ID DESC LIMIT $inicio,$registros";
            }else{
                $EsConsulta=false;
                $consulta="SELECT *,tblhistorialcargos.ID as idHistorialCargo, tblempleado.Codigo as Code, tblhistorialcargos.FechaRegistro as daate, 
                catestado.ID as IDestado
                FROM tblhistorialcargos 
                INNER JOIN tblempleado ON tblhistorialcargos.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                INNER JOIN catestado ON tblhistorialcargos.Estado = catestado.ID WHERE tblhistorialcargos.Estado !=3 
                ORDER BY tblhistorialcargos.ID DESC LIMIT $inicio,$registros";
            }
            
            /*Se establece la conexión con la bd */
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            //Identificador: 222
            if($EsConsulta){
                $pdf = $conexion->query($consulta);
                $pdf = $pdf->fetchAll();
                $pasarPDF=json_encode($pdf);
            }else{
                $pdf = $conexion->query($consulta);
                $pdf = $pdf->fetchAll();
                $pasarPDF=json_encode($pdf);
            }
            //Termino identificador: 222
            
            $Npaginas=ceil($total/15);
            $tabla.='
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>COD EMPLEADO</th>
                        <th>NOMBRE</th>
                        <th>CARGO</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;
                foreach($datos as $rows){
                    if($rows['NombreEstado']=="Activo"){
                        $tabla.='
                        <tr class="text-center" >
                                <td>'.$rows['Code'].'</td>
                                <td>'.$rows['Nombres'].' '.$rows['Apellidos'].'</td>
                                <td>'.$rows['Nombre'].'</td>
                                <td>'.$rows['daate'].'</td>
                                <td><span class="badge badge-primary">'.$rows['NombreEstado'].'</span></td>
                                
                                </tr>
                        ';$contador++;
                    }else{
                        $tabla.='
                        <tr class="text-center" >
                                <td>'.$rows['Code'].'</td>
                                <td>'.$rows['Nombres'].' '.$rows['Apellidos'].'</td>
                                <td>'.$rows['Nombre'].'</td>
                                <td>'.$rows['daate'].'</td>
                                <td><span class="badge badge-dark">'.$rows['NombreEstado'].'</span></td>
                                
                                </tr>
                        ';$contador++;
                    }
                    
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
        }
        public function paginador_solicitudescargos_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
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

                $EsConsulta=false;
                $consulta="SELECT *,tblhistorialcargos.ID as idHistorialCargo, tblempleado.Codigo as Code, tblhistorialcargos.FechaRegistro as daate, 
                tblhistorialcargos.Estado as IDestado
                FROM tblhistorialcargos 
                INNER JOIN tblempleado ON tblhistorialcargos.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
                INNER JOIN catestado ON tblhistorialcargos.Estado = catestado.ID  WHERE tblhistorialcargos.Estado =3  
                ORDER BY tblhistorialcargos.ID DESC LIMIT $inicio,$registros";
            
            
            /*Se establece la conexión con la bd */
            $conexion= mainModel::conectar();
            $datos = $conexion->query($consulta);
            $datos = $datos->fetchAll();
            $total = $conexion->query("SELECT FOUND_ROWS()");
            $total = (int) $total->fetchColumn();

            //Identificador: 222
            if($EsConsulta){
                $pdf = $conexion->query($consulta);
                $pdf = $pdf->fetchAll();
                $pasarPDF=json_encode($pdf);
            }else{
                $pdf = $conexion->query($consulta);
                $pdf = $pdf->fetchAll();
                $pasarPDF=json_encode($pdf);
            }
            //Termino identificador: 222
            
            $Npaginas=ceil($total/15);
            $tabla.='
            <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                        <th>COD EMPLEADO</th>
                        <th>NOMBRE</th>
                        <th>CARGO SOLICITADO</th>
                        <th>FECHA DE REGISTRO</th>
                        <th>ESTADO</th>
                        <th>ACEPTAR CAMBIO</th>
                        <th>NEGAR CAMBIO</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                $contador=$inicio+1;
                foreach($datos as $rows){
                    $tabla.='
                        <tr class="text-center" >
                                <td>'.$rows['Code'].'</td>
                                <td>'.$rows['Nombres'].' '.$rows['Apellidos'].'</td>
                                <td>'.$rows['Nombre'].'</td>
                                <td>'.$rows['daate'].'</td>
                                <td>'.$rows['NombreEstado'].'</td>
                                <td>
                                <form style="padding: 0px; border:none;" class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/historialdecargoAjax.php" method="POST" data-form= "update" autocomplete="off"> <!-- Esto fue modificado -->
                                <input type="hidden" name="id" value="'.$rows['idHistorialCargo'].'">
                                <button type="submit" <i class="btn btn-success fas fa-check-circle"></i> &nbsp; </button>
                                </form>                                            
                                </td>
                                <td>
                                <form style="padding: 0px; border:none;" class="form-neon FormularioAjax" action="'.SERVERURL.'ajax/historialdecargoAjax.php" method="POST" data-form= "update" autocomplete="off"> <!-- Esto fue modificado -->
                                <input type="hidden" name="idRechazar" value="'.$rows['idHistorialCargo'].'">
                                <button type="submit" <i class="btn btn-danger fas fa-times-circle"></i> &nbsp; </button>
                                </form>                                            
                                </td>
                                
                                </tr>
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
        }
    } 