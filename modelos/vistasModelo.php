<?php   
    class vistasModelo{
        /*---------Modelo para obtener las vistas---------*/ 
        protected static function obtener_vistas_modelo($vistas){
/*se crea lista blanca para palabras admitidas dentro de URL */
            $listablanca=["home",
            "empleado-new","empleado-list","empleado-search","empleado-update","gestion-empleado","familiares-new","familiares-list",
            "especialidad-list","especialidad-new","especialidad-search","estudio-academico-new","estudio-academico-list","estudio-academico-search","historial-de-cargo-list",
            "historial-de-cargo-new","historial-de-cargo-search","historial-de-cargo-solicitud","familiares-search","historial-de-cargo-search","empleado-update","familiar-update",

            "catalogo-estado","catalogo-cargos", "catalogo-nivel-academico", "catalogo-especialidades",
            "catalogo-grupo-sanguineo","catalogo-parentesco", "catalogo-consultorio", "catalogo-sala-examen",
            "catalogo-maquinaria","catalogo-enfermedades", "catalogo-estado-cita", "catalogo-estado-consulta",
            "catalogo-examen-medico","catalogo-medicamentos", "catalogo-metodos-de-pago", "signos-vitales-auto","consultas-proceso","diagnostico-auto","consultas-anulada",
            "gestion-farmacia","inventario-farmacia-list"
            ,"compras-farmacia-list","detalle-compras-farmacia-new","detalle-compras-farmacia-list",
            "user-new","user-list","user-search","user-update",
            "paciente-new","paciente-list","paciente-search","paciente-update",
            "solicitudConsulta-new","solicitudConsulta-list","solicitudConsulta-search","solicitudConsulta-update",
            "cita-new","cita-list","cita-historial","cita-search","gestion-catalogos","constancia","consulta","consulta-list"
            ,"diagnostico-list","diagnostico","diagnostico-search","gestion-consulta","receta-examen-list","receta-medica-list","receta-examen","receta-medica",
            "signos-vitales","signos-vitales-list","signos-vitales-search","user-list","constancia-list","examen-new","examen-list","examen-search","resultado-examen-new","resultado-examen-lista"
            ,"gestion-examen","Enrique","solicitud-consulta-list","solicitud-consultadr-list","resultado-examen-search", "constancia-auto"
            ,"receta-examen-auto","receta-medica-auto","solicitud-empleado","historial-de-solicitud-cargo-list","receta-medica-search","receta-examen-search","constancia-search","consulta-search","solicitud-consulta-search"
            ,"inventario-farmacia-search","compras-farmacia-search","detalle-compras-farmacia-search","respaldo-new","gestion-sistema","ventas-farmacia-list","ventas-farmacia-new","ventas-farmacia-search"
            ,"detalle-ventas-farmacia-new","detalle-ventas-farmacia-list","detalle-ventas-farmacia-search"
            ,"inventario-lote-farmacia-list","solicitud-consulta-signos-list","solicitud-consulta-asignada-list","inventario-rop-farmacia-list","catalogo-proveedores"
            //Adiciones luis
            ,"responsable-new", "paciente-new-menor","gestion-paciente","responsable-list","familiar-paciente-new","familiar-paciente-list"
            ,"compras-solicitud-farmacia-new","compras-solicitud-farmacia-list"
            ,"compras-recibir-mercancia-farmacia-list","compras-recibir-mercancia-farmacia"
            ,"inventario-agregar-lote-farmacia-list","inventario-agregar-lote-farmacia",
            //caja
            "gestion-caja", "cajaPaciente-search", "cajaPaciente-list", "cajaServicios-list", "cajaServiciosCobro-list", "catalogo-monedas",
            "catalogo-caja", "aperturaCaja-new", "cajaCliente-new", "recibosCaja-list"
            ];
            if(in_array($vistas,$listablanca)){
                if(is_file("./vistas/contenidos/".$vistas."-view.php")){
                    $contenido="./vistas/contenidos/".$vistas."-view.php";
                }else{
                    $contenido="404";
                }
            }elseif($vistas=="login" || $vistas=="index"){
                $contenido="login";
            }else{
                $contenido="404"; /*Muestra 404 porque no encuentra la vista */
            }
            return $contenido;
        }
    }