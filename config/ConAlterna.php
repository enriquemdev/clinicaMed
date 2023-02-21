<?php 
 
	$connect = mysqli_connect("localhost","root","","bdcleanfull");
	if (!$connect) {
		echo "Connection Failed" . mysqli_connect_error();
	}
?>