<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['cita_paciente_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/citaControlador.php";
        $ins_cita = new citaControlador();
        /* agregar un usuario */
        if(isset($_POST['cita_paciente_reg'])) {
            echo $ins_cita->agregar_cita_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    