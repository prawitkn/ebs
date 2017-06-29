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


<h3>กำหนดหน่วยเข้าเวร อาคาร บก.ทท.</h3>
 <form action = "blinding_mapping_orgs.php" method = "post" class="form-horizontal">
 

 <input type="hidden" value="1" name="bank_from" />
 <input type="hidden" value="1" name="bank_to" />
 <div class="form-group">
	 <label for="year_month" class="control-label col-md-2">เดือน</label>
	 <div class="col-md-4">
		<select name="year_month" id="sl_year_month" class="form-control">
						<option value="">Please Select</option>
			<?php
				   
				   
				   $ses_sql = mysqli_query($db,"select * from core_years_months order by year, month asc");
				   
				   while($r = mysqli_fetch_array($ses_sql)) {
					  echo '<option value="'.$r['code'].'" '.($_POST['year_month']==$r['code']?'selected':'').' >'.$r['name'].'</option>';
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

<form method="post" action="blinding_mapping_orgs_submit.php">
	<?php
		if(isset($_POST['year_month']) && !empty($_POST['year_month'])){			
			$year_month_code = $_POST['year_month'];
			$year = substr($year_month_code,0,4);
			$month = (int)substr($year_month_code,4);
			
			
			$org_all = mysqli_query($db,"select * from rtarfwen_m_orgs ");
			$orgs = mysqli_query($db,"select * from rtarfwen_m_orgs where building_code=1");			
			
		   $ses_sql = mysqli_query($db,"select * from rtarfwen_t_duty_headers where year_month_code='$year_month_code' and building_code=1 ");
		   $rowcount=mysqli_num_rows($ses_sql);			
		
		   if($rowcount>0){
			   $hdr = mysqli_fetch_array($ses_sql);
			   	echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
				echo '<input type="hidden" name="building_code" value="1">';
				echo '<input type="hidden" name="hdr_id" id="hid_hdr_id" value="'.$hdr['id'].'">';
			   echo '<table class="table">';
			   echo '<thead>
						<tr bgcolor="4169E1" style="color: white; text-align: center;">
							<td>วันที่</td>							
							<td>นายทหารผู้ใหญ่</td>
							<td>นายทหารเวร</td>
							<td>ผู้ช่วยนายทหารเวร</td>
							<td>เสมียนเวร</td>
							<td>การปฏิบัติ</td>
						</tr>
					</thead>';
					
			   $sql = '
						select a.*,
						b.name as year_month_name,
						x.id as hdr_id, x.status as hdr_status,
						y.org_code, y.mid, y.fullname, y.mobile_phone_no,
						y.org_code2, y.mid2, y.fullname2, y.mobile_phone_no2,
						y.org_code3, y.mid3, y.fullname3, y.mobile_phone_no3,
						y.org_code4, y.mid4, y.fullname4, y.mobile_phone_no4,
						y.org_code5, y.mid5, y.fullname5, y.mobile_phone_no5,
						y.org_code6, y.mid6, y.fullname6, y.mobile_phone_no6

						from rtarfwen_t_duty_headers x
						inner join rtarfwen_t_duty_details y on x.id=y.hdr_id 
						inner join core_years_months b on x.year_month_code = b.code 
						inner join core_days a on y.date=a.date
						
						where 1=1
						and x.year_month_code='.$year_month_code.'
						and x.building_code=1 
						order by y.date asc
						';
			   $ses_sql = mysqli_query($db,$sql);
			   
				$icount = 1;
			   while($r = mysqli_fetch_array($ses_sql)) {
					$tmp_holiday = "";
					switch((int)$r['date_type_code']){
					   case 1 : echo '<tr bgcolor=#ff8080>'; break;
					   case 2 : echo '<tr bgcolor=#ff3333>'; break;
					   default : echo '<tr>';
					}
				  echo '	<td>
								<input type="hidden" name="hid_date" value="'.$r['date'].'" />
								'.to_thaiyear_short_date($r['date']).'</br>
								<span style="font-weight: bold">'.$r['remark'].'</span>
							</td>';
				  echo '	<td>
								<select name="org_code-'. (string)$icount .'">';
									echo '<option value=""> -- เลือก --</option>';
								while($x = mysqli_fetch_array($org_all)) {
								  echo '<option value="'.$x['code'].'" ';
									if($x['code'] == $r['org_code']){ echo ' selected="selected" '; }
								  echo '>'.$x['name'].'</option>';
								}
								mysqli_data_seek($org_all,0);
					echo '		</select>
							</td>';
					echo '	<td>
								<select name="org_code2-'. (string)$icount .'">';
									echo '<option value=""> -- เลือก --</option>';
								while($x = mysqli_fetch_array($orgs)) {
								  echo '<option value="'.$x['code'].'" ';
									if($x['code'] == $r['org_code2']){ echo ' selected="selected" '; }
								  echo '>'.$x['name'].'</option>';
								}
								mysqli_data_seek($orgs,0);
					echo '		</select>
							</td>';
					echo '	<td>
								<select name="org_code3-'. (string)$icount .'">';	
									echo '<option value=""> -- เลือก --</option>';
								while($x = mysqli_fetch_array($orgs)) {
								  echo '<option value="'.$x['code'].'" ';
									if($x['code'] == $r['org_code3']){ echo ' selected="selected" '; }
								  echo '>'.$x['name'].'</option>';
								}
								mysqli_data_seek($orgs,0);
					echo '		</select>
							</td>';
					echo '	<td>
								<select name="org_code4-'. (string)$icount .'">';
									echo '<option value=""> -- เลือก --</option>';
								while($x = mysqli_fetch_array($orgs)) {
								  echo '<option value="'.$x['code'].'" ';
									if($x['code'] == $r['org_code4']){ echo ' selected="selected" '; }
								  echo '>'.$x['name'].'</option>';
								}
								mysqli_data_seek($orgs,0);
					echo '		</select>
							</td>';
					echo '	<td>
								'.($hdr['status'] == 'C' ? '<a href="#" class="btn btn-primary" disabled >บันทึก</a>' : '<a href="#" class="btn btn-primary" name="btn_submit_row" >บันทึก</a>').'										
							</td>
						</tr>';
					$icount +=1;
				}// loop while
				echo '</table>';
		   }else{
			   echo '<div style="color: red; text-align: center;">
							ผู้ดูแลระบบยังไม่สร้างตารางข้อมูลประจำวันของเดือนที่ท่านเลือก				
						</div>';
		   }//result row count;
		   
		   //echo '<input type="submit" class="btn btn-primary" Value="บันทึก" />';
		}// isset post	
	?>
	
</form>


</div><!--container-->
</body>
</html>



<script>
$(document).ready(function(){
	$('a[name=btn_submit_row]').click(function(){		
		$tr = $(this);
		var params = {
			hdr_id: $('#hid_hdr_id').val(),
			date: $(this).closest("tr").find('td:eq(0) input[name="hid_date"]').val(),
			org_code: $(this).closest("tr").find('td:eq(1) select option:selected').val(),
			org_code2: $(this).closest("tr").find('td:eq(2) select option:selected').val(),
			org_code3: $(this).closest("tr").find('td:eq(3) select option:selected').val(),
			org_code4: $(this).closest("tr").find('td:eq(4) select option:selected').val(),
			org_code5: '',
			org_code6: ''
		};
		$.ajax({
			url: "blinding_mapping_orgs_row_submit.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){				
				console.log(data);
				json = JSON.parse(data);
				json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
				if(json.success){
					//alert('บันทึกเรียบร้อย');
					$tr.html('เรียบร้อย').fadeOut('fast').removeClass('btn-primary').addClass('btn-success').fadeIn('slow');										
				}else{
					$tr.html('ผิดพลาด').fadeOut('fast').removeClass('btn-primary').addClass('btn-danger').fadeIn('slow');
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