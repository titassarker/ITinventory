<html>
	<head>
		<title>Show Inventory</title>
	</head>
	<body>
		<?php
			$data = null;
			include('./connect.php');

			$query_string = "SELECT * FROM products";
			$result = $conn->query($query_string);
			
		    if ($result->num_rows > 0) {
		    	unset($data);
		    	$data = array();
			    while($row = $result->fetch_assoc()) {
			        $data[] = $row;
			    }
			} else {
			    echo "0 results";
			}

			include('./close.php');
		?>
		
		<?php 
			if(count($data)){
		?>
		<table>
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
						<td><?php echo $row['stock_count']; ?></td>
						<td><?php echo $row['consumed_count']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		<?php } else echo "no data";?>
	</body>
</html>