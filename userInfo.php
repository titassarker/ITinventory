<html>
	<head>
		<title>User Information</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(function() {
				$( ".update_toggler" ).click(function() {
				  	$( "#normal_div" ).toggle();
				  	$( "#update_div" ).toggle();
				});
			});
		</script>
	</head>
	<body>
		<?php
			include('./connect.php');

			$user_id = $_GET['uid'];
			$user_info = null;

			if(!empty($user_id)){
				$select_query = "SELECT * FROM user WHERE user_id = {$user_id} LIMIT 1";
				$user_info = $conn->query($select_query)->fetch_assoc();

				$transaction_fetch_query = "SELECT * FROM transaction WHERE user_id = {$user_id} ORDER BY transaction_time DESC";
				$transactions = $conn->query($transaction_fetch_query);
			}

			include('./close.php');
		?>
		
		<?php include('./header.php'); ?>

		<div class="container">
			<div class="panel panel-default">
				<div class="panel-body">
					<div id="update_div" style="display: none;">
			  			<form class="form-inline" action="updateUser.php" method="post">
			  				<input type="hidden" name="uid" value="<?php echo $user_info['user_id']; ?>">
			  				<input name="updated_name" type="text" class="form-control" value="<?php echo $user_info['user_name']; ?>">
			  				<button type="submit" name='submit' value="update" class="btn btn-primary">Update</button>
			  				<button type="button" class="btn btn-default update_toggler">Cancel</button>
			  			</form>
			  		</div>
			  		<div id="normal_div">
				  		<table width="100%" cellspacing="0" border="0px">
				  			<tbody>
				  				<tr>
				  					<td width="50%"><h2><?php echo $user_info['user_name']; ?></h2></td>
				  					<td width="50%" style="text-align: right;padding: 0px">
				  						<a class="btn btn-primary update_toggler"><span class="glyphicon glyphicon-edit"></span>Update</a>
			  							<a title="Delete user" type="button" href="delete_user.php?uid=<?php echo $user_info['user_id']; ?>" class="btn btn-danger" onClick="return confirm(
'Are you sure you want to remove this user?');"><span class="glyphicon glyphicon-remove"></span>Delete</a>
				  						
				  					</td>
				  				</tr>
				  			</tbody>
				  		</table>
			  		</div>
				</div>
			</div>
			<div class="panel panel-default">
			  	<div class="panel-heading"><h3 style="color: #66CCFF;">Transaction History</h3></div>
			  	<div class="panel-body">
		  			<?php
		  				while($row = $transactions->fetch_assoc()){
		  					echo '<br><div style="border-bottom: solid #f0f0f0;">';
		  					echo "<span><strong>{$row['type']} {$row['quantity']}</strong> at {$row['transaction_time']}</span><br>";
		  					echo "<i>{$row['comment']}</i>";
		  					echo '</div>';
		  				}
		  			?>
			  	</div>
			</div>
		</div>
	</body>
</html>