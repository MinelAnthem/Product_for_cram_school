<?php
session_start();

//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

$account = $_SESSION['account'];
echo "<p>".htmlspecialchars($account,ENT_QUOTES)."さん、こんにちは！</p>";

echo "<a href='check_registered_information.php'>登録情報を確認</a><br>";

echo "<a href='announce.php'>お知らせを作成</a><br>";
echo "<a href='delete_notice.php'>お知らせを削除</a><br>";
echo "<a href='notification.php'>塾のお知らせを表示</a><br>";
echo "<a href='check_interview_registration.php'>保護者面談の予約状況を見る</a><br>";

echo "<a href='logout.php'>ログアウト</a>";
?>