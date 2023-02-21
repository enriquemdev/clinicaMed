<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['usuario_usuario_reg']) || isset($_POST['usuario_codigo_up'])){
        /* Insttancia al controlador */
        require_once "../controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();
        /* agregar un usuario */
        if(isset($_POST['usuario_empleado_reg']) && isset($_POST['usuario_usuario_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_usuario->agregar_usuario_controlador();

        }
        /* actualizar un usuario */
        if(isset($_POST['usuario_codigo_up'])){
            echo $ins_usuario->actualizar_usuario_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    