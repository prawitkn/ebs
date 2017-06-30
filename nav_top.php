<?php 
	$sql = "
			select * from rtarfwen_m_buildings where code=".$_SESSION['user_building_code']."  limit 1 						
			";
	$ses_sql = mysqli_query($db,$sql);		
	$t1 = mysqli_fetch_array($ses_sql);
	
	$sql = "
			select * from rtarfwen_m_orgs where code=".$_SESSION['user_org_code']."  limit 1 						
			";
	$ses_sql = mysqli_query($db,$sql);		
	$t2 = mysqli_fetch_array($ses_sql);
?>
<div class="container">
 <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://dag.rtarf.mi.th/index2.php">กรมสารบรรณทหาร</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">ระบบส่งรายชื่อเวรรักษาความปลอดภัยประจำเดือน</a></li>
            <!--<li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>-->
          </ul>
          <ul class="nav navbar-nav navbar-right">
			<li><a href="#" ><label style="color: red;!important "><?php echo 'สิทธิผู้ใช้ : อาคาร '.$t1['name'].',&nbsp;หน่วย '.$t2['name']; ?></label></a></li>
			<li class="dropdown">
              <a href="#" class="dropdown-toggle" style="color: blue;!important " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $fullname; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
				<li><a href="user_change_pw.php">เปลี่ยนรหัสผ่าน</a></li>
				<li role="separator" class="divider"></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
              </ul>
            </li>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
</br></br></br>