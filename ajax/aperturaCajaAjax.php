<?php
    $peticionAjax = true;
    require_once "../config/APP.php";
    if(isset($_POST['codCajaApertura']) || isset($_POST['cerrarCajaReg'])){
        /* Insttancia al controlador */
        require_once "../controladores/aperturaCajaControlador.php";
        $ins_Apcaja = new AperturaCajaControlador();
        /* agregar un usuario */
        if(isset($_POST['codCajaApertura'])){
            echo $ins_Apcaja->agregar_apertura_controlador();

        }
        else if(isset($_POST['cerrarCajaReg']))
        {
            echo $ins_Apcaja->cerrarCaja_controlador();
        }


    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    