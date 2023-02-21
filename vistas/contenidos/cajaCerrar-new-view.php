<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==28 && $key['CodPrivilegio']==1){
        $agregar=true;
      }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; APERTURA DE CAJA
				</h3>
				<p class="text-justify">
				APERTURA DE CAJA CLINICA MEDICA
				</p>
			</div>
            <div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">

					<li>
						<a class="active" href="<?php echo SERVERURL; ?>aperturaCaja-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR APERTURA DE CAJA</a>
					</li>
                    
					<?php   
          			if($ver==true){ ?> 
					<li>
						<a href="<?php echo SERVERURL; ?>diagnostico-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE DIAGNOSTICO</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>diagnostico-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR DIAGNOSTICO</a>
					</li>
					<?php } ?>
				</ul>	
			</div>
		
		
			<!-- Content -->
			<?php 
				require_once "./controladores/aperturaCajaControlador.php";
				$ins_item = new AperturaCajaControlador();
				$datos_item=$ins_item->datos_item1_controlador();
				// if($datos_item->rowCount()==1){
				// 	$campos=$datos_item->fetch();
				// }
					?>
			<div class="container-fluid">
			<form class="form-neon FormularioAjax" action="<?php echo SERVERURL;?>ajax/aperturaCajaAjax.php" method="POST" data-form= "save" autocomplete="off">
					<fieldset>
						<legend><i class="fas fa-user"></i> &nbsp; Información De Apertura de Caja</legend>
						<div class="container-fluid">
							<div class="row">
                                

                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="codCajaApertura" class="bmd-label-floating">SELECCIONE UNA CAJA DISPONIBLE <span class="Obligar">*</span></label>
										<select class="form-control" name="codCajaApertura" id="codCajaApertura" required="">
											<option value="" selected="" disabled=""></option>
											<?php foreach($datos_item as $campo){ ?>
											<option value="<?php echo$campo['idCaja'] ?>"><?php echo$campo['nombreCaja'] ?></option>
											<?php }?>
										</select>
										</div>
								</div>
                                
                                <div class="col-12 col-md-6">
									<div class="form-group">
										<label for="montoInicial" class="bmd-label-floating">MONTO INICIAL DE CAJA</label>
										<input class="form-control inputDecimal" type="number" value="" id="montoInicial" name="montoInicial" maxlength="10" min="0" step="0.01" required>
									</div>
								</div>
                                
                                
                                
							</div>
						</div>
					</fieldset>
					<br><br><br>
					<p class="text-center" style="margin-top: 40px;">
						<button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; DESHACER</button>
						&nbsp; &nbsp;
						<button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; REGISTRAR</button>
					</p>
				</form>
			</div>