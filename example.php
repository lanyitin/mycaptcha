<?php
	include "./myCAPTCHALib.php";
	session_start();
	$ip = getUserIP();
	$session_id = session_id();
?>
<html>
	<head>
	</head>
	<body>
		<?php
			if (isset($_POST['challenge']) && isset($session_id) && isset($ip)) {
				$result = myCAPTCHA("check", $_POST['challenge'], $ip, $session_id,"pynux.no-ip.info");
				echo $result;
			}else{
				echo '<form method="post" action="">';
				myCAPTCHA("get_image", NULL, $ip, $session_id,"pynux.no-ip.info");
				echo '<input type="submit"/>';
				echo '</form>';
		    }
		?>
	</body>
</html>
