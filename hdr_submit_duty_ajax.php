<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
	session_start();
	include('config.php');
	$username = $_SESSION['username'];	
		
		$hdr_id=$_POST['hdr_id'];
		$verify_fullname=$_POST['verify_fullname'];
		$verify_position=$_POST['verify_position'];
		//$year_month_code=$_POST['year_month_code'];
		//$building_code=$_POST['building_code'];
		
		$sql = '';
		$sql = "update rtarfwen_t_duty_headers set status='S', submit_time=now(), submit_by_id=".$username.", verify_fullname='".$verify_fullname."', verify_position='".$verify_position."' where id=$hdr_id";
		$result = mysqli_query($db, $sql);
		
		//$result = mysqli_query($db, "update rtarfwen_t_duty_headers set status='S', verify_fullname='".$verify_fullname."', verify_position='".$verify_position."' where id=$hdr_id ");
		
		$json = '';		
		if ($result) {
			$json = '{"success": true}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
		}
		
		echo json_encode($json);
					
?>
