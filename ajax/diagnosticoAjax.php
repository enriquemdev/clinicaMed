<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['cod_enfermedad_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/diagnosticoControlador.php";
        $ins_Controlador = new diagnosticoControlador();
        /* agregar un empleado */
        if(/*isset($_POST['sintoma_reg']) && */isset($_POST['cod_enfermedad_reg']) && !isset($_POST['codigo_consulta_reg2'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA*/
            echo $ins_Controlador->agregar_diagnostico_controlador();

        }else if(isset($_POST['codigo_consulta_reg2'])){
            echo $ins_Controlador->agregar_diagnostico_auto_controlador();
        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    