<?php
	session_start();
	include('config.php');
	$username = $_SESSION['username'];	
		
		$hdr_id=$_POST['hdr_id'];
		//$year_month_code=$_POST['year_month_code'];
		//$building_code=$_POST['building_code'];

		$sql = '';
		
		$result = mysqli_query($db, "update rtarfwen_t_duty_headers set status='V' where id=$hdr_id ");
		
		$json = '';		
		if ($result) {
			$json = '{"success": true}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
		}
		
		echo json_encode($json);
					
?>
