<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['cod_empleado_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/estudiosacademicosControlador.php";
        $ins_estudio = new estudiosacademicosControlador();
        /* agregar un empleado */
        if(isset($_POST['cod_empleado_reg']) && isset($_POST['completo_estudio_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_estudio->agregar_estudio_academico_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    