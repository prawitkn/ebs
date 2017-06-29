<?php
session_start();
include('config.php');

		$username = $_SESSION['username'];	
		//$username = '1474602794';
		
		$year_month_code=$_POST['year_month_code'];

		//$db_servername = "localhost";
		//$db_username = "root";
		//$db_password = "";
		//$db_dbname = "daginterdb";
		
		$db_servername = "localhost";
		$db_username = "daginterdb";
		$db_password = "P8gws6K";
		$db_dbname = "daginterdb";

		// Create connection
		$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);
		mysqli_set_charset($conn,"utf8");
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		$sql = 'truncate rtarfwen_t_duty_orgs;
				truncate rtarfwen_t_duty_details;
				truncate rtarfwen_t_duty_headers;
				ALTER TABLE rtarfwen_t_duty_orgs AUTO_INCREMENT = 1;
				ALTER TABLE rtarfwen_t_duty_details AUTO_INCREMENT = 1;
				ALTER TABLE rtarfwen_t_duty_headers AUTO_INCREMENT = 1;
				';
		
		$result=mysqli_multi_query($conn, $sql);
		
		$json = '';
		if($result){
			$json = '{"success": false, "msg": "เคยสร้างตารางข้อมูลประจำเดือนนี้แล้ว"}';
		}else{
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($conn).'" }';
		}
		
		mysqli_close($conn);
		
		echo json_encode($json);
					
			
?>
