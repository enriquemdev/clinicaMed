<?php
        if($peticionAjax){
            require_once "../modelos/loginModelo.php";
        }else{
            require_once "./modelos/loginModelo.php";
        }
    class loginControlador extends loginModelo{
        /*Controlador inicio de sesión*/
        public function iniciar_sesion_controlador(){
            $usuario=mainModel::limpiar_cadena($_POST['usuario_log']);
            $clave=mainModel::limpiar_cadena($_POST['clave_log']);

            /*Comprobar campos vacios */
            if($usuario=="" || $clave==""){
                echo '
                <script>
                    Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "Campos requeridos no llenados",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>';
                exit();
            }
            /*Comprobar integridad de datos*/
            if(mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)){
                echo '
                <script>
                    Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "El nombre de usuario no coincide con el formato solicitado",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>';
            }
            if(mainModel::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)){
                echo '
                <script>
                    Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "La clave no coincide con el formato solicitado",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>';
                exit();
            }
            $clave=mainModel::encryption($clave);
            $datos_login=[
                "Usuario"=>$usuario,
                "Clave"=>$clave
            ];
            $datos_cuenta=loginModelo::iniciar_sesion_modelo($datos_login);
            $datos_cuenta_privilegio=loginModelo::iniciar_sesion_modelo2($datos_login);/*ti*/


            if($datos_cuenta->rowCount()==1){

                $row=$datos_cuenta->fetch();
                $row2=$datos_cuenta_privilegio->fetch();/*ti*/

                //AQUI COMIENZA EL MANEJO DE SESIONES EN LA BD
                //Para manejar las sesiones a ver si no tiene ya sesion iniciada

                $datos_sesion_bd=[
                    "CodigoUsuario"=>$row['Codigo'],
                    "EstadoSesion"=>1//Estamos Activando la sesion
                ];

                $datos_sesiones=loginModelo::obtener_sesion_modelo($datos_sesion_bd);//Aqui ocupa el row del inicio de sesion

                if($datos_sesiones->rowCount()==1){
                    $registro_Sesion=$datos_sesiones->fetch();
                    $estadoSesion=$registro_Sesion['EstadoSesion'];//Estado del ultimo inicio de sesion del usuario(1- Activo / 2-Inactivo)
                }else{
                    $estadoSesion=0;//Primer inicio de Sesion
                }

                if($estadoSesion!=1){//Verificar la sesion del usuario en la bd   
                session_start(['name'=>'SPM']);

                $_SESSION['id_spm']=$row['Codigo'];
                $_SESSION['usuario_spm']=$row['NombreUsuario'];
                $_SESSION['clave_spm']=$row['Pass'];
                $_SESSION['imagen-usuario_spm']=$row['imgUsuario'];
                $_SESSION['estado_spm']=$row['Estado'];
                $_SESSION['codPersona_spm']=$row['CodPersonaU'];
                
                //Obtencion del ultimo cargo 23/03/2022
                $datos_cargo = [
                    "CodigoPersona"=>$row['CodPersonaU']
                ];
                $cargoUsuario = loginModelo::usuario_cargo_modelo($datos_cargo);

                if ($cargoUsuario->rowCount() > 0)
                {
                    $cargoUsuario = $cargoUsuario->fetch();
                    $nombreCargoUsuario = $cargoUsuario['name_cargo'];
                    $cargoUsuario = $cargoUsuario['cod_cargo'];//Codigo del ultimo cargo activo asignado.
                }
                else
                {
                    $cargoUsuario = -1;//este usuario no tiene ningun cargo activo
                }
                $_SESSION['cargo_spm'] = $cargoUsuario;
                $_SESSION['name-cargo_spm'] = $nombreCargoUsuario;
                ////TERMINA VARIABLE SESION CARGO

                $_SESSION['token_spm']=md5(uniqid(mt_rand(),true));

                if($_SESSION['estado_spm']==1){//Si el usuario tiene estado activo en tblUsuarios
                    $_SESSION['privilegio_spm']=$row2['CodPrivilegio'];

                               
                        loginModelo::agregar_sesion_modelo($datos_sesion_bd);

                        return header("Location: ".SERVERURL."home/");//Que pueda ingresar al sistema :D                               
                }else{
                echo '
                <script>
                    Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "Este Usuario está deshabilitado.",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>';
            }//Termina else de verificacion de estado de usuario

        }else{
            echo '
        <script>
            Swal.fire({
            title: "Ocurrió un error inesperado",
            text: "Este Usuario ya tiene una Sesión Iniciada.",
            type: "error",
            confirmButtonText: "Aceptar"});
        </script>';         
    }//Termina else de la verificacion de estado de sesion      
    
            }else{
                echo '
                <script>
                    Swal.fire({
                    title: "Ocurrió un error inesperado",
                    text: "El usuario o clave son incorrectos",
                    type: "error",
                    confirmButtonText: "Aceptar"});
                </script>';
            }

        }/*Fin controlador */
        /*Controlador para forzar cierre de sesión */
        public function forzar_cierre_sesion_controlador(){//Creo que esta esta en desuso
            session_unset();
            session_destroy();
            if(headers_sent()){
                return"
                <script>
                    window.location.href='".SERVERURL."login/';
                </script>";
            }else{
                return header("Location: ".SERVERURL."login/");
            }
        }/*Fin controlador */

        public function redireccionar_home_controlador(){
            if(headers_sent()){
                return"
                <script>
                    window.location.href='".SERVERURL."home/';
                </script>";
            }else{
                return header("Location: ".SERVERURL."home/");
            }
        }/*Fin controlador */
        
        /*Controlador para cierre de sesión */
        public function cerrar_sesion_controlador(){
            session_start(['name'=>'SPM']);
            $token=mainModel::decryption($_POST['token']);
            $usuario=mainModel::decryption($_POST['usuario']);

            if($token==$_SESSION['token_spm'] && $usuario==$_SESSION['usuario_spm']){

                //AQUI MANEJAMOS EL INGRESO DEL REGISTRO DEL CIERRE DE SESION EN LA BD
                $datos_sesion_bd2=[
                    "CodigoUsuario"=>$_SESSION['id_spm'],
                    "EstadoSesion"=>2//Estamos DESActivando la sesion y el Estado inactivo es 2
                ];
                loginModelo::agregar_sesion_modelo($datos_sesion_bd2);
                //Termina manejo de sesion con bd
                
                session_unset();
                session_destroy();
                $alerta=[
                    "Alerta"=>"redireccionar",
                    "URL"=>SERVERURL."login/"
                ];
            }else{
                $alerta=[
                  "Alerta"=>"simple",
                  "Titulo"=>"Ocurrió un error",
                  "Texto"=>"No se pudo cerrar sesión",
                  "Tipo"=>"error"
                ];

            }
            
            echo json_encode($alerta);
        }
        /*Fin controlador */

