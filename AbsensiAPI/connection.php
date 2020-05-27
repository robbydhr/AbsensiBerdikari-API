<?php

define ('hostname','localhost');
define ('user','phpmyadmin');
define ('pass','Zsaqwe4321');
define ('databaseName','phpmyadmin');

$con = mysqli_connect(hostname, user, pass, databaseName);

if (mysqli_connect_errno()) {
		echo "Gagal terhubung MySQL: " . mysqli_connect_error();
	}

?>