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
		$sql = 'select a.*,
				b.name as building_name
				from rtarfwen_t_duty_headers a
				inner join rtarfwen_m_buildings b on a.building_code=b.code 
				where a.year_month_code='.$year_month_code.' ';
		
		$result=mysqli_query($conn, $sql);
		
		$rowcount=mysqli_num_rows($result);
		
		$json = '';
		if($rowcount>0){
			$json = '{"success": false, "msg": "เคยสร้างตารางข้อมูลประจำเดือนนี้แล้ว"}';
		}else{
			$sql = "INSERT INTO rtarfwen_t_duty_headers 
					(building_code,year_month_code,status,create_by)
					SELECT a.code, '".$year_month_code."', 'B', '".$username."' 
					FROM rtarfwen_m_buildings a
					where 1=1
					and a.code not in (select building_code from rtarfwen_t_duty_headers where year_month_code='".$year_month_code."') 
					ORDER BY a.code ASC ";
			
			$result=mysqli_query($db, $sql);
			if(!$result){
				$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($conn).'" }';
			}else{
				$sql = 'select a.*,
				b.name as building_name 
				from rtarfwen_t_duty_headers a 
				inner join rtarfwen_m_buildings b on a.building_code=b.code 
				where a.year_month_code='.$year_month_code.' ';
				
				$result=mysqli_query($db, $sql);
				$rowcount=mysqli_num_rows($result);
				if($rowcount){
					$sql = "INSERT INTO rtarfwen_t_duty_details 
					(hdr_id, date, create_by)
					SELECT a.id, c.date, '".$username."' 
					FROM rtarfwen_t_duty_headers a
					inner join core_years_months b on a.year_month_code=b.code
					inner join core_days c on b.year=year(c.date) and b.month=month(c.date)
					where 1=1
					and a.year_month_code=".$year_month_code." 
					and a.id not in (select hdr_id from rtarfwen_t_duty_details)
					ORDER BY a.id, a.building_code, c.date ASC 
					;
					INSERT INTO rtarfwen_t_duty_orgs 
					(hdr_id, org_code, status_code) 
					SELECT a.id, b.code, 'B'
					FROM rtarfwen_t_duty_headers a
					inner join rtarfwen_m_orgs b on a.building_code=b.building_code 
					where 1=1
					and a.year_month_code=".$year_month_code." 
					and not exists 	(select bb.hdr_id from rtarfwen_t_duty_orgs bb
									where a.id=bb.hdr_id
									and b.code=bb.org_code )
					ORDER BY a.id, a.building_code, b.code ASC 
					;
					INSERT INTO rtarfwen_t_duty_orgs 
					(hdr_id, org_code, status_code) 
					SELECT a.id, b.code, 'B'
					FROM rtarfwen_t_duty_headers a
					inner join rtarfwen_m_orgs b on is_nor_yai=1
					where 1=1
					and a.building_code=1
					and a.year_month_code=".$year_month_code." 
					and not exists (select bb.hdr_id from rtarfwen_t_duty_orgs bb
								where a.id=bb.hdr_id
								and b.code=bb.org_code )
					ORDER BY a.id, a.building_code, b.code ASC";
			
					$result=mysqli_multi_query($db, $sql);
					if($result){
						$json = '{"success": true }';
					}else{
						$json = '{"success": false }';
					}
				}// rowcount duty hdr
			}// insert duty hdr
		}// rowcount duty hdr exists
		
		mysqli_close($conn);
		
		echo json_encode($json);
				
		//unset($_SESSION["bank_from"]);		
			
?>
