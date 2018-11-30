<?php
session_start();

//ログイン状態チェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

//自分のアカウント名
$account = $_SESSION["account"];
//POSTされた値をを代入
//$your_account = $_POST["to"];
$subject = $_POST["subject"];
$body = $_POST["body"];

//データベース接続
require_once("db.php");
$dbh=db_connect();


//エラーメッセージの初期化
$errors = array();


//無題なら「無題」と表記
if(!isset($_POST["subject"])){
	$subject = "無題";
}
?>

<!DOCTYPE html>
<html lang='ja'>
<html>
<head>
	<title>お知らせ</title>
	<meta charset="UTF-8">
</head>

<body>

<form action="notification.php" method="post">

<input type="butten" value="戻る" onClick="history.back()">
</form>
</body>
<html>


<?php



if(isset($_POST["account"]) && isset($_POST["subject"]) && isset($_POST["body"])){

	try{
		//エラーを投げるように設定
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		//投稿した時間も挿入、idも設定予定（時間順に並べるため）
		$sql=$dbh->prepare("INSERT INTO notice (my_account,subject,body,date) VALUES(:my_account,:subject,:body,now() )");
		$sql->bindValue(':my_account', $account, PDO::PARAM_STR);
		$sql->bindValue(':subject', $subject, PDO::PARAM_STR);
		$sql->bindValue(':body', $body, PDO::PARAM_STR);

		$sql->execute();
	}catch(PDOException $e){
		print('ERROR;'.$e->getMessage());
	}
}


try{
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$result=$dbh->query("SELECT * FROM notice ORDER BY id");

	while($row=$result->fetch()){
		echo $row['id'].',';
		echo "From:".$row['my_account'].'   ';
		echo "題名:".$row['subject'].$row['date']."<br>";
		echo $row['body']."<br>";
	}
}catch(PDOException $e2){
	print('Error:'.$e2->getMessage());
}

?>