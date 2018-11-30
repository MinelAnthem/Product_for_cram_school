<?php
session_start();

//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".htmlspecialchars($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='message.php'>質問を確認する</a>"."<br>";
echo "<a href='announce.php'>お知らせを作成</a><br>";
echo "<a href='delete_notice.php'>お知らせを削除</a><br>";
echo "<a href='notification.php'>塾のお知らせを表示</a><br>";
echo "<a href='attendence_of_students.php'>出席簿</a><br>";
echo "<a href='attendence_of_teachers.php'>出勤簿</a><br>";

echo "<a href='logout.php'>ログアウト</a>";
?>