<?php
// if($_SERVER["REQUEST_METHOD"]=="POST"){
    require 'connection.php';
    $id_kantorx;
	getAllData();
// }
function getAllData(){
	global $con;
    $jarak_min = 100;
    
    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    else{
    
    // $lat2 = floatval("-6.171660");
    // $long2 = floatval("106.822117");

    // $nik = "1317";
    // $status = "sore";
    
    $lat2 = floatval($_POST["v_lat"]);
    $long2 = floatval($_POST["v_long"]);
    $nik = $_POST["v_id"];
    $status = $_POST["v_status"];
    $image = $_POST["v_image"];
    
    date_default_timezone_set('Asia/Jakarta');
	$date = date('Y-m-d');
	$time = date('H:i:s');
    
    $query = mysqli_query($con, "SELECT * FROM attendance WHERE nik='$nik' AND date='$date'");
	$row = mysqli_fetch_array($query);
	
	if (!empty($row)){
	    $query = mysqli_query($con, "SELECT departemen, id_kantor FROM list_employee WHERE nik='$nik'");
	    $row = mysqli_fetch_array($query);
	    $departemen = $row['departemen'];
	    $id_kantor = $row['id_kantor'];
	    $query2 = mysqli_query($con, "SELECT lat, lng FROM office WHERE id_kantor='$id_kantor'");
	    $row2 = mysqli_fetch_array($query2);
	    $lat1 = floatval($row2['lat']);
        $long1 = floatval($row2['lng']);
	    
	    if($status == "sore"){
	        $test = mysqli_query($con, "SELECT * FROM attendance WHERE nik='$nik' AND date='$date' AND employee_out='0'");
	        
	        $row2 = mysqli_fetch_array($test);
	        if(!empty($row2)){
                //absen sore
                $jarak = getDistance($lat1, $long1, $lat2, $long2);
                if($jarak <= $jarak_min){
        	        //jarak memenuhi
        	        $sql = "UPDATE attendance SET employee_out = '$time', image_out = '$image' WHERE nik = '$nik' AND date='$date'";
                    if (mysqli_query($con, $sql)) {
                    	$sqli = mysqli_query($con, "SELECT nama_kantor FROM office WHERE id_kantor='$id_kantor'");
            			$row = mysqli_fetch_array($sqli);
                        $age = array("success"=>1, "message"=>"Absen Berhasil di ".$row['nama_kantor']."!");
                    	echo json_encode($age);
                    } else {
                        echo "Error:". $sql . "<br>" . mysqli_error($con);
                    }  
        	    } else {
        	    	if($departemen == "Marketing"){
        	    		//jika dia departemen marketing maka
	    	        	$check = getDistanceMarketing($con, $lat2, $long2);
	    	        	echo $id_kantorx;
	    	        	if($check){
        	        		$sql = mysqli_query($con,"UPDATE attendance SET employee_out = '$time', image_out = '$image' WHERE nik = '$nik' AND date='$date'");
	    	        		if (!$sql) {
	                			printf("Error: %s\n", mysqli_error($con));
	                			exit();
	                			}
		        			$age = array("success"=>1, "message"=>"Absen Berhasil!");
	            			echo json_encode($age);
	    	        	} else {
	    	        		$age = array("success"=>0, "message"=>"Jarak tidak memenuhi");
		                	echo json_encode($age);
	    	        	}
        	    	} else {
        	        //jarak tidak memenuhi
        	        $error = array("success"=>0, "message"=>"Jarak tidak memenuhi");
                    echo json_encode($error);      	    		
        	    	}
        	    }
    	    } else {
    	        $error = array("success"=>2, "message"=>"Anda sudah absen");
                echo json_encode($error);
    	    }
	    } else {
	        $error = array("success"=>2, "message"=>"Anda sudah absen");
            echo json_encode($error);
	    }
	} else {
	    $query = mysqli_query($con, "SELECT departemen, id_kantor FROM list_employee WHERE nik='$nik'");
	    $row = mysqli_fetch_array($query);
	    $departemen = $row['departemen'];
	    $id_kantor = $row['id_kantor'];
	    $query2 = mysqli_query($con, "SELECT lat, lng FROM office WHERE id_kantor='$id_kantor'");
	    $row2 = mysqli_fetch_array($query2);
	    $lat1 = floatval($row2['lat']);
        $long1 = floatval($row2['lng']);
        
	    if($status == "pagi"){
	    //absen pagi
	    
	    $jarak = getDistance($lat1, $long1, $lat2, $long2);
	    if($jarak <= $jarak_min){
	        //jarak memenuhi
	        $sql = mysqli_query($con, "INSERT INTO attendance (nik, date, id_kantor, employee_in, employee_out, image_in, image_out)
	        VALUES ('$nik', '$date', '$id_kantor', '$time', '0', '$image', '0')");
	        // mysqli_fetch_array($sql);
	        if (!$sql) {
                printf("Error: %s\n", mysqli_error($con));
                exit();
                }
            $sqli = mysqli_query($con, "SELECT nama_kantor FROM office WHERE id_kantor='$id_kantor'");
            $row = mysqli_fetch_array($sqli);
	        $age = array("success"=>1, "message"=>"Absen Berhasil di ".$row['nama_kantor']."!");
            echo json_encode($age);
    	    } else {
    	        if($departemen == "Marketing"){
    	        	//jika dia departemen marketing maka
    	        	list ($value_a, $value_b) = getDistanceMarketing($con, $lat2, $long2);

    	        	if($value_a){
    	        		$sql = mysqli_query($con, "INSERT INTO attendance (nik, date, id_kantor, employee_in, employee_out, image_in, image_out) VALUES ('$nik', '$date', '$value_b', '$time', '0', '$image', '0')");
    	        		if (!$sql) {
                			printf("Error: %s\n", mysqli_error($con));
                			exit();
                			}

                		$sqli = mysqli_query($con, "SELECT nama_kantor FROM office WHERE id_kantor='$value_b'");
                		$row = mysqli_fetch_array($sqli);

	        			$age = array("success"=>1, "message"=>"Absen Berhasil di ".$row['nama_kantor']."!");
            			echo json_encode($age);
    	        	} else {
    	        		$age = array("success"=>0, "message"=>"Jarak tidak memenuhi");
	                	echo json_encode($age);
    	        	}
    	        } else {
    	        	//jarak tidak memenuh
	    	        $age = array("success"=>0, "message"=>"Jarak tidak memenuhi");
	                echo json_encode($age);
    	        	}
    	        }
    	    } else {
    	        $error = array("success"=>2, "message"=>"Absen pagi dulu");
                echo json_encode($error);
    	    }
	    }
    mysqli_close($con);
    }
}

function getDistance($lat1, $long1, $lat2, $long2){
    $earth_radius = 6371; //km
    $earth_radius_miles = 3958.756; //result in miles
 
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($long2 - $long1);
 
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = number_format(($earth_radius * $c), 2, '.', '');
    
        return $d * 1000;
}
function getDistanceMarketing($con, $lat2, $long2){
	$sql = "SELECT id_kantor, (6371 * ACOS(SIN(RADIANS(lat)) * SIN(RADIANS($lat2)) + COS(RADIANS(lng - $long2)) * COS(RADIANS(lat)) * COS(RADIANS($lat2)))) AS jarak FROM office WHERE type = 'Toko' ORDER BY (jarak*1000) ASC LIMIT 1";

	$result = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($result);
	$value3 = $row['jarak']*1000;
	// $id_kantorx = $row['id_kantor'];
	if(($value3)<=100){
		$x = true;
	} else {
		$x = false;
	}

    return array ($x, $row['id_kantor']);
}
?>