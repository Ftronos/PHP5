<?
	include "config.php";

	$id = $_POST['id'];

	$sql = "select click_count from photos where id = '" . $id . "'";
	$result = mysqli_query($connect, $sql);
	$data = mysqli_fetch_assoc($result);

	$click_count =  $data['click_count'] + 1;

	$con = "update photos set click_count='$click_count' where id='$id'";
	mysqli_query($connect, $con);