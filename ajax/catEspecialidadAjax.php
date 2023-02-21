<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['ESPECIALIDAD-NOMBRE_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/catalogosControlador.php";
        $ins_catalogo = new catalogosControlador();
        /* agregar un usuario */
        if(isset($_POST['ESPECIALIDAD-NOMBRE_reg']) && isset($_POST['ESPECIALIDAD-DESCRIPCION_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_catalogo->agregar_especialidad_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    