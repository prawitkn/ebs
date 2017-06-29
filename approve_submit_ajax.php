<?php
	session_start();
	include('config.php');

	//$username = $_SESSION['username'];	
	
	$id=$_POST['id'];
	
	$sql = "UPDATE rtarfwen_t_duty_headers SET 
			status='A' 
			where id=".$id." ";
	if (mysqli_query($db, $sql);) {
		$json = '{"success": true, "msg": "This is message."}';
	} else {
		$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
	}
	
	echo json_encode($json);				
?>
