<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codigo_medicamento_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/recetaControlador.php";
        $ins_receta = new recetaControlador();
        /* agregar un usuario */
        if(isset($_POST['codigo_medicamento_reg']) && isset($_POST['dosis_medicamento_reg'])){
            echo $ins_receta->agregar_receta_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    