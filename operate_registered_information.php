<?php
session_start();
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

require_once("db.php");
$dbh = db_connect();

$name = $_POST['name'];
$operation = $_POST['operation'];
$type = $_POST['type'];

$errors = array();

try{
	//例外処理を投げるように設定
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if($operation == 'delete'){
	$sql = "DELETE FROM member WHERE name='$name'";
	$result = $dbh->query($sql);
	}
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
}

/*
if($operation == 'change'){
	$sql = "UPDATE 
*/
?>
<DOCTYPE html>
<html>
<head>
<title>アカウント削除完了</title>
<meta charset = "UTF-8">
</head>
<body>
<a href = 'check_registered_information.php'>戻る</a>
</body>
</html>