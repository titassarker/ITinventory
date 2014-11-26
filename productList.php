<html>
	<head>
		<title>Add Product</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<style type="text/css">
			.error{
				color: red;
				font-weight: bold;
			}
			.success{
				color: green;
				font-weight: normal;
			}
			input{
				width: 100%;
			}
		</style>

		<script type="text/javascript">
			$(function(){
				$.datepicker.setDefaults({
			  		dateFormat: 'yy-mm-dd'
				});

				$( ".datepicker" ).datepicker({ defaultDate: +0 });
				$('#menu_prod').addClass('active');
			});
		</script>
		<style type="text/css">
		.ui-datepicker{ z-index: 9999 !important;}
		</style>
	</head>
	<body>
		<?php
			$message = "";
			include('./connect.php');

			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(!empty($_POST['prod_name'])){
					$prod_name = $_POST['prod_name'];
					$comment = $_POST['purchase_comment'];
					$init_count = 0;
					$transaction_time = $_POST['transaction_date'];
					if(empty($transaction_time)) $transaction_time = date('Y-m-d');

					if(!empty($_POST['init_stock_count'])){
						$init_count = intval($_POST['init_stock_count']);
					}

					$query_string = "INSERT INTO products(product_name,stock_count) VALUES('{$prod_name}',{$init_count});";

					if ($conn->query($query_string) === TRUE) {
						$transaction_string = "INSERT INTO transaction (product_id, transaction_time, quantity, type, comment) VALUES";
						$transaction_string .= "({$conn->insert_id}, '{$transaction_time}', {$init_count}, 'purchase', '{$comment}')";
						$conn->query($transaction_string);
					} else {
					    $message = "<div class=\"error\">{$conn->error}</div>";
					}

				} else {
					$message = '<div class="error">Please provide a product name</div>';
				}
			}

			$data = $conn->query("SELECT * FROM products");

			include('./close.php');
		?>

		<?php include('./header.php'); ?>

		<div class="container">
			<div class="col-md-8" style="border: #ccc 2px solid; border-radius: 10px; padding-top: 15px;">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Stock</th>
							<th>Consumed</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $row) { ?>
							<tr>
								<td> <a href="showProduct.php?pid=<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></a></td>
								<td>
									<?php 
										$stock_count = $row['stock_count'];
										if(!empty($stock_count)){
											$style='';
											if(intval($stock_count)>5){
												$style=' style="color: green;"';
											} else if(intval($stock_count)<3){
												$style=' style="color: red;"';
											}
											echo "<div{$style}>{$stock_count}</div>";
										}
									?>
								</td>
								<td><?php echo $row['consumed_count']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><h2>Add Product</h2></div>
					<div class="panel-body">
						<?php echo $message; ?>
						<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
							<div class="form-group">
								<label for="prod_name">Product Name</label>
								<input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="Product Name">
							</div>
							<div class="form-group">
								<label for="init_stock_count">Initial Stock</label>
								<input type="number" min="1" value="1" class="form-control" name="init_stock_count" placeholder="Initial Stock">
							</div>
							<div class="form-group">
					  			<label for="transaction_date">Transaction Date</label>
					  			<input class="datepicker form-control" name="transaction_date" id="transaction_date" >
					  		</div>
							<div class="form-group">
								<label for="purchase_comment">Comment</label>
								<textarea class="form-control" id="purchase_comment" name="purchase_comment" placeholder="Comment . . ."></textarea>
							</div>
							<button type="submit" name="submit" class="btn btn-default">Add</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		
	</body>
</html>