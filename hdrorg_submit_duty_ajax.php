<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
	session_start();
	include('config.php');
	$username = $_SESSION['username'];	
		
		$hdr_id=$_POST['hdr_id'];
		$hdr_org_id=$_POST['hdr_org_id'];
		$verify_fullname=$_POST['verify_fullname'];
		$verify_position=$_POST['verify_position'];
		//$year_month_code=$_POST['year_month_code'];
		//$building_code=$_POST['building_code'];
		
		/*$sql = '
				select *
				from  rtarfwen_m_users 
				where username='.$username
				;
	   $ses_sql = mysqli_query($db,$sql);
	   $rowcount=mysqli_num_rows($ses_sql);
	   $usr='';
	   if($rowcount == 1){
		   $usr = mysqli_fetch_array($ses_sql);	
	   }else{
		   echo 'error';
	   }
		$sql = "
				select *
				from  rtarfwen_t_duty_orgs
				where id=".$hdr_org_id." limit 1"
				;
		$result = mysqli_query($db, $sql);
		if(mysqli_num_rows($result)==0){
			$sql = "insert into rtarfwen_t_duty_orgs (hdr_id, org_code, status_code, submit_time, submit_by_id, verify_fullname, verify_position) VALUES
					(".$hdr_id.",'".$usr['org_code'].$"', 'S', now(),".$username.",'".$verify_fullname."', '".$verify_position."')";
			$result = mysqli_query($db, $sql);
		}else{
			$sql = '';
			$sql = "update rtarfwen_t_duty_orgs set status='S', verify_fullname='".$verify_fullname."', verify_position='".$verify_position."' where id=$hdr_org_id";
			$result = mysqli_query($db, $sql);
		}*/
		
		$sql = '';
		$sql = "update rtarfwen_t_duty_orgs set status_code='S', submit_time=now(), submit_by_id=".$username.", verify_fullname='".$verify_fullname."', verify_position='".$verify_position."' where id=".$hdr_org_id;
		$result = mysqli_query($db, $sql);
		
	
		$json = '';		
		if ($result) {
			$json = '{"success": true}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
		}
		
		echo json_encode($json);
					
?>
