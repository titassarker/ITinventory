<?php

include('./connect.php');

$conn->query("UPDATE user SET user_name = '{$_POST['updated_name']}' WHERE user_id = ".$_POST['uid']);

include('./close.php');

header('Location: userInfo.php?uid='.$_POST['uid']);

?>