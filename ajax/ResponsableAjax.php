<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['nombre_familiar_reg']) || isset($_POST['nombre_familiar_up'])){
        /* Instancia al controlador */
        require_once "../controladores/responsableControlador.php";
        $ins_fam = new responsableControlador();
        /* agregar un responsable de futuro chatel*/
        if(isset($_POST['nombre_familiar_reg']) && isset($_POST['cedula_reg']) ){/* En esta parte se manda a llamar controlador de responsable */
            echo $ins_fam->agregar_responsable_controlador();

        }


    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    