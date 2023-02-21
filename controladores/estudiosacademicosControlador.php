<?php
        if($peticionAjax){
            require_once "../modelos/estudiosacademicosModelo.php";
        }else{
            require_once "./modelos/estudiosacademicosModelo.php";
        }
    class estudiosacademicosControlador extends estudiosacademicosModelo {
        public function datos_item1_controlador(){return estudiosacademicosModelo::datos_item1_modelo();}/*Fin de controlador */
        /*-----------------Controlador para agregar receta-----------------*/        
        public function agregar_estudio_academico_controlador(){
            $NomEmpleado=mainModel::limpiar_cadena(explode("__",$_POST['cod_empleado_reg'])[1]);
            $NombreEstudio=mainModel::limpiar_cadena($_POST['nombre_estudio_reg']);
            $TipoEstudio=mainModel::limpiar_cadena($_POST['tipo_estudio_reg']);
            $Institucion=mainModel::limpiar_cadena($_POST['institucion_estudio_reg']);
            $Inicio=mainModel::limpiar_cadena($_POST['inicio_estudios_reg']);
            $completacion=mainModel::limpiar_cadena($_POST['completo_estudio_reg']);
            
            
            /*----------------Comprobar campos vacíos -----------------*/  
            
            if($NomEmpleado=="" || $NombreEstudio=="" || $TipoEstudio=="" || $Institucion=="" || $completacion==""|| $Inicio=="" ){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de nombre de estudio -----------------*/
                
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚ1-9 ]{3,100}",$NombreEstudio)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre del estudio no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de nombre de institución -----------------*/
                
            if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚ1-9 ]{3,60}",$Institucion)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El nombre de la institución no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Comprobando codigos (Si no existe lanza error)-----------------*/
            $check_CodP=mainModel::ejecutar_consulta_simple("SELECT ID FROM catnivelacademico WHERE ID='$TipoEstudio'");
            if($check_CodP->rowCount()==0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo del tipo de estudio no está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
        
            /*Datos por enviar receta */
            $datos_estudio_reg = [
                "CodEmpleado"=>$NomEmpleado,
                "NombreEstudio"=>$NombreEstudio,
                "TipoEstudio"=>$TipoEstudio,
                "Institucion"=>$Institucion,
                "InicioEstudio"=>$Inicio,
                "FinEstudio"=>$completacion,
                "Diploma"=>$_FILES['certificado_estudio_reg'],
            ];
            

            $agregar_estudio=estudiosacademicosModelo::agregar_estudio_academico_modelo($datos_estudio_reg);

            if($agregar_estudio->rowCount()==1 ){
                $alerta=[
                    "Alerta"=>"recargar",
                    "Titulo"=>"Estudio académico registrado",
                    "Texto"=>"Estudio académico registrado correctamente",
                    "Tipo"=>"success"
                ];
                echo json_encode($alerta);
            }else{
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se logró añadir el estudio",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
            exit();
                
            }
        }//termina controlador
        
        /*-----------------Controlador para paginar receta----------------- Nota- Necesitamos las vistas para los detalles de receta*/    
        public function paginador_estudio_academico_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda){
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
                $consulta="SELECT * FROM tblestudioacademico  
                INNER JOIN tblempleado ON tblestudioacademico.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                INNER JOIN catnivelacademico ON tblestudioacademico.TipoEstudio = catnivelacademico.ID 
                WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                OR (tblempleado.Codigo LIKE '$busqueda') 
                OR (tblpersona.Cedula LIKE '$busqueda') 
                OR (tblpersona.Telefono LIKE '$busqueda') 
                OR (tblpersona.Email LIKE '$busqueda') 
                OR (tblestudioacademico.Institucion LIKE '$busqueda') 
                OR (tblestudioacademico.NombreEstudio LIKE '$busqueda') 
                OR (catnivelacademico.NombreNivelAcademico LIKE '$busqueda') 
                OR (Nombres LIKE '%$busqueda%') 
                OR (Apellidos LIKE '%$busqueda%') 
                ORDER BY tblestudioacademico.IDEstudioAcademico DESC LIMIT $inicio,$registros";
            }else{
                $EsConsulta=false;
                $consulta="SELECT * FROM tblestudioacademico  
                INNER JOIN tblempleado ON tblestudioacademico.CodEmpleado = tblempleado.Codigo 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
                ORDER BY tblestudioacademico.IDEstudioAcademico DESC LIMIT $inicio,$registros";
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
								<th>FUNCIONARIO</th>
								<th>ESPECIALIDAD</th>
								<th>FECHA REGISTRO</th>
                        </tr>
                            </thead>
                            <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                foreach($datos as $rows){
                    $tabla.='
                        <tr class="text-center" >
                                <td>'.$rows['Nombres'].' '.$rows['Apellidos'].'</td>
                                <td>'.$rows['NombreEstudio'].'</td>
                                <td>'.$rows['FinEstudio'].'</td>
                        </tr>
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
        }
    }
