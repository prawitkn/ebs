<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   unset($_SESSION["user_building_code"]);
   unset($_SESSION["user_org_code"]);
   unset($_SESSION["user_is_building_major"]);
   unset($_SESSION["user_is_checker"]);
   unset($_SESSION["user_is_administrator"]);
   echo '<html>
   <header>
   <title>E-DAG : ระบบส่งรายชื่อเวรรักษาความปลอดภัยประจำเดือน</title>
   <meta http-equiv=Content-Type content="text/html; charset=utf-8">
   <h1 style="color: red;">คุณออกจากระบบเรียบร้อยแล้ว<h1>
   <h3 style="color: blue;">ระบบกำลังไปที่หน้าเข้าสู่ระบบ...<h3>
   </html>';
   header('Refresh: 2; URL = login.php');
?>

