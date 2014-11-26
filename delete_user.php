<?php

include('./connect.php');

if(!empty($_GET['uid'])){
	$user_id = $_GET['uid'];
	$conn->query("DELETE FROM user WHERE user_id = {$user_id}");

	$conn->query("DELETE FROM transaction WHERE user_id = {$user_id}");	
}

header('Location: userList.php');

include('./close.php');

?>