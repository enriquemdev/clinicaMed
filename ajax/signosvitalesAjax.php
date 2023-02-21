<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['peso_signos_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/signosvitalesControlador.php";
        $ins_signos = new signosvitalesControlador();
        /* agregar un usuario */
        if(isset($_POST['codigo_consulta_reg2']) && isset($_POST['altura_signos_reg'])){
            echo $ins_signos->agregar_signosvitales_controlador_auto();

        }
        if(isset($_POST['peso_signos_reg']) && !isset($_POST['codigo_consulta_reg2'])){
            echo $ins_signos->agregar_signosvitales_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    