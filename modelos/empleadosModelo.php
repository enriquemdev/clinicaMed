<?php
    require_once "mainModel.php";
    class empleadosModelo extends mainModel{
        /*Función para catalogos cargos*/
        protected static function agregar_persona_modelo($datos){
            $sql =mainModel::conectar()->prepare("INSERT INTO tblpersona(Cedula,Nombres,Apellidos,Fecha_de_nacimiento,Genero,Estado_civil,Direccion,Telefono
            ,Email,Estado) VALUES(:Cedula,:Nombres,:Apellidos,:Fecha_de_nacimiento,:Genero,:Estado_civil,:Direccion,:Telefono,:Email,:Estado)");

            $sql->bindParam(":Cedula",$datos['Cedula']);
            $sql->bindParam(":Nombres",$datos['Nombres']);
            $sql->bindParam(":Apellidos",$datos['Apellidos']);
            $sql->bindParam(":Fecha_de_nacimiento",$datos['Fecha_de_nacimiento']);
            $sql->bindParam(":Genero",$datos['Genero']);
            $sql->bindParam(":Estado_civil",$datos['Estado_civil']);
            $sql->bindParam(":Direccion",$datos['Direccion']);
            $sql->bindParam(":Telefono",$datos['Telefono']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->execute();
            return $sql;
            
        }
        protected static function agregar_empleado_modelo($datos){
            $sql2 =mainModel::conectar()->prepare("INSERT INTO tblempleado(Codigo,INSS,CodPersona) VALUES(:Codigo,:INSS,:CodPersona)");

            $sql2->bindParam(":Codigo",$datos['Codigo']);
            $sql2->bindParam(":INSS",$datos['INSS']);
            $sql2->bindParam(":CodPersona",$datos['CodPersona']);
            $sql2->execute();
            return $sql2;
        }
        protected static function obtener_persona_modelo($datos){
            $sql3 =mainModel::conectar()->prepare("SELECT * FROM tblpersona 
                WHERE Cedula=:Cedula");

            $sql3->bindParam(":Cedula",$datos['Cedula']);
            $sql3->execute();
            return $sql3;
            }
    /*
            protected static function obtener_codigo(){
                $sql4 =mainModel::conectar()->prepare("SELECT COUNT(*) FROM tblpersona");
                $sql4->execute();
                return $sql4;
                }
    */
                protected static function obtener_codigo2($datos){
                $sql5=mainModel::conectar()->prepare("call increment_persona(:increm)");
                $sql5->bindParam(":increm",$datos);
                $sql5->execute();
                return $sql5;
                }
                /*Obtener datos de item 1 */
                protected static function datos_item1_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catgenero ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */
                /*Obtener datos de item 2 */
                protected static function datos_item2_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catestadocivil ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */
                protected static function datos_item3_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catestado ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */
             //Aqui manoseo steven
        protected static function datos_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("CALL sp_ObtenerDatosEmpleado($id);");
            $sql->execute();
            return $sql;
        }
        protected static function datos_familiares_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("SELECT a.Tutor as EsTutor,b.ContactoEmergencia,c.Nombres, c.Apellidos,c.Telefono,c.Email,d.Nombre as Parentesco
            FROM tblrelacionpersonafamiliar as a 
            INNER JOIN tblfamiliares as b ON (b.ID = a.Codigo_Familiar)
            INNER JOIN tblpersona as c ON (c.Codigo = b.CodPersona)
            INNER JOIN catparentesco as d on (d.ID=a.ID_Parentesco)
            WHERE a.Codigo_Persona=$id;");
            $sql->execute();
            return $sql;
        }
        protected static function datos_especialidades_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("CALL sp_ObtenerEspecialidadesEmpleado($id);");
            $sql->execute();
            return $sql;
        }

        protected static function obtener_cargos_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("SELECT a.FechaAsignacion, a.Salario, 
            a.Estado,b.Nombre as NombreCargo
            FROM tblhistorialcargos as a 
            INNER JOIN catcargos as b ON (a.IdCargo = b.ID)
            where a.CodEmpleado=$id
            ORDER BY a.FechaAsignacion DESC;");
            $sql->execute();
            return $sql;
        }

        protected static function obtener_ultimo_cargo_activo_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("CALL sp_ObtenerUltimoCargoActivoEmpleado($id);");
            $sql->execute();
            return $sql;
        }
        
        protected static function desencriptar_datos_pdf_modelo($datos){
            return $datos;
        }
        //Aqui termino
        /*Aquí cambió cosas luis */
        protected static function datos_empleado_modeloUPDATE($id){
                $sql4 =mainModel::conectar()->prepare("SELECT * FROM tblempleado 
                INNER JOIN tblpersona ON tblempleado.CodPersona = tblpersona.Codigo WHERE tblempleado.Codigo= :Codigo");
                $sql4->bindParam(":Codigo",$id);
                $sql4->execute();
                return $sql4;
        }
        /*Modelo actualizar usuario */
        protected static function actualizar_persona_modelo($datos){
            $sql =mainModel::conectar()->prepare("UPDATE tblpersona SET Cedula=:Cedula, Nombres=:Nombres, Apellidos=:Apellidos,
            Fecha_de_nacimiento=:Fecha_de_nacimiento, Estado_civil=:Estado_civil,  Direccion=:Direccion,  Telefono=:Telefono, 
            Email=:Email, Estado=:Estado WHERE Codigo=:Codigo");
            $sql->bindParam(":Codigo",$datos['Codigo']);
            $sql->bindParam(":Cedula",$datos['Cedula']);
            $sql->bindParam(":Nombres",$datos['Nombres']);
            $sql->bindParam(":Apellidos",$datos['Apellidos']);
            $sql->bindParam(":Fecha_de_nacimiento",$datos['Fecha_de_nacimiento']);
            $sql->bindParam(":Estado_civil",$datos['Estado_civil']);
            $sql->bindParam(":Direccion",$datos['Direccion']);
            $sql->bindParam(":Telefono",$datos['Telefono']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->execute();
            return $sql;
        }
        /*Modelo actualizar persona y empleado */
        protected static function actualizar_personayempleado_modelo($datos){
            $sql =mainModel::conectar()->prepare("UPDATE tblpersona INNER JOIN tblempleado ON tblpersona.Codigo = tblempleado.CodPersona 
            SET tblpersona.Cedula=:Cedula, tblpersona.Nombres=:Nombres, tblpersona.Apellidos=:Apellidos,
            tblpersona.Fecha_de_nacimiento=:Fecha_de_nacimiento, tblpersona.Genero=:Genero , tblpersona.Estado_civil=:Estado_civil,  tblpersona.Direccion=:Direccion, 
            tblpersona.Telefono=:Telefono, tblpersona.Email=:Email, tblpersona.Estado=:Estado, tblempleado.INSS=:INSS WHERE tblpersona.Codigo=:Codigo");
            $sql->bindParam(":Codigo",$datos['Codigo']);
            $sql->bindParam(":Cedula",$datos['Cedula']);
            $sql->bindParam(":Nombres",$datos['Nombres']);
            $sql->bindParam(":Genero",$datos['Genero']);
            $sql->bindParam(":Apellidos",$datos['Apellidos']);
            $sql->bindParam(":Fecha_de_nacimiento",$datos['Fecha_de_nacimiento']);
            $sql->bindParam(":Estado_civil",$datos['Estado_civil']);
            $sql->bindParam(":Direccion",$datos['Direccion']);
            $sql->bindParam(":Telefono",$datos['Telefono']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Estado",$datos['Estado']);
            $sql->bindParam(":INSS",$datos['INSS']);
            $sql->execute();
            return $sql;
        }
        protected static function actualizar_empleado_modelo($datos){
            $sql2 =mainModel::conectar()->prepare("UPDATE tblempleado SET INSS=:INSS WHERE CodPersona=:CodPersona");

            $sql2->bindParam(":INSS",$datos['INSS']);
            $sql2->bindParam(":CodPersona",$datos['CodPersona']);
            $sql2->execute();
            return $sql2;
        }
        /*Aquí terminó de molestar luis uwu */  
        /*AQUÍ TOCÓ STEVEN */
        protected static function datos_estudios_academicos_empleado_modelo($id){
            $sql =mainModel::conectar()->prepare("SELECT  NombreEstudio, b.NombreNivelAcademico, Institucion, InicioEstudio, FinEstudio 
            FROM tblestudioacademico AS A
            INNER JOIN catnivelacademico AS B ON (a.TipoEstudio=b.ID)
            where CodEmpleado=:codEmpleado;");
            $sql->bindParam(":codEmpleado",$id);
            $sql->execute();
            return $sql;
        }

    } 