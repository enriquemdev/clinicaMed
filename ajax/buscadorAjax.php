<?php 
 session_start(['name'=>'SPM']);
		 require_once "../config/APP.php";

		 if (isset($_POST['combobox']) || isset($_POST['condRadio2']) || isset($_POST['condRadio']) || isset($_POST['condicion'])
			|| isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) 
			|| isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) 
		{

		 	$data_url=[
		 		"usuario"=>"user-search",
		 		"cita"=>"cita-search",
		 		"empleado"=>"empleado-search",
				"paciente"=>"paciente-search",
				"familiar"=>"familiares-search",
				"especialidad"=>"especialidad-search",
				"estudio-academico"=>"estudio-academico-search",
				"historial-de-cargo"=>"historial-de-cargo-search",
				"examen"=>"examen-search",
				"resultado-examen"=>"resultado-examen-search",
				"signos-vitales"=>"signos-vitales-search",
				"diagnostico"=>"diagnostico-search",
				"receta-medica"=>"receta-medica-search",
				"receta-examen"=>"receta-examen-search",
				"constancia"=>"constancia-search",
				"consulta"=>"consulta-search",
				"solicitud-consulta"=>"solicitud-consulta-search",
				"inventario-farmacia"=>"inventario-farmacia-search",
				"compras-farmacia"=>"compras-farmacia-search",
				"detalle-compras-farmacia"=>"detalle-compras-farmacia-search",
				"ventas-farmacia"=>"ventas-farmacia-search",
				"paciente-Caja"=>'cajaPaciente-search'
		 	];

		 	if (isset($_POST['modulo'])) {
		 		$modulo=$_POST['modulo'];


		 		if (!isset($data_url[$modulo])) {
					$alerta=[
                  "Alerta"=>"simple",
                  "Titulo"=>"Ocurrió un error",
                  "Texto"=>"Hubo un error en la búsqueda.",
                  "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();		 		
            	}
		 	}else{
		 		$alerta=[
                  "Alerta"=>"simple",
                  "Titulo"=>"Ocurrió un error",
                  "Texto"=>"Hubo un error en la configuración de la búsqueda.",
                  "Tipo"=>"error"
                ];
                echo json_encode($alerta);
                exit();
		 	}


		 	if ($modulo=="cita"){//Osea multiples valores que son de fecha como en cita list view
		 		$fecha_inicio="fecha_inicio_".$modulo;
		 		$fecha_final="fecha_final_".$modulo;

		 		//Iniciar busqueda
		 		if(isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])){
		 			
		 			if ($_POST['fecha_inicio']=="" || $_POST['fecha_final']==""){
		 				$alerta=[
		                  "Alerta"=>"simple",
		                  "Titulo"=>"Ocurrió un error inesperado",
		                  "Texto"=>"Por favor introduce una fecha inicial y una fecha final.",
		                  "Tipo"=>"error"
		                ];
		                echo json_encode($alerta);
		                exit();
		 				
		 			}

		 			$_SESSION[$fecha_inicio]=$_POST['fecha_inicio'];
		 			$_SESSION[$fecha_final]=$_POST['fecha_final'];

		 		}//cierre iniciar busqueda 

		 		//eliminar busqueda
		 		if(isset($_POST['eliminar_busqueda']) ){
		 			unset($_SESSION[$fecha_inicio]);
		 			unset($_SESSION[$fecha_final]);

		 		}
		 		
		 	}else {
		 		$name_var="busqueda_".$modulo;

		 		//iniciar busqueda
		 		if(isset($_POST['busqueda_inicial']) || isset($_POST['condicion']) || isset($_POST['condRadio'])
				   || isset($_POST['condRadio2']) || isset($_POST['combobox']))
				{
		 			if($_POST['busqueda_inicial']=="" && !isset($_POST['condicion']) && !isset($_POST['condRadio'])
					 	&& !isset($_POST['condRadio2']) && !isset($_POST['combobox']))
					{
		 				$alerta=[
		                  "Alerta"=>"simple",
		                  "Titulo"=>"Ocurrió un error inesperado",
		                  "Texto"=>"Por favor introduce un término de búsqueda para empezar.",
		                  "Tipo"=>"error"
		                ];
		                echo json_encode($alerta);
		                exit();

		 			}
					
		 			$_SESSION[$name_var]=$_POST['busqueda_inicial'];
					 /****************************************************** */
					if(isset($_POST['condicion'])){
						$_SESSION['condicion'] = 1;
					}
					else
					{
						$_SESSION['condicion'] = 0;
					}

					if(isset($_POST['condRadio'])){
						$_SESSION['condRadio'] = $_POST['condRadio'];
					}
					else
					{
						$_SESSION['condRadio'] = "";
					}

					if(isset($_POST['condRadio2'])){
						$_SESSION['condRadio2'] = $_POST['condRadio2'];
					}
					else
					{
						$_SESSION['condRadio2'] = "";
					}

					if(isset($_POST['combobox'])){
						$_SESSION['combobox'] = $_POST['combobox'];
					}
					else
					{
						$_SESSION['combobox'] = "";
					}
					/************************************************************* */



		 		}//cierre iniciar busqueda

		 		//eliminar busqueda
		 		if(isset($_POST['eliminar_busqueda']) ){
		 			unset($_SESSION[$name_var]);
					unset($_SESSION['condicion']);
					unset($_SESSION['condRadio']);
					unset($_SESSION['condRadio2']);
					unset($_SESSION['combobox']);

		 		}
		 	}
		 	
		 	//redireccionar
		 	$url=$data_url[$modulo];
		 	$alerta=[
                  "Alerta"=>"redireccionar",
                  "URL"=>SERVERURL.$url."/"
                ];
                echo json_encode($alerta);

		 	
		 }else{
			session_unset();
	        session_destroy();
	        header("Location:".SERVERURL."login/");
	        exit();

		 }

		