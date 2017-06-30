<?php
session_start();

include('config.php');
?>
<html>
<head>
<title>E-DAG : ระบบส่งรายชื่อเวรรักษาความปลอดภัยประจำเดือน</title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<script src="assets\jquery-3.1.1.min.js"></script>

<script src="assets\bootstrap-3.3.7\js\bootstrap.min.js"></script>
<link rel="stylesheet" href="assets\bootstrap-3.3.7\css\bootstrap.min.css">

</head>
	
   <body>
      <div class = "container form-signin">
         
         <?php
            $msg = '';
            
            if (!empty($_POST['username']) 
               && !empty($_POST['password'])) {			
			   
			   //$user_check = $_SESSION['login_user'];
			   $user_name = $_POST['username'];
			   $user_pass = $_POST['password'];
			   
			   $ses_sql = mysqli_query($db,"select id, title_abb_name_surname as fullname from core_persons where id = '$user_name' and passw0rd = MD5('$user_pass') ");
			   
			   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
			   if(!$row){
					$msg = 'ชื่อผู้ใช้หหรือรหัสผ่านไม่ถูกต้อง';
			   }else{
					$_SESSION['username'] = $row['id'];
					$_SESSION['fullname'] = $row['fullname'];
					$_SESSION['valid'] = true;
					$_SESSION['timeout'] = time();
					
					 //getAuthenticaton
					$sql = "select * from rtarfwen_m_users where username='".$row['id']."' ";						
					$result=mysqli_query($db, $sql);	
					if($result){
						$r = mysqli_fetch_array($result);
						$_SESSION['user_building_code'] = $r['building_code'];
						$_SESSION['user_org_code'] = $r['org_code'];
						$_SESSION['user_is_building_major'] = $r['is_building_major'];
						$_SESSION['user_is_checker'] = $r['is_checker'];
						$_SESSION['user_is_administrator'] = $r['is_administrator'];
					}else{
						$_SESSION['user_building_code'] = '';
						$_SESSION['user_org_code'] = '';
						$_SESSION['user_is_building_major'] = '';
						$_SESSION['user_is_checker'] = '';
						$_SESSION['user_is_administrator'] = '';
					}
				   
				   $msg = '';
				   header("Location: /ebs/index.php");
					exit;
			   }
            }else{
				$msg = '';
			}
         ?>
		<div style="text-align: center;">
		<h2 class="form-signin-heading">ระบบส่งรายชื่อเวร รปภ.บก.ทท.</h2>
		</div>
	  <form class="form-signin" action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); 
				?>" method = "post" style="width: 350px; margin: 0 auto;">		
		<h6 class="form-signin-heading" style="color: red;"><?php echo $msg; ?></h6>
		<label for="inputUsername" class="sr-only">Username</label>
		<input id="inputUsername" name="username" class="form-control" placeholder="Username" required="" autofocus="" autocomplete="off">
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="" autocomplete="off">
		<br/>
		<button class="btn btn-lg btn-primary btn-block" type="submit">เข้าสู่ระบบ</button>
	  </form>

	  <!-- On Dev -->
	  <h3 style="color: red; text-decoration: underline;">ชื่อผู้ใช้สำหรับทดสอบระบบ</h3>
	  <?php
			
			$sql = "select a.*,
					b.title_abb_name_surname as fullname,
					c.name as building_name
					from rtarfwen_m_users a
					left join core_persons b on a.username=b.id
					left join rtarfwen_m_buildings c on a.building_code=c.code
					";
		   $ses_sql = mysqli_query($db,$sql);
			$rowcount = mysqli_num_rows($ses_sql);
			if($rowcount>0){
				$icount = 1;
				echo '<table class="table">';
				echo '<thead>';
				echo '<tr>';
				echo '	<td>ลำดับ</td>';
				echo '	<td>ชื่อผู้ใช้<br/>username</td>';
				echo '	<td>ยศ-ชื่อ-นามสกุล</td>';
				echo '	<td>อาคาร</td>';
				echo '	<td>เจ้าหน้าที่หลัก<br/>ประจำอาคาร</td>';
				echo '	<td>เจ้าหน้าที่<br/>กสบ.สบ.ทหาร</td>';
				echo '	<td>สถานะ</td>';
				echo '</tr>';
				echo '</thead>';
				echo '<tbody>';				
				while($r = mysqli_fetch_array($ses_sql)) {	
					echo '<tr>';
					echo '	<td>'.$icount.'</td>';
					echo '	<td>'.$r['username'].'</td>';
					echo '	<td>'.$r['fullname'].'</td>';
					echo '	<td>'.$r['building_name'].'</td>';
					echo '	<td>
								<select name="sl_is_building_major">
									<option value="0"'.($r['is_building_major']==0 ? 'selected' : '').'>ไม่ใช่</option>
									<option value="1"'.($r['is_building_major']==1 ? 'selected' : '').'>ใช่</option>
								</select>
							';
					echo '	<td>
								<select name="sl_is_administrator">
									<option value="0"'.($r['is_administrator']==0 ? 'selected' : '').'>ไม่ใช่</option>
									<option value="1"'.($r['is_administrator']==1 ? 'selected' : '').'>ใช่</option>
								</select>
							';
					echo '	<td>
								<select name="sl_status_code">
									<option value="0"'.($r['status']==0 ? 'selected' : '').'>ไม่ใช้งาน</option>
									<option value="1"'.($r['status']==1 ? 'selected' : '').'>ใช้งาน</option>
								</select>
							';// mysql 1=true, 0=false
					echo '</tr>';
					$icount+=1;
				}	
				echo '</tbody>';
				echo '</table>';
			}		
	?>	
	<!-- On Dev -->
	  
	</div> <!-- /container -->
      
   </body>
</html>