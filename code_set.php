<?php
//session_start();
//データベース接続
require_once("db.php");
$dbh=db_connect();

if(!empty($_POST)){
	$account=$_POST["account"];
	$code = $_POST["code"];
//debug
echo $account."に授業コード".$code."を設定しました";
	//codeをアカウント名と一致するものに追加
	$sql="UPDATE member SET code='$code' WHERE account='$account'";
	$result = $dbh->query($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>授業コード設定</title>
	<meta charset="utf-8">
</head>
<body>

<form action="code_set.php" method="post">
	<p><input type="text" name="code" placeholder="授業コード"></p>
	<p><input type="text" name="account" placeholder="アカウント名"></p>
	<input type="submit" value="登録">
</form>
<br>
<a href="check_registered_information.php">戻る</a>
</body>
</html>