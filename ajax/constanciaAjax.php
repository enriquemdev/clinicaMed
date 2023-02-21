<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codigo_diagnostico_reg']) ){
        /* Insttancia al controlador */
        require_once "../controladores/constanciaControlador.php";
        $ins_constancia = new constanciaControlador();
        /* agregar una consulta */
        if(isset($_POST['codigo_diagnostico_reg']) && isset($_POST['comienzo_cons_reg']) ) {
            echo $ins_constancia->agregar_constancia_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    