<?php 
		$auxiliar = new loginControlador();
		$listaVistas = $auxiliar -> navLateral_controlador();/*Aqui se recibe el array de la lista de la Vistas para este usuario*/
   	?>

<!-- Page header
<div class="full-box page-header">
        <h3 class="text-left">
            <i class="fab fa-dashcube fa-fw"></i> &nbsp; HOME
        </h3>
        

    </div>
     -->
   <!-- Content -->
   <div class="full-box tile-container">
       <div >
            <?php 
                //Dashboard por cargo
                if(($_SESSION['name-cargo_spm'])=="Gerente"){
                
                require_once "./controladores/dashboardControlador.php";
                echo' <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h3 class="h3 mb-0 text-gray-800"><i class="fab fa-dashcube fa-fw"></i> DASHBOARD</h3>
                    
                </div>';
                $dashboard = new dashboardControlador();
                echo $dashboard->dashboard(1);
                } else if(($_SESSION['name-cargo_spm'])=="Doctor"){
                    echo' <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h3 class="h3 mb-0 text-gray-800"><i class="fab fa-dashcube fa-fw"></i> HOME</h3>
                        
                    </div>';
                    require_once "./controladores/dashboardControlador.php";
                    $dashboard = new dashboardControlador();
                    echo $dashboard->dashboard(2);
                } else if(($_SESSION['name-cargo_spm'])=="Recepcionista"){
                    echo' <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h3 class="h3 mb-0 text-gray-800"><i class="fab fa-dashcube fa-fw"></i> HOME</h3>
                        
                    </div>';
                    require_once "./controladores/dashboardControlador.php";
                    $dashboard = new dashboardControlador();
                    echo $dashboard->dashboard(3);
                } 
                else if(($_SESSION['name-cargo_spm'])=="Cajero"){
                    echo' <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h3 class="h3 mb-0 text-gray-800"><i class="fab fa-dashcube fa-fw"></i> HOME</h3>
                    
                </div>';
                    require_once "./controladores/dashboardControlador.php";
                    $dashboard = new dashboardControlador();
                    echo $dashboard->dashboard(4);
                } else if(($_SESSION['name-cargo_spm'])=="Administrador"){
                    echo' <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h3 class="h3 mb-0 text-gray-800"><i class="fab fa-dashcube fa-fw"></i> HOME</h3>
                        
                    </div>';
                    require_once "./controladores/dashboardControlador.php";
                    $dashboard = new dashboardControlador();
                    echo $dashboard->dashboard(5);
                } 

			?>
       </div>
       
      
   </div>
   
</div>


