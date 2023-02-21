<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['EXAMEN_MEDICO-NOMBRE_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/catalogosControlador.php";
        $ins_catalogo = new catalogosControlador();
        /* agregar un estado */
        echo $ins_catalogo->agregar_examen_medico_controlador();

        

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    