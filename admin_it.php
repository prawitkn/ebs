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


<h3>เจ้าหน้าที่ กกม.สบป.สบ.ทหาร</h3>
 <form action = "blinding_mapping_orgs.php" method = "post" class="form-horizontal">

 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2">เดือน</label>
	 <div class="col-md-4">
		<select name="year_month" id="sl_year_month" class="form-control">
						<option value="">Please Select</option>
			<?php
				$sql = 'select * from core_years_months 
						where exists (select * from core_days where year=year(date) and month=month(date)) 
						order by year, month asc';
			   $ses_sql = mysqli_query($db,$sql);
			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				  echo '<option value="'.$r['code'].'" data-year="'.$r['year'].'" data-month="'.$r['month'].'">'.$r['name'].'</option>';
					//echo "1";
				}
			?>

			</select>
	 </div>
 </div>
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<a id="btn_create" class="btn btn-primary" >ล้างฐานข้อมูลเริ่มต้นใหม่</a>
	 </div>
 </div>
</form>

<form method="post" action="blinding_mapping_orgs_submit.php">
	<?php
			$sql = '
			select distinct a.create_time,
			c.name as year_month_name
			from rtarfwen_t_duty_headers a
			left join core_years_months c on a.year_month_code=c.code
			order by a.year_month_code desc';
			
			$result = mysqli_query($db,$sql);		
		   
		   $rowcount=mysqli_num_rows($result);		   
		   if($rowcount>0){			   
			   echo '<table class="table">';
				echo '<thead>
							<tr>
								<td>เดือน ปี</td>		
								<td>วันเวลาที่สร้าง</td>
							</tr>
						</thead>
						<tbody>';
				$icount = 1;
			   while($r = mysqli_fetch_array($result)) {	
				  echo '<tr>';
				  echo '	<td>'.$r['year_month_name'].'</td>';
				  echo '	<td>'.$r['create_time'].'</td>';
				  echo '</tr>';
					$icount +=1;				
				}
				echo '</tbody>';
				echo '</table>';
		   }//result row count;
	?>	
</form>


</div><!--container-->
</body>
</html>




<script>
$(document).ready(function(){
	$('#btn_create').click(function(){	
		$year_month_code = 'null';
		var params = {
			year_month_code: $year_month_code
		};
		$.ajax({
			url: "admin_it_reset_ajax.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				data = $.parseJSON(data);
				json = JSON.parse(data);
				if(json.success){
					alert('Success');
					//post_to_url('create_rtarfwen_monthly.php');
				}else{
					alert(json.msg);
				}					
			}, //success
			error:function(){
				alert('error');
			}   
		});
    });
	$('#btn_create_bup').click(function(){		
		$year_month_code = $('#sl_year_month').val();
		if($year_month_code==''){
			alert('Please specify Year and Month');
		}else{
			var params = {
				year_month_code: $year_month_code
			};
			$.ajax({
				url: "create_rtarfwen_monthly_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					data = $.parseJSON(data);
					json = JSON.parse(data);
					if(json.success){
						alert('Success');
						//post_to_url('create_rtarfwen_monthly.php');
					}else{
						alert(json.msg);
					}					
				}, //success
				error:function(){
					alert('error');
				}   
			});
		}
    });
});
</script>	