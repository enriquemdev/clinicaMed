<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['metodoPago'])){
        /* Insttancia al controlador */
        require_once "../controladores/cajaControlador.php";
        $ins_caja = new cajaControlador();
        /* agregar un usuario */
        if(isset($_POST['metodoPago'])){
            echo $ins_caja->agregar_cobro_controlador();

        }


    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    