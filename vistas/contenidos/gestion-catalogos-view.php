<?php 
        $auxiliar = new loginControlador();
        $listaVistas = $auxiliar -> navLateral_controlador();

    if( !(in_array("Catalogos", $listaVistas)) ){
        echo $lc->forzar_cierre_sesion_controlador();
        exit();
    }

?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION CATALOGOS
				</h3>
				<p class="text-justify">
					<!-- COMENTARIO -->
				</p>
			</div>
			
			<!-- Content -->
            <div class="full-box tile-container">
                <a href="<?php echo SERVERURL; ?>catalogo-cargos/" class="tile">
                    <div class="tile-tittle">CARGOS</div>
                    <div class="tile-icon">
                        <i class="fas fa-clipboard-list fa-fw"></i>

                    </div>
                </a>

				<a href="<?php echo SERVERURL; ?>catalogo-proveedores/" class="tile">
                    <div class="tile-tittle">PROVEEDORES</div>
                    <div class="tile-icon">
                        <i class="fas fa-clipboard-list fa-fw"></i>
                    </div>
                </a>

                <a href="<?php echo SERVERURL; ?>catalogo-consultorio/" class="tile">
                    <div class="tile-tittle">CONSULTORIO</div>
                    <div class="tile-icon">
                        <i class="fas fa-clipboard-list fa-fw"></i>

                    </div>
                </a>

				<a href="<?php echo SERVERURL; ?>catalogo-enfermedades/" class="tile">
					<div class="tile-tittle">ENFERMEDADES</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>

				

				<a href="<?php echo SERVERURL; ?>catalogo-especialidades/" class="tile">
					<div class="tile-tittle">ESPECIALIDADES</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>

				<a href="<?php echo SERVERURL; ?>catalogo-estado-cita/" class="tile">
					<div class="tile-tittle">ESTADO CITA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-estado-consulta/" class="tile">
					<div class="tile-tittle">ESTADO CONSULTA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-estado/" class="tile">
					<div class="tile-tittle">ESTADO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-examen-medico/" class="tile">
					<div class="tile-tittle">EXAMEN MEDICO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-grupo-sanguineo/" class="tile">
					<div class="tile-tittle">GRUPO SANGUINEO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-maquinaria/" class="tile">
					<div class="tile-tittle">MAQUINARIA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-medicamentos/" class="tile">
					<div class="tile-tittle">MEDICAMENTOS</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-metodos-de-pago/" class="tile">
					<div class="tile-tittle">METODOS DE PAGO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-nivel-academico/" class="tile">
					<div class="tile-tittle">NIVEL ACADEMICO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-parentesco/" class="tile">
					<div class="tile-tittle">PARENTESCO</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
                <a href="<?php echo SERVERURL; ?>catalogo-sala-examen/" class="tile">
					<div class="tile-tittle">SALA EXAMEN</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
				<a href="<?php echo SERVERURL; ?>catalogo-monedas/" class="tile">
					<div class="tile-tittle">MONEDA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
				<a href="<?php echo SERVERURL; ?>catalogo-caja/" class="tile">
					<div class="tile-tittle">CAJA</div>
					<div class="tile-icon">
						<i class="fas fa-clipboard-list fa-fw"></i>

					</div>
				</a>
			</div>