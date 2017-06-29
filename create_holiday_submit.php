<?php
session_start();
include('config.php');

		$username = $_SESSION['username'];	
		
		$date=$_POST['date'];
		$date_type_code=$_POST['date_type_code'];
		$remark=$_POST['remark'];

		$sql = '';
		$sql = "UPDATE core_days SET 
						date_type_code=".$date_type_code.",
						remark='".$remark."' 
						where date='".$date."' ";
		if (mysqli_query($db, $sql)) {
			$json = '{"success": true, "rows": '.'0'.'}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
		}
		
		echo json_encode($json);	
			
?>
