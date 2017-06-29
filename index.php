<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$fullname = $_SESSION['fullname'];
$user_building_code = $_SESSION['user_building_code'];
$user_is_building_major = $_SESSION['user_is_building_major'];
$user_is_administrator = $_SESSION['user_is_administrator'];
   
include('config.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
</head>
<body>

<?php include('nav_top.php'); ?>

<?php
	if($user_is_administrator){
		?>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">เจ้าหน้าที่ กกม.สบป.สบ.ทหาร</legend>  
			 <div class="col-md-12 text-center"> 
				<a href="admin_it.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;รีเซ็ทฐานข้อมูล</a><br/>
			</div>
		</fieldset>		
		<?php
	}
?>

<?php
	if($user_is_administrator){
		?>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">เจ้าหน้าที่ กสบ.สบ.ทหาร</legend>  
			 <div class="col-md-12 text-center"> 
				<a href="m_users.php" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;กำหนดผู้ใช้ระบบ</a><br/>
				<a href="create_holiday.php" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-calendar"></span>&nbsp;&nbsp;กำหนดวันหยุดราชการ</a><br/>
				<a href="create_rtarfwen_monthly.php" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-repeat"></span>&nbsp;&nbsp;สร้างตารางเวร รปภ.บก.ทท. ประจำเดือน</a><br/>
				<a href="blinding_mapping_orgs.php" class="btn btn-danger btn-block" ><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;กำหนดหน่วย รปภ.บก.ทท. ประจำเดือน</a><br/>
				<a href="reports.php" class="btn btn-success btn-block"  ><span class="glyphicon glyphicon-file"></span>&nbsp;&nbsp;รายงานเวรประจำเดือน</a><br/>
			</div>
		</fieldset>		
		<?php
	}
?>

<?php
	if($user_is_building_major<>'' and $user_building_code<>''){
		?>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">เจ้าหน้าที่จัดเวรประจำอาคารบริวาร</legend>  
			 <div class="col-md-12 text-center">
				<a href="blinding_mapping_orgs_child.php" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-cog"></span>&nbsp;&nbsp;กำหนดหน่วยเวร รปภ. ประจำอาคาร ประจำเดือน</a><br/>
			</div>
		</fieldset>
		<?php
	}
?>

<?php
	if($user_building_code<>''){
		?>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">เจ้าหน้าที่จัดเวรประจำหน่วย</legend>  
			 <div class="col-md-12 text-center">			
				<a href="set_duty_fixed_org.php" class="btn btn-danger btn-block" ><span class="glyphicon glyphicon-th-list"></span>&nbsp;&nbsp;กำหนดรายชื่อเวร รปภ.บก.ทท. ประจำเดือน</a><br/>
				<a href="set_duty_fixed_org_new.php" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;กำหนดรายชื่อเวร รปภ. ประจำอาคาร ประจำเดือน</a><br/>
			</div>
		</fieldset>
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">รายงาน</legend>  
			 <div class="col-md-12 text-center"> 				
				<a href="reports_stat.php" class="btn btn-primary btn-block" ><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;รายงานสถิติการเข้าเวรรายบุคคล</a><br/>
			</div>
		</fieldset>
		<?php
	}
?>



</div><!--container-->
</body>
</html>