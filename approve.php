<?php
session_start();
if(!isset($_SESSION['username'])==true){
	header("Location: /ebs/login.php");
	exit;
}
$fullname = $_SESSION['fullname'];
$building_code = $_SESSION['user_building_code'];
include('config.php');
?>
<html>
<head>
	<?php include('header.php'); ?>
</head>
<body>

	<?php include('nav_top.php'); ?>
	

<h3>ตรวจถูกต้อง เวรรักษาความปลอดภัย</h3>

<form method="post" action="review.php">
	<input type="hidden" id="hid_building_code" value="<?php echo $building_code; ?>" />
	<?php
		$sql = '
				select a.*,
				b.name as building_name,
				c.name as year_month_name
				from rtarfwen_t_duty_headers a
				left join rtarfwen_m_buildings b on a.building_code=b.code
				left join core_years_months c on a.year_month_code=c.code ';
		
		$sql .= 'where 1=1 ';
		if($building_code <> 0){
			$sql .= 'and a.building_code="'.$building_code.'" ';
		}			
		$sql .= 'order by a.year_month_code desc ';
		$sql .= 'limit 100 ';
		
	   $ses_sql = mysqli_query($db,$sql);
	   $rowcount=mysqli_num_rows($ses_sql);
		
		echo '<input type="hidden" name="year_month_code" value="'.$year_month_code.'">';
	   
	   
	   if($rowcount>0){
			$tmp = mysqli_fetch_row($ses_sql);
			echo '<input type="hidden" id="hid_hdr_id" value="'.$tmp[0].'" />';
		   echo '<table id="tbl_main" class="table">';
		   echo '<thead>
					<tr>
						<td style="width: 100px!; overflow: hidden;">เดือน/ปี</td>							
						<td>อาคาร</td>
						<td>สถานะ</td>
						<td>วันเวลาที่ยืนยันรายการ</td>
						<td>
							การปฏิบัติ
						</td>
					</tr>
				</thead>';
		   mysqli_data_seek($ses_sql,0);
			$icount = 1;
		   while($r = mysqli_fetch_array($ses_sql)) {
				echo '<tr>';
				echo '	<td style="width: 100px!; overflow: hidden;">
						<input type="hidden" id="hid_id_'.(string)$icount.'" value="'.$r['id'].'">
						'.$r['year_month_name'].'
						</td>';
				echo '	<td style="width: 100px!; overflow: hidden;">
						'.$r['building_name'].'
						</td>';
				echo '	<td style="width: 100px!; overflow: hidden;">
						'.$r['status'].'
						</td>';
				echo '	<td style="width: 100px!; overflow: hidden;">
						'.$r['update_time'].'
						</td>';
				echo '	<td>
							<a class="btn btn-primary" name="btn_approve" data-id="'.$r['id'].'" >อนุมัติ</a>
							<a class="btn btn-primary" name="btn_print_pdf" data-id="'.$r['id'].'" >พิมพ์ (pdf)</a>
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
	?>
</form>




</div><!--container-->
</body>
</html>





<!-- Modal -->
<div id="modal_search_person" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ตรวจถูกต้อง</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
			<div class="form-group">	
				<label for="txt_search_fullname" class="control-label col-md-2">ชื่อ - นามสกุล</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="txt_fullname" />
				</div>
			</div>
			<div class="form-group">	
				<label for="txt_search_fullname" class="control-label col-md-2">ตำแหน่ง</label>
				<div class="col-md-4">
					<textarea rows="4" cols="50" class="form-control" id="txt_position_name">
					</textarea> 
				</div>
			</div>
			<div class="form-group">	
				<label for="txt_search_fullname" class="control-label col-md-2">รหัสยืนยันการทำงาน</label>
				<div class="col-md-4">
					<input type="text" class="form-control" id="txt_password" />
				</div>
			</div>
		
		
		</form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-default" id="btn_approve_submit" >บันทึก</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
      </div>
    </div>

  </div>
</div>






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
	curId = '';
	$(document).ready(function(){
		$('a[name=btn_approve]').click(function(){
			curId = $(this).attr('data-id');			
			//curTxtMobilePhoneNo = $(this).next().next().attr('id');
			//$id = $(this).closest("tr").find('td:eq(0) input[id^="hid_id_"]').val();
			
			//alert(tmpId);
			//$('#sl_search_org_code').val(curSlOrgCode);
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
		$('#btn_approve_submit').click(function(e){
			if(confirm("ยืนยันการทำงาน...")){
				var params = {
					id: curId
				};
				/*if(params.search_fullname.length < 3){
					alert('search name surname must more than 3 character.');
					return false;
				}*/
				/* Send the data using post and put the results in a div */
				  $.ajax({
					  url: "approve_submit_ajax.php",
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
			}
		});
		$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
			$('#'+curTxtFullName).prev().val($(this).closest("tr").find('td:eq(1)').text());
			$('#'+curTxtFullName).val($(this).closest("tr").find('td:eq(2)').text());
			
			$('#modal_search_person').modal('hide');
		});
		$('#btn_review').click(function(){
			post_to_url('review.php', {'back_url': 'set_duty.php', 'year_month_code': $('#sl_year_month_code').val(),'building_code': $("#sl_building_code").val()});
		});
	$('a[name=btn_print_pdf]').click(function(){
		$hdr_id = $(this).attr('data-id');
		//location.href = 'prints.php?hdr_id=' + $hdr_id;
		var print_url = '';
		switch($('#hid_building_code').val()){
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
	});
</script>