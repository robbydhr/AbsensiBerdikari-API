<?php
// if($_SERVER["REQUEST_METHOD"]=="POST"){
    require 'connection.php';
    global $con;

    $nik = $_POST["v_id"];

    $totalMasuk = 0;
    $telat = 0;
    
    if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
    }
    else{
    date_default_timezone_set('Asia/Jakarta');

    $m = date('m');

    $v_bulan = new DateTime(date('Y-m-d'));

    // $nik = '608';

    $sql = "SELECT
        id_attend,
        nik,
        date,
        employee_in,
        employee_out
    FROM
        attendance
        WHERE MONTH(date) = $m AND nik = '$nik'
        ORDER BY date(date) DESC, employee_in DESC";

        $result = mysqli_query($con,$sql);  

        $response = array();

        while($row = mysqli_fetch_array($result))
        {
            array_push($response,array("id_attend"=>$row[0],"nik"=>$row[1],"date"=>dateFormarter($row[2]),"employee_in"=>timeNoSec($row[3]),"employee_out"=>timeNoSec($row[4]), "status"=>keterlambatan($row[3]),"diff_time"=>timeDiff($row[3], $row[4])));

            $totalMasuk++;

            if(keterlambatan($row[3])==false){
            	$telat++;
            }
        }

        if(!empty($response)){
        	$obj = (object) [
        	"success" => 1,
		    "month" => getMonth($v_bulan),
		    "j_masuk" => $totalMasuk,
		    "j_telat" => $telat,
		    "list_attend"=>$response
		];

		echo json_encode($obj);

        } else {
        	$obj = (object) [
        	"success" => 0,
        	"month" => getMonth($v_bulan),
		];
		echo json_encode($obj);
        }

        mysqli_close($con);

    }

    function timeDiff($val1, $val2){
    	if($val2 == 0){
    		return "-";
    	} else {
	    	$buka_jam = intVal(substr($val1,0,2));
	    	$buka_menit = intVal(substr($val1,3,2));
	    	$tutup_jam = intVal(substr($val2,0,2));
	    	$tutup_menit = intVal(substr($val2,3,2));

	    	$date_awal  = new DateTime($buka_jam.":".$buka_menit);
	        $date_akhir = new DateTime($tutup_jam.":".$tutup_menit);
	        $selisih = $date_akhir->diff($date_awal);

	        $jam = $selisih->format('%h');
	        $menit = $selisih->format('%i');
	     
	        if($menit >= 0 && $menit <= 9){
	            $menit = "0".$menit;
	        }
	        
	        $hasil = $jam.":".$menit;
	        // if($hasil == 0){
	        //     $hasil=24;
	        // }
	        // $hasil = number_format($hasil,2);
	        return $hasil;
    	}

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
            return false;
        } else{
            //ontime
            return true;
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

    function timeNoSec($val){
    	$time = new DateTime($val);
    	$value = $time->format("H:i");
    	return $value;
    }

?>