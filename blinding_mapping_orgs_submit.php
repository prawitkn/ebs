<?php
session_start();
/*if(!isset($_SESSION['username'])==true){
	header("Location: /emts/login.php");
	exit;
}*/
$username = 123; // $_SESSION['username'];
include('config.php');
?>
<html>
<head>
	<meta http-equiv=Content-Type content="text/html; charset=utf-8">
	<script src="assets\bootstrap-3.3.7\js\bootstrap.min.js"></script>
	<link rel="stylesheet" href="assets\bootstrap-3.3.7\css\bootstrap.min.css">

	<script src="assets\jquery-3.1.1.min.js"></script>
	<script>
	$(document).ready(function(){
		$("#sl_acc_from").change(function(){
			$('#lbl_balance').attr('disabled','');
			$('#lbl_balance').val($('option:selected', this).attr('data-balance'));
			//var tmp = $('option:selected', this).attr('data-balance');
			//var a = tmp.toString();
			//$('#balance_from').val(a);
			//alert(tmp);
			//$('#lbl_balance').attr('disabled','disabled');
		});
	});
	</script>		

</head>
<body>
<div class="container">
<h1 style="color: blue;">E-DAG : ระบบส่งรายชื่อเข้าเวรรักษาความปลอดภัยประจำเดือน</h1>
</h1>

<a href="index.php" class="btn btn-primary" >Home</a>
<!--<a href="#" class="btn btn-primary" >กำหนดหน่วยเข้าเวรอาคาร บก.ทท.</a>-->
<a href="blinding_mapping_orgs.php" class="btn btn-primary" >ส่งรายชื่อเข้าเวรอาคารบริวาร</a>
<a href="transfer.php" class="btn btn-primary" >รายงานการเข้าเวร</a>

<h3>กำหนดหน่วยเข้าเวรอาคาร บก.ทท.</h3>

