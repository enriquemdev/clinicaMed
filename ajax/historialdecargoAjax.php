<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['cargo_reg']) || isset($_POST['id']) || isset($_POST['idRechazar'])){
        /* Insttancia al controlador */
        require_once "../controladores/historialdecargoControlador.php";
        $ins_cargo = new historialdecargoControlador();
        /* Solicitud de cambio de cargo */
        if(isset($_POST['cargo_reg']) && isset($_POST['solicitud'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_cargo->agregar_solicitudcargo_controlador();

        }
        /*Asignación de cargo */
        else if(isset($_POST['cargo_empleado_reg']) && isset($_POST['salario_cargo_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_cargo->agregar_cargo_controlador();

        }
        /* actualizar estado cargo */
        else if (isset($_POST['id']))
        {
            echo $ins_cargo->cambiarestado();
        }
        else if (isset($_POST['idRechazar']))
        {
            echo $ins_cargo->cambiarEstadoRechazado();
        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    