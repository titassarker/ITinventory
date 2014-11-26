<html>
	<head>
		<title>Purchase Product</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		

		$(function() {
			$.datepicker.setDefaults({
		  		dateFormat: 'yy-mm-dd'
			});

			$( ".datepicker" ).datepicker({ defaultDate: +0 });

			$( ".update_toggler" ).click(function() {
			  	$( "#normal_div" ).toggle();
			  	$( "#update_div" ).toggle();
			});
		});
		</script>
		<style type="text/css">
		.ui-datepicker{ z-index: 9999 !important;}
		</style>
	</head>
	<body>
		<?php
			include('./connect.php');

			$product_id = $_GET['pid'];
			$product_info = null;

			if(!empty($product_id)){
				$select_query = "SELECT * FROM products WHERE product_id = {$product_id} LIMIT 1";
				$product_info = $conn->query($select_query)->fetch_assoc();

				$transaction_fetch_query = "SELECT trans.*, usr.user_name FROM transaction as trans LEFT JOIN user as usr on usr.user_id = trans.user_id WHERE product_id = {$product_id} ORDER BY transaction_time DESC";
				$transactions = $conn->query($transaction_fetch_query);

				$all_user_query = "SELECT * FROM user";
				$users = $conn->query($all_user_query);
			}

			include('./close.php');
		?>
		
		<?php include('./header.php'); ?>

		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div class="panel panel-default">
					  	<div class="panel-heading">
					  		<div id="update_div" style="display: none;">
					  			<form class="form-inline" action="updateProduct.php" method="post">
					  				<input type="hidden" name="pid" value="<?php echo $product_info['product_id']; ?>">
					  				<input name="updated_name" type="text" class="form-control" value="<?php echo $product_info['product_name']; ?>">
					  				<button type="submit" name='submit' value="update" class="btn btn-primary">Update</button>
					  				<button type="button" class="btn btn-default update_toggler">Cancel</button>
					  			</form>
					  		</div>
					  		<div id="normal_div">
						  		<table width="100%" cellspacing="0" border="0px">
						  			<tbody>
						  				<tr>
						  					<td width="50%"><h2><?php echo $product_info['product_name']; ?></h2></td>
						  					<td width="50%" style="text-align: right;padding: 0px">
						  						<a class="btn btn-primary update_toggler"><span class="glyphicon glyphicon-edit"></span>Update</a>
					  							<a title="Delete Product" type="button" href="delete_product.php?pid=<?php echo $product_info['product_id']; ?>" class="btn btn-danger" onClick="return confirm(
	  'Are you sure you want to delete this product?');"><span class="glyphicon glyphicon-remove"></span>Delete</a>
						  						
						  					</td>
						  				</tr>
						  			</tbody>
						  		</table>
					  		</div>
					  	</div>
					  	<div class="panel-body">
					    	<table class="table">
								<tbody>
									<tr>
										<td>Stock Count</td>
										<td><?php echo $product_info['stock_count']; ?></td>
									</tr>
									<tr>
										<td>Consumed Count</td>
										<td><?php echo $product_info['consumed_count']; ?></td>
									</tr>
								</tbody>
							</table>
					  	</div>
					</div>

					<div class="panel panel-default">
					  	<div class="panel-heading"><h3 style="color: #66CCFF;">Transaction History</h3></div>
					  	<div class="panel-body">
				  			<?php
				  				while($row = $transactions->fetch_assoc()){
				  					echo '<br><div style="border-bottom: solid #f0f0f0;">';
				  					echo "<span><strong>{$row['type']} {$row['quantity']}</strong> at {$row['transaction_time']}";
				  					echo ((!empty($row['user_name'])) ? " to <strong>".$row['user_name']."</strong>"  : "") . "</span><br>";
				  					echo "<i>{$row['comment']}</i>";
				  					echo '</div>';
				  				}
				  			?>
					  	</div>
					</div>

				</div>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-heading"><h2>Purchase/Consume</h2></div>
						<div class="panel-body">
							<form role="form" action="product_action.php" method="POST">
								<input type="hidden" name="pid" value="<?php echo $product_info['product_id']; ?>">
								<div class="form-group">
									<label for="action_type">Action Type</label>
									<select name="action_type" id="action_type" class="form-control">
										<option value="">Choose Action Type</option>
										<option value="purchase">Purchase</option>
										<option value="consume">Consume</option>
									</select>
								</div>
								<div class="form-group">
									<label for="quantity">Quantity</label>
									<input type="number" min="1" class="form-control" id="quantity" name="quantity" value="1">
								</div>
								<div class="form-group">
									<label for="uid">User</label>
									<select name="uid" id="uid" class="form-control">
										<option value="">Choose User</option>
										<?php
											while ($row =  $users->fetch_assoc()) {
												echo "<option value='{$row['user_id']}'>{$row['user_name']}</option>";
											}
										?>
									</select>
								</div>
								<div class="form-group">
						  			<label for="transaction_date">Transaction Date</label>
						  			<input class="datepicker form-control" name="transaction_date" id="transaction_date" >
						  		</div>
								<div class="form-group">
									<label for="action_comment">Message</label>
									<textarea class="form-control" id="action_comment" placeholder="action comment ..." name="action_comment"></textarea><br>
								</div>
								<button type="submit" name="submit" class="btn btn-success">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>