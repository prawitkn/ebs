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

$building_code=0;
if(isset($_GET['building_code']) && !empty($_GET['building_code'])){
	$building_code = $_GET['building_code'];
}else{
	if($user_building_code==0){
		$building_code = 1;
	}else{
		$building_code=$user_building_code;
	}
}




include('config.php');
include('inc_helper.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
	
	<script src="assets\underscore-min.js"></script>
	
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
	</script>
</head>
<body>

	<?php include('nav_top.php'); ?>
	<?php
		$result = mysqli_query($db,"select * from rtarfwen_m_buildings where code='".$building_code."' ");
		$r='';
		if($result){
			$r = mysqli_fetch_array($result);
		}	
	?>

<h3>กำหนดรายชื่อ รปภ. <?php echo $r['name']; ?></h3>
 <form action = "" method = "post" class="form-horizontal">
 

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
				   $selected = '';
				   if(isset($_POST['year_month']) && !empty($_POST['year_month'])){
					   if($_POST['year_month'] == $r['code']){
						   $selected = "selected";
					   }
				   }
				  echo '<option value="'.$r['code'].'" data-year="'.$r['year'].'" data-month="'.$r['month'].'" '.$selected.' >'.$r['name'].'</option>';
					//echo "1";
				}
			?>

			</select>
			<?php echo '<input type="hidden" name="building_code" id="hid_building_code" value="'. $building_code .'">'; ?>
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
			
			
			
			//$orgs = mysqli_query($db,"select * from core_orgs");			
			$sql = '
					select a.id, a.status, 
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
			
			echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
		   
		   if($rowcount>0){
				//echo '<a id="btn_submit" class="btn btn-primary" >บันทึกทั้งหมด</a>';				
				echo '<a id="btn_review_by_org" class="btn btn-primary" >แสดงตัวอย่าง แบบเฉพาะวันที่ที่หน่วยรับผิดชอบ</a>&nbsp;';
				echo '<a id="btn_print_by_org" class="btn btn-success" >พิมพ์ แบบเฉพาะวันที่ที่หน่วยรับผิดชอบ</a>&nbsp;';
				echo '<a id="btn_review" class="btn btn-primary" >แสดงตัวอย่าง แบบเต็มเดือน</a>&nbsp;';
				echo '<a id="btn_print" class="btn btn-success" >พิมพ์ แบบเต็มเดือน</a>';			
				
				$hdr = mysqli_fetch_array($ses_sql);
				echo '<input type="hidden" id="hid_hdr_id" value="'.$hdr['id'].'" />';
				
			   echo '<table id="tbl_main" class="table">';
			   echo '<thead>
						<tr bgcolor="4169E1" style="color: white; text-align: center;">
							<td>วันที่</td>							
							<td>นายทหารผู้ใหญ่</td>
							<td>นายทหารเวร</td>
							<td>ผู้ช่วยนายทหารเวร</td>
							<td id="td_position_name_5">เสมียนเวร</td>
							<td id="td_position_name_6">นายทหารเวรประชาสัมพันธ์</td>
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
					echo '	<td style="width: 100px; overflow: hidden;">
							<input type="hidden" name="hid_date" value="'.$r['date'].'" />
							'.to_thaiyear_short_date($r['date']).'</br>
							<span style="font-weight: bold">'.$r['remark'].'</span>
							</td>';
					echo '	<td>'.$r['org_abb'].'</br>
							<input type="hidden" id="hid_set_no" value="1" />
							<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code'].'" />';					
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid-'.(string)$icount.'" value="'.$r['mid'].'" />
								<input type="text" id="txt_name-'.(string)$icount.'" class="txt_name" value="'.$r['fullname'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no-'.(string)$icount.'" value="'.$r['mobile_phone_no'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname'].'<br/>'
								.$r['mobile_phone_no'].'
								</td>';
					}
					
					//2
					echo '	<td>'.$r['org_abb2'].'<br/>
								<input type="hidden" id="hid_set_no" value="2" />
								<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code2'].'" />';
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code2'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid2-'.(string)$icount.'" value="'.$r['mid2'].'" />
								<input type="text" id="txt_name2-'.(string)$icount.'" class="txt_name" value="'.$r['fullname2'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no2-'.(string)$icount.'" value="'.$r['mobile_phone_no2'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname2'].'<br/>'
								.$r['mobile_phone_no2'].'
								</td>';
					}
					
					//3
					echo '	<td>'.$r['org_abb3'].'<br/>
								<input type="hidden" id="hid_set_no" value="3" />
								<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code3'].'" />';
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code3'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid3-'.(string)$icount.'" value="'.$r['mid3'].'" />
								<input type="text" id="txt_name3-'.(string)$icount.'" class="txt_name" value="'.$r['fullname3'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no3-'.(string)$icount.'" value="'.$r['mobile_phone_no3'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname3'].'<br/>'
								.$r['mobile_phone_no3'].'
								</td>';
					}
					
					//4
					echo '	<td>'.$r['org_abb4'].'<br/>
								<input type="hidden" id="hid_set_no" value="4" />
								<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code4'].'" />';
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code4'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid4-'.(string)$icount.'" value="'.$r['mid4'].'" />
								<input type="text" id="txt_name4-'.(string)$icount.'" class="txt_name" value="'.$r['fullname4'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no4-'.(string)$icount.'" value="'.$r['mobile_phone_no4'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname4'].'<br/>'
								.$r['mobile_phone_no4'].'
								</td>';
					}
					
					//5
					echo '	<td>'.$r['org_abb5'].'<br/>
								<input type="hidden" id="hid_set_no" value="5" />
								<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code5'].'" />';
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code5'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid5-'.(string)$icount.'" value="'.$r['mid5'].'" />
								<input type="text" id="txt_name5-'.(string)$icount.'" class="txt_name" value="'.$r['fullname5'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no5-'.(string)$icount.'" value="'.$r['mobile_phone_no5'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname5'].'<br/>'
								.$r['mobile_phone_no5'].'
								</td>';
					}
					
					//6
					echo '	<td>'.$r['org_abb6'].'<br/>
								<input type="hidden" id="hid_set_no" value="6" />
								<input type="hidden" id="hid_org_code-'.(string)$icount.'" value="'.$r['org_code6'].'" />';
					if($user_is_administrator or ($hdr['status'] == 'B' and $r['org_code6'] == $user_org_code) ){
						echo '<input type="hidden" id="hid_mid6-'.(string)$icount.'" value="'.$r['mid6'].'" />
								<input type="text" id="txt_name6-'.(string)$icount.'" class="txt_name" value="'.$r['fullname6'].'" /><br/>
								<input type="text" id="txt_mobile_phone_no6-'.(string)$icount.'" value="'.$r['mobile_phone_no6'].'" /><br/>
								<a class="btn btn-primary" name="btn_submit_cell" >บันทึก</a>&nbsp;
								<a class="btn btn-primary" name="btn_submit_cell_remove" >ลบ</a>&nbsp;
								</td>';
					}else{
						echo $r['fullname6'].'<br/>'
								.$r['mobile_phone_no6'].'
								</td>';
					}
					
					echo '</tr>';
					$icount +=1;
				}
				echo '</table>';
				//echo '<a id="btn_submit" class="btn btn-primary" >บันทึกทั้งหมด</a>';
		   }else{
				echo '<div style="color: red; text-align: center;">
							ผู้ดูแลระบบยังไม่สร้างตารางข้อมูลประจำวันของเดือนที่ท่านเลือก				
						</div>';
		   }//rowcount>0		   
		}// issetPOST
	?>