<?php
		if (!$db) {
			die("Connection failed: " . mysqli_connect_error());
		}
		
		//mysqli_query($db, "BEGIN");
		
		$year_month_code = $_POST['year_month_code'];
		$building_code = $_POST['building_code'];
		$hdr_id = $_POST['hdr_id'];
		
		$sql = "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-1']."', org_code2='".$_POST['org_code2-1']."', org_code3='".$_POST['org_code3-1']."', org_code4='".$_POST['org_code4-1']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date1']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-2']."', org_code2='".$_POST['org_code2-2']."', org_code3='".$_POST['org_code3-2']."', org_code4='".$_POST['org_code4-2']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date2']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-3']."', org_code2='".$_POST['org_code2-3']."', org_code3='".$_POST['org_code3-3']."', org_code4='".$_POST['org_code4-3']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date3']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-4']."', org_code2='".$_POST['org_code2-4']."', org_code3='".$_POST['org_code3-4']."', org_code4='".$_POST['org_code4-4']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date4']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-5']."', org_code2='".$_POST['org_code2-5']."', org_code3='".$_POST['org_code3-5']."', org_code4='".$_POST['org_code4-5']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date5']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-6']."', org_code2='".$_POST['org_code2-6']."', org_code3='".$_POST['org_code3-6']."', org_code4='".$_POST['org_code4-6']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date6']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-7']."', org_code2='".$_POST['org_code2-7']."', org_code3='".$_POST['org_code3-7']."', org_code4='".$_POST['org_code4-7']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date7']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-8']."', org_code2='".$_POST['org_code2-8']."', org_code3='".$_POST['org_code3-8']."', org_code4='".$_POST['org_code4-8']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date8']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-9']."', org_code2='".$_POST['org_code2-9']."', org_code3='".$_POST['org_code3-9']."', org_code4='".$_POST['org_code4-9']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date9']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-10']."', org_code2='".$_POST['org_code2-10']."', org_code3='".$_POST['org_code3-10']."', org_code4='".$_POST['org_code4-10']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date10']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-11']."', org_code2='".$_POST['org_code2-11']."', org_code3='".$_POST['org_code3-11']."', org_code4='".$_POST['org_code4-11']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date11']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-12']."', org_code2='".$_POST['org_code2-12']."', org_code3='".$_POST['org_code3-12']."', org_code4='".$_POST['org_code4-12']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date12']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-13']."', org_code2='".$_POST['org_code2-13']."', org_code3='".$_POST['org_code3-13']."', org_code4='".$_POST['org_code4-13']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date13']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-14']."', org_code2='".$_POST['org_code2-14']."', org_code3='".$_POST['org_code3-14']."', org_code4='".$_POST['org_code4-14']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date14']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-15']."', org_code2='".$_POST['org_code2-15']."', org_code3='".$_POST['org_code3-15']."', org_code4='".$_POST['org_code4-15']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date15']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-16']."', org_code2='".$_POST['org_code2-16']."', org_code3='".$_POST['org_code3-16']."', org_code4='".$_POST['org_code4-16']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date16']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-17']."', org_code2='".$_POST['org_code2-17']."', org_code3='".$_POST['org_code3-17']."', org_code4='".$_POST['org_code4-17']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date17']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-18']."', org_code2='".$_POST['org_code2-18']."', org_code3='".$_POST['org_code3-18']."', org_code4='".$_POST['org_code4-18']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date18']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-19']."', org_code2='".$_POST['org_code2-19']."', org_code3='".$_POST['org_code3-19']."', org_code4='".$_POST['org_code4-19']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date19']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-20']."', org_code2='".$_POST['org_code2-20']."', org_code3='".$_POST['org_code3-20']."', org_code4='".$_POST['org_code4-20']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date20']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-21']."', org_code2='".$_POST['org_code2-21']."', org_code3='".$_POST['org_code3-21']."', org_code4='".$_POST['org_code4-21']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date21']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-22']."', org_code2='".$_POST['org_code2-22']."', org_code3='".$_POST['org_code3-22']."', org_code4='".$_POST['org_code4-22']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date22']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-23']."', org_code2='".$_POST['org_code2-23']."', org_code3='".$_POST['org_code3-23']."', org_code4='".$_POST['org_code4-23']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date23']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-24']."', org_code2='".$_POST['org_code2-24']."', org_code3='".$_POST['org_code3-24']."', org_code4='".$_POST['org_code4-24']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date24']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-25']."', org_code2='".$_POST['org_code2-25']."', org_code3='".$_POST['org_code3-25']."', org_code4='".$_POST['org_code4-25']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date25']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-26']."', org_code2='".$_POST['org_code2-26']."', org_code3='".$_POST['org_code3-26']."', org_code4='".$_POST['org_code4-26']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date26']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-27']."', org_code2='".$_POST['org_code2-27']."', org_code3='".$_POST['org_code3-27']."', org_code4='".$_POST['org_code4-27']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date27']."'; ";
		$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-28']."', org_code2='".$_POST['org_code2-28']."', org_code3='".$_POST['org_code3-28']."', org_code4='".$_POST['org_code4-28']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date28']."'; ";
						
		if(isset($_POST["date29"]) && !empty($_POST["date29"])){
				$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-29']."', org_code2='".$_POST['org_code2-29']."', org_code3='".$_POST['org_code3-29']."', org_code4='".$_POST['org_code4-29']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date29']."'; ";
		}
		if(isset($_POST["date30"]) && !empty($_POST["date30"])){
				$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-30']."', org_code2='".$_POST['org_code2-30']."', org_code3='".$_POST['org_code3-30']."', org_code4='".$_POST['org_code4-30']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date30']."'; ";
		}
		if(isset($_POST["date31"]) && !empty($_POST["date31"])){
				$sql .= "UPDATE rtarfwen_t_duty_details SET org_code='".$_POST['org_code-31']."', org_code2='".$_POST['org_code2-31']."', org_code3='".$_POST['org_code3-31']."', org_code4='".$_POST['org_code4-31']."' WHERE hdr_id=".$hdr_id." AND date='".$_POST['date31']."'; ";
		}
		
		if (mysqli_multi_query($db, $sql)) {
			echo "<h3 style='color: green;'>บันทึกรายการเรียบร้อยแล้ว</h3>";
			//mysqli_query($db, "COMMIT");
		} else {
			echo "<h3 style='color: red;'>ผิดพลาด: " . $sql . "<br>" . mysqli_error($db).'</h3>';
			//mysqli_query($db, "ROLLBACK");
		}
		
		//mysqli_query($db, "CLOSE");
		
?>

</div><!--container-->
</body>
</html>