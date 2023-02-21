<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['consulta_paciente_reg']) || isset($_POST['consulta_codigo_up'])){
        /* Insttancia al controlador */
        require_once "../controladores/solicitudConsultaControlador.php";
        $ins_consulta = new solicitudConsultaControlador();//instancia a solicitudConsultaControlador
        /* agregar una Consulta */
        if(isset($_POST['registro']) && isset($_POST['consulta_paciente_reg']) ) {
            echo $ins_consulta->agregar_solconsulta_controlador();

            
        }
        /*ACTUALIZAR SOLICITUD */
        else if(isset($_POST['consulta_codigo_up']) ) {
            echo $ins_consulta->actualizar_consulta_controlador();

        }
        
        
        

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    