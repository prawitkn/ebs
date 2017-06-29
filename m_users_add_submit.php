<?php
session_start();
include('config.php');
?>
<html>
<head>
<title>E-DAG : ระบบส่งรายชื่อเวรรักษาความปลอดภัยประจำเดือน</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<?php


		$username = $_SESSION['username'];	
		
		$is_update=$_POST['is_update'];
		$id=$_POST['id'];
		$mid=$_POST['mid'];
		$building_code=$_POST['building_code'];
		$org_code=$_POST['org_code'];
		$is_building_major; ( isset($_POST['is_building_major']) ? $is_building_major=1 : $is_building_major=0 );
		$is_checker; ( isset($_POST['is_checker']) ? $is_checker=1 : $is_checker=0 );
		$is_administrator; ( isset($_POST['is_administrator']) ? $is_administrator=1 : $is_administrator=0 );
		$status; ( isset($_POST['status']) ? $status=1 : $status=0 );
		$sql = '';
		if($is_update){
			$sql = "UPDATE rtarfwen_m_users SET 
					username=".$mid.",
					building_code=".$building_code.",
					org_code=".$org_code.",
					is_building_major=".$is_building_major.",
					is_checker=".$is_checker.",
					is_administrator=".$is_administrator.",
					status=".$status." 
					where id='".$id."' ";
		}else{
			$sql = "INSERT INTO rtarfwen_m_users (username, building_code, is_building_major, org_code, is_checker, is_administrator, status)
					VALUE ($mid, $building_code,$is_building_major,$org_code,$is_checker,$is_administrator,$status)";
		}
		
		if (mysqli_query($db, $sql)) {
			//$json = '{"success": true}';
			echo "เรียบร้อย";
			header( "refresh: 1; url=m_users.php" );
			exit;
		} else {
			//$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
			echo "Error : ".$sql."<br> ".mysqli_error($db);
		}
		
		//echo json_encode($json);	
			
?>
</html>
