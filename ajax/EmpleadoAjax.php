<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['empleado_cedula_reg']) || isset($_POST['empleado_codigo_up'])){
        /* Insttancia al controlador */
        require_once "../controladores/empleadosControlador.php";
        $ins_empleado = new empleadosControlador();
        /* agregar un empleado */
        if(isset($_POST['empleado_cedula_reg']) && isset($_POST['empleado_nombre_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_empleado->agregar_persona_controlador();

        }
        /* actualizar un empleado */
        if(isset($_POST['empleado_codigo_up'])){
            echo $ins_empleado->actualizar_empleado_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    