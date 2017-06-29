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


<fieldset class="scheduler-border">
    <legend class="scheduler-border">คุณไม่มีสิทธิในการใช้งานในเรื่องนี้</legend>  
	<a href="index.php" class="btn btn-danger" >กลับไปหน้าแรก</a>
</fieldset>

</div><!--container-->
</body>
</html>