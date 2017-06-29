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
	
	<script src="assets\underscore-min.js"></script><!-- for ajax and loop $result from mysql -->
</head>
<body>

	<?php include('nav_top.php'); ?>


<h3><?php echo ($_POST['is_update']==0?'เพิ่ม':'ปรับปรุง').'ผู้ใช้งานระบบ'; ?></h3>

<a id="btn_back" class="btn btn-primary" href="m_users.php" >กลับ</a>

<form action ="m_users_add_submit.php" method = "post" class="form-horizontal">
	<?php 
		//if(!isset($_POST['is_update']) or empty($_POST['is_update'])){
		//	echo 'Action lost.';
		//	exit;
		//}
		$sql = "select a.*,
				b.title_abb_name_surname as fullname,
				c.name as building_name
				from rtarfwen_m_users a
				left join core_persons b on a.username=b.id
				left join rtarfwen_m_buildings c on a.building_code=c.code
				where a.id=".$_POST['id']."
				limit 1
				";
		   $result = mysqli_query($db,$sql);
		   $item = mysqli_fetch_array($result);
		   
			
			//$rowcount = mysqli_num_rows($result);
			//if($rowcount>0){
		
	?>
	<input type="hidden" value="<?php echo $_POST['is_update']; ?>" name="is_update" />
	<div class="form-group">
		 <label for="fullname" class="control-label col-md-2">ยศ-ชื่อ-นามสกุล</label> 
		 <div class="col-md-4">
			<input type="hidden" value="<?php echo $item['id']; ?>" name="id" />
			<input type="hidden" value="<?php echo $item['username']; ?>" name="mid" />
			<input type="textbox" class="txt_fullname form-control" name="fullname" id="txt_fullname" value="<?php echo $item['fullname']; ?>" />
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="sl_building_code" class="control-label col-md-2">อาคาร</label>
		 <div class="col-md-4">
			<select name="building_code" id="sl_building_code" class="form-control">
				<?php if($item['building_code'] == '99'){
				   $selected = "selected";
			   }
			   ?>
				<option value="99" <?php echo $selected; ?>>Please Select</option>
				<?php
			   $ses_sql = mysqli_query($db,"select * from rtarfwen_m_buildings");			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				   $selected = '';
				   if($item['building_code'] == $r['code']){
					   $selected = "selected";
				   }
				  echo '<option value="'.$r['code'].'" '.$selected.'>'.$r['name'].'</option>';
				}
				?>
			</select>
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="sl_org_code" class="control-label col-md-2">หน่วย/สังกัด</label>
		 <div class="col-md-4">
			<select name="org_code" id="sl_org_code" class="form-control">
				<?php if($item['org_code'] == ''){
				   $selected = "selected";
			   }
			   ?>
				<option value="99" <?php echo $selected; ?>>Please Select</option>
				<?php
			   $ses_sql = mysqli_query($db,"select * from rtarfwen_m_orgs");			   
			   while($r = mysqli_fetch_array($ses_sql)) {
				   $selected = '';
				   if($item['org_code'] == $r['code']){
					   $selected = "selected";
				   }
				  echo '<option value="'.$r['code'].'" '.$selected.'>'.$r['name'].'</option>';
				}
				?>
			</select>
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="is_building_major" class="control-label col-md-2"></label>
		 <div class="col-md-4">
			<input type="checkbox" name="is_building_major" <?php echo ($item['is_building_major'] ? 'checked' : '' ); ?> > เป็นเจ้าหน้าที่หลักประจำอาคาร
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="is_checker" class="control-label col-md-2"></label>
		 <div class="col-md-4">
			<input type="checkbox" name="is_checker" <?php echo ($item['is_checker'] ? 'checked' : '' ); ?> > เป็นผู้ตรวจถูกต้อง
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="is_administrator" class="control-label col-md-2"></label>
		 <div class="col-md-4">
			<input type="checkbox" name="is_administrator" <?php echo ($item['is_administrator'] ? 'checked' : '' ); ?> > เป็นเจ้าหน้าที่ กสบ.สบ.ทหาร
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="status" class="control-label col-md-2"></label>
		 <div class="col-md-4">
			<input type="checkbox" name="status" <?php echo ($item['status'] ? 'checked' : '' ); ?> > <span style="color: red;">สถานะใช้งาน</span>
		 </div>	 		 
	 </div>
	 <div class="form-group">
		 <label for="chk_status" class="control-label col-md-2"></label>
		 <div class="col-md-4">
			<input type="submit" name="btn_submit" class="btn btn-primary"  value="บันทึก" />
		 </div>	 		 
	 </div>	
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
        <h4 class="modal-title">ค้นหารายชื่อกำลังพล</h4>
      </div>
      <div class="modal-body">
        <div class="form-horizontal">
			<div class="form-group">	
				<label for="txt_search_fullname" class="control-label col-md-2">ชื่อ - นามสกุล</label>
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





<script>
$(document).ready(function(){
	$('.txt_fullname').click(function(){
		//prev() and next() count <br/> too.
		curHidMid = $(this).prev().attr('id');
		curSlOrgCode = $(this).prev().prev().val();
		curTxtFullName = $(this).attr('id');			
		curTxtMobilePhoneNo = $(this).next().next().attr('id');
				
		$('#modal_search_person').modal('show');
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
	$(document).on("click",'a[data-name="search_person_btn_checked"]',function() {
		$('#'+curTxtFullName).prev().val($(this).closest("tr").find('td:eq(1)').text());
		$('#'+curTxtFullName).val($(this).closest("tr").find('td:eq(2)').text());		
		$('#modal_search_person').modal('hide');
	});
	
	$('a[name=btn_row_edit]').click(function(){		
		var params = {
			date: $(this).closest("tr").find('td:eq(0)').text(),
			date_type_code: $(this).closest("tr").find('td:eq(2) select option:selected').val(),
			remark: $(this).closest("tr").find('td:eq(3) input[name="txt_remark"]').val()
		};
		$.ajax({
			url: "create_holiday_submit.php",
			type: "post",
			data: params,
			datatype: 'json',
			success: function(data){
				alert(data.success);
				console.log(data);
				json = JSON.parse(data);
				alert(json.success);
				if(json.success){
					alert('success');
				}else{
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