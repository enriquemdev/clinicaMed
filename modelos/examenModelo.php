<?php
require_once "mainModel.php";
class examenModelo extends mainModel
{
    protected static function agregar_servicio_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblserviciosbrindados (tipoServicio,estadoServicio,MontoServicio,RebajaServicio)
        VALUES (:tipoServicio,:estadoServicio,:MontoServicio,:RebajaServicio);");
        $sql->bindParam(":tipoServicio", $datos['TipoServicio']);
        $sql->bindParam(":estadoServicio", $datos['EstadoServicio']);
        $sql->bindParam(":MontoServicio", $datos['MontoServicio']);
        $sql->bindParam(":RebajaServicio", $datos['RebajaServicio']);
        $sql->execute();
        return $sql;
    }
    /*Función para catalogos cargos*/
    protected static function agregar_examen_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblexamen(RecetaPrevia,CodPaciente,SalaMedica,MaquinariaMedica,EmpleadoRealizacion,FechaYHora,idServicio) 
        VALUES(:RecetaPrevia,:CodPaciente,:SalaMedica,:MaquinariaMedica,:EmpleadoRealizacion, :FechaYHora,:idServicio)");
        $sql->bindParam(":RecetaPrevia", $datos['RecetaExamen']);
        $sql->bindParam(":CodPaciente", $datos['PacienteExamen']);
        $sql->bindParam(":SalaMedica", $datos['SalaMedicaExamen']);
        $sql->bindParam(":MaquinariaMedica", $datos['MaquinariaExamen']);
        $sql->bindParam(":EmpleadoRealizacion", $datos['EspecialistaExamen']);
        $sql->bindParam(":FechaYHora", $datos['FechaExamen']);
        $sql->bindParam(":idServicio", $datos['ServicioExamen']);
        $sql->execute();
        return $sql;
    }
    /*Obtener datos de item 1 */
    protected static function datos_item1_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT * FROM catsalaexamen ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */

    /*Obtener datos de item 1 */
    protected static function datos_maquinaria_modelo()
    {
        $sql = mainModel::conectar()->prepare("SELECT * FROM catmaquinaria ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */

    /*Obtener datos de item 1 */
    protected static function datos_item2_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catsalaexamen ");
        $sql->execute();
        return $sql;
    }/*Termina modelo */

    //Aqui manoseo steven
    protected static function datos_examen_modelo($id)
    {
        $sql = mainModel::conectar()->prepare("SELECT a.Codigo,a.FechaYHora,c.Nombres,
            c.Apellidos,c.Fecha_de_nacimiento,e.Nombres as NombresMedico,
            e.Apellidos as ApellidosMedico, f.Nombre as SalaExamen, g.Motivo as MotivoExamen, 
            h.Nombre as TipoExamen, i.Nombre as GrupoSanguineo
            FROM tblexamen as a  
            INNER JOIN tblpaciente as b ON (a.CodPaciente=b.CodigoP)
            INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
            INNER JOIN tblempleado as d ON (a.EmpleadoRealizacion=d.Codigo)
            INNER JOIN tblpersona as e ON (d.CodPersona=e.Codigo)
            INNER JOIN catsalaexamen as f ON (f.ID=a.SalaMedica)
            INNER JOIN tblrecetaexamen as g ON (a.RecetaPrevia=g.Codigo)
            INNER JOIN catexamenesmedicos as h ON (h.ID=g.TipoExamen)
            INNER JOIN catgruposanguineo as i ON (b.GrupoSanguineo=i.ID)
            WHERE a.Codigo = $id;");

        $sql->execute();

        return $sql;
    }
    //Aqui termino
}
