<?php
		session_start();
		include('config.php');

		$username = $_SESSION['username'];	
		$pwold = $_POST['pwold'];
		$pwnew = $_POST['pwnew'];
		
		$sql = '';
		
		$result = mysqli_query($db, "update core_persons set passw0rd=md5('$pwnew') where id=$username and passw0rd=md5('$pwold') ");
		
		$json = '';		
		if (mysqli_affected_rows($result)==1) {
			$json = '{"success": true}';
		} else {
			$json = '{"success": false, "msg": "Error: "' . $sql . '<br> . '.mysqli_error($db).'" }';
		}
		
		echo json_encode($json);
					
			
?>