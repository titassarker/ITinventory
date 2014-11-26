<html>
	<head>
		<title>Add Product</title>
		<style type="text/css">
			.error{
				color: red;
				font-weight: bold;
			}
			.success{
				color: green;
				font-weight: normal;
			}
		</style>
	</head>
	<body>
		<?php
			include('./connect.php');
			$message = "";
			if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if(!empty($_POST['prod_name'])){
					$prod_name = $_POST['prod_name'];
					$init_count = 0;

					if(!empty($_POST['init_stock_count'])){
						$init_count = intval($_POST['init_stock_count']);
					}

					$query_string = "INSERT INTO products(product_name,stock_count) VALUES('{$prod_name}',{$init_count});";

					if ($conn->query($query_string) === TRUE) {
					    $message = "<div class=\"success\">{$query_string}</div>";
					} else {
					    $message = "<div class=\"error\">{$conn->error}</div>";
					}

					
				} else {
					$message = '<div class="error">Please provide a product name</div>';
				}
			}

			include('./close.php');

			echo $message;
		?>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Product Name <input type="text" name="prod_name"><br>
			Initial Stock Count <input type="text" name="init_stock_count"><br>
			<input type="submit" value="ADD" name="submit">
		</form>
	</body>
</html>