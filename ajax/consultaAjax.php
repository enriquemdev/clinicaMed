<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(/*isset($_POST['consulta_medico_reg']) && */isset($_POST['consulta_paciente_reg'] )  
        || isset($_POST['id']) || isset($_POST['idRechazar']) || (isset($_POST['consulta_codigo'])) ){//el primero es para cuando se acepta la consulta
        /* Insttancia al controlador */
        require_once "../controladores/consultaControlador.php";
        $ins_consulta = new consultaControlador();
        /* agregar una constancia */
        if(/*isset($_POST['consulta_medico_reg']) &&*/ isset($_POST['consulta_paciente_reg']) ) {
            echo $ins_consulta->agregar_consulta_controlador();

        }
        if (isset($_POST['consulta_codigo']) && !isset($_POST['notas_anulada_reg']) )
        {
            echo $ins_consulta->agregarnotasconsulta();
        }
        if (isset($_POST['consulta_codigo']) && isset($_POST['notas_anulada_reg']) )
        {
            echo $ins_consulta->agregaranulacionconsulta();
        }
        /* actualizar estado consulta */
        if (isset($_POST['id']))
        {
            echo $ins_consulta->cambiarestado();
        }
        
        if (isset($_POST['idRechazar']))
        {
            echo $ins_consulta->cambiarEstadoRechazado();
        }
    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    