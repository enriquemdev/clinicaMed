<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?php echo COMPANY;  ?></title>
    <?php 
        include "./vistas/inc/link.php"; /* SE INCLUYE REFERENCIA A LINKS */
        // include "./vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
        
    ?>
    <!-- jQuery V3.4.1 -->
	<script src="<?php echo SERVERURL; ?>vistas/js/jquery-3.4.1.min.js" ></script>
</head>
<script> //Script para cerrar sesion cuando se cierra la ventana/pestaña
            function alCierre(){
            	var serverTi = document.getElementById("serverTi").innerText;//Este elemento esta antes de la llamada al scrpt en el html
                fetch(serverTi+"buscadores/phpControlador.php");
            }
		</script>
<body onunload="alCierre()">
    <?php 
        $peticionAjax = false;
        require_once "./controladores/vistasControlador.php";
        $IV = new vistasControlador();

        $vistas = $IV->obtener_vistas_controlador();

        if($vistas == "login"  || $vistas == "404"){
        require_once "./vistas/contenidos/".$vistas."-view.php";
        
        }else{
                    session_start(['name'=>'SPM']);

                    $pagina=explode("/",$_GET['views']);
                    require_once "./controladores/loginControlador.php";
                    $lc = new loginControlador();
                    if(!isset($_SESSION['token_spm']) || !isset($_SESSION['usuario_spm']) || 
                    !isset($_SESSION['privilegio_spm']) || !isset($_SESSION['id_spm'])){
                        echo $lc->forzar_cierre_sesion_controlador();
                        exit();
                    }

            ?>
            <!-- Main container -->
            <main class="full-box main-container">
                <!-- Nav lateral -->
                <?php 
                    include "./vistas/inc/NavLateral.php"; /* SE INCLUYE REFERENCIA A BARRA NAVEGACIÓN LATERAL */
                ?>

                <!-- Page content -->
                <section class="full-box page-content">
                <?php 
                    include "./vistas/inc/NavBar.php"; /* SE INCLUYE REFERENCIA A BARRA NAVEGACIÓN */
                    include $vistas; /* SE INCLUYE REFERENCIA A VISTAS */
                ?>
            
                </section>
            </main>
            <?php 
                    include "./vistas/inc/LogOut.php"; /* SE INCLUYE REFERENCIA A BOTON SALIR DE SISTEMA */
        }
        include "./vistas/inc/script.php"; /* SE INCLUYE REFERENCIA A SCRIPTS */
        ?>

<script>
				$(function(){
					//Clona la fila oculta que tiene los campos base y la agrega al final de la tabla
					$("#adicional").on('click', function(){
						$("#tabla tbody tr:eq(0)").clone().removeClass('fila-fija').appendTo("#tabla");
					});

					//Evento que selecciona la fila y la elimina
					$(document).on("click", ".eliminar", function(){
						var parent = $(this).parents().get(0);
						$(parent).remove();
					});
				});
			
			</script>
	
    <div style="display:none;" id="serverTi"><?php echo SERVERURL;?></div> <!--Esta etiqueta tiene en su cocntenido la constante de servidor de config/app-->	
</body>
</html>