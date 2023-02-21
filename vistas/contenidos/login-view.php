<?php

	if(isset($_POST['usuario_log']) && isset($_POST['clave_log'])){
		require_once "./controladores/loginControlador.php";
		$ins_login=new loginControlador();

		echo $ins_login->iniciar_sesion_controlador();
	}


?>

<?php
           
            session_start(['name'=>'SPM']);

            if(isset($_SESSION['id_spm']))
            {
                if(headers_sent()){
                    return"
                    <script>
                        window.location.href='".SERVERURL."home/';
                    </script>";
                }else{
                    return header("Location: ".SERVERURL."home/");
                }
            } else
                {
                ?>

<div class="login-container">
        <div class="login-content">
            <p class="text-center">
                <i class="fas fa-user-circle fa-5x"></i>
            </p>
                    <legend class="text-center">
                INICIA SESION CON TU CUENTA
            </legend><b</b>
            <form action="" method="POST" autocomplete="off" >
                <div class="form-group">
                    <label for="UserName" class="bmd-label-floating"><i class="fas fa-user-secret"></i> &nbsp; USUARIO</label>
                    <input type="text" class="form-control" id="UserName" name="usuario_log" pattern="[a-zA-Z0-9]{1,35}" maxlength="35" required="" >
                </div>
                <div class="form-group">
                    <label for="UserPassword" class="bmd-label-floating"><i class="fas fa-key"></i> &nbsp; CONTRASEÑA</label>
                    <input type="password" class="form-control" id="UserPassword" name="clave_log" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="" >
                </div>
                <button type="submit" class="btn-login text-center">INGRESAR</button>
                
            </form>
           
        </div>
    </div>
    
<?php
}
?>


