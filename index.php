<?php
	include "./myCAPTCHAPrivateLib.php";
	#Connect to database server
	mysql_connect("localhost", "tiny_reCAPTCHA", "12345678") or die(mysql_error());
	#select database:myCAPRCHA
	mysql_select_db("myCAPTCHA") or die(mysql_error());
	if ($_POST['op'] == 'check' && isset($_POST['challenge']) && isset($_POST['session_id']) && isset($_POST['user_ip']) && isset($_POST['server_ip'])){
		if(isset($_POST['challenge'])){
			$temp_challenge = $_POST['challenge'];
		}else{
			die("challenge is not specified");
		}

		if(isset($_POST['session_id'])){
			$session_id = $_POST['session_id'];
		}else{
			die("session_id is not specified");
		}

		if(isset($_POST['user_ip']) && $_POST['user_ip'] != ''){
			$user_ip = $_POST['user_ip'];
		}else{
			die("IP is not specified");
		}

		if(isset($_POST['server_ip'])){
			$server_ip = $_POST['server_ip'];
		}else{
			die("IP is not specified");
		}


		$sql_text = "select * from inform where user_ip='$user_ip' AND challenge='$temp_challenge' AND session_id='$session_id' AND server_ip='$server_ip'";

		$result = mysql_query($sql_text);
		if(!$result){
			die(mysql_error());
		}
		
		if (mysql_num_rows($result) != 0){
			$c_result = "True";
		}else{
			$c_result = "False";
		}
		mysql_free_result($result);
		$sql_text = "delete from inform where user_ip='$user_ip' AND session_id='$session_id' AND server_ip='$server_ip'";
		$result = mysql_query($sql_text);
		if(!$result){
			die(mysql_error());
		}
		echo $c_result;
	}else{
		$user_ip = $_GET['user_ip'];
		$server_ip = $_GET['server_ip'];
		$session_id = $_GET['session_id'];
		$temp_challenge = generate_random_string();

		$sql_text = "select * from inform where user_ip='$user_ip' AND session_id='$session_id' AND server_ip='$server_ip'";
		$result = mysql_query($sql_text);
		
		if (mysql_num_rows($result) != 0){
			$sql_text = "update inform set challenge='$temp_challenge' where user_ip='$user_ip' AND session_id='$session_id' AND server_ip='$server_ip'";
		}else{
			$sql_text = "insert into inform (user_ip, challenge, session_id, server_ip) values ('$user_ip','$temp_challenge','$session_id', '$server_ip')";
		}
		mysql_query($sql_text) or die(mysql_error());
		generate_image($temp_challenge);
	}
	mysql_close();
?>
