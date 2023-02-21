<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['item_civil_reg']) || isset($_POST['parentesco_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/familiarControlador.php";
        $ins_fam = new familiarControlador();
        
        if(isset($_POST['nombre_familiar_reg']) && isset($_POST['cedula_familiar_reg'])){//Agregar familiar de paciente
            echo $ins_fam->agregar_familiar_paciente();
        }

        if(isset($_POST['nombre_familiar_empleado_reg']) && isset($_POST['cedula_familiar_empleado_reg'])){//Agregar familiar de empleado
            echo $ins_fam->agregar_familiar_empleado_controlador();
        }
        
        /* actualizar un familiar 
        if(isset($_POST['nombre_familiar_up'])){
            echo $ins_fam->actualizar_familiar_controlador();

        }

        */



    }else if(isset($_POST['fam_id'])){ 
        require_once "../controladores/familiarControlador.php";
        $ins_fam = new familiarControlador();
        /* actualizar un familiar de empleado */
        if(isset($_POST['nombre_familiar_up']) && isset($_POST['fam_id'] )){
            echo $ins_fam->actualizar_familiar_empleado_controlador($_POST['fam_id']);

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    