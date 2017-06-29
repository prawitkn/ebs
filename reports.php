<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
$user_org_code = $_SESSION['user_org_code'];
$user_building_code = $_SESSION['user_building_code'];
$user_is_building_major = $_SESSION['user_is_building_major'];
$user_is_checker = $_SESSION['user_is_checker'];
$user_is_administrator = $_SESSION['user_is_administrator'];

include('config.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
</head>
<body>	
	<?php include('nav_top.php'); ?>	

<h3>รายงานเวรรักษาความปลอดภัย</h3>
 <form action = "" method = "post" class="form-horizontal">
 

 <input type="hidden" value="1" name="bank_from" />
 <input type="hidden" value="1" name="bank_to" />
 <div class="form-group">
	 <label for="year_month_code" class="control-label col-md-2">เดือน</label>
	 <div class="col-md-4">
		<select name="year_month_code" id="sl_year_month_code" class="form-control">
			<option value="">Please Select</option>
			<?php
		   $ses_sql = mysqli_query($db,"select * from core_years_months order by year, month asc");
		   
		   while($r = mysqli_fetch_array($ses_sql)) {
			   $selected = '';
			   if($_POST['year_month_code'] == $r['code']){
				   $selected = "selected";
			   }
			  echo '<option value="'.$r['code'].'" data-year="'.$r['year'].'" data-month="'.$r['month'].'" '.$selected.'>'.$r['name'].'</option>';
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


<form method="post" action="review.php">
	<?php	
			$sql = '
					select a.*,
					b.name as building_name,
					c.name as year_month_name,
					d.name as status_name
					from rtarfwen_t_duty_headers a
					left join rtarfwen_m_buildings b on a.building_code=b.code
					left join core_years_months c on a.year_month_code=c.code 
					left join rtarfwen_m_status d on a.status=d.code ';
			if($user_is_administrator==0){
				$sql .= 'where a.building_code='.$user_building_code.' ';
			}			
			
			$sql .= 'order by a.year_month_code desc ';
			$sql .= 'limit 100 ';
			
		   $ses_sql = mysqli_query($db,$sql);
		   $rowcount=mysqli_num_rows($ses_sql);
			
			//echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
		   
		   
		   if($rowcount>0){
				$tmp = mysqli_fetch_row($ses_sql);
				echo '<input type="hidden" id="hid_hdr_id" value="'.$tmp[0].'" />';
			   echo '<table id="tbl_main" class="table">';
			   echo '<thead>
						<tr bgcolor="4169E1" style="color: white; text-align: center;">
							<td style="width: 100px!; overflow: hidden;">เดือน/ปี</td>							
							<td>อาคาร</td>
							<td>สถานะ</td>
							<td>
								#
							</td>
						</tr>
					</thead>';
			   mysqli_data_seek($ses_sql,0);
				$icount = 1;
			   while($r = mysqli_fetch_array($ses_sql)) {
					echo '<tr>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['year_month_name'].'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['building_name'].'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['status_name'].'
							</td>';
					echo '	<td>
								<a class="btn btn-primary" name="btn_print_pdf" building-code='.$r['building_code'].' data-id="'.$r['id'].'" >พิมพ์ (pdf)</a>
							</td>';
					echo '</tr>';
					$icount +=1;
				}
				echo '</table>';
		   }else{
			   echo '<div style="color: red; text-align: center;">
							ผู้ดูแลระบบยังไม่สร้างตารางข้อมูลประจำวันของเดือนที่ท่านเลือก				
						</div>';
		   }//rowcount>0			
		
	?>
</form>




</div><!--container-->
</body>
</html>





<script>	
	function post_to_url(url, params) {
		var form = document.createElement('form');
		form.action = url;
		form.method = 'POST';

		for (var i in params) {
			if (params.hasOwnProperty(i)) {
				var input = document.createElement('input');
				input.type = 'hidden';
				input.name = i;
				input.value = params[i];
				form.appendChild(input);
			}
		}
		document.body.appendChild(form);
		form.submit();
	}
	
	$(document).ready(function(){
		$('#btn_review').click(function(){
			post_to_url('review.php', {'back_url': 'set_duty.php', 'year_month_code': $('#sl_year_month_code').val(),'building_code': $("#sl_building_code").val()});
		});
	$('a[name=btn_print_pdf]').click(function(){
		$hdr_id = $(this).attr('data-id');
		$building_code = $(this).attr('building-code');
		
		var print_url = '';		
		switch($building_code){
			case '1' :
			case '9' : print_url = 'prints1.php?pnk=1&hdr_id=';	//บก.ทท. 	สน.บก.ทท.
					break;
			case '3' : print_url = 'prints2.php?pnk=1&hdr_id=';
					break;
			case '4' : 
			case '5' :
			case '6' : print_url = 'prints3.php?pnk=1&hdr_id=';
					break;
			case '7' : print_url = 'prints4.php?pnk=1&hdr_id=';	//สส.ทหาร
					break;
			case '8' : print_url = 'prints5.php?pnk=1&hdr_id=';	//อาคารสันติภาพ
					break;
			case '10' : print_url = 'prints6.php?pnk=1&hdr_id=';	//สนพ.
					break;
		}
		window.open(print_url + $hdr_id,'_blank');
	});	
	});
</script>