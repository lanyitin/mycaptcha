<?php
	function getUserIP(){ 
		$ip = "127.0.0.1"; 

		if (isset($_SERVER)){ 
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){ 
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) { 
				$ip = $_SERVER["HTTP_CLIENT_IP"]; 
			} else { 
				$ip = $_SERVER["REMOTE_ADDR"]; 
			} 
		}else { 
			if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) { 
				$ip = getenv( 'HTTP_X_FORWARDED_FOR' ); 
			} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) { 
				$ip = getenv( 'HTTP_CLIENT_IP' ); 
			} else { 
				$ip = getenv( 'REMOTE_ADDR' ); 
			} 
		} 
		return $ip; 
	}
	function myCAPTCHA($para_op, $para_challenge, $para_user_ip, $para_session_id,$server_ip){
		if ($para_op == "get_image"){
			echo "<script type='text/javascript'>";
			echo "	function refreshCAPTCHA(){";
			echo "		var now = new Date();";
			echo "		document.getElementById('CHAPTCAH_img').src = 'http://pynux.no-ip.info/myCAPTCHA/?op=get_image&user_ip=$para_user_ip&session_id=$para_session_id&server_ip=$server_ip&t=' + now.getTime();";
			echo "	}";
			echo "</script>";
			echo "<div id='myCAPTCHA'>";
			echo "<img id='CHAPTCAH_img' src='http://pynux.no-ip.info/myCAPTCHA?op=get_image&user_ip=$para_user_ip&session_id=$para_session_id&server_ip=$server_ip'>";
			echo "<button type='button' onclick='refreshCAPTCHA()'>try another one</button><br/>";
			echo '<input type="text" name="challenge"/>';
			echo "</div>";
		}else if ($para_op == "check"){
			$ch = curl_init("pynux.no-ip.info/myCAPTCHA/index.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			$temp_text = "op=check&challenge=$para_challenge&user_ip=$para_user_ip&session_id=$para_session_id&server_ip=$server_ip";
			curl_setopt($ch, CURLOPT_POSTFIELDS, $temp_text);
			$para_result = curl_exec($ch);
			curl_close($ch);
		}else{
			echo "Operation error";
		}
	}
?>
