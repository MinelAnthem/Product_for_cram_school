<?php
session_start();
//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

$dsn = 'mysql:dbname=tt_146_99sv_coco_com;host=localhost';
$user = 'tt-146.99sv-coco';
$password = 'f9Zgk5iY';
$pdo = new PDO($dsn,$user,$password);
?>

<?php
echo "<h1>生徒アカウント一覧</h1>";
$sql = "SELECT * FROM member WHERE type='生徒'";
$results = $pdo -> query($sql);
foreach($results as $row){
  echo "氏名:".$row['name'].',';
  echo "アカウント:".$row['account'].',';
  echo "メールアドレス:".$row['mail'].',';
  echo "パスワード:".$row['password'].',';
  echo "アカウントの種類:".$row['type'].',';
  echo "授業コード:".$row['code'].'<br>';
}

echo "<h1>保護者アカウント一覧</h1>";

$sql = "SELECT * FROM member WHERE type='保護者'";
$results = $pdo -> query($sql);
foreach($results as $row){
  echo "氏名:".$row['name'].',';
  echo "アカウント:".$row['account'].',';
  echo "メールアドレス:".$row['mail'].',';
  echo "パスワード:".$row['password'];
  echo "アカウントの種類:".$row['type'].'<br>';
}
echo "<h1>講師アカウント一覧</h1>";
$sql = "SELECT * FROM member WHERE type='講師'";
$results = $pdo -> query($sql);
foreach($results as $row){
  echo "氏名:".$row['name'].',';
  echo "アカウント:".$row['account'].',';
  echo "メールアドレス:".$row['mail'].',';
  echo "パスワード:".$row['password'];
  echo "アカウントの種類:".$row['type'].',';
  echo "授業コード".$row['code'].'<br>';
}

echo "<h1>責任者アカウント一覧</h1>";
$sql = "SELECT * FROM member WHERE type='責任者'";
$results = $pdo -> query($sql);
foreach($results as $row){
  echo "氏名:".$row['name'].',';
  echo "アカウント:".$row['account'].',';
  echo "メールアドレス:".$row['mail'].',';
  echo "パスワード:".$row['password'].',';
  echo "アカウントの種類:".$row['type'].',';
  echo "授業コード".$row['code'].'<br>';
}
?>

<!DOCTYPE html>
<html>
<head>
<title>登録情報</title>
<meta charset = "UTF-8">
</head>
<body>

<form action="operate_registered_information.php" method = "post">
<input type="text" name = "name" placeholder ="氏名">
<br>
<select name = "type">
		<option value = "生徒" selected>生徒</option>
		<option value = "保護者">保護者</option>
		<option value = "講師">講師</option>
		<option value = "責任者">責任者</option>
	</select>
<br>
<select name = "operation">
	<!--	<option value = "change" selected>変更</option> -->
		<option value = "delete">削除</option>
	</select>
<br>
<input type="submit" value = "確定">
<br>
<a href="code_set.php">授業コードを設定</a>
<br>
<a href='login_admin_manager.php'>戻る</a>
</body>
</html>