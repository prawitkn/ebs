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
include('inc_helper.php');

?>
<html>
<head>
	<?php include('header.php'); ?>
	<script src="assets\underscore-min.js"></script><!-- for ajax and loop $result from mysql -->
</head>
<body>

	<?php include('nav_top.php'); ?>


<h3>เส้นทางการปฏิบัติการส่งรายชื่อเวร</h3>

	<?php
		if(isset($_POST['year_month_code']) && !empty($_POST['year_month_code']) && isset($_POST['building_code']) && !empty($_POST['building_code'])){
			$building_code = $_POST['building_code'];			
			$year_month_code = $_POST['year_month_code'];
			
			if(!$user_is_administrator){
				if($building_code <> $user_building_code){
					header( "refresh: 0; url=access_denied.php" );
					exit;
				}
			}	
			//$year = substr($year_month_code,0,4);
			//$month = (int)substr($year_month_code,4);
		
			$sql = '
					select a.id, a.status, a.remark as hdr_remark, 
					b.*,
					c.name as "org_abb", c2.name as "org_abb2", c3.name as "org_abb3", c4.name as "org_abb4", c5.name as "org_abb5", c6.name as "org_abb6",
					d.date_type_code, d.remark
					
					from rtarfwen_t_duty_headers a
					inner join rtarfwen_t_duty_details b on a.id=b.hdr_id
					left join core_days d on b.date=d.date
					left join rtarfwen_m_orgs c on b.org_code=c.code
					left join rtarfwen_m_orgs c2 on b.org_code2=c2.code
					left join rtarfwen_m_orgs c3 on b.org_code3=c3.code
					left join rtarfwen_m_orgs c4 on b.org_code4=c4.code
					left join rtarfwen_m_orgs c5 on b.org_code5=c5.code
					left join rtarfwen_m_orgs c6 on b.org_code6=c6.code
					
					where a.year_month_code='.$year_month_code.' and a.building_code='.$building_code.'
					
					order by b.date asc					
					';
		   $ses_sql = mysqli_query($db,$sql);
		   $rowcount=mysqli_num_rows($ses_sql);		
		   $hdr = mysqli_fetch_array($ses_sql);	
		   
			echo '<input type="hidden" id="hid_back_url" value="'.$_POST['back_url'].'" />';
			echo '<input type="hidden" id="hid_year_month_code" name="year_month_code" value="'.$_POST['year_month_code'].'" />';			
			echo '<input type="hidden" id="hid_building_code" name="building_code" value="'.$_POST['building_code'].'" />';
?>

			<div class="row" style="text-align: right;">
				<?php
					switch($hdr['status']){
						case 'S': echo '<h3 style="color: blue;">สถานะ : ยืนยันข้อมูลเรียบร้อย</h3>'; break;
						case 'V': echo '<h3 style="color: blue;">สถานะ : ตรวจถูกต้องเรียบร้อย</h3>'; break;
						case 'C': echo '<h3 style="color: green;">สถานะ : หัวหน้าส่วนอนุมัติเรียบร้อย</h3>'; break;
						default: echo '<h3 style="color: red;">สถานะ : เริ่มต้น</h3>'; break;	//B
					}
				?>
				<p style="color: red;">
				<?php
				echo (trim($hdr['hdr_remark'])=="" ? 'หมายเหตุ : -' : 'หมายเหตุ :&nbsp;'.$hdr['hdr_remark'] );
				?>
				</p>
			</div><!-- row-->
			<?php
				$sql = "
						select
						b.org_code,
						d.name as org_name,
						b.status_code,
						case b.status_code 
							when 'B' then 'เริ่มต้น'
							when 'S' then 'ยืนยันข้อมูลแล้ว'
							when 'V' then 'ตรวจถูกต้องแล้ว'
							when 'C' then 'บันทึกอนุมัติแล้ว'
						end as status_name,
						b.remark
						from rtarfwen_t_duty_headers a
						inner join rtarfwen_t_duty_orgs b on a.id=b.hdr_id
						left join rtarfwen_m_orgs d on b.org_code=d.code					
						where a.year_month_code=".$year_month_code." and a.building_code=".$building_code."
						
						order by b.id, b.org_code asc					
						";
			   $result_sub = mysqli_query($db,$sql);	
			   $sub = mysqli_fetch_array($result_sub);		
			   $i = 0;
				while($r = mysqli_fetch_array($result_sub)) {
					echo ($i==0?'':'<label style="color: black; font-weight: bold;">&nbsp;/&nbsp;</label>');
					switch($r['status_code']){
						case 'S': echo '<label style="color: blue; font-weight: bold;">'; break;
						case 'V': echo '<label style="color: blue; font-weight: bold;">'; break;
						case 'C': echo '<label style="color: green; font-weight: bold;">'; break;
						default: echo '<label style="color: red; font-weight: bold;">'; break;	//B
					}				
					echo $r['org_name'].' '.$r['status_name'];
					echo '</label>';
					$i+=1;
				}
			?>
<div class="row" style="">

<ul id="myTab" class="nav nav-tabs">																																													
<li class="active"><a href="#t01" data-toggle="tab">การปฏิบัติ</a></li>
<?PHP 				
		if( $hdr['status']=='B' and ($user_is_administrator or $user_is_building_major) ){
			echo '<li><a href="#t02" data-toggle="tab">ยืนยันการบันทึกข้อมูลทั้งหมด</a></li>';
		}
		if( $hdr['status']=='S' and ($user_is_administrator or $user_is_building_major or $user_is_checker) ){
			echo '<li><a href="#t03" data-toggle="tab">ส่งกลับเพื่อแก้ไข</a></li>';
			echo '<li><a href="#t04" data-toggle="tab">ตรวจถูกต้อง</a></li>';
		}
		if( $hdr['status']=='V' and ($user_is_administrator or $user_is_building_major or $user_is_checker) ){
			echo '<li><a href="#t05" data-toggle="tab">อนุมัติ</a></li>';
		}
?>		
</ul>	

																																																
<div class="tab-content">	
<div id="t01" class="tab-pane active" style="margin: 5px">	
	<a id="btn_back" class="btn btn-primary" >กลับ</a>																																																	
</div>												
<?php
if( $hdr['status']=='B' ){
	?>
	<div id="t02" class="tab-pane" style="margin: 5px">	
		<div class="row" style="padding-bottom: 5px">
			<div class="col-md-2" style="font-weight: bold; text-align: right;">ยศ ชื่อ นามสกุล</div>
			<div class="col-md-4">
				<input type="text" class="form-control txt_name" id="txt_verify_fullname" />
			</div>
		</div>
		<div class="row" style="padding-bottom: 5px">
			<div class="col-md-2" style="font-weight: bold; text-align: right;">ตำแหน่ง</div>
			<div class="col-md-4">
				<textarea  class="form-control"id="txt_verify_position" ></textarea>
			</div>
		</div>
		<div class="row" style="padding-bottom: 5px">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<a id="btn_submit" class="btn btn-primary" >บันทึก ยืนยันการบันทึกข้อมูลทั้งหมด</a>
			</div>
		</div>
	</div>
	<?php
}

if($hdr['status']=='S'){
	?>
	<div id="t03" class="tab-pane" style="margin: 5px">		
		<div class="row" style="padding-bottom: 5px">
			<div class="col-md-2" style="font-weight: bold; text-align: right;">หมายเหตุ</div>
			<div class="col-md-4">
				<input type="text"  class="form-control" id="txt_remark" />
			</div>
		</div>
		<div class="row" style="padding-bottom: 5px">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<a id="btn_verify_no" class="btn btn-danger" >บันทึก การส่งกลับเพื่อแก้ไข</a>
			</div>
		</div>	
	</div>		
			
	<div id="t04" class="tab-pane" style="margin: 5px">																																																	
		<a id="btn_verify_yes" class="btn btn-success" >บันทึก การตรวจถูกต้อง</a>	
	</div>
<?php
}

if( $hdr['status']=='V'  ){
	?>
	<div id="t05" class="tab-pane" style="margin: 5px">																																													
		<a id="btn_complete" class="btn btn-success" >บันทึก การอนุมัติ</a>	
	</div>	
<?php
}
?>
																																		
		

	
</div><!-- tab-content -->


</div><!-- row -->			
			

			
<?php
		   if($rowcount>0){				
				
				echo '<input type="hidden" id="hid_hdr_id" value="'.$hdr['id'].'" />';
			   echo '<table id="tbl_main" class="table">';
			   echo '<thead>
						<tr bgcolor="4169E1" style="color: white; text-align: center;">
							<td style="width: 100px!; overflow: hidden;">วันที่</td>							
							<td>นายทหารผู้ใหญ่</td>
							<td>นายทหารเวร</td>
							<td>ผู้ช่วยนายทหารเวร</td>
							<td id="td_position_name_5">เสมียนเวร</td>
							<td>นายทหารเวรประชาสัมพันธ์</td>
							<td id="td_position_name_7">เสมียนเวรประชาสัมพันธ์</td>
						</tr>
					</thead>';
			   mysqli_data_seek($ses_sql,0);
				$icount = 1;
			   while($r = mysqli_fetch_array($ses_sql)) {
					switch((int)$r['date_type_code']){
					   case 1 : echo '<tr bgcolor=#ff8080>'; break;
					   case 2 : echo '<tr bgcolor=#ff3333>'; break;
					   default : echo '<tr>';
					}
					echo '	<td style="width: 100px!; overflow: hidden;">
							'.to_thaiyear_short_date($r['date']).'</br>
							<span style="font-weight: bold">'.$r['remark'].'</span>
							</td>';
							//1
					echo '	<td>'.$r['org_abb'].'</br>
							'.$r['fullname'].'</br>
							'.$r['mobile_phone_no'].'
							</td>';
							//2
					echo '	<td>'.$r['org_abb2'].'</br>
							'.$r['fullname2'].'</br>
							'.$r['mobile_phone_no2'].'
							</td>';
							//3
					echo '	<td>'.$r['org_abb3'].'</br>
							'.$r['fullname3'].'</br>
							'.$r['mobile_phone_no3'].'
							</td>';
							//4
					echo '	<td>'.$r['org_abb4'].'</br>
							'.$r['fullname4'].'</br>
							'.$r['mobile_phone_no4'].'
							</td>';
							//5
					echo '	<td>'.$r['org_abb5'].'</br>
							'.$r['fullname5'].'</br>
							'.$r['mobile_phone_no5'].'
							</td>';
							//6
					echo '	<td>'.$r['org_abb6'].'</br>
							'.$r['fullname6'].'</br>
							'.$r['mobile_phone_no6'].'
							</td>';
					echo '</tr>';
					$icount +=1;
				}
				echo '</table>';
		   }//rowcount>0			
		}// issetPOST
	?>





