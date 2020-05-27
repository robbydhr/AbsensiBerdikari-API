<?php
    require 'connection.php';
    global $con;

    $lat2 = floatval("-6.1715383");
    $long2 = floatval("106.822084");

	$sql = "SELECT id_kantor, (6371 * ACOS(SIN(RADIANS(lat)) * SIN(RADIANS($lat2)) + COS(RADIANS(lng - $long2)) * COS(RADIANS(lat)) * COS(RADIANS($lat2)))) AS jarak FROM office WHERE type='Toko' ORDER BY (jarak*1000) ASC LIMIT 1";

	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);
	$value = $row['jarak']*1000;
	if(($value)<=100){
		echo "true";
	} else {
		echo "false";
	}
	
?>