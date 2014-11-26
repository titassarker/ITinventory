<?php

include('./connect.php');

if(!empty($_GET['pid'])){
	$prod_id = $_GET['pid'];
	$conn->query("DELETE FROM products WHERE product_id = {$prod_id}");

	$conn->query("DELETE FROM transaction WHERE product_id = {$prod_id}");	
}

header('Location: productList.php');

include('./close.php');

?>