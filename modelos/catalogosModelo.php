<?php
require_once "mainModel.php";
class catalogosModelo extends mainModel
{
    /*Función para catalogos cargos*/
    protected static function agregar_cargo_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catcargos(Nombre,Descripcion) VALUES(:Nombre,:Descripcion)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos proveedores*/
    protected static function agregar_proveedor_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblproveedores (nombreProveedor,telefonoProveedor,direccionProveedor,emailProveedor,ranking,tiempoEntrega,estadoProveedor,descripcionProveedor)
            VALUES(:nombreProveedor,:telefonoProveedor,:direccionProveedor,:emailProveedor,:ranking,:tiempoEntrega,:estadoProveedor,:descripcionProveedor)");

        $sql->bindParam(":nombreProveedor", $datos['nombreProveedor']);
        $sql->bindParam(":telefonoProveedor", $datos['telefonoProveedor']);
        $sql->bindParam(":direccionProveedor", $datos['direccionProveedor']);
        $sql->bindParam(":emailProveedor", $datos['emailProveedor']);
        $sql->bindParam(":ranking", $datos['ranking']);
        $sql->bindParam(":tiempoEntrega", $datos['tiempoEntrega']);
        $sql->bindParam(":estadoProveedor", $datos['estadoProveedor']);
        $sql->bindParam(":descripcionProveedor", $datos['descripcionProveedor']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos consultorio*/
    protected static function agregar_consultorio_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catconsultorio(Nombre,Descripcion) VALUES(:Nombre,:Descripcion)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos enfermedades*/
    protected static function agregar_enfermedades_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catenfermedades(NombreEnfermedad,Descripcion,TipoEnfermedad) VALUES(:Nombre,:Descripcion,:TipoEnfermedad)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":TipoEnfermedad", $datos['TipoEnfermedad']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos especialidades*/
    protected static function agregar_especialidad_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catespecialidades(Nombre,Descripcion) VALUES(:Nombre,:Descripcion)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos estados*/
    protected static function agregar_estado_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catestado(NombreEstado) VALUES(:Nombre)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos estadoscita*/
    protected static function agregar_estadocita_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catestadocita(Nombre) VALUES(:Nombre)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos estadoscita*/
    protected static function agregar_estadoconsulta_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catestadoconsulta(Nombre) VALUES(:Nombre)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos examen médico*/
    protected static function agregar_examen_medico_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catexamenesmedicos(Nombre,Precio) VALUES(:Nombre,:Precio)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Precio", $datos['Precio']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos grupo sanguíneo*/
    protected static function agregar_grupo_sanguineo_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catgruposanguineo(Nombre) VALUES(:Nombre)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos maquinaria*/
    protected static function agregar_maquinaria_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catmaquinaria(NombreMaquinaria,Descripcion) VALUES(:NombreMaquinaria,:Descripcion)");

        $sql->bindParam(":NombreMaquinaria", $datos['NombreMaquinaria']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos maquinaria*/
    protected static function agregar_medicamento_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catmedicamentos (nombreComercial,nombreGenerico,formula,presentacion,descripcionMedicamento)
        VALUES (:NombreComercial,:NombreGenerico,:Formula,:Presentacion,:Descripcion);");

        $sql->bindParam(":NombreComercial", $datos['NombreComercial']);
        $sql->bindParam(":NombreGenerico", $datos['NombreGenerico']);
        $sql->bindParam(":Formula", $datos['Formula']);
        $sql->bindParam(":Presentacion", $datos['Presentacion']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos metodos de pago*/
    protected static function agregar_metodo_de_pago_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catmetodosdepago(NombreMetodoPago, Descripcion) VALUES(:Nombre,:descrip)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":descrip", $datos['desc']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos nivel académico*/
    protected static function agregar_nivel_academico_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catnivelacademico(NombreNivelAcademico) VALUES(:NombreNivelAcademico)");

        $sql->bindParam(":NombreNivelAcademico", $datos['NombreNivelAcademico']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos parentesco*/
    protected static function agregar_parentesco_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catparentesco(Nombre) VALUES(:Nombre)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos sala examenes*/
    protected static function agregar_sala_examen_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catsalaexamen(Nombre,Dimensiones) VALUES(:Nombre,:Dimensiones)");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Dimensiones", $datos['Dimensiones']);
        $sql->execute();
        return $sql;
    }

    /*Función para catalogos moneda*/
    protected static function agregar_moneda_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catmoneda(nombreMoneda, simbolo, Descripcion, EsReferencia) VALUES(:Nombre, :simbolo, :Descripcion, :EsReferencia)");

        $sql->bindParam(":Nombre", $datos['nombre']);
        $sql->bindParam(":simbolo", $datos['simbolo']);
        $sql->bindParam(":Descripcion", $datos['descripcion']);
        $sql->bindParam(":EsReferencia", $datos['referencia']);
        $sql->execute();
        return $sql;
    }

    /*Función para catalogos CAJA*/
    protected static function agregar_caja_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO catcaja(nombreCaja, Descripcion, EstadoCaja) VALUES(:Nombre, :Descripcion, :EstadoCaja)");

        $sql->bindParam(":Nombre", $datos['nombre']);
        $sql->bindParam(":Descripcion", $datos['descripcion']);
        $sql->bindParam(":EstadoCaja", $datos['EstadoCaja']);
        $sql->execute();
        return $sql;
    }
}/*Aquí termina la clase */