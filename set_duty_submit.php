<?php
session_start();

		$username = $_SESSION['username'];	
		
		$hdr_id=$_POST['hdr_id'];
		$year_month_code=$_POST['year_month_code'];
		$building_code=$_POST['building_code'];
		$arrDuty=$_POST['arrDuty'];

		$db_servername = "localhost";
		$db_username = "root";
		$db_password = "";
		$db_dbname = "daginterdb";

		// Create connection
		$conn = mysqli_connect($db_servername, $db_username, $db_password, $db_dbname);
		mysqli_set_charset($conn,"utf8");
		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
		$sql = '';
		foreach ($_POST['arrDuty'] as $item) {
				$date = $item['date'];
								
				$org_code = $item['org_code'];
				$mid = $item['mid'];
				$fullname = $item['fullname'];
				$mobile_no = $item['mobile_no'];
				
				$org_code2 = $item['org_code2'];
				$mid2 = $item['mid2'];
				$fullname2 = $item['fullname2'];
				$mobile_no2 = $item['mobile_no2'];
				
				$org_code3 = $item['org_code3'];
				$mid3 = $item['mid3'];
				$fullname3 = $item['fullname3'];
				$mobile_no3 = $item['mobile_no3'];
				
				$org_code4 = $item['org_code4'];
				$mid4 = $item['mid4'];
				$fullname4 = $item['fullname4'];
				$mobile_no4 = $item['mobile_no4'];
				
				$org_code5 = $item['org_code5'];
				$mid5 = $item['mid5'];
				$fullname5 = $item['fullname5'];
				$mobile_no5 = $item['mobile_no5'];
				
				$org_code6 = $item['org_code6'];
				$mid6 = $item['mid6'];
				$fullname6 = $item['fullname6'];
				$mobile_no6 = $item['mobile_no6'];
				
				$sql .= "UPDATE rtarfwen_t_duty_details SET 
						org_code='$org_code', mid=$mid, fullname='$fullname', mobile_phone_no='$mobile_no',
						org_code2='$org_code2', mid2=$mid2, fullname2='$fullname2', mobile_phone_no2='$mobile_no2',
						org_code3='$org_code3', mid3=$mid3, fullname3='$fullname3', mobile_phone_no3='$mobile_no3',
						org_code4='$org_code4', mid4=$mid4, fullname4='$fullname4', mobile_phone_no4='$mobile_no4',
						org_code5='$org_code5', mid5=$mid5, fullname5='$fullname5', mobile_phone_no5='$mobile_no5',
						org_code6='$org_code6', mid6=$mid6, fullname6='$fullname6', mobile_phone_no6='$mobile_no6',
						update_by='$username' 
						where date='$date' 
						and hdr_id=$hdr_id; ";
		}
		
		$json = '';		
		if (mysqli_multi_query($conn, $sql)) {
			$json = '{"success": true}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($conn).'" }';
		}

		mysqli_close($conn);
		
		echo json_encode($json);
		
		//unset($_SESSION["bank_from"]);		
			
?>
