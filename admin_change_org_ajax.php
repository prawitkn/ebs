<?php
		session_start();
		include('config.php');

		$username = $_SESSION['username'];	
		
		//$_SESSION['user_building_code'] = $_POST['building_code'];
		$_SESSION['user_org_code'] = $_POST['org_code'];
				
		$json = '';
		$json = '{"success": true, "msg": "เรียบร้อย"}';
		
		echo json_encode($json);				
		
?>