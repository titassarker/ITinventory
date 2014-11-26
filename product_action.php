<?php

if(isset($_POST['submit']) 
	&& !empty($_POST['pid']) 
	&& !empty($_POST['quantity']) 
	&& !empty($_POST['action_type'])){
	
	$product_id = $_POST['pid'];
	$quantity = intval($_POST['quantity']);
	$action_type = $_POST['action_type'];
	$comment = $_POST['action_comment'];
	$user_id = $_POST['uid'];
	$transaction_time = $_POST['transaction_date'];
	if(empty($transaction_time)) $transaction_time = date('Y-m-d');

	/* create select string to fetch values for chacking */

	$select_string = "SELECT * FROM products WHERE product_id = {$product_id} LIMIT 1";

	/* create transaction entry */
	$transaction_string = "INSERT INTO transaction (product_id, user_id, transaction_time, quantity, type, comment) VALUES";
	$transaction_string .= "({$product_id}, {$user_id}, '{$transaction_time}', {$quantity}, '{$action_type}', '{$comment}')";

	/* create update string */
	$update_string = "UPDATE products ";
	$update_string .= "SET ";
	if($action_type==="purchase"){
		$update_string .= " stock_count = stock_count + ".$quantity;
	} else {
		$update_string .= " stock_count = stock_count - ".$quantity;
		$update_string .= " , consumed_count = consumed_count + ".$quantity;
	}
	$update_string .= " WHERE product_id = {$product_id} ";

	include('./connect.php');

	$valid_update = true;

	// check update validity
	$product_info = $conn->query($select_string)->fetch_assoc();
	if(!empty($product_info)){
		$temp_stock = $product_info['stock_count'];
		$temp_consumed = $product_info['consumed_count'];

		if($action_type==="consume" && $quantity > $temp_stock){
			$valid_update = false;
		}
	}

	if ($valid_update){
		$conn->query($update_string);
		$conn->query($transaction_string);
	}

	include('./close.php');
	header('Location: showProduct.php?pid='.$product_id);

} else {
	$message = "<div class=\"error\">Check out parameters</div>";
}

echo $message;

?>