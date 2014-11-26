<html>
	<head>
		<title>Report</title>
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

			$( ".datepicker" ).datepicker();

			$('#menu_report').addClass('active');
		});
		</script>
		<style type="text/css">
		.ui-datepicker{ z-index: 9999 !important;}
		</style>
	</head>
	<body>
		<?php include('./header.php'); ?>

		<?php 
			include('./connect.php');

			$all_users = $conn->query("SELECT user_id,user_name FROM user");

			$all_product = $conn->query("SELECT product_id,product_name FROM products");

			$select_string = "SELECT trans.*, prod.product_name, usr.user_name FROM transaction as trans JOIN products as prod on trans.product_id = prod.product_id LEFT JOIN user as usr on usr.user_id = trans.user_id";

			if (isset($_POST['submit'])){
				$condition_array = array();
				if(!empty($_POST['uid'])) array_push($condition_array, array('var'=>'trans.user_id','val'=>" = ".$_POST['uid']));
				if(!empty($_POST['pid'])) array_push($condition_array, array('var'=>'trans.product_id','val'=>" = ".$_POST['pid']));
				if(!empty($_POST['type'])) array_push($condition_array, array('var'=>'type','val'=>" = '{$_POST['type']}'"));

				$start_date = isset($_POST['start_date']) ? $_POST['start_date']: "";
				$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : "";

				if(empty($end_date)){
					$end_date = date('Y-m-d H:i:s');
				}

				if(empty($start_date)){
					$start_date = date('Y-m-d H:i:s', strtotime($end_date." -1 year"));
				}

				array_push($condition_array, array('var'=>'transaction_time','val'=>" >= '{$start_date}'"));
				array_push($condition_array, array('var'=>'transaction_time','val'=>" <= '{$end_date}'"));

				$is_first = true;

				foreach ($condition_array as $cond) {
					if($is_first) $select_string .= " WHERE ";
					else $select_string .= " AND ";

					$select_string .= $cond['var']."".$cond['val'];

					$is_first = false;
				}
			
			}

			$select_string .= " ORDER BY transaction_time DESC";

			$all_transaction = $conn->query($select_string);
			include('./close.php');
		?>

		<div class="container">
			<!-- <?php echo $select_string ; ?> -->
			<form class="form-inline" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		  		<div class="form-group">
	      			<label class="sr-only" for="uid">User</label>
	      			<select id="uid" name="uid" class="form-control">
	      				<option value="">Choose User</option>
	      				<?php 
	      					if($all_users) foreach ($all_users as $user) {
	      						echo "<option value={$user['user_id']}>";
	      						echo $user['user_name'];
	      						echo "</option>";
	      					}
	      				?>
	      			</select>
		  		</div>
		  		<div class="form-group">
	      			<label class="sr-only" for="pid">Product</label>
	      			<select id="pid" name="pid" class="form-control">
	      				<option value="">Choose Product</option>
	      				<?php 
	      					if($all_product) foreach ($all_product as $prod) {
	      						echo "<option value={$prod['product_id']}>";
	      						echo $prod['product_name'];
	      						echo "</option>";
	      					}
	      				?>
	      			</select>
		  		</div>
		  		<div class="form-group">
	      			<label class="sr-only" for="type">Type</label>
	      			<select id="type" name="type" class="form-control">
	      				<option value="">Choose Type</option>
	      				<option value="purchase">Purchase</option>
	      				<option value="consume">Consume</option>
	      			</select>
		  		</div>
		  		<div class="form-group">
		  			<label class="sr-only" for="start_date">Start Date</label>
		  			<input class="datepicker form-control" name="start_date" id="start_date" >
		  		</div>
		  		<div class="form-group">
		  			<label class="sr-only" for="end_date">End Date</label>
		  			<input class="datepicker form-control" name="end_date" id="end_date" >
		  		</div>
			  	<button type="submit" name="submit" class="btn btn-primary">Generate Report</button>
			</form>
			
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Product Name</th>
						<th>User Name</th>
						<th>Action Type</th>
						<th>Quantity</th>
						<th>Comment</th>
						<th>Time</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						if($all_transaction) foreach ($all_transaction as $transaction) {
							echo "<tr>";
								echo "<td><a href=\"./showProduct.php?pid={$transaction['product_id']}\">{$transaction['product_name']}</a></td>";
								echo "<td><a href=\"./userInfo.php?uid={$transaction['user_id']}\">{$transaction['user_name']}</a></td>";
								echo "<td>{$transaction['type']}</td>";
								echo "<td>{$transaction['quantity']}</td>";
								echo "<td>{$transaction['comment']}</td>";
								echo "<td>{$transaction['transaction_time']}</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</body>
</html>