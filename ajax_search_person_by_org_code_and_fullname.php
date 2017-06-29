<?php
	session_start();
	include('config.php');
	$search_org_code = $_POST['search_org_code'];
	$search_fullname = $_POST['search_fullname'];
	$result = mysqli_query($db, "SELECT id, title_abb_name_surname as fullname, mobile_no, position_act_name FROM core_persons where  title_abb_name_surname like '%$search_fullname%' limit 100");

	$jsonData = array();
	while ($array = mysqli_fetch_row($result)) {
		$jsonData[] = $array;
	}
 					   
	echo json_encode($jsonData);
	
?>


