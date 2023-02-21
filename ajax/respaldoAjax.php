<?php
    $peticionAjax = true;
    if(isset($_POST['backup'])){
        require_once "../Respaldos/RESPALDO.php";

    }else{
        session_start(['name'=>'SPM']);
        session_unset();
        session_destroy();
        header("Location:".SERVERURL."login/");
        exit();
    }
    