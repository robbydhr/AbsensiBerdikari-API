<?php
    include_once "connection.php";

	class usr{}
	
	$nik = $_POST["v_nik"];
	$pass = $_POST["v_pass"];
	
// 	$nik = "608";
// 	$pass = "1234";

    if ((empty($nik)) || (empty($pass))) { 
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom tidak boleh kosong"; 
		die(json_encode($response));
	}
	
	$query = mysqli_query($con, "SELECT * FROM list_employee WHERE nik='$nik' AND pass='$pass'");
	
	$row = mysqli_fetch_array($query);
	
	if (!empty($row)){
		$response = new usr();
		$response->success = 1;
		$response->message = "Selamat datang ".$row['nama'];
		$response->id = $row['nik'];
		$response->username = $row['nama'];
		$response->jabatan = $row['posisi']." ".$row['departemen'];
		die(json_encode($response));
		
	} else { 
		$response = new usr();
		$response->success = 0;
		$response->message = "Username atau password salah";
		die(json_encode($response));
	}
	
	mysqli_close($con);
	
?>