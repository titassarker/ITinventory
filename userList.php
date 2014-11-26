<html>
	<head>
		<title>Enosis Inventory Users</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript">
			$(function(){
				$('#menu_user').addClass('active');
			});
		</script>
	</head>
	<body>

		<?php include('./header.php'); ?>

		<?php
			$data = null;
			include('./connect.php');

			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(!empty($_POST['user_name'])){
					$user_name = $_POST['user_name'];
					$insert_string = "INSERT INTO user(user_name) VALUES('{$user_name}')";
					$conn->query($insert_string);
				}
			}

			$query_string = "SELECT * FROM user";
			$result = $conn->query($query_string);
			
		    if ($result->num_rows > 0) {
		    	unset($data);
		    	$data = array();
			    while($row = $result->fetch_assoc()) {
			        $data[] = $row;
			    }
			} 

			include('./close.php');
		?>
		
		
		<div class="container">
			<div class="row">
				<div class="col-md-6" style="border: #ccc 2px solid; border-radius: 10px; padding-top: 15px;">
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>User List</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($data)) foreach ($data as $row) { ?>
								<tr>
									<td> <a href="userInfo.php?uid=<?php echo $row['user_id']; ?>"><?php echo $row['user_name']; ?></a></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading"><h3 style="color: #0000ff;">Add User Form</h3></div>
						<div class="panel-body">
							<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
								<div class="form-group">
									<label for="user_name">User Name</label>
									<input type="text" class="form-control" id="user_name" name="user_name">
								</div>
								<div class="form-group">
									<label for="designation">Designation</label>
									<input type="text" class="form-control" name="designation" id="designation">
								</div>
								<button type="submit" name="submit" class="btn btn-primary">Add</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</body>
</html>