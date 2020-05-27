<?php
// if($_SERVER["REQUEST_METHOD"]=="POST"){
    require 'connection.php';
	updateData();
// }
function updateData(){
    global $con;
    
    //data from apps
    $pass_lama = $_POST["v_pass_lama"];
    $pass_baru = $_POST["v_pass_baru"];
    $nik = $_POST["v_id"];
    
    // $pass_lama = "1234";
    // $pass_baru = "1234";
    // $nik = "1234";
    
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    } else{
        $query = mysqli_query($con, "SELECT * FROM list_employee WHERE nik='$nik' AND pass='$pass_lama'");
	    $row = mysqli_fetch_array($query);
	    
        if (!empty($row)){
            $sql = "UPDATE list_employee SET pass = '$pass_baru' WHERE nik = '$nik'";
            if (mysqli_query($con, $sql)) {
                    $age = array("success"=>1);
            		echo json_encode($age);
                  
            } else {
                  echo "Error:". $sql . "<br>" . mysqli_error($con);
            }
        }else{
            $age = array("success"=>0);
            echo json_encode($age);
        }
        mysqli_close($con);
    }
}
?>