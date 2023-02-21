<?php
//SEGURIDAD AÑADIDA EL 03/04/2022
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;

    $realizarCobro[0]=false;


    foreach ($permisos as $key) {
      if(($key['CodigoSubModulo']==28)  && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if(($key['CodigoSubModulo']==28)  && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if(($key['CodigoSubModulo']==28)  && $key['CodPrivilegio']==3){
        $actualizar=true;
      }


if($key['CodigoSubModulo']==28){//Si tiene el modulo 
	$realizarCobro[0]=true;//Se mirara el boton

	if($key['CodPrivilegio']==1){//Si es el privilegio agregar
		$realizarCobro[1]=1;//Ira a la agregar
	}else if($key['CodPrivilegio']==2){
		$realizarCobro[1]=2;
	}
	}

    }

    if($ver==false && $agregar==false && $actualizar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

	require_once "./controladores/aperturaCajaControlador.php";
	$ins_item = new AperturaCajaControlador();

	//session_start(['name'=>'SPM']);
	

	$datosCajaEmpleado=$ins_item->verificar_apertura_controlador();

?>
<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fab fa-dashcube fa-fw"></i> &nbsp; GESTION CAJA
				</h3>
			</div>
		
			<!-- Content -->
			<div class="full-box tile-container">

				<?php
				if ($realizarCobro[0]==true )
				{			
				?>
					

					<?php
					if ($datosCajaEmpleado['EstadoCaja']==2)
					{			
					?>
						<a href="<?php echo SERVERURL; ?>aperturaCaja-new/" class="tile">
							<div class="tile-tittle">Aperturar Caja</div>
							<div class="tile-icon">
							<i class="fas fa-box-open"></i>
								
							</div>
							
						</a>
					<?php		
					}
					else
					{
					?>
						
						

						<div data-bs-toggle="modal" data-bs-target="#myModal" class="tile" onclick="loadData(this.getAttribute('data-id'));" data-id="<?= $datosCajaEmpleado['idApertura'] ?>">
							<div class="tile-tittle">Cerrar <?= $datosCajaEmpleado['nombreCaja'] ?></div>
							<div class="tile-icon">
							<i class="far fa-window-close"></i>
								
							</div>
							
						</div>

						<a href="<?php echo SERVERURL; ?>cajaPaciente-search/" class="tile">
							<div class="tile-tittle">Realizar Cobro</div>
							<div class="tile-icon">
								<i class="fas fa-money-check-alt"></i>
							</div>
						</a>

					<?php		
					}
					?>

						<a href="<?php echo SERVERURL; ?>cajaCliente-new/" class="tile">
							<div class="tile-tittle">Agregar Cliente</div>
							<div class="tile-icon">
							<i class="fas fa-user-plus"></i>
							</div>
						</a>

						<a href="<?php echo SERVERURL; ?>recibosCaja-list/" class="tile">
							<div class="tile-tittle">Recibos Caja</div>
							<div class="tile-icon">
							<i class="fas fa-file-invoice"></i>
							</div>
						</a>
					<!-- <button data-bs-toggle="modal" data-bs-target="#myModal" onclick="loadData(this.getAttribute(`data-id`));" data-id="<?= $datosCajaEmpleado['idApertura'] ?>"><?= $datosCajaEmpleado['EstadoCaja'] ?></button> -->
				<?php		
				}
				?>
			</div>


<!-- Modal -->
<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-hidden = "true">
    
	<div class = "modal-dialog">
	   <div class = "modal-content">
		   
		  <div class = "modal-header">
			 <h4 class = "modal-title">
				Cerrar Caja
			 </h4>
  
			 <button type = "button" class = "close" data-bs-dismiss = "modal" aria-hidden = "true">
				×
			 </button>
		  </div>
		   
		  <div id = "modal-body">
			 Press ESC button to exit.
		  </div>
		   
		  <div class = "modal-footer">
			 <!-- <button type = "button" class = "btn btn-default" data-bs-dismiss = "modal">
				OK
			 </button> -->

			
		  </div>
		   
	   </div>
	</div>
	 
 </div>
<!-- TERMINA MODAL -->

 <script>
    function loadData(id) {
        $.ajax({
            url: "../vistas/modals/cerrarCajaModal.php",
            method: "POST",
            data: {get_data: 1, id: id},
            success: function (response) {
				response = JSON.parse(response);
				//console.log(response);
				var suma = 0;
				var html = "";
			
				// Displaying city
				html += "<div class='row m-3'>";
					html += "<div class='col-md-6 d-flex align-items-center'>"+response[0]['nombreCaja']+"</div>";
					html += "<div class='col-md-6 row'>";
					for(var elem of response)
					{
						suma +=  parseInt(elem['Monto']);
						
					}
					html += "<div class='col-md-12'> El Monto total vendido es: " + suma + "</div>";
					html += "</div>";
					//html += "<div class='col-md-6'>" + response.Nota + "</div>";
				html += "</div>";


				html += `<form class="form-neon FormularioAjax formSinEstilo" action="<?php echo SERVERURL;?>ajax/aperturaCajaAjax.php" method="POST" data-form= "save" autocomplete="off">
				
				<input type="hidden" name="cerrarCajaReg" value="`+response[0]['idCaja']+`">
				<div class="d-flex justify-content-center">
				<button type="submit" class="btn btn-raised btn-danger btn-md ><i class="far fa-save"></i> &nbsp; CERRAR CAJA</button>
				</div>

				</form>`;
				// And now assign this HTML layout in pop-up body
				$("#modal-body").html(html);
			
				// And finally you can this function to show the pop-up/dialog
				$("#myModal").modal();
				
			}
        });
    }
</script>