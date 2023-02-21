<?php
        if($peticionAjax){
            require_once "../modelos/pacienteModelo.php";
        }else{
            require_once "./modelos/pacienteModelo.php";
        }
    class pacienteControlador extends pacienteModelo {
        public function datos_item1_controlador(){return pacienteModelo::datos_item1_modelo();}/*Fin de controlador */
        public function datos_item2_controlador(){return pacienteModelo::datos_item2_modelo(); }/*Fin de controlador */
        public function datos_item3_controlador(){return pacienteModelo::datos_item3_modelo(); }/*Fin de controlador */
        /*-----------------Controlador para agregar cargo-----------------*/        
        /*Tabla Persona */
        public function agregar_persona_controlador($kid){
            if($kid==0){//Caso Kid=0 cuando paciente es mayor
            $Cedula=mainModel::limpiar_cadena($_POST['paciente_cedula_reg']);
            $Estado_civil=mainModel::limpiar_cadena($_POST['item_civil_reg']);
            $Telefono=mainModel::limpiar_cadena($_POST['paciente_telefono_reg']);
            $Email=mainModel::limpiar_cadena($_POST['paciente_correo_reg']);
            /*Tabla paciente */
            $INSS=mainModel::limpiar_cadena($_POST['paciente_inss_reg']);
            

            if($Cedula=="" || $Estado_civil==""|| $Telefono=="" || $INSS=="" ){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------------Verificando integridad de cedula -----------------*/
            if(mainModel::verificar_datos("[a-zA-Z0-9- ]{16,16}",$Cedula)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La cédula no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de INSS -----------------*/
            if(mainModel::verificar_datos("[0-9-]{9,9}",$INSS)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo INSS no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------------Comprobando Estado civil-----------------*/
            if($Estado_civil<1 ||$Estado_civil>3){
                $alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Seleccione un estado civil valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            /*-----------------Comprobando INSS (Solo puede existir uno)-----------------*/
            $check_INSS=mainModel::ejecutar_consulta_simple("SELECT INSS FROM tblpaciente WHERE INSS='$INSS'");
            if($check_INSS->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El INSS ingresado ya está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de teléfono -----------------*/
            
            if(mainModel::verificar_datos("[0-9#-+ ]{8,15}",$Telefono)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El Telefono no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Comprobando EMAIL-----------------*/
            if($Email!=""){
                if(filter_var($Email,FILTER_VALIDATE_EMAIL)){
                    $check_email=mainModel::ejecutar_consulta_simple("SELECT Email FROM tblpersona WHERE Email='$Email'");
                        if($check_email->rowCount()>0){
                            $alerta=[
                                "Alerta"=>"simple",
                                "Titulo"=>"Ocurrió un error inesperado",
                                "Texto"=>"El Email ingresado ya está registrado en el sistema",
                                "Tipo"=>"error"
                            ];
                            echo json_encode($alerta);
                            exit();
            }
                }else{
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El Email ingresado no es valido",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            
            }
                }//Aquí termina caso de mayor -- Esto de abajo ocurre siempre
                $Nombres=mainModel::limpiar_cadena($_POST['paciente_nombre_reg']);
                $Apellidos=mainModel::limpiar_cadena($_POST['paciente_apellido_reg']);
                $Fecha_de_nacimiento=mainModel::limpiar_cadena($_POST['paciente_nacio_reg']);
                $Genero=mainModel::limpiar_cadena($_POST['item_genero_reg']);
                $Direccion=mainModel::limpiar_cadena($_POST['paciente_direccion_reg']);
                $grupo_sanguineo=mainModel::limpiar_cadena($_POST['item_grupo_sanguineo_reg']);
                if($Nombres=="" || $Apellidos==""|| $Fecha_de_nacimiento==""|| $Genero==""|| $Direccion==""||
                $grupo_sanguineo=="" ){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"No se han llenado los campos obligatorios",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                if($kid==1){
                    $parentesco = mainModel::limpiar_cadena($_POST['parentesco_reg']);
                    $responsable =mainModel::limpiar_cadena( $_POST['Responsable_ID_reg']);
                    $tutor = 1; //Se considera que siempre es tutor ya que estamos en agregando su responsable
                    if($responsable=="" ){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Por favor seleccione un responsable",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                    }
                    if($parentesco=="" ){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"Por favor seleccione un parentesco",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }

                }
                /*          VALIDACIONES DE DATOS aquí tocó luis */
                /*-----------------Verificando integridad de nombre -----------------*/
                    
                if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ()1-9 ]{3,60}",$Nombres)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre no coinicide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                /*-----------------Verificando integridad de nombre -----------------*/
                if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,60}",$Apellidos)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El apellido no coinicide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                
                /*-----------------Comprobando Genero-----------------*/
                if($Genero<1 ||$Genero>3){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Seleccione un genero valido",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                /*------------------VALIDANDO EDAD------------------------- */
                if(mainModel::calculaedad($Fecha_de_nacimiento)<=0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La persona ingresada no es mayor de edad",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                        exit();
                

                }
                /*-----------------Verificando integridad de Dirección -----------------*/
                    if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$Direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"La dirección no coinicide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                /*De alguna manera aquí se tendrá que validar que el tutor existe y se le tendrá que asignar el chatel
                En caso de que se cumpla y exista el tutor y se le haya asignado se procede*/
                
                //Se cargan datos según caso
                if($kid==0){
                    /*Datos por enviar persona */
                $datos_persona_reg = [
                    "Cedula"=>$Cedula,
                    "Nombres"=>$Nombres,
                    "Apellidos"=>$Apellidos,
                    "Fecha_de_nacimiento"=>$Fecha_de_nacimiento,
                    "Genero"=>$Genero,
                    "Estado_civil"=>$Estado_civil,
                    "Direccion"=>$Direccion,
                    "Telefono"=>$Telefono,
                    "Email"=>$Email,
                    "Estado"=>1
                    
                ];
                }else{
                        /*Datos por enviar persona menor */
                        //Nota: La cédula va como fecha de nacimiento porque no puede ir null
                $datos_persona_reg = [
                    "Cedula"=>$Fecha_de_nacimiento,
                    "Nombres"=>$Nombres,
                    "Apellidos"=>$Apellidos,
                    "Fecha_de_nacimiento"=>$Fecha_de_nacimiento,
                    "Genero"=>$Genero,
                    "Direccion"=>$Direccion,
                    "Estado"=>1
                    
                ];
                }
                
                

                $agregar_persona=pacienteModelo::agregar_persona_modelo($datos_persona_reg);
                // $maxpersonas=mainModel::ejecutar_consulta_simple("SELECT COUNT(*) FROM tblpersona");
                // $max = $maxpersonas->fetch();
                // $maximo = $max['COUNT(*)']+1;
                $cambiarcodigo=pacienteModelo::obtener_codigo2(0); //Se le asigna el último Código de persona + 1
                if($cambiarcodigo->rowCount()!=1  ){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No se logró añadir el paciente",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                }
                $codigoPersona=pacienteModelo::obtener_persona_modelo($datos_persona_reg);
                $row2=$codigoPersona->fetch();/*ti*/
                $codigoPersona=$row2['Codigo'];
                $CodExpediente=$codigoPersona*10;
                if($kid==0){
                    $datos_paciente_reg = [
                        "CodExpediente"=>$CodExpediente,
                        "INSS"=>$INSS,
                        "GrupoSanguineo"=>$grupo_sanguineo,
                        "CodPersona"=>$codigoPersona
                    ];
                }else{
                    $datos_paciente_reg = [
                        "CodExpediente"=>$CodExpediente,
                        "INSS"=>0,
                        "GrupoSanguineo"=>$grupo_sanguineo,
                        "CodPersona"=>$codigoPersona
                    ];
                }
                
                $agregar_paciente=pacienteModelo::agregar_paciente_modelo($datos_paciente_reg);//Se registra el paciente
                if($kid!=0){//Cuando es chatel se busca validar todo
                    if($agregar_persona->rowCount()==1 && $agregar_paciente->rowCount()==1 ){

                        $datos_relacion = [
                            "Codigo_Persona" =>$codigoPersona,
                            "Codigo_Familiar" =>$responsable,
                            "ID_Parentesco" =>$parentesco,
                            "Tutor"=>$tutor
    
                        ];
                        $relacion = pacienteModelo::agregar_relacion_modelo($datos_relacion);
                        if($relacion->rowCount()==1  ){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Paciente registrado",
                            "Texto"=>"Paciente registrado correctamente",
                            "Tipo"=>"success"
                        ];
                        echo json_encode($alerta);
                        }
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No se logró añadir el paciente",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                    exit();}
                    
                }else{//Cuando se es mayor sólo se valida si se registró la persona y el paciente
                    if($agregar_persona->rowCount()==1 && $agregar_paciente->rowCount()==1 ){
                        $alerta=[
                            "Alerta"=>"recargar",
                            "Titulo"=>"Paciente registrado",
                            "Texto"=>"Paciente registrado correctamente",
                            "Tipo"=>"success"
                        ];
                        echo json_encode($alerta);
                        
                    }else{
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"No se logró añadir el paciente",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                    exit();}
                }
                
        }//Termina controlador
        public function agregar_paciente_controlador(){
            $Cedula=mainModel::limpiar_cadena($_POST['paciente_cedula_reg']);
            $Estado_civil=mainModel::limpiar_cadena($_POST['item_civil_reg']);
            $Telefono=mainModel::limpiar_cadena($_POST['paciente_telefono_reg']);
            $Email=mainModel::limpiar_cadena($_POST['paciente_correo_reg']);
            $Nombres=mainModel::limpiar_cadena($_POST['paciente_nombre_reg']);
            $Apellidos=mainModel::limpiar_cadena($_POST['paciente_apellido_reg']);
            $Fecha_de_nacimiento=mainModel::limpiar_cadena($_POST['paciente_nacio_reg']);
            $Genero=mainModel::limpiar_cadena($_POST['item_genero_reg']);
            $Direccion=mainModel::limpiar_cadena($_POST['paciente_direccion_reg']);
            /*Tabla paciente */
            $INSS=mainModel::limpiar_cadena($_POST['paciente_inss_reg']);
            $grupo_sanguineo=mainModel::limpiar_cadena($_POST['item_grupo_sanguineo_reg']);
            

            if($Cedula=="" || $Estado_civil==""|| $Telefono=="" || $INSS=="" || $Nombres=="" ||
             $Apellidos==""|| $Fecha_de_nacimiento==""|| $Genero==""|| $Direccion==""||
            $grupo_sanguineo==""  ){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"No se han llenado los campos obligatorios",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------------Verificando integridad de cedula -----------------*/
            if(mainModel::verificar_datos("[a-zA-Z0-9- ]{16,16}",$Cedula)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"La cédula no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de INSS -----------------*/
            if(mainModel::verificar_datos("[0-9-]{9,9}",$INSS)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El codigo INSS no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            
            /*-----------------Comprobando Estado civil-----------------*/
            if($Estado_civil<1 ||$Estado_civil>3){
                $alerta=[
					"Alerta"=>"simple",
					"Titulo"=>"Ocurrió un error inesperado",
					"Texto"=>"Seleccione un estado civil valido",
					"Tipo"=>"error"
				];
				echo json_encode($alerta);
				exit();
            }
            /*-----------------Comprobando INSS (Solo puede existir uno)-----------------*/
            $check_INSS=mainModel::ejecutar_consulta_simple("SELECT INSS FROM tblpaciente WHERE INSS='$INSS'");
            if($check_INSS->rowCount()>0){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El INSS ingresado ya está registrado en el sistema",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*-----------------Verificando integridad de teléfono -----------------*/
            
            if(mainModel::verificar_datos("[0-9#-+ ]{8,15}",$Telefono)){
                $alerta=[
                    "Alerta"=>"simple",
                    "Titulo"=>"Ocurrió un error inesperado",
                    "Texto"=>"El Telefono no coinicide con el formato solicitado",
                    "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
            }
            /*          VALIDACIONES DE DATOS aquí tocó luis */
            /*-----------------Verificando integridad de nombre -----------------*/
                    
                if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ()1-9 ]{3,60}",$Nombres)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El nombre no coinicide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                /*-----------------Verificando integridad de nombre -----------------*/
                if(mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ() ]{3,60}",$Apellidos)){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"El apellido no coinicide con el formato solicitado",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                
                /*-----------------Comprobando Genero-----------------*/
                if($Genero<1 ||$Genero>3){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"Seleccione un genero valido",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                /*------------------VALIDANDO EDAD------------------------- */
                if(mainModel::calculaedad($Fecha_de_nacimiento)<=0){
                    $alerta=[
                        "Alerta"=>"simple",
                        "Titulo"=>"Ocurrió un error inesperado",
                        "Texto"=>"La persona ingresada no es mayor de edad",
                        "Tipo"=>"error"
                    ];
                    echo json_encode($alerta);
                        exit();
                

                }
                /*-----------------Verificando integridad de Dirección -----------------*/
                    if(mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$Direccion)){
                        $alerta=[
                            "Alerta"=>"simple",
                            "Titulo"=>"Ocurrió un error inesperado",
                            "Texto"=>"La dirección no coinicide con el formato solicitado",
                            "Tipo"=>"error"
                        ];
                        echo json_encode($alerta);
                        exit();
                    }
                
                //Se cargan datos según caso
                //Estos datos se tienen que actualizar no Insertar
                $datos_persona_reg = [
                    "Cedula"=>$Cedula,
                    "Telefono"=>$Telefono
                    
                ];
                /*Datos por enviar paciente */
                
                $codigoPersona=pacienteModelo::obtener_persona_modelo($datos_persona_reg);
                $row2=$codigoPersona->fetch();/*ti*/
                $codigoPersona=$row2['Codigo'];
                $CodExpediente=$codigoPersona*10;
                
                $datos_paciente_reg = [
                    "CodExpediente"=>$CodExpediente,
                    "INSS"=>$INSS,
                    "GrupoSanguineo"=>$grupo_sanguineo,
                    "CodPersona"=>$codigoPersona
                ];
                
                
                $agregar_paciente=pacienteModelo::agregar_paciente_modelo($datos_paciente_reg);
                if($agregar_paciente->rowCount()==1 ){
                    $alerta=[
                        "Alerta"=>"recargar",
                        "Titulo"=>"Paciente registrado",
                        "Texto"=>"Paciente registrado correctamente",
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
        }//Termina controlador
        public function paginador_persona_controlador($pagina,$registros,$privilegio,$id,$url,$busqueda, $condicion, $condRadio, $condRadio2){
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

            if((isset($busqueda) && $busqueda!="") || $condicion!= "" || $condRadio != "" || $condRadio2 != ""){
                $EsConsulta=true;

                $consulta="SELECT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento,b.Telefono,b.Email
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        WHERE ((CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                        OR (a.CodigoP LIKE '$busqueda') 
                        OR (INSS LIKE '$busqueda') 
                        OR (b.Cedula LIKE '$busqueda') 
                        OR (b.Telefono LIKE '$busqueda') 
                        OR (b.Email LIKE '$busqueda') 
                        OR (b.Nombres LIKE '%$busqueda%') 
                        OR (b.Apellidos LIKE '%$busqueda%')) ";
                
                if ($_SESSION['condicion'] == 1)
                {
                    $consulta = $consulta."AND (a.CodigoP = 1) ";
                }

                if ($_SESSION['condRadio'] != "")
                {
                    $consulta = $consulta."AND (b.Genero = ".$_SESSION['condRadio'].") ";
                }

                if ($_SESSION['condRadio2'] != "")
                {
                    $consulta = $consulta."AND (b.Estado = ".$_SESSION['condRadio2'].") ";
                }

                $consulta = $consulta."ORDER BY CodigoP DESC LIMIT $inicio,$registros";
                    
                

            }else{
                $EsConsulta=false;
                $consulta="SELECT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                b.Fecha_de_nacimiento as FechaNacimiento,b.Telefono,b.Email
                FROM tblpaciente as a
                INNER JOIN tblpersona as b
                ON a.CodPersona = b.Codigo 
                ORDER BY CodigoP DESC LIMIT $inicio,$registros";
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
                    <tr class="text-center" >
                        <th>ID</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>EDAD</th>
                        <th>TELEFONO</th>
                        <th>REPORTE</th>
                        </tr>
                    </thead>
                    <tbody>
            ';
            if($total>=1 && $pagina<=$Npaginas){
                foreach($datos as $rows){
                    $tabla.='
                    <tr class="text-center" >
                        <th>'.$rows['CodigoPaciente'].'</th>
                        <th>'.$rows['NombresPaciente'].'</th>
                        <th>'.$rows['ApellidosPaciente'].'</th>
                        <th>'.$edad=mainModel::calculaedad($rows['FechaNacimiento']).'</th>
                        <th>'.$rows['Telefono'].'</th>
                        '//Aqui manoseo steven
                        .'<td>
                        <a href="'.SERVERURL.'Reportes/reporte-u-paciente.php?idPaciente='
                        .mainModel::encryption($rows['CodigoPaciente']).'&idPersona='
                        .mainModel::encryption($rows['CodigoPersona']).'" 
                        target="_blank"  
                        class="btn btn-info">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>
                        '//Aqui termino
                        .'    
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
        
        public function datos_familiares_paciente_controlador($id){
            $id=mainModel::decryption($id);
            $id=mainModel::limpiar_cadena($id);

            return pacienteModelo::datos_familiares_paciente_modelo($id);
        }
        public function consultas_paciente_controlador($id){
            $id=mainModel::decryption($id);
            $id=mainModel::limpiar_cadena($id);

            return pacienteModelo::consultas_paciente_modelo($id);
        }
        public function datos_paciente_controlador($id){
            $id=mainModel::decryption($id);
            $id=mainModel::limpiar_cadena($id);

            return pacienteModelo::datos_paciente_modelo($id);
        }
       public function diagnostico_paciente_controlador($id){
            return pacienteModelo::diagnostico_paciente_modelo($id);
        }
        public function recetas_medicamento_paciente_controlador($id){
            return pacienteModelo::recetas_medicamento_paciente_modelo($id);
        }
        public function recetas_examen_paciente_controlador($id){
            return pacienteModelo::recetas_examen_paciente_modelo($id);
        }
        public function diagnostico_sintomas_paciente_controlador($id){
            return pacienteModelo::diagnostico_sintomas_paciente_modelo($id);
        }
        //Aqui termino

    }