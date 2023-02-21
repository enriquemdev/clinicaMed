<?php

include_once "phpscript.php";

$modelo = new BusquedasModelo();

session_start(['name'=>'SPM']);
$datos_usuario_reg = [
    "CodigoUsuario"=>$_SESSION['id_spm'],
    "EstadoSesion"=>2
];

$datos_sesiones = $modelo->obtener_sesion_modelo($datos_usuario_reg);



if($datos_sesiones->rowCount()==1){
  $registro_Sesion=$datos_sesiones->fetch();
    $estadoSesion=$registro_Sesion['EstadoSesion'];//Estado del ultimo inicio de sesion del usuario(1- Activo / 2-Inactivo)
 }else{
  $estadoSesion=0;//Primer inicio de Sesion
    }

if($estadoSesion!=2){
    /*
    $modelo->agregar_sesion_modelo($datos_usuario_reg);

    session_unset();
    session_destroy();
    
    $alerta=[
        "Alerta"=>"redireccionar",
        "URL"=>SERVERURL."login/"
    ];*/
}


?>