<?php
session_start();

		$username = $_SESSION['username'];	
		
		$hdr_id=$_POST['hdr_id'];
		$year_month_code=$_POST['year_month_code'];
		$building_code=$_POST['building_code'];
		$date=$_POST['date'];
		$set_no=$_POST['set_no'];
		$org_code=$_POST['org_code'];
		$mid=$_POST['mid'];
		$fullname=$_POST['fullname'];
		$mobile_no=$_POST['mobile_no'];

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
		$sql = '';	
		
		$sql = "UPDATE rtarfwen_t_duty_details SET ";
		switch($set_no){
			case '1' : 
				$sql .= "mid=0, fullname='', mobile_phone_no='', ";
				break;
			case '2' : 
				$sql .= "mid2=0, fullname2='', mobile_phone_no2='', ";
				break;
			case '3' : 
				$sql .= "mid3=0, fullname3='', mobile_phone_no3='', ";
				break;
			case '4' : 
				$sql .= "mid4=0, fullname4='', mobile_phone_no4='', ";
				break;
			case '5' : 
				$sql .= "mid5=0, fullname5='', mobile_phone_no5='', ";
				break;
			case '6' : 
				$sql .= "mid6=0, fullname6='', mobile_phone_no6='', ";
				break;
		}
		$sql .= "update_by='$username' 
				where date='$date' 
				and hdr_id=$hdr_id ";
		//$sql = "UPDATE rtarfwen_t_duty_details SET update_by='$username' where date='$date' and hdr_id=$hdr_id ";
		$json = '';		
		if (mysqli_query($conn, $sql)) {
			$json = '{"success": true, "rows": '.'0'.'}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($conn).'" }';
		}

		mysqli_close($conn);
		
		echo json_encode($json);
		
		//unset($_SESSION["bank_from"]);		
			
?>
