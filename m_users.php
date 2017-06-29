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
	</script>
</head>
<body>

	<?php include('nav_top.php'); ?>


<h3>จัดการผู้ใช้งานระบบ</h3>

<a id="btn_add" class="btn btn-primary" >เพิ่มผู้ใช้ระบบใหม่</a>

<form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
	<?php
			
			$sql = "select a.*,
					b.title_abb_name_surname as fullname,
					c.name as building_name,
					d.name as org_name 
					from rtarfwen_m_users a
					left join core_persons b on a.username=b.id
					left join rtarfwen_m_buildings c on a.building_code=c.code
					left join rtarfwen_m_orgs d on a.org_code=d.code 
					";
		   $ses_sql = mysqli_query($db,$sql);
			$rowcount = mysqli_num_rows($ses_sql);
			if($rowcount>0){
				$icount = 1;
				echo '<table class="table">';
				echo '<thead>';
				echo '<tr>';
				echo '	<td style="text-align: center; font-weight: bold;">ลำดับ</td>';
				echo '	<td style="text-align: center; font-weight: bold;">ชื่อผู้ใช้<br/>(username)</td>';
				echo '	<td style="text-align: center; font-weight: bold;">ยศ-ชื่อ-นามสกุล</td>';
				echo '	<td style="text-align: center; font-weight: bold;">อาคาร</td>';
				echo '	<td style="text-align: center; font-weight: bold;">หน่วย</td>';
				echo '	<td style="text-align: center; font-weight: bold;">เจ้าหน้าที่หลัก</td>';
				echo '	<td style="text-align: center; font-weight: bold;">ผู้ตรวจถูกต้อง</td>';
				echo '	<td style="text-align: center; font-weight: bold;">เจ้าหน้าที่<br/>กสบ.สบ.ทหาร</td>';
				echo '	<td style="text-align: center; font-weight: bold;">สถานะ</td>';
				echo '	<td style="text-align: center; font-weight: bold;">#</td>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';				
				while($r = mysqli_fetch_array($ses_sql)) {	
					echo '<tr>';
					echo '	<td style="text-align: center;">'.$icount.'</td>';
					echo '	<td style="text-align: center;">'.$r['username'].'</td>';
					echo '	<td>'.$r['fullname'].'</td>';
					echo '	<td style="text-align: center;">'.$r['building_name'].'</td>';
					echo '	<td style="text-align: center;">'.$r['org_name'].'</td>';
					echo '	<td style="text-align: center;">
								'.($r['is_building_major']==1 ? 'ใช่' : 'ไม่ใช่').'</td>
							';
					echo '	<td style="text-align: center;">
								'.($r['is_checker']==1 ? 'ใช่' : 'ไม่ใช่').'</td>
							';
					echo '	<td style="text-align: center;">
								'.($r['is_administrator']==1 ? 'ใช่' : 'ไม่ใช่').'</td>
							';
					echo '	<td style="text-align: center;">
								'.($r['status']==1 ? 'เปิด' : 'ปิด').'</td>
							';// mysql 1=true, 0=false
					echo '	<td style="text-align: center;">
								<a class="btn btn-primary" name="btn_row_edit" data-id="'.$r['id'].'" >แก้ไข</a>
								<a class="btn btn-danger" name="btn_row_delete" data-id="'.$r['id'].'" >ลบ</a>
							</td>';
					echo '</tr>';
					$icount+=1;
				}	
				echo '</tbody>';
				echo '</table>';
			}		
	?>	
</form>


</div><!--container-->
</body>
</html>




<script>
$(document).ready(function(){
	$('#btn_add').click(function(){
		post_to_url('m_users_add.php', {'back_url': 'm_users.php', 'is_update': '0' ,'id': '0'});
	});
	$('a[name=btn_row_edit]').click(function(){
		$id = $(this).attr('data-id');
		post_to_url('m_users_add.php', {'back_url': 'm_users.php', 'is_update': '1' ,'id': $id});
	});
	$('a[name=btn_row_delete]').click(function(){	
		var t = $(this);
		var tmpId=$(this).attr('data-id');
		var tmpMid=$(this).closest("tr").find('td:eq(1)').text();
		var tmpName=$(this).closest("tr").find('td:eq(2)').text();
		if(confirm('คุณต้องการที่จะลบ \n\n'+tmpMid+'\n'+tmpName+'\n\n ?')){
			var params = {
				id: tmpId
			};			
			$.ajax({
				url: "m_users_delete_ajax.php",
				type: "post",
				data: params,
				datatype: 'json',
				success: function(data){
					json = JSON.parse(data);
					json = JSON.parse(json);	//Must parse for 2 time to get JSON object.	
					if(json.success){
						alert('เรียบร้อย');
						$(t).parents("tr").fadeOut();
					}else{
						alert(json.msg);
					}					
				}, //success
				error:function(){
					alert('error');
				}   
			}); 
		}// confirm
		
	});// btn_row_delete click
});
</script>	