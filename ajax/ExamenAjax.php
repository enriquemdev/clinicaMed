<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['receta_examen_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/examenControlador.php";
        $ins_examen = new examenControlador();
        /* agregar un empleado */
        if(isset($_POST['receta_examen_reg']) && isset($_POST['especialista_examen_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_examen->agregar_examen_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    