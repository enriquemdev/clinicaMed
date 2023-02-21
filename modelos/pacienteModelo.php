<?php
    require_once "mainModel.php";
    class pacienteModelo extends mainModel{
        /*Función para agregar persona*/
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
        protected static function agregar_paciente_ocupacion_modelo($datos){
            $sql5 =mainModel::conectar()->prepare("INSERT INTO tblocupacionpacientes(CodPaciente,Nombre,Empresa,Telefono,Referencia) VALUES(:CodPaciente,:Nombre,:Empresa,:Telefono,:Referencia)");

            $sql5->bindParam(":CodPaciente",$datos['CodPaciente']);
            $sql5->bindParam(":Nombre",$datos['Nombre']);
            $sql5->bindParam(":Empresa",$datos['Empresa']);
            $sql5->bindParam(":Telefono",$datos['Telefono']);
            $sql5->bindParam(":Referencia",$datos['Referencia']);
            $sql5->execute();
            return $sql5;
            
        }
        protected static function agregar_relacion_modelo($datos){
            $sql5 =mainModel::conectar()->prepare("INSERT INTO tblrelacionpersonafamiliar(Codigo_Persona,Codigo_Familiar,ID_Parentesco,Tutor) VALUES
            (:Codigo_Persona,:Codigo_Familiar,:ID_Parentesco,:Tutor)");

            $sql5->bindParam(":Codigo_Persona",$datos['Codigo_Persona']);
            $sql5->bindParam(":Codigo_Familiar",$datos['Codigo_Familiar']);
            $sql5->bindParam(":ID_Parentesco",$datos['ID_Parentesco']);
            $sql5->bindParam(":Tutor",$datos['Tutor']);
            $sql5->execute();
            return $sql5;
            
        }

        protected static function agregar_paciente_modelo($datos){
            $sql2 =mainModel::conectar()->prepare("INSERT INTO tblpaciente(CodExpediente,INSS,GrupoSanguineo,CodPersona) 
            VALUES(:CodExpediente,:INSS,:GrupoSanguineo,:CodPersona)");

            $sql2->bindParam(":CodExpediente",$datos['CodExpediente']);
            $sql2->bindParam(":INSS",$datos['INSS']);
            $sql2->bindParam(":GrupoSanguineo",$datos['GrupoSanguineo']);
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
            protected static function obtener_codpaciente_modelo($datos){
                $sql4 =mainModel::conectar()->prepare("SELECT * FROM tblpaciente 
                    WHERE INSS=:INSS");
    
                $sql4->bindParam(":INSS",$datos['INSS']);
                $sql4->execute();
                return $sql4;
                }

                protected static function obtener_codigo2($datos){
                    $sql5=mainModel::conectar()->prepare("call increment_persona(:increm)");
                    $sql5->bindParam(":increm",$datos);
                    $sql5->execute();
                    return $sql5;
                    }
                protected static function cambiar_codigo($datos){
                    $sql5=mainModel::conectar()->prepare("UPDATE tblpersona SET Codigo = :maximo WHERE Codigo = 0;");
                    $sql5->bindParam(":maximo",$datos);
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
                 /*Obtener datos de item 3 */
                 protected static function datos_item3_modelo(){
                    
                    $sql =mainModel::conectar()->prepare("SELECT * FROM catgruposanguineo ");
                    $sql->execute();
                    return $sql;
                }/*Termina modelo */

                //Aqui manoseo steven
        
                protected static function datos_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.CodigoP,a.INSS,a.CodPersona,b.Cedula,
                    b.Nombres,b.Apellidos,b.Fecha_de_nacimiento,b.Direccion,b.Telefono,b.Email,
                    c.Nombre as GrupoSanguineo
                    FROM tblpaciente as a
                    INNER JOIN tblpersona as b ON (a.CodPersona = b.Codigo) 
                    INNER JOIN catgruposanguineo as c ON (a.GrupoSanguineo=c.ID)
                    where a.CodigoP=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function datos_pacientes_modelo($EsConsulta,$busqueda,$inicio,$registros){    

                    if($EsConsulta){
                        $sql =mainModel::conectar()->prepare("SELECT a.CodigoP as CodigoPaciente, a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        WHERE (CONCAT(Nombres,' ',Apellidos) LIKE '%$busqueda%')
                        OR (a.CodigoP LIKE '$busqueda') 
                        OR (INSS LIKE '$busqueda') 
                        OR (b.Cedula LIKE '$busqueda') 
                        OR (b.Telefono LIKE '$busqueda') 
                        OR (b.Email LIKE '$busqueda') 
                        OR (b.Nombres LIKE '%$busqueda%') 
                        OR (b.Apellidos LIKE '%$busqueda%') 
                        ORDER BY CodigoP DESC LIMIT $inicio,$registros;");
                        $sql->execute();
                        
                        return $sql;
                    }else{
                        $sql =mainModel::conectar()->prepare("SELECT a.CodigoP as CodigoPaciente, 
                        a.CodPersona as CodigoPersona,
                        b.Nombres as NombresPaciente, b.Apellidos as ApellidosPaciente, 
                        b.Fecha_de_nacimiento as FechaNacimiento
                        FROM tblpaciente as a
                        INNER JOIN tblpersona as b
                        ON a.CodPersona = b.Codigo 
                        ORDER BY CodigoP DESC LIMIT $inicio,$registros;");
                        $sql->execute();
                        return $sql;
                    }
                }
                protected static function datos_familiares_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.Tutor as EsTutor,b.ContactoEmergencia,c.Nombres, c.Apellidos,c.Telefono,c.Email,d.Nombre as Parentesco
                    FROM tblrelacionpersonafamiliar as a 
                    INNER JOIN tblfamiliares as b ON (b.ID = a.Codigo_Familiar)
                    INNER JOIN tblpersona as c ON (c.Codigo = b.CodPersona)
                    INNER JOIN catparentesco as d on (d.ID=a.ID_Parentesco)
                    WHERE a.Codigo_Persona=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function consultas_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.Codigo,a.CodMedico,a.IdCita,a.CodConsultorio,a.FechaYHora,a.MotivoConsulta,a.NotasConsulta,c.Nombres as NombresMedico
                    ,c.Apellidos as ApellidosMedico,d.Nombre as NombreConsultorio
                                        FROM tblconsulta as a
                                        inner join tblempleado as b on (b.Codigo=a.CodMedico)
                                        INNER JOIN tblpersona as c on (c.Codigo=b.CodPersona)
                                        INNER join catconsultorio as d on (d.ID=a.CodConsultorio)
                                        where a.CodPaciente=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function diagnostico_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.Codigo, a.Descripcion, a.IdEnfermedad, a.Nota, b.NombreEnfermedad
                    FROM tbldiagnosticoconsulta as a 
                    INNER JOIN catenfermedades as b on (b.ID=a.IdEnfermedad)
                    where a.CodConsulta=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function recetas_medicamento_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.Codigo, a.FechaEmision, b.Medicamento,b.Dosis,b.Frecuencia,c.NombreComercial
                    FROM tblrecetamedicamentos as a
                    INNER JOIN tbldetallereceta as b on (b.CodReceta=a.Codigo)
                    INNER JOIN catmedicamentos as c on (c.Codigo=b.Medicamento)
                    WHERE a.CodigoConsulta=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function recetas_examen_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("SELECT a.Codigo,a.TipoExamen,a.Motivo,b.Nombre as NombreExamen
                    FROM tblrecetaexamen as a
                    inner JOIN catexamenesmedicos as b on (b.ID=a.TipoExamen)
                    where a.ConsultaPrevia=$id;");
                    $sql->execute();
                    return $sql;
                }
                protected static function diagnostico_sintomas_paciente_modelo($id){
                    $sql =mainModel::conectar()->prepare("Select a.sintoma, b.nombreSintoma, b.descripcionSintoma from  tblSintomasDiagnostico  as a INNER JOIN catSintomas as b on(a.sintoma=b.idSintoma)
                    Where a.diagnostico=$id;");
                    $sql->execute();
                    return $sql;
                   }
                //Aqui termino
            } 