<?php
    require_once "mainModel.php";
    class citaModelo extends mainModel{
        /* Modelor para agregar usuario*/        
        protected static function agregar_cita_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblcita(CodPaciente,fechaProgramada) VALUES(:CodPaciente,:fechaProgramada)");
            $sql->bindParam(":CodPaciente",$datos['pacienteCita']);
            $sql->bindParam(":fechaProgramada",$datos['fechaCita']);
            $sql->execute();
            return $sql;
        }
        protected static function agregar_detCita_modelo($datos){
            $sql2 =mainModel::conectar()->prepare("INSERT INTO tbldetallesdecita(IdCita, HoraInicio, HoraFin, IdConsultorio, CodDoctor, Estado) VALUES(:IdCita, :HoraInicio, :HoraFin, :IdConsultorio, :CodDoctor, :Estado)");
            $sql2->bindParam(":IdCita",$datos['IdCita']);
            $sql2->bindParam(":HoraInicio",$datos['HoraInicio']);
            $sql2->bindParam(":HoraFin",$datos['HoraFin']);
            $sql2->bindParam(":IdConsultorio",$datos['IdConsultorio']);
            $sql2->bindParam(":CodDoctor",$datos['CodDoctor']);
            $sql2->bindParam(":Estado",$datos['Estado']);
            $sql2->execute();
            return $sql2;
        }
        protected static function obtener_cita_modelo(){
            $sql3 =mainModel::conectar()->prepare("SELECT * 
            FROM tblcita 
            WHERE IDCita = (SELECT MAX(IDCita) FROM tblcita)");
            $sql3->execute();
            return $sql3;
        }
        /*Obtener datos de item 1 */
        protected static function datos_item1_modelo(){  
            $sql =mainModel::conectar()->prepare("SELECT * FROM catconsultorio ");
            $sql->execute();
            return $sql;
        }/*Termina modelo */
        protected static function buscarCodEmpleado($datos2){//Este se ocupa por el buscador
            $sql2 =mainModel::conectar()->prepare("SELECT *, tblpersona.Codigo as cod_persona, catcargos.Nombre as nom_cargo, tblempleado.Codigo as cod_empleado FROM tblempleado INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo 
            INNER JOIN tblhistorialcargos ON tblempleado.Codigo = tblhistorialcargos.CodEmpleado
            INNER JOIN catcargos ON tblhistorialcargos.IdCargo = catcargos.ID 
            WHERE tblpersona.Nombres=:NombreEmpleado AND tblpersona.Apellidos=:ApellidoEmpleado AND catcargos.Nombre=:UltimoCargo AND tblempleado.Codigo=:CodigoEmpleado");
            $sql2->bindParam(":NombreEmpleado",$datos2['NombreEmpleado']);//El query de arriba contiene alias de sql que es lo de los as para prevenir el fallo de tener el mismo nombre de columnas en las dos tablas inlvolucradas en el inner join.
            $sql2->bindParam(":ApellidoEmpleado",$datos2['ApellidoEmpleado']);
            $sql2->bindParam(":UltimoCargo",$datos2['UltimoCargo']);
            $sql2->bindParam(":CodigoEmpleado",$datos2['CodigoEmpleado']);
            $sql2->execute();
             return $sql2;
        }
        protected static function datos_cita_modelo($id){
            $sql3 =mainModel::conectar()->prepare("SELECT a.IDCita, a.fechaProgramada,
            b.INSS,c.Cedula,c.Nombres,c.Apellidos,c.Fecha_de_nacimiento, c.Direccion,
            c.Telefono,c.Email,d.Nombre as GrupoSanguineo, g.Nombres as NombresDoctor, 
            g.Apellidos as ApellidosDoctor, h.Nombre as NombreConsultorio, e.HoraInicio, 
            e.HoraFin
            FROM tblcita as a  
            INNER JOIN tblpaciente as b ON (a.CodPaciente=b.CodigoP)
            INNER JOIN tblpersona as c ON (b.CodPersona=c.Codigo)
            INNER JOIN catgruposanguineo as d ON (b.GrupoSanguineo=d.ID)
            INNER JOIN tbldetallesdecita as e ON (a.IDCita=e.IdCita)
            INNER JOIN tblempleado as f ON (e.CodDoctor=f.Codigo)
            INNER JOIN tblpersona as g ON (f.CodPersona=g.Codigo)
            INNER JOIN catconsultorio as h ON (e.IdConsultorio=h.ID)
            WHERE a.IDCita = $id;");
            $sql3->execute();
            return $sql3;
        }

        protected static function buscarCodPaciente($datos2){//Este se ocupa por el buscador autocompletar
            $sql2 =mainModel::conectar()->prepare("SELECT * FROM tblpaciente INNER JOIN tblpersona ON tblpaciente.CodPersona = tblpersona.Codigo 
            WHERE tblpersona.Nombres=:NombrePaciente AND tblpersona.Apellidos=:ApellidoPaciente AND tblpaciente.CodigoP=:CodigoPaciente");
            $sql2->bindParam(":NombrePaciente",$datos2['NombrePaciente']);
            $sql2->bindParam(":ApellidoPaciente",$datos2['ApellidoPaciente']);
            $sql2->bindParam(":CodigoPaciente",$datos2['CodigoPaciente']);
            $sql2->execute();
            return $sql2;
            }
    }