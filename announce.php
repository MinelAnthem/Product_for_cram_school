<?php
session_start();

//ログイン状態チェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

//自分のアカウント名
$account = $_SESSION["account"];
?>

<!DOCTYPE html>
<html lang='ja'>
<html>
<head>
	<title>お知らせ作成</title>
	<meta charset="UTF-8">
</head>

<body>

<form action="notification.php" method="post">

<!--後でhidden-->
<input type="text" name="account" value="<?php echo $account; ?>" >
<br> 

<input type="text" name="subject" placeholder="件名">
<br>
<textarea name="body" cols="50" rows="5">内容</textarea>
<br>
<input type="submit" value="送信">
<br>
<input type="butten" value="戻る" onClick="history.back()">
</form>
</body>
<html>
