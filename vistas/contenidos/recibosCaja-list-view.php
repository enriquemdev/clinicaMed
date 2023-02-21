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

    require_once "./controladores/cajaControlador.php";
	$ins_caja = new cajaControlador();

    // $datosPaciente = $ins_caja->datosPacienteCaja($codPaciente);

?>

<!-- Page header -->
<div class="full-box page-header pb-4">
    <h3 class="text-left">
        <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE RECIBOS DE PAGO
    </h3>
    <p class="text-justify">
        Se muestra la lista de recibos de pago realizados.
    </p>
</div>
    <div class="container-fluid">
<?php 
        
        echo $ins_caja->paginador_recibosVenta_controlador($pagina[1],100,$_SESSION['privilegio_spm'],$_SESSION['id_spm'],$pagina[0],"","","");

    ?>
</div>