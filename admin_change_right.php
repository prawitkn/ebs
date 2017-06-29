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


<h3>เจ้าหน้าที่ กสบ.สบ.ทหาร เปลี่ยนสิทธิโปรแกรม</h3>
 <form action = "blinding_mapping_orgs.php" method = "post" class="form-horizontal">

 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2">อาคาร</label>
	 <div class="col-md-4">
		<select name="year_month" id="sl_building_code" class="form-control">
						<option value="0">- - ทั้งหมด - -</option>
			<?php
				$sql = 'select * from rtarfwen_m_buildings  ';
			   $ses_sql = mysqli_query($db,$sql);
			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				  echo '<option value="'.$r['code'].'" data-name="'.$r['name'].'" >'.$r['name'].'</option>';
					//echo "1";
				}
			?>

			</select>
	 </div>
 </div>
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<a id="btn_change_building" class="btn btn-primary" >บันทึก การเปลี่ยนสิทธิอาคาร</a>
	 </div>
 </div>
 
 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2">หน่วย</label>
	 <div class="col-md-4">
		<select name="year_month" id="sl_org_code" class="form-control">
						<option value="0">- - ทั้งหมด - -</option>
			<?php
				$sql = 'select * from rtarfwen_m_orgs';
			   $ses_sql = mysqli_query($db,$sql);
			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				  echo '<option value="'.$r['code'].'" data-name="'.$r['name'].'" >'.$r['name'].'</option>';
					//echo "1";
				}
			?>

			</select>
	 </div>
 </div>
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<a id="btn_change_org" class="btn btn-primary" >บันทึก การเปลี่ยนสิทธิหน่วย</a>
	 </div>
 </div>
</form>


</div><!--container-->
</body>
</html>




<script>
$(document).ready(function(){
	$('#btn_change_building').click(function(){	
		$btn = $(this);
		//$year_month_code = 'null';
		var params = {
			building_code: $('#sl_building_code').val()
		};
		alert(params.building_code);
		$.ajax({
			url: "admin_change_building_ajax.php",
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
	$('#btn_change_org').click(function(){	
		$btn = $(this);
		//$year_month_code = 'null';
		var params = {
			org_code: $('#sl_org_code').val()
		};
		$.ajax({
			url: "admin_change_org_ajax.php",
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