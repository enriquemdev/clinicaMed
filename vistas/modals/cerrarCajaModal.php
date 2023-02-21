<?php
	require_once "../../config/Server.php" ;
    // Check if user has requested to get detail
    if (isset($_POST["get_data"]))
    {
        // Get the ID of customer user has selected
        $id = $_POST["id"];
 
		$connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

		$query = "
            SELECT idCaja, nombreCaja, d.Monto FROM tblaperturacaja as a
            INNER JOIN catcaja as b on a.Caja = b.idCaja
            LEFT JOIN tblrecibosventa as c on a.idApertura = c.aperturaCaja
            LEFT JOIN tbldetpagoservicios as d on c.idRecibo = d.NumeroRecibo
            WHERE a.idApertura = '".$id."'
		";
	   
		$statement = $connect->prepare($query);
	   
		$statement->execute();

		$statement = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Important to echo the record in JSON format
        echo json_encode($statement);
 
        // Important to stop further executing the script on AJAX by following line
        exit();
    }
?>