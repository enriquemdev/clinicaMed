<?php
    require_once "mainModel.php";
    class signosvitalesModelo extends mainModel{

        /* Modelor para agregar usuario*/        
        protected static function agregar_signosvitales_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblsignosvitales(CodConsulta,Peso,Altura,Presion_Arterial,Frecuencia_Respiratoria,Frecuencia_Cardiaca,Temperatura,CodEnfermera) VALUES(:CodConsulta,:Peso,:Altura,:Presion_Arterial,:Frecuencia_Respiratoria,:Frecuencia_Cardiaca,:Temperatura,:CodEnfermera)");

            $sql->bindParam(":CodConsulta",$datos['CodConsulta']);
            $sql->bindParam(":Peso",$datos['Peso']);
            $sql->bindParam(":Altura",$datos['Altura']);
            $sql->bindParam(":Presion_Arterial",$datos['Presion_Arterial']);
            $sql->bindParam(":Frecuencia_Respiratoria",$datos['Frecuencia_Respiratoria']);
            $sql->bindParam(":Frecuencia_Cardiaca",$datos['Frecuencia_Cardiaca']);
            $sql->bindParam(":Temperatura",$datos['Temperatura']);
            $sql->bindParam(":CodEnfermera",$datos['CodEnfermera']);
            $sql->execute();
            return $sql;
        }
        protected static function actualizar_consulta_en_espera($datos)
        {
            $sql =mainModel::conectar()->prepare("UPDATE tblconsulta SET Estado=2 WHERE Codigo=:Codigo");

            $sql->bindParam(":Codigo",$datos);
            $sql->execute();
            return $sql;
        }

        protected static function buscarCodConsulta($datos2){//Este se ocupa por el buscador
            $sql5 =mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, tblconsulta.Codigo as cod_consulta
            FROM tblconsulta INNER JOIN tblpaciente 
           ON tblconsulta.CodPaciente = tblpaciente.CodigoP INNER JOIN tblpersona 
           ON tblpaciente.CodPersona = tblpersona.Codigo 
           WHERE tblconsulta.FechaYHora=:FechaYHora AND tblpersona.Nombres=:NombrePaciente");//tblconsulta.FhInicio=:FhInicio AND tblconsulta.CodMedico=:CodigoMedico AND
            $sql5->bindParam(":FechaYHora",$datos2['FechaYHora']);
            $sql5->bindParam(":NombrePaciente",$datos2['NombrePaciente']);
            $sql5->execute();
            return $sql5;
            }

            protected static function reporte_signos_vitales_modelo($id)
    {
        $sql2 = mainModel::conectar()->prepare("SELECT a.Codigo, a.CodConsulta, a.Peso,a.Altura,a.Presion_Arterial,a.Frecuencia_Respiratoria,a.Frecuencia_Cardiaca,a.Temperatura,a.CodEnfermera
        ,c.CodigoP,c.INSS,c.GrupoSanguineo,d.Nombres as NombresPaciente, d.Apellidos as ApellidosPaciente, d.Fecha_de_nacimiento,d.Genero,d.Estado_civil
        ,d.Direccion,d.Telefono,d.Email,d.Cedula,f.Nombres as NombresTomoSignos, f.Apellidos as ApellidosTomoSignos
        FROM tblsignosvitales as a
        inner join tblconsulta as b on(a.CodConsulta=b.Codigo)
        inner join tblpaciente as c on(b.CodPaciente=c.CodigoP)
        inner join tblpersona as d on(c.CodPersona=d.Codigo)
        inner join tblempleado as e on(e.Codigo=a.CodEnfermera)
        inner join tblpersona as f on(e.CodPersona=f.Codigo)
        where a.Codigo=:id");
        $sql2->bindParam(":id", $id);
        $sql2->execute();
        return $sql2;
    }

    }