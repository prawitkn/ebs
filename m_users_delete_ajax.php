<?php
	session_start();
	include('config.php');

	$username = $_SESSION['username'];	
	
	$id=$_POST['id'];
	$sql = "DELETE FROM rtarfwen_m_users WHERE id=$id";		
	$json = '';
	if (mysqli_query($db, $sql)) {
		$json = '{"success": true, "msg": "This is message."}';
	} else {
		$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
	}		
	echo json_encode($json);				
?>
