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
</head>
<body>	
	<?php include('nav_top.php'); ?>
	<?php
		$sql = '
				select *
				from  rtarfwen_m_users 
				where username='.$_SESSION['username']
				;
		$ses_sql = mysqli_query($db,$sql);
		$rowcount=mysqli_num_rows($ses_sql);
		$usr='';
		if($rowcount == 1){
		   $usr = mysqli_fetch_array($ses_sql);	
		}else{
		   echo 'error';
		}
	?>
	

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
	 <label for="building_code" class="control-label col-md-2">อาคาร</label>
	 <div class="col-md-4">
		<select name="building_code" id="sl_building_code" class="form-control">
			<option value="">Please Select</option>
			<?php
		   $ses_sql = mysqli_query($db,"select * from rtarfwen_m_buildings ");
		   
		   while($r = mysqli_fetch_array($ses_sql)) {
			   $selected = '';
			   if($_POST['building_code'] == $r['code']){
				   $selected = "selected";
			   }
			  echo '<option value="'.$r['code'].'" '.$selected.'>'.$r['name'].'</option>';
				//echo "1";
			}
			?>
		</select>
	 </div>	 
 </div>
 
  <div class="form-group">
	 <label for="check" class="control-label col-md-2"></label>
	 <div class="col-md-2">		
		<input name="check" type="submit" class="btn btn-primary" Value="Submit" />
	 </div>
 </div>
</form>


<form method="post" action="review.php">
	<?php
			//$building_code = $_POST['building_code'];
			
			//$year_month_code = $_POST['year_month_code'];
			//$year = substr($year_month_code,0,4);
			//$month = (int)substr($year_month_code,4);
			
			//$year_month_code = $_POST['year_month_code'];
			//$year = substr($year_month_code,0,4);
			//$month = (int)substr($year_month_code,4);
						
			//$orgs = mysqli_query($db,"select * from rtarfwen_m_orgs where building_code=$building_code");
			
			
			//$orgs = mysqli_query($db,"select * from core_orgs");			
			$sql = '
					select a.*,
					b.name as building_name,
					c.name as year_month_name,
					d.name as status_name
					from rtarfwen_t_duty_headers a
					left join rtarfwen_m_buildings b on a.building_code=b.code
					left join core_years_months c on a.year_month_code=c.code 
					left join rtarfwen_m_status d on a.status=d.code ';
			if($usr['building_code'] <> 0){
				$sql .= 'where a.building_code='.$usr['building_code'].' ';
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
						<tr>
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
								<input type="hidden" name="hid_building_code" value="'.$r['building_code'].'">
								<a class="btn btn-primary" name="btn_org_print_pdf" data-id="'.$r['id'].'" >พิมพ์เฉพาะหน่วย (pdf)</a>
								<a class="btn btn-primary" name="btn_print_pdf" data-id="'.$r['id'].'" >พิมพ์ (pdf)</a>
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
		//location.href = 'prints.php?hdr_id=' + $hdr_id;
		var print_url = '';
		$building_code = $(this).prev().val();
		switch($building_code){
			case '1' :
			case '9' : print_url = 'prints1.php?hdr_id=';	//บก.ทท. 	สน.บก.ทท.
					break;
			case '3' : print_url = 'prints2.php?hdr_id=';
					break;
			case '4' : 
			case '5' :
			case '6' : print_url = 'prints3.php?hdr_id=';
					break;
			case '7' : print_url = 'prints4.php?hdr_id=';	//สส.ทหาร
					break;
			case '8' : print_url = 'prints5.php?hdr_id=';	//อาคารสันติภาพ
					break;
			case '10' : print_url = 'prints6.php?hdr_id=';	//สนพ.
					break;
		}
		window.open(print_url + $hdr_id,'_blank');
	});	
	$('a[name=btn_org_print_pdf]').click(function(){
		$hdr_id = $(this).attr('data-id');
		//location.href = 'prints.php?hdr_id=' + $hdr_id;
		var print_url = '';
		$building_code = $(this).prev().val();
		switch($building_code){
			case '1' :
			case '9' : print_url = 'prints_org1.php?hdr_id=';	//บก.ทท. 	สน.บก.ทท.
					break;
			case '3' : print_url = 'prints_org2.php?hdr_id=';
					break;
			case '4' : 
			case '5' : 
			case '6' : print_url = 'prints_org3.php?hdr_id=';
					break;
			case '7' : print_url = 'prints_org4.php?hdr_id=';	//สส.ทหาร
					break;
			case '8' : print_url = 'prints_org5.php?hdr_id=';	//อาคารสันติภาพ
					break;
			case '10' : print_url = 'prints_org6.php?hdr_id=';	//สนพ.
					break;
		}
		window.open(print_url + $hdr_id,'_blank');
	});	
});
</script>