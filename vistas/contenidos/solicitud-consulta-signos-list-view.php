<?php
    //Privilegios van aquí 
?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; CONSULTAS EN ESPERA
				</h3>
                <p class="text-justify">
					Lista de todos las Consultas en espera
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<?php if ($_SESSION['cargo_spm'] == 2){ ?>
            <li>
						<a href="<?php echo SERVERURL; ?>solicitud-consulta-signos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CONSULTAS CON SIGNOS POR TOMAR</a>
					</li>
						<li>
							<a class="active" href="<?php echo SERVERURL; ?>solicitud-consultadr-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; CONSULTAS EN ESPERA</a>
						</li>
            
						
					<?php } ?>
					
                    <li>
						<a href="<?php echo SERVERURL; ?>consulta-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CONSULTAS TERMINADAS</a>
					</li>

					
                    <li>
						<a href="<?php echo SERVERURL; ?>consulta-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CONSULTAS CONCLUIDAS</a>
					</li>

				</ul>	
			</div>
            <!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/consultaControlador.php";
					$ins_consulta = new consultaControlador();
					echo $ins_consulta->paginador_consulta_signos_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>