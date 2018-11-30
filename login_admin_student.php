<?php
session_start();

//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".htmlspecialchars($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='message.php'>先生に質問</a>"."<br>";
echo "<a href='notification.php'>塾からのお知らせ</a><br>";

echo "<a href='logout.php'>ログアウト</a>";
?>