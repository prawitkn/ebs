<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$fullname = $_SESSION['fullname'];
include('config.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
	<script src="assets\apps.js"></script>
</head>
<body>

	<?php include('nav_top.php'); ?>


<h3>เปลี่ยนรหัสผ่าน E-DAG Password</h3>
 <form action = "blinding_mapping_orgs.php" method = "post" class="form-horizontal">

 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2" >รหัสผ่านเดิม</label>
	 <div class="col-md-4">
		<input type="text" class="form-control" style="font-size: 20px;" id="txt_pwold" />
	 </div>
 </div>
 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2" style="color: red;">รหัสผ่านใหม่</label>
	 <div class="col-md-4">
		<input type="text" class="form-control" style="font-size: 20px;" id="txt_pwnew" />
	 </div>
 </div>
 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2" style="color: red;">รหัสผ่านใหม่ ยืนยัน</label>
	 <div class="col-md-4">
		<input type="text" class="form-control" style="font-size: 20px;" id="txt_pwnew2" />
	 </div>
 </div>
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<a id="btn_change_pw" class="btn btn-primary" >บันทึก การเปลี่ยนรหัสผ่าน</a>
	 </div>
 </div>
 
</form>


</div><!--container-->
</body>
</html>




<script>
$(document).ready(function(){
	$('#btn_change_pw').click(function(){	
		$btn = $(this);
		//$year_month_code = 'null';
		var params = {
			pwold: $('#txt_pwold').val(),
			pwnew: $('#txt_pwnew').val(),
			pwnew2: $('#txt_pwnew2').val()
		};
		if(params.pwnew != params.pwnew2){
			alert('รหัสผ่านใหม่ ไม่ตรงกัน');
			exit;
		}
		$.ajax({
			url: "user_change_pw_ajax.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				data = $.parseJSON(data);
				json = JSON.parse(data);
				if(json.success){
					$btn.html('เรียบร้อย').fadeOut('fast').removeClass('btn-primary').addClass('btn-success').fadeIn('slow');
				}else{
					alert(json.msg);
					$btn.html('ผิดพลาด').fadeOut('fast').removeClass('btn-primary').addClass('btn-danger').fadeIn('slow');
				}					
			}, //success
			error:function(){
				alert('error');
			}   
		});
    });	
});
</script>	