<!-- Modal -->
<div id="modal_search_person" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ค้นหารายชื่อกำลังพล</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
			<div class="form-group">	
				<label for="year_month" class="control-label col-md-2">หน่วย</label>
				<div class="col-md-4">
					<select name="year_month" id="sl_search_org_code" class="form-control">
						<option value="">- กรุณาเลือก -</option>
						<?php
						   $ses_sql = mysqli_query($db,"select * from rtarfwen_m_orgs");						   
						   while($r = mysqli_fetch_array($ses_sql)) {
							  echo '<option value="'.$r['code'].'" >'.$r['name'].'</option>';					
							}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">	
				<label for="year_month" class="control-label col-md-2">ชื่อ - นามสกุล</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="txt_search_fullname" />
				</div>
			</div>
		
		<table id="tbl_search_person_main" class="table">
			<thead>
				<tr>
					<td>#</td>
					<td></td>
					<td>ยศ ชื่อ นามสกุล</td>
					<td>ตำแหน่ง</td>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		</form>
		<div id="div_search_person_result">
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">ไม่เลือกข้อมูล และปิด</button>
      </div>
    </div>

  </div>
</div>






</div><!--container-->
</body>
</html>





<script>
	var curSlOrgCode = "";
	var curHidMid = "";
	var curTxtFullName = "";
	var curTxtMobilePhoneNo = "";
	
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
		//
		switch($("#hid_building_code").val()){
			case '1' :
			case '9' : 	$('#tbl_main td:nth-child(6)').hide();	//บก.ทท. 	สน.บก.ทท.
						$('#tbl_main td:nth-child(7)').hide();		
					break;
			case '3' : 	$('#tbl_main td:nth-child(4)').hide();
						$('#tbl_main td:nth-child(6)').hide();	
						$('#tbl_main td:nth-child(7)').hide();		
					break;
			case '4' : 
			case '5' :
			case '6' : $('#tbl_main td:nth-child(4)').hide();
					break;
			case '7' : $('#tbl_main td:nth-child(4)').hide();	//สส.ทหาร
					break;
			case '8' : $('#tbl_main td:nth-child(3)').hide();	//อาคารสันติภาพ
					   $('#tbl_main td:nth-child(4)').hide();
					   $('#tbl_main td:nth-child(6)').hide();
					   $('#td_position_name_5').html('เวรรักษาความปลอดภัย');
					   $('#td_position_name_7').html('เวรประชาสัมพันธ์');
					break;
			case '10' : $('#tbl_main td:nth-child(2)').hide();	//สนพ.
						$('#tbl_main td:nth-child(4)').hide();
						$('#tbl_main td:nth-child(6)').hide();
						$('#tbl_main td:nth-child(7)').hide();
					break;					
		}
		
	});
	//txt_verify_fullname
	$('.txt_name').click(function(){
		//prev() and next() count <br/> too.
		/*curHidMid = $(this).prev().attr('id');
		curSlOrgCode = $(this).prev().prev().val();
		curTxtFullName = $(this).attr('id');			
		curTxtMobilePhoneNo = $(this).next().next().attr('id');*/
		
		$('#sl_search_org_code').val(curSlOrgCode);
		$('#modal_search_person').modal('show');
	});	
	$('#txt_search_fullname').keyup(function(e){
		if(e.keyCode == 13)
		{
			var params = {
				search_org_code: $('#sl_search_org_code').val(),
				search_fullname: $('#txt_search_fullname').val()
			};
			if(params.search_fullname.length < 3){
				alert('search name surname must more than 3 character.');
				return false;
			}
			/* Send the data using post and put the results in a div */
			  $.ajax({
				  url: "ajax_search_person_by_org_code_and_fullname.php",
				  type: "post",
				  data: params,
				datatype: 'json',
				  success: function(data){	
								$('#tbl_search_person_main tbody').empty();
							 _.each($.parseJSON(data), function(v){
								$('#tbl_search_person_main tbody').append(
									'<tr>' +
										'<td>' +
										'	<div class="btn-group">' +
										'	<a href="javascript:void(0);" data-name="search_person_btn_checked" ' +
										'	class="btn" title="เลือก"> ' +
										'	<i class="glyphicon glyphicon-ok"></i> เลือก</a> ' +
										'	</div>' +
										'</td>' +
										'<td>'+ v[0] +'</td>' +
										'<td>'+ v[1] +'</td>' +
										'<td>'+ v[3] +'</td>' +
									'</tr>'
								);			
							});
							
				  }, //success
				  error:function(){
					  alert('error');
				  }   
				}); 
		}/* e.keycode=13 */	
	});
	$('#btn_back').click(function(){
		var back_url = $('#hid_back_url').val();
		var year_month_code = $('#hid_year_month_code').val();
		var building_code = $('#hid_building_code').val();
		post_to_url(back_url, {'year_month_code': year_month_code,'building_code': building_code});
	});
	$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
		//$('#'+curHidMid).val($(this).closest("tr").find('td:eq(1)').text());
		//$('#'+curTxtFullName).val($(this).closest("tr").find('td:eq(2)').text());
		//$('#'+curTxtMobilePhoneNo).val($(this).closest('tr').find('td:eq(3)').text());
		
		
		$('#txt_verify_fullname').val($(this).closest("tr").find('td:eq(2)').text());
		$('#txt_verify_position').val($(this).closest("tr").find('td:eq(3)').text());
		$('#modal_search_person').modal('hide');
	});
	$('#btn_submit').click(function(){
		
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
		$verify_fullname = $("#txt_verify_fullname").val();
		$verify_position = $("#txt_verify_position").val();
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code,
			verify_fullname: $verify_fullname,
			verify_position: $verify_position
		};
		
		if(confirm('คุณต้องการ ยืนยันการบันทึกข้อมูลทั้งหมด ใช่หรือไม่ ?')){
			$.ajax({
				url: "hdr_submit_duty_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					console.log(data);
					json = JSON.parse(data);
					json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
					if(json.success){
						alert('บันทึกเรียบร้อย');
						location.reload();
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
	$('#btn_verify_no').click(function(){
		
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
		$remark = $("#txt_remark").val();
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code,
			remark: $remark
		};
		
		if(confirm('คุณต้องการ ส่งกลับข้อมูล ใช่หรือไม่ ?')){
			$.ajax({
				url: "hdr_verify_no_duty_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					console.log(data);
					json = JSON.parse(data);
					json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
					if(json.success){
						alert('บันทึกเรียบร้อย');
						location.reload();
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
	$('#btn_verify_yes').click(function(){
		
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code
		};
		
		if(confirm('คุณต้องการ ตรวจถูกต้อง ใช่หรือไม่ ?')){
			$.ajax({
				url: "hdr_verify_yes_duty_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					console.log(data);
					json = JSON.parse(data);
					json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
					if(json.success){
						alert('บันทึกเรียบร้อย');
						location.reload();
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
	//btn_complete
	$('#btn_complete').click(function(){
		
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code
		};
		
		if(confirm('คุณต้องการ ยืนยันหัวหน้าส่วนอนุมัติ ใช่หรือไม่ ?')){
			$.ajax({
				url: "hdr_complete_duty_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					console.log(data);
					json = JSON.parse(data);
					json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
					if(json.success){
						alert('บันทึกเรียบร้อย');
						location.reload();
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
</script>