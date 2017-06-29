<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$fullname = $_SESSION['fullname'];
include('config.php');
include('inc_helper.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
</head>
<body>

	<?php include('nav_top.php'); ?>


<h3>กำหนดวันหยุดราชการ</h3>
 <form action ="" method = "post" class="form-horizontal">

<div class="form-group">
	 <label for="year_month_code" class="control-label col-md-2">เดือน</label>
	 <div class="col-md-4">
		<select name="year_month_code" id="sl_year_month_code" class="form-control">
						<option value="">Please Select</option>
			<?php
				$sql = 'select * from core_years_months 
						where exists (select * from core_days where year=year(date) and month=month(date)) 
						order by year, month asc';
			   $ses_sql = mysqli_query($db,$sql);			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				  echo '<option value="'.$r['code'].'" '.($_POST['year_month_code']==$r['code']?'selected':'').' >'.$r['name'].'</option>';
					//echo "1";
				}
			?>
			</select>
	 </div>
 </div>
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<input name="check" type="submit" class="btn btn-primary" Value="ค้นหา" />
	 </div>
 </div>
</form>



<form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
	<?php
		if(isset($_POST['year_month_code'] ) && !empty($_POST['year_month_code'])){
			$year_month_code = $_POST['year_month_code'];
			$year = substr($year_month_code,0,4);
			$month = substr($year_month_code,-2);
			
			$sql = "select * from core_days 
					where ".$year."=year(date) and ".(int)$month."=month(date) 
					order by date asc";
		   $ses_sql = mysqli_query($db,$sql);
			$rowcount = mysqli_num_rows($ses_sql);
			if($rowcount>0){
				echo '<table class="table">';
				echo '<thead>';
				echo '<tr bgcolor="4169E1" style="color: white; text-align: center;">';
				echo '	<td>วันที่</td>';
				echo '	<td>วัน</td>';
				echo '	<td>ประเภทวัน</td>';
				echo '	<td>หมายเหตุ</td>';
				echo '	<td>#</td>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';				
				while($r = mysqli_fetch_array($ses_sql)) {	
					$dayOfWeek = '';
					switch(date('l', strtotime($r['date']))){
						case 'Monday' : $dayOfWeek='วันจันทร์'; break;
						case 'Tuesday' : $dayOfWeek='วันอังคาร'; break;
						case 'Wednesday' : $dayOfWeek='วันพุธ'; break;
						case 'Thursday' : $dayOfWeek='วันพฤหัสบดี'; break;
						case 'Friday' : $dayOfWeek='วันศุกร์'; break;
						case 'Saturday' : $dayOfWeek='วันเสาร์'; break;
						case 'Sunday' : $dayOfWeek='วันอาทิตย์'; break;
					}
					//echo "1";
					switch((int)$r['date_type_code']){
						case 1 : echo '<tr bgcolor=#ff8080>'; break;
						case 2 : echo '<tr bgcolor=#ff3333>'; break;
						default : echo '<tr>'; break;
					}
					echo '	<td>
							<input type="hidden" name="hid_date" value="'.$r['date'].'" />
							'.to_thaiyear_short_date($r['date']).'</br>
							</td>';
					echo '	<td>'.$dayOfWeek.'</td>';
					echo '	<td>
								<select name="sl_date_type_code">
									<option value="0"'.($r['date_type_code']==0 ? 'selected' : '').'>วันทำงาน</option>
									<option value="1"'.($r['date_type_code']==1 ? 'selected' : '').'>วันหยุดที่ ติด วันทำงาน</option>
									<option value="2"'.($r['date_type_code']==2 ? 'selected' : '').'>วันหยุดที่ไม่ ติด วันทำงาน</option>
								</select>
							';
					echo '	<td>
								<input type="textbox" name="txt_remark" value="'.$r['remark'].'" />
							</td>';
					echo '	<td>
								<a class="btn btn-primary" name="btn_submit_row" >บันทึก</a>
							</td>';
					echo '</tr>';
				}	
				echo '</tbody>';
				echo '</table>';
			}
		   	
		}			
	?>	
</form>


</div><!--container-->
</body>
</html>




<script>
$(document).ready(function(){
	$('a[name=btn_submit_row]').click(function(){		
		$btn = $(this);
		var params = {
			date: $(this).closest("tr").find('td:eq(0) input[name="hid_date"]').val(),
			date_type_code: $(this).closest("tr").find('td:eq(2) select option:selected').val(),
			remark: $(this).closest("tr").find('td:eq(3) input[name="txt_remark"]').val()
		};
		$.ajax({
			url: "create_holiday_submit.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				console.log(data);
				json = JSON.parse(data);
				json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
				if(json.success){
					//alert('บันทึกเรียบร้อย');
					$btn.html('เรียบร้อย').fadeOut('fast').removeClass('btn-primary').addClass('btn-success').fadeIn('slow');										
				}else{
					$btn.html('ผิดพลาด').fadeOut('fast').removeClass('btn-primary').addClass('btn-danger').fadeIn('slow');
					alert(json.msg);
				}					
			}, //success
			error:function(){
				alert('error');
			}   
		}); 
	});
});
</script>	