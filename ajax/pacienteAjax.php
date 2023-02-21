<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['paciente_nombre_reg'])){
        /* Insttancia al controlador */
        require_once "../controladores/pacienteControlador.php";
        $ins_paciente = new pacienteControlador();
        /* agregar un paciente */
        if(isset($_POST['paciente_nombre_reg']) && isset($_POST['paciente_inss_reg']) && isset($_POST['activador'])){//Caso mayor de edad y ya registrado
            echo $ins_paciente->agregar_paciente_controlador();
        }
        if(isset($_POST['paciente_nombre_reg']) && isset($_POST['paciente_inss_reg']) && !isset($_POST['activador'])){/* CASO DE MAYOR DE EDAD */
            echo $ins_paciente->agregar_persona_controlador(0);

        }
        if(isset($_POST['paciente_nombre_reg']) && isset($_POST['Responsable_ID_reg'])){/* CASO DE CHATEL */
            echo $ins_paciente->agregar_persona_controlador(1);

        }

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    