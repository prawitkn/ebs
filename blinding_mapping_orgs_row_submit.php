<?php
	session_start();
	include('config.php');

	$username = $_SESSION['username'];	
	
	$hdr_id=trim($_POST['hdr_id']);	
	$date=trim($_POST['date']);
	$org_code=trim($_POST['org_code']);	
	$org_code2=trim($_POST['org_code2']);	
	$org_code3=trim($_POST['org_code3']);	
	$org_code4=trim($_POST['org_code4']);	
	$org_code5=trim($_POST['org_code5']);
	$org_code6=trim($_POST['org_code6']);

	$sql = '';
	$sql = "UPDATE rtarfwen_t_duty_details SET 
					org_code='".$org_code."',
					org_code2='".$org_code2."',
					org_code3='".$org_code3."',
					org_code4='".$org_code4."',
					org_code5='".$org_code5."',
					org_code6='".$org_code6."' 
					where 1=1 
					and hdr_id=".$hdr_id." 
					and date='".$date."' " ;
	if (mysqli_query($db, $sql)) {
		$json = '{"success": true}';
	} else {
		$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
	}

	echo json_encode($json);	
?>