/*Esta funcion sirve para hacer el recorrido del navLateral*/
        public static function permisos_controlador(){
            
$datos_privilegio=loginModelo::privilegiosUsuario_modelo();
$matrizPrivilegios=$datos_privilegio->fetchAll();//PDO::FETCH_NUM
/*
$datos_privilegio2=loginModelo::obtener_privEdicion_modelo();
$matrizEdiciones=$datos_privilegio2->fetchAll(PDO::FETCH_NUM);
*/
/*
$conta= count($matrizPrivilegios);

//$listaVistas= array();

for ($i=0; $i < $conta; $i++) { 
    
    for ($j=0; $j < 2; $j++) { 



        if($j==0){
                    $matriz[$i][$j]=$matrizPrivilegios[$i][$j];
                }//cierra if j 0

        if($j==1){
                    $matriz[$i][$j]=$matrizEdiciones[$i][0];
                }//cierra if j 1        
    }
}//cierra for grande
      */  
    return $matrizPrivilegios;
}/*Fin controlador */

public static function navLateral_controlador(){
            
$datos_privilegio2=loginModelo::obtener_privilegios_modelo();   

$matrizDatos=$datos_privilegio2->fetchAll(PDO::FETCH_NUM);//fetchAll(PDO::FETCH_NUM);

        $listaVistas= array();  

        foreach($matrizDatos as $privilegio)//este foreach doble recorre la matriz de datos que contiene el id de la columna CodPrivilegio de la tblPrivilegiosUsuarios del usuario que este logueado.
        {                              
            foreach($privilegio as $codigoPriv)
                {

                    array_push($listaVistas, $codigoPriv);

                /* ($codigoPriv) {
                        case 1://ADMINISTRADOR
                            array_push($listaVistas, "Usuarios", "Catalogos", "Empleado", "Admision", "Cita", 
                                "Consulta", "Examen", "Caja");
                            break;

                        case 2://RECEPCIONISTA
                            array_push($listaVistas, "Admision", "Cita", "Consulta", "Examen", "Caja");
                            break;  

                        case 3://CONSULTA
                            array_push($listaVistas, "Admision", "Cita", "Consulta", "Examen");
                            break;    
                            

                        case 4://EXAMEN
                            array_push($listaVistas, "Admision", "Consulta", "Examen", "Caja");
                            break;    
                            

                        case 5:/CAJA
                            array_push($listaVistas, "Admision", "Cita", "Consulta", "Examen", "Caja");
                            break;                 
                                }*/
                    }

        }
                return $listaVistas;
}/*Fin controlador */

}//fin clase


//Como lanzar una alerta en js con variable php
//echo '<script language="javascript">alert(" Numero de lineas es '.$datos_sesiones->rowCount().'");</script>';