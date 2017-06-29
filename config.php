<?php
   define('DB_SERVER', 'localhost');
   define('DB_USERNAME', 'daginterdb');
   define('DB_PASSWORD', 'P8gws6K');
   define('DB_DATABASE', 'daginterdb');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
   mysqli_set_charset($db,"utf8");
   
?>