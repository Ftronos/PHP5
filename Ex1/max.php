<?php
	include "config.php";

	$id = $_GET['id'];

	$sql = "select * from photos where id = '" . $id . "'";
	$result = mysqli_query($connect, $sql);
	$data = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<img src="max/<?= $data[path] ?>" alt="<?= $data[path] ?>">
<p>Просмотров: <span><?= $data[click_count] ?></span></p>
</body>
</html>
