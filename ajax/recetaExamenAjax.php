<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codigo_consulta_reg'])){
        /* Instancia al controlador */
        require_once "../controladores/recetaExamenControlador.php";
        $ins_receta = new recetaExamenControlador();
        /* agregar una receta */
        if(isset($_POST['codigo_consulta_reg']) && isset($_POST['tipo_examen_reg'])){
            echo $ins_receta->agregar_receta_examen_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    