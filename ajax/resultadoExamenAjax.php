<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codigo_examen_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/resultadoExamenControlador.php";
        $ins_resultadoexamen = new resultadoExamenControlador();
        /* agregar un empleado */
        if(isset($_POST['codigo_examen_reg']) && isset($_POST['examen_date_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_resultadoexamen->agregar_resultado_examen_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    