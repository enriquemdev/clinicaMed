<?php
require_once "../config/Server.php" ;

$data = array();

if(isset($_GET["query"]))
{
 $connect = new PDO("mysql:host=".SERVER."; dbname=".DB, USER, PASS);

 $query = "
 SELECT * FROM catsintomas
 WHERE nombreSintoma LIKE '".$_GET["query"]."%' 
 LIMIT 15";

 $statement = $connect->prepare($query);

 $statement->execute();

 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row["nombreSintoma"];
 }
}

echo json_encode($data);

?>