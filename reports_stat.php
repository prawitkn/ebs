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
	<?php include 'inc_helper.php'; ?>
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
	

<h3>รายงานสถิติเวรรักษาความปลอดภัย</h3>
 <form action = "reports_stat.php" method = "post" class="form-horizontal">
 
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
		 <label for="year_month_code" class="control-label col-md-2">ห้วงวันที่</label>
		 <div class="col-md-4">
			<?php 
				echo '<input type="textbox" class="form-control"  data-type="tdate" name="date_from" id="txt_date_from"  placeholder="" data-original-title="ระบุวันที่เป็น พ.ศ. (dd/mm/yyyy) เช่น 28/02/2556" value="';
				if( isset($_POST['date_from']) && !empty($_POST['date_from']) ){					
					echo $_POST['date_from'];
				}else{
					echo get_current_thai_date();
				}
				echo '"  />';
				?>				
		 </div>	 
		 <div class="col-md-1">
			ถึง
		 </div>	
		 <div class="col-md-4">			
			<?php 
				echo '<input type="textbox" class="form-control"  data-type="tdate" name="date_to" id="txt_date_to"  placeholder="" data-original-title="ระบุวันที่เป็น พ.ศ. (dd/mm/yyyy) เช่น 28/02/2556" value="';
				if( isset($_POST['date_to']) && !empty($_POST['date_to']) ){					
					echo $_POST['date_to'];
				}else{
					echo get_current_thai_date();
				}
				echo '"  />';
				?>	
		 </div>	
	 </div> 
	
	 
	  <div class="form-group">
		 <label for="check" class="control-label col-md-2"></label>
		 <div class="col-md-2">		
			<input name="check" type="submit" class="btn btn-primary" Value="ค้นหา" />
		 </div>
	 </div>
</form>


<form method="post" action="">
	<?php
		if( isset($_POST['building_code']) && !empty($_POST['building_code']) && isset($_POST['date_from']) && !empty($_POST['date_from']) && isset($_POST['date_to']) && !empty($_POST['date_to']) ){
			
			$building_code = $_POST['building_code'];
			$date_from = $_POST['date_from'];
			$date_to = $_POST['date_to'];
			$date_from = to_mysql_date($date_from);
			$date_to = to_mysql_date($date_to);
			
						
			
			$sql = "
					select y.mid, cp.title_abb_name_surname as fullname, 
					sum(workday) as sumWorkday,
					sum(holiday) as sumHoliday,
					sum(total) as sumTotal
					from 
						(select x.date, mid, 
						(case when cd.date_type_code = 0 then 1 ELSE 0 END) as workday,
						(case when cd.date_type_code != 0 then 1 ELSE 0 END) as holiday,
						1 as total
						from (
							select  date, mid
							from rtarfwen_t_duty_details  
							where mid <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							union 
							select  date, mid2 as mid
							from rtarfwen_t_duty_details  
							where mid2 <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							union 
							select  date, mid3 as mid
							from rtarfwen_t_duty_details  
							where mid3 <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							union 
							select  date, mid4 as mid
							from rtarfwen_t_duty_details  
							where mid4 <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							union 
							select  date, mid5 as mid
							from rtarfwen_t_duty_details  
							where mid5 <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							union 
							select  date, mid6 as mid
							from rtarfwen_t_duty_details  
							where mid6 <> 0
							and hdr_id in (select id from rtarfwen_t_duty_headers where status='C' and building_code=".$building_code.")
							) as x
						left join core_days cd on x.date=cd.date
						group by x.mid
						) as y 
					left join core_persons cp on y.mid=cp.id
					group by y.mid, cp.title_abb_name_surname


					";

			
			//$sql .= 'order by a.year_month_code desc ';
			//$sql .= 'limit 100 ';
			//echo $sql;
		   $ses_sql = mysqli_query($db,$sql);
		   $rowcount=mysqli_num_rows($ses_sql);
			
			//echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
		   
		   if($rowcount>0){
				$tmp = mysqli_fetch_row($ses_sql);
			
				echo '<div class="row" style="text-align: right;">
							<a class="btn btn-primary" href="prints_stat_xls.php?building_code='.$building_code.'&date_from='.str_replace("/",".",$date_from).'&date_to='.str_replace("/",".",$date_to).'" >Excel</a>
						</div>
							';
			   echo '<table id="tbl_main" class="table">';
			   echo '<thead>
						<tr  bgcolor="4169E1" style="color: white; text-align: center;">					
							<td>ลำดับ</td>
							<td>ยศ ชื่อ นามสกุล</td>
							<td>จำนวนครั้ง วันปฏิบัติงาน</td>
							<td>จำนวนครั้ง วันหยุด</td>
							<td>รวม</td>
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
							'.$icount.'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['fullname'].'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['sumWorkday'].'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['sumHoliday'].'
							</td>';
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.$r['sumTotal'].'
							</td>';							
					echo '</tr>';
					$icount +=1;
				}
				echo '</table>';
		   }else{
			   echo '<div style="color: red; text-align: center;">
							ไม่พบข้อมูล				
						</div>';
		   }//rowcount>0			
		}//if isset POST
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
	});
</script>