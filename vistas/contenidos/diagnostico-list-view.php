<?php
    $auxiliar2 = new loginControlador();
    $permisos= $auxiliar2 -> permisos_controlador();

    $agregar=false;
    $ver=false;
    $actualizar=false;


    foreach ($permisos as $key) {
      if($key['CodigoSubModulo']==14 && $key['CodPrivilegio']==1){
        $agregar=true;
      }

      if($key['CodigoSubModulo']==14 && $key['CodPrivilegio']==2){
        $ver=true;
      }

      if($key['CodigoSubModulo']==14 && $key['CodPrivilegio']==3){
        $actualizar=true;
      }
    }


    if($ver==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

?>

<!-- Page header -->
<div class="full-box page-header">
				<h3 class="text-left">
					<i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE DIAGNOSTICO
				</h3>
				<p class="text-justify">
					Lista de todos los diagnosticos
					
				</p>
			</div>

			<div class="container-fluid">
				<ul class="full-box list-unstyled page-nav-tabs">
					<?php   
          			if($agregar==true){ ?> 
					<li>
						<a  href="<?php echo SERVERURL; ?>diagnostico/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR DIAGNOSTICO</a>
					</li>
					<?php } ?>
					
					<li>
						<a class="active" href="<?php echo SERVERURL; ?>diagnostico-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE DIAGNOSTICO</a>
					</li>
                    <li>
						<a href="<?php echo SERVERURL; ?>diagnostico-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR DIAGNOSTICO</a>
					</li>
				</ul>	
			</div>
			
			<!-- Content here-->
			<div class="container-fluid">
			<?php 
					require_once "./controladores/diagnosticoControlador.php";
					$ins_diagnostico = new diagnosticoControlador();
					echo $ins_diagnostico->paginador_diagnostico_controlador($pagina[1],15,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"");

				?>
			</div>

			<!-- Modal -->
<div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-hidden = "true">
    
	<div class = "modal-dialog">
	   <div class = "modal-content">
		   
		  <div class = "modal-header">
			 <h4 class = "modal-title">
				Detalles del diagnóstico
			 </h4>
  
			 <button type = "button" class = "close" data-bs-dismiss = "modal" aria-hidden = "true">
				×
			 </button>
		  </div>
		   
		  <div id = "modal-body">
			 Press ESC button to exit.
		  </div>
		   
		  <div class = "modal-footer">
			 <button type = "button" class = "btn btn-default" data-bs-dismiss = "modal">
				OK
			 </button>
		  </div>
		   
	   </div>
	</div>
	 
 </div>
<!-- TERMINA MODAL -->

 <script>
    function loadData(id) {
        $.ajax({
            url: "../vistas/modals/diagnosticoModal.php",
            method: "POST",
            data: {get_data: 1, id: id},
            success: function (response) {
				response = JSON.parse(response);
				console.log(response);
			
				var html = "";
			
				// Displaying city
				html += "<div class='row m-3'>";
					html += "<div class='col-md-6 d-flex align-items-center'>SINTOMAS</div>";
					html += "<div class='col-md-6 row'>";
					for(var elem of response)
					{
						html += "<div class='col-md-12'>" + elem['nombreSintoma'] + "</div>";
					}
					html += "</div>";
					//html += "<div class='col-md-6'>" + response.Nota + "</div>";
				html += "</div>";
			
				// And now assign this HTML layout in pop-up body
				$("#modal-body").html(html);
			
				// And finally you can this function to show the pop-up/dialog
				$("#myModal").modal();
			}
        });
    }
</script>