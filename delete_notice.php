<?php
session_start();

//ログイン状態チェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

//データベース接続
require_once("db.php");
$dbh=db_connect();

if(!empty($_POST)){
	$id=$_POST['number'];
	$sql="DELETE FROM notice WHERE id='$id'";
	$result = $dbh->query($sql);
	echo "削除しました";
}

?>

<!DOCTYPE html>
<html lang='ja'>
<html>
<head>
	<title>お知らせ削除</title>
	<meta charset="UTF-8">
</head>

<body>

<form action="delete_notice.php" method="post">
<input type="text" name="number" placeholder="削除したい番号を入力"><br>
<input type="submit" value="削除"><br>
<input type="butten" value="戻る" onClick="history.back()">
</form>
</body>
<html>
