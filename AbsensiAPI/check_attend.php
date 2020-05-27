<?php
    require 'connection.php';
    global $con;

    date_default_timezone_set('Asia/Jakarta');

    $m = date('m');

    $v_bulan = new DateTime(date('Y-m-d'));

    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    else{

	$sql = "SELECT
	    a.id_attend,
	    a.nik,
	    e.nama,
	    a.date,
	    a.employee_in,
	    a.employee_out
	FROM
	    list_employee e
	INNER JOIN 
	    attendance a 
	    ON e.nik = a.nik
	    ORDER BY date(a.date) DESC, a.employee_in DESC";

	    $result = mysqli_query($con,$sql);

	    $response = array();

	    while($row = mysqli_fetch_array($result))
	        {
	            array_push($response,array("id_attend"=>$row[0], "nik"=>$row[1], "nama"=>$row[2], "date"=>dateFormarter($row[3]),"employee_in"=>$row[4],"employee_out"=>$row[5], "status"=>keterlambatan($row[4])));
	        }
	    
		echo json_encode($response);

	    mysqli_close($con);
	}

	    function dateFormarter($val){
	        $date = new DateTime($val);

	        $tanggal = $date->format('d');
	        $tahun = $date->format('Y');

	        return getDay($date).", ".$tanggal." ".getMonth($date)." ".$tahun;
    	}

    	function keterlambatan($val){
	        if (strtotime($val) >= strtotime('08:00:59')){
	            //terlambat
	            return "terlambat";
	        } else{
	            //ontime
	            return "ontime";
	        }
    	}

    	function getDay($date){
	    	$day;
	    	if($date->format('w')==1){
	            $day = "Senin";
	        } else if($date->format('w')==2){
	            $day = "Selasa";
	        } else if($date->format('w')==3){
	            $day =  "Rabu";
	        } else if($date->format('w')==4){
	            $day =  "Kamis";
	        } else if($date->format('w')==5){
	            $day = "Jumat";
	        } else if($date->format('w')==6){
	            $day = "Sabtu";
	        } else {
	            $day = "Minggu";
	        }
	        return $day;
    	}

	    function getMonth($date){
	    	$month;
	    	if($date->format('m')=="01"){
	            $month = "Januari";
	        } else if($date->format('m')=="02"){
	            $month = "Februari";
	        } else if($date->format('m')=="03"){
	            $month = "Maret";
	        } else if($date->format('m')=="04"){
	            $month = "April";
	        } else if($date->format('m')=="05"){
	            $month = "Mei";
	        } else if($date->format('m')=="06"){
	            $month = "Juni";
	        } else if($date->format('m')=="07"){
	            $month = "Juli";
	        } else if($date->format('m')=="08"){
	            $month = "Agustus";
	        } else if($date->format('m')=="09"){
	            $month = "September";
	        } else if($date->format('m')=="10"){
	            $month = "Oktober";
	        } else if($date->format('m')=="11"){
	            $month = "November";
	        } else if($date->format('m')=="12"){
	            $month = "Desember";
	        }
	        return $month;
	    }
?>