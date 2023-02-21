<?php
    require_once "mainModel.php";
    class resultadoExamenModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_resultado_examen_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblresultado(CodExamen,ArchivoResultado,FechaYHora) 
            VALUES(:CodExamen,:ArchivoResultado,:FechaYHora)");

            $sql->bindParam(":CodExamen",$datos['CodExamen']);
            $sql->bindParam(":ArchivoResultado",$datos['ArchivoResultado']);
            $sql->bindParam(":FechaYHora",$datos['FechaYHora']);
            $sql->execute();
            return $sql;
            
        }/*Termina modelo */

        protected static function datos_resultado_examen_modelo($id){
            $sql =mainModel::conectar()->prepare("SELECT a.Codigo,
            a.ArchivoResultado as DescripcionResultadoExamen, a.FechaYHora as FechaRealizacion,
            b.FechaYHora as FechaEstipulada, d.Nombres as NombresMedico,
            d.Apellidos as ApellidosMedico,
            e.Nombre as SalaExamen, f.INSS,g.Cedula,g.Nombres,g.Apellidos,g.Fecha_de_nacimiento,
            g.Direccion,g.Telefono,g.Email, h.Nombre as GrupoSanguineo,j.Nombre as TipoExamen
            FROM tblresultado as a 
            INNER JOIN tblexamen as b on (a.CodExamen=b.Codigo)
            inner join tblempleado as c on (b.EmpleadoRealizacion=c.Codigo)
            inner join tblpersona as d on (c.CodPersona=d.Codigo)
            inner join catsalaexamen as e on (b.SalaMedica=e.ID)
            INNER JOIN tblpaciente as f on (b.CodPaciente=f.CodigoP)
            INNER JOIN tblpersona as g on (f.CodPersona=g.Codigo)
            INNER JOIN catgruposanguineo as h on (f.GrupoSanguineo=h.ID)
            INNER JOIN tblrecetaexamen as i on (b.RecetaPrevia=i.Codigo)
            INNER JOIN catexamenesmedicos as j on (i.TipoExamen=j.ID)
            where a.Codigo = $id;");

            $sql->execute();

            return $sql;
        }

    } 