</form>






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
				<label for="year_month" class="control-label col-md-2">ชื่อ - นามสกุล</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="txt_search_fullname" />
				</div>
			</div>
		
		<table id="tbl_search_person_main" class="table">
			<thead>
				<tr bgcolor="4169E1" style="color: white; text-align: center;">
					<td>การปฏิบัติ</td>
					<td>เลขประจำตัวทหาร ๑๐ หลัก</td>
					<td>ยศ ชื่อ นามสกุล</td>
					<td>หมายเลขมือถือ</td>
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
	
$(document).ready(function(){
	$('.txt_name').click(function(){
		//prev() and next() count <br/> too.		
		curSlOrgCode = $(this).prev().prev().val();	
		curHidMid = $(this).prev().attr('id');				
		curTxtFullName = $(this).attr('id');			
		curTxtMobilePhoneNo = $(this).next().next().attr('id');
		$('#modal_search_person').modal('show');
		//alert(curHidMid+' '+curSlOrgCode+' '+curTxtFullName+' ' +curTxtMobilePhoneNo);
		
	});	
	$('#txt_search_fullname').keyup(function(e){
		if(e.keyCode == 13)
		{
			var params = {
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
										'<td>'+ v[2] +'</td>' +
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
		
	switch($("#hid_building_code").val()){
		case '1' :
		case '9' : 	//บก.ทท. 	สน.บก.ทท.
					$('#tbl_main td:nth-child(6)').hide();	
					$('#tbl_main td:nth-child(7)').hide();		
				break;
		case '3' : 	
					$('#tbl_main td:nth-child(4)').hide();
					$('#tbl_main td:nth-child(6)').hide();	
					$('#tbl_main td:nth-child(7)').hide();		
				break;
		case '4' : 
		case '5' :
		case '6' : $('#tbl_main td:nth-child(4)').hide();					
				break;
		case '7' : $('#tbl_main td:nth-child(4)').hide();	//สส.ทหาร
					$('#td_position_name_6').html('นายทหารเวร (หญิง)');
					$('#td_position_name_7').html('เสมียรเวร (หญิง)');
				break;
		case '8' : $('#tbl_main td:nth-child(3)').hide();	//อาคารสันติภาพ
				   $('#tbl_main td:nth-child(4)').hide();
				   $('#tbl_main td:nth-child(6)').hide();
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
	
	
	$('#btn_review').click(function(){
		post_to_url('review.php', {'back_url': 'set_duty_fixed_org_new.php', 'year_month_code': $('#sl_year_month').val(),'building_code': $("#hid_building_code").val()});
	});
	$('#btn_review_by_org').click(function(){
		$building_code=$('#hid_building_code').val();
		post_to_url('review_by_org.php', {'back_url': 'set_duty_fixed_org_new.php?building_code='+$building_code, 'year_month_code': $('#sl_year_month').val(),'building_code': $building_code });
	});
	$('#btn_review_org').click(function(){
		post_to_url('review2.php', {'back_url': 'set_duty_fixed_org_new.php', 'year_month_code': $('#sl_year_month').val(),'building_code': $("#hid_building_code").val()});
	});
	$('#btn_print_by_org').click(function(){
		$building_code=$('#hid_building_code').val();
		$hdr_id = $('#hid_hdr_id').val();
		//location.href = 'prints.php?hdr_id=' + $hdr_id;
		var print_url = '';
		switch($building_code){
			case '1' :
			case '9' : print_url = 'prints_org1.php?pnk=0&hdr_id=';	//บก.ทท. 	สน.บก.ทท.
					break;
			case '3' : print_url = 'prints_org2.php?pnk=0&hdr_id=';
					break;
			case '4' : 
			case '5' : 
			case '6' : print_url = 'prints_org3.php?pnk=0&hdr_id=';
					break;
			case '7' : print_url = 'prints_org3.php?pnk=0&hdr_id=';	//สส.ทหาร
					break;
			case '8' : print_url = 'prints_org5.php?pnk=0&hdr_id=';	//อาคารสันติภาพ
					break;
			case '10' : print_url = 'prints_org6.php?pnk=0&hdr_id=';	//สนพ.
					break;
		}
		window.open(print_url + $hdr_id,'_blank');
	});
	$('#btn_print').click(function(){
		$building_code=$('#hid_building_code').val();
		$hdr_id = $('#hid_hdr_id').val();
		var print_url = '';
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
			case '7' : print_url = 'prints3.php?hdr_id=';	//สส.ทหาร
					break;
			case '8' : print_url = 'prints5.php?hdr_id=';	//อาคารสันติภาพ
					break;
			case '10' : print_url = 'prints6.php?hdr_id=';	//สนพ.
					break;
		}
		window.open(print_url + $hdr_id,'_blank');
	});
	$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
		
		$('#'+curHidMid).val($(this).closest("tr").find('td:eq(1)').text());
		$('#'+curTxtFullName).val($(this).closest("tr").find('td:eq(2)').text());
		$('#'+curTxtMobilePhoneNo).val($(this).closest('tr').find('td:eq(3)').text());		
		$('#modal_search_person').modal('hide');
	});
	
	$('a[name=btn_submit_row]').click(function(){
		//$('#'+curHidMid).val($(this).closest("tr").find('td:eq(1)').text());
		//var tr = $(this).closest("tr");
		//alert(tr.html());
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
		
		var arrDuty = new Array(31);
		
		var icount = 0;
		//$('#tbl_main > tbody  > tr').each(function() {
			var pr = {
				date: '',
				org_code: '',
				org_name: '',
				mid: null,
				fullname: '',
				mobile_no: '',
				org_code2: '',
				org_name2: '',
				mid2: null,
				fullname2: '',
				mobile_no2: '',
				org_code3: '',
				org_name3: '',
				mid3: null,
				fullname3: '',
				mobile_no3: '',
				org_code4: '',
				org_name4: '',
				mid4: null,
				fullname4: '',
				mobile_no4: '',
				org_code5: '',
				org_name5: '',
				mid5: 'null',
				fullname5: '',
				mobile_no5: '',
				org_code6: '',
				org_name6: '',
				mid6: 'null',
				fullname6: '',
				mobile_no6: ''
			};
			pr.date = $(this).closest("tr").find('td:eq(0)').text();
			pr.org_code = $(this).closest("tr").find('td:eq(1) input[id^="hid_org_code"]').val();
			pr.org_name = $(this).closest("tr").find('td:eq(1)').text();
			pr.mid = $(this).closest("tr").find('td:eq(1) input[id^="hid_mid"]').val();
			pr.fullname = $(this).closest("tr").find('td:eq(1) input[id^="txt_name"]').val();
			pr.mobile_no = $(this).closest("tr").find('td:eq(1) input[id^="txt_mobile_phone_no"]').val();
			
			pr.org_code2 = $(this).closest("tr").find('td:eq(2) input[id^="hid_org_code"]').val();
			pr.org_name2 = $(this).closest("tr").find('td:eq(2)').text();
			pr.mid2 = $(this).closest("tr").find('td:eq(2) input[id^="hid_mid"]').val();
			pr.fullname2 = $(this).closest("tr").find('td:eq(2) input[id^="txt_name"]').val();
			pr.mobile_no2 = $(this).closest("tr").find('td:eq(2) input[id^="txt_mobile_phone_no"]').val();
			
			pr.org_code3 = $(this).closest("tr").find('td:eq(3) input[id^="hid_org_code"]').val();
			pr.org_name3 = $(this).closest("tr").find('td:eq(3)').text();
			pr.mid3 = $(this).closest("tr").find('td:eq(3) input[id^="hid_mid"]').val();
			pr.fullname3 = $(this).closest("tr").find('td:eq(3) input[id^="txt_name"]').val();
			pr.mobile_no3 = $(this).closest("tr").find('td:eq(3) input[id^="txt_mobile_phone_no"]').val();
			
			pr.org_code4 = $(this).closest("tr").find('td:eq(4) input[id^="hid_org_code"]').val();
			pr.org_name4 = $(this).closest("tr").find('td:eq(4)').text();
			pr.mid4 = $(this).closest("tr").find('td:eq(4) input[id^="hid_mid"]').val();
			pr.fullname4 = $(this).closest("tr").find('td:eq(4) input[id^="txt_name"]').val();
			pr.mobile_no4 = $(this).closest("tr").find('td:eq(4) input[id^="txt_mobile_phone_no"]').val();
			arrDuty[icount] = pr;
			icount = icount + 1;
		//}); 	//foreach
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code,
			arrDuty: arrDuty
		};
		$.ajax({
			url: "set_duty_fixed_org_submit.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				console.log(data);
				json = JSON.parse(data);
				json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
				if(json.success){
					alert('บันทึกเรียบร้อย');
				}else{
					alert(json.msg);
				}					
			}, //success
			error:function(){
				alert('error');
			}   
		}); 
	});
	$('a[name=btn_submit_cell]').click(function(){
		$btn = $(this);
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
	
		$date = $(this).closest("tr").find('input[name="hid_date"]').val();		
		
		$set_no = $(this).closest("td").find('input[id^="hid_set_no"]').val();
		$org_code = $(this).closest("td").find('input[id^="hid_org_code"]').val();
		$mid = $(this).closest("td").find('input[id^="hid_mid"]').val();
		$fullname = $(this).closest("td").find('input[id^="txt_name"]').val();
		$mobile_no = $(this).closest("td").find('input[id^="txt_mobile_phone_no"]').val();		
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code,
			date: $date,
			set_no: $set_no,
			org_code: $org_code,
			mid: $mid,
			fullname: $fullname,
			mobile_no: $mobile_no
		};
		//alert('hdr_id:'+params.hdr_id+', '+'year_month_code:'+params.year_month_code+', '+'building_code:'+params.building_code+', '+'date:'+params.date+', '+'set_no:'+params.set_no+', '+'org_code:'+params.org_code+', '+'mid:'+params.mid+', '+'fullname:'+params.fullname+', '+'mobile_no:'+params.mobile_no+', ');
		$.ajax({
			url: "set_duty_fixed_org_cell_submit.php",
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
	$('a[name=btn_submit_cell_remove]').click(function(){
		$btn = $(this);
		$hdr_id = $('#hid_hdr_id').val();
		$year_month_code = $('#sl_year_month').val();
		$building_code = $("#hid_building_code").val();
	
		$date = $(this).closest("tr").find('input[name="hid_date"]').val();		
		
		$set_no = $(this).closest("td").find('input[id^="hid_set_no"]').val();
		$org_code = $(this).closest("td").find('input[id^="hid_org_code"]').val();
		$mid = $(this).closest("td").find('input[id^="hid_mid"]').val();
		$fullname = $(this).closest("td").find('input[id^="txt_name"]').val();
		$mobile_no = $(this).closest("td").find('input[id^="txt_mobile_phone_no"]').val();		
		
		var params = {
			hdr_id: $hdr_id,
			year_month_code: $year_month_code,
			building_code: $building_code,
			date: $date,
			set_no: $set_no,
			org_code: $org_code,
			mid: $mid,
			fullname: $fullname,
			mobile_no: $mobile_no
		};
		//alert('hdr_id:'+params.hdr_id+', '+'year_month_code:'+params.year_month_code+', '+'building_code:'+params.building_code+', '+'date:'+params.date+', '+'set_no:'+params.set_no+', '+'org_code:'+params.org_code+', '+'mid:'+params.mid+', '+'fullname:'+params.fullname+', '+'mobile_no:'+params.mobile_no+', ');
		$.ajax({
			url: "set_duty_fixed_org_cell_submit_remove.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				console.log(data);
				json = JSON.parse(data);
				json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
				if(json.success){
					//alert('บันทึกเรียบร้อย');
					$btn.html('ลบเรียบร้อย').fadeOut('fast').removeClass('btn-primary').addClass('btn-success').fadeIn('slow');										
				}else{
					$btn.html('ลบผิดพลาด').fadeOut('fast').removeClass('btn-primary').addClass('btn-danger').fadeIn('slow');
					alert(json.msg);
				}					
			}, //success
			error:function(){
				alert('error');
			}   
		});
	});
	
</script>		
