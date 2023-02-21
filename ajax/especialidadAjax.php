<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codigo_doc_especialidad_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/especialidadControlador.php";
        $ins_especialidad = new especialidadControlador();
        /* agregar un empleado */
        if(isset($_POST['codigo_doc_especialidad_reg']) && isset($_POST['especialidad_reg'])){/* CAMBIAR NOMBRE DE CAMPOS SEGUN VISTA DE NUEVO USUARIO */
            echo $ins_especialidad->asignar_especialidad_controlador();

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    