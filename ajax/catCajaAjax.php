<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['nombre_caja_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/catalogosControlador.php";
        $ins_catalogo = new catalogosControlador();

        if(isset($_POST['nombre_caja_reg'])){
            echo $ins_catalogo->agregar_caja_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }