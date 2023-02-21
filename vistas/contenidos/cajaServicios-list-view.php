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

    //   if($key['CodigoSubModulo']==28 && $key['CodPrivilegio']==2){
    //     $ver=true;
    //   }

    //   if($key['CodigoSubModulo']==8 && $key['CodPrivilegio']==3){
    //     $actualizar=true;
    //   }
    }


    if($agregar==false){
        echo $lc->redireccionar_home_controlador();
        exit();
    }

    $codPaciente = $pagina[2];

    require_once "./controladores/cajaControlador.php";
	$ins_caja = new cajaControlador();

    $datosPaciente = $ins_caja->datosPacienteCaja($codPaciente);

?>

<style>
    #NoPagaElMismo {
        display: none;
    }
</style>

<!-- Page header -->
<div class="full-box page-header pb-4">
                <h3 class="text-left">
                    <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE SERVICIOS DEL PACIENTE: <?= $datosPaciente['Nombres']." ".$datosPaciente['Apellidos'] ?>
                </h3>
                <!-- <p class="text-justify">
                    Se muestra la lista de pacientes con servicios médicos pendientes por pagar
                </p> -->
            </div>



             <div class="container-fluid">
			<?php 
            
					
					echo $ins_caja->paginador_serviciosPaciente_controlador($pagina[1],100,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"","","",$codPaciente, $datosPaciente);

				?>
			</div>

            <script src="<?php echo SERVERURL; ?>buscadores/cliente_creaPagoCaja.js"></script>
            <script src="<?php echo SERVERURL; ?>vistas/js/funcionesCaja.js"></script>
            <script>
                var cbPagaPaciente = document.getElementById("cbPagaPaciente");
                var DivNoPagaElMismo = document.getElementById("NoPagaElMismo");
                cbPagaPaciente.addEventListener('click', (event) => {
                    if (cbPagaPaciente.checked)
                    {
                        DivNoPagaElMismo.style.display = 'none';
                    }
                    else
                    {
                        DivNoPagaElMismo.style.display = 'block';
                    }

                });
            </script>