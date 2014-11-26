<?php

include('./connect.php');

$conn->query("UPDATE products SET product_name = '{$_POST['updated_name']}' WHERE product_id = ".$_POST['pid']);

include('./close.php');

header('Location: showProduct.php?pid='.$_POST['pid']);



?>