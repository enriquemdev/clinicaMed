<?php
//BUSCADOR EN TIEMPO REAL PRUEBA

include "../config/ConAlterna.php";
if (isset($_POST['input']) ) {
    
    $input = $_POST['input'];

    $query = "SELECT *, tblfamiliares.ID as ID_Familiar FROM tblpersona INNER JOIN tblfamiliares ON tblpersona.Codigo = tblfamiliares.CodPersona WHERE Nombres like '{$input}%'";

    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) { ?>
        <!--Aquí se arma la tabla -->
        <table class="table table-dark table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Cédula</th>
                    <th>Telefono</th>
                    <th>Estado</th>
                    <th>Seleccionar?</th>
                </tr>
            </thead>
            <tbody>
                <?php

                while ($row = mysqli_fetch_assoc($result)) {
                    $id =  $row['ID_Familiar'];
                    $name =  $row['Nombres'] . " " . $row['Apellidos'];
                    $card =  $row['Cedula'];
                    $phone =  $row['Telefono'];
                    $status =  $row['Estado'];
                    $genero = "Sin";
                    if($row['Genero']==1){
                        $genero = "M";
                    }else{
                        $genero = "F";
                    }
                    $estadoCivil = "Sin";
                    if($row['Estado_civil']==1){
                        $estadoCivil = "Solter@";
                    }else{
                        $estadoCivil = "Casad@";
                    }
                    
                ?>
                    <tr>
                        <th><?php echo $id ?></th>
                        <th><?php echo $name ?></th>
                        <th><?php echo $phone ?></th>
                        <th><?php echo $card ?></th>
                        <th><?php echo $status ?></th>
                        <form action="" id="persona-form">
                        <!--<th>Este es un método que aún no pruebo <input type="submit" id="codigo" class="btn btn-raised btn-dark btn-sm"></th>-->
                        <th><button type="button" class="btn btn-raised btn-info btn-sm" onclick="rellenar_Datos_Responsable('<?php echo $id ?>','<?php echo $name ?>')" 
                        value="<?php echo $id ?>" data-bs-dismiss="modal">Seleccionar</button></th>
                        <!--Se le agregó al button la propiedad de cerrar el modal-->
                        </form>  
                    </tr>
                <?php
                }
                ?>


            </tbody>
        </table>
<?php
    } else {
        echo "<h6 class='text-danger text-center mt-3'>No data found</h6>";
    }
}
