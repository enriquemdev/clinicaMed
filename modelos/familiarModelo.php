<?php
require_once "mainModel.php";
class familiarModelo extends mainModel
{
    /*Función para agregar persona*/
    protected static function agregar_persona_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("INSERT INTO tblpersona(Cedula,Nombres,Apellidos,Fecha_de_nacimiento,Genero,Estado_civil,Direccion,Telefono
            ,Estado,Email) VALUES(:Cedula,:Nombres,:Apellidos,:Fecha_de_nacimiento,:Genero,:Estado_civil,:Direccion,:Telefono,:Estado,:Correo)");

        $sql->bindParam(":Cedula", $datos['Cedula']);
        $sql->bindParam(":Nombres", $datos['Nombres']);
        $sql->bindParam(":Apellidos", $datos['Apellidos']);
        $sql->bindParam(":Fecha_de_nacimiento", $datos['Fecha_de_nacimiento']);
        $sql->bindParam(":Genero", $datos['Genero']);
        $sql->bindParam(":Estado_civil", $datos['Estado_civil']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Correo", $datos['correo']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->execute();
        return $sql;
    }


    protected static function agregar_familiar_modelo($datos)
    {
        $sql2 = mainModel::conectar()->prepare("INSERT INTO tblfamiliares(ID,CodPersona,ContactoEmergencia) 
            VALUES(:ID,:CodPersona,:ContactoEmergencia)");

        $sql2->bindParam(":ID", $datos['ID']);
        $sql2->bindParam(":CodPersona", $datos['CodPersona']);
        $sql2->bindParam(":ContactoEmergencia", $datos['ContactoEmergencia']);
        $sql2->execute();
        return $sql2;
    }
    protected static function obtener_persona_modelo($datos)
    {
        $sql3 = mainModel::conectar()->prepare("SELECT * FROM tblpersona 
                WHERE Cedula=:Cedula");

        $sql3->bindParam(":Cedula", $datos['Cedula']);
        $sql3->execute();
        return $sql3;
    }

    protected static function obtener_codigo_modelo($datos)
    {
        $sql3 = mainModel::conectar()->prepare("SELECT * FROM tblpersona 
                WHERE Nombres= ':Nombres'");

        $sql3->bindParam(":Nombres", $datos['Nombres']);
        $sql3->execute();
        return $sql3;
    }

    protected static function obtener_codigo2($datos)
    {
        $sql5 = mainModel::conectar()->prepare("call increment_persona(:increm)");
        $sql5->bindParam(":increm", $datos);
        $sql5->execute();
        return $sql5;
    }
    /*Aquí cambió cosas luis */
    protected static function datos_familiar_modeloUPDATE($id)
    {
        $sql4 = mainModel::conectar()->prepare("SELECT *, a.Nombres AS namefamiliar, a.Apellidos as apellidofam,a.Cedula as CedulaFam, a.Fecha_de_nacimiento as dateoffam,
        a.Direccion as direccionfam, a.Telefono as telefonofam, a.Email as correofam,
        e.Nombres as NombreEmpleado,e.Apellidos as ApellidoEmpleado,f.Codigo as CodigoEmpleado		
        FROM tblrelacionpersonafamiliar
       INNER JOIN tblfamiliares as b ON tblrelacionpersonafamiliar.Codigo_Familiar = b.ID
       INNER JOIN tblpersona as a ON b.CodPersona = a.Codigo 
       INNER JOIN tblpersona as e on tblrelacionpersonafamiliar.Codigo_Persona = e.Codigo
       INNER JOIN tblempleado as f on (f.CodPersona=e.Codigo)
WHERE tblrelacionpersonafamiliar.Codigo_Familiar =:ID;");
        $sql4->bindParam(":ID", $id);
        $sql4->execute();
        return $sql4;
    }
    /*Modelo actualizar persona y empleado */
    protected static function actualizar_personayFAMILIAR_modelo($datos)
    {
        $sql = mainModel::conectar()->prepare("UPDATE tblpersona as a INNER JOIN tblfamiliares as b ON a.Codigo = b.CodPersona 
            INNER JOIN  tblrelacionpersonafamiliar as c ON b.ID = c.Codigo_Familiar
            SET a.Cedula=:Cedula,
            a.Nombres=:Nombres, 
            a.Apellidos=:Apellidos,
            a.Fecha_de_nacimiento=:Fecha_de_nacimiento, 
            a.Estado_civil=:Estado_civil,
            a.Direccion=:Direccion, 
            a.Telefono=:Telefono, 
            a.Email=:Email, 
            a.Estado=:Estado, 
            b.ContactoEmergencia=:ContactoEmergencia, 
            c.ID_Parentesco=:parentesco,
            c.Tutor=:Tutor,
            c.Codigo_Persona=:familiarde

            WHERE a.Codigo=:Codigo");
        $sql->bindParam(":Codigo", $datos['Codigo']); // 
        $sql->bindParam(":Cedula", $datos['Cedula']); //
        $sql->bindParam(":Nombres", $datos['Nombres']); //
        $sql->bindParam(":Apellidos", $datos['Apellidos']); //
        $sql->bindParam(":Fecha_de_nacimiento", $datos['Fecha_de_nacimiento']); //
        $sql->bindParam(":Estado_civil", $datos['Estado_civil']); //
        $sql->bindParam(":Direccion", $datos['Direccion']); //
        $sql->bindParam(":Telefono", $datos['Telefono']); //
        $sql->bindParam(":Email", $datos['Email']); //
        $sql->bindParam(":ContactoEmergencia", $datos['ContactoEmergencia']); //
        $sql->bindParam(":familiarde", $datos['familiarde']); //
        $sql->bindParam(":parentesco", $datos['parentesco']); //
        $sql->bindParam(":Tutor", $datos['Tutor']); //
        $sql->bindParam(":Estado", $datos['Estado']); //
        $sql->execute();
        return $sql;
    }
    /*Datos de item select parentesco */
    protected static function datos_item3_modelo()
    {

        $sql = mainModel::conectar()->prepare("SELECT * FROM catparentesco ");
        $sql->execute();
        return $sql;
    }

    protected static function agregar_relacion_modelo($datos)
    {
        $sql5 = mainModel::conectar()->prepare("INSERT INTO tblrelacionpersonafamiliar(Codigo_Persona,Codigo_Familiar,ID_Parentesco,Tutor) VALUES
            (:Codigo_Persona,:Codigo_Familiar,:ID_Parentesco,:Tutor)");

        $sql5->bindParam(":Codigo_Persona", $datos['Codigo_Persona']);
        $sql5->bindParam(":Codigo_Familiar", $datos['Codigo_Familiar']);
        $sql5->bindParam(":ID_Parentesco", $datos['ID_Parentesco']);
        $sql5->bindParam(":Tutor", $datos['Tutor']);
        $sql5->execute();
        return $sql5;
    }
    /*Termina modelo */
}
