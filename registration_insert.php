<?php
session_start();
//header("Content-type: text/html: charset = utf-8");//あると完了画面が開けない

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベース接続
require_once("db.php");
$dbh = db_connect();

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
	header("Location: registration_mail_form.php");
	exit();
}

$name = $_SESSION['name'];
$mail = $_SESSION['mail'];
$account = $_SESSION['account'];
$type = $_SESSION['type'];

//パスワードのハッシュ化をする必要がある
$password = $_SESSION['password'];
$salt = hash('sha256','dhf8723yr8h');    //適当な文字列をハッシュ化し、saltを生成。
$password_hash = crypt($password,$salt);

//データベースに登録
try{
	//例外処理を投げるように設定
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//トランザクション開始
	$dbh->beginTransaction();

	//memberテーブルに本登録する
	$statement=$dbh->prepare("INSERT INTO member (name,account,mail,password,type) VALUES (:name,:account,:mail,:password_hash,:type)");
	//プレースホルダに実際の値を設定
	$statement->bindValue(':name', $name, PDO::PARAM_STR);
	$statement->bindValue(':account', $account, PDO::PARAM_STR);
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
	$statement->bindValue(':type', $type, PDO::PARAM_STR);
	$statement->execute();

	//pre_memberのflagを1にする。
	$statement=$dbh->prepare("UPDATE pre_member SET flag=1 WHERE mail=(:mail)");
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->execute();

	//トランザクション完了。成功したので更新する。
	$dbh->commit();

	//データベース接続切断
	$dbh = null;

	//セッション変数を全て解除
	$_SESSION = array();

	//セッションクッキーの削除
	if (isset($_COOKIE["PHPSESSID"])){
		//全てのドメインで有効？
		setcookie("PHPSESSID", '', time() - 1800, '/');
	}

	//セッションを破棄する
	session_destroy();

	//登録完了のメールを送信
	$mailTo = $mail;
	$returnMail = 'リターンするメールアドレス';
	$name = "--塾";
	$mymail = '送信主のメールアドレス';
	$subject = "【--塾】会員登録完了のお知らせ";

$body = <<<EOM
登録が完了しました。
EOM;

	mb_language('ja');
	mb_internal_encoding('UTF-8');

	$header = 'From; ' .mb_encode_mimeheader($name).'<'.$mymail.'>';

	mb_send_mail($mailTo, $subject, $body, $header, '-f'.$returnMail);

}catch (PDOException $e){
	//トランザクション取り消し（ロールバック）。失敗したらデータをもとに戻す
	$dbh->rollBack();
	$errors['error'] = "もう一度やり直してください。";
	print('Error:'.$e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
<title>会員登録完了画面</title>
<meta charset = "UTF-8">
</head>
<body>

<?php if(count($errors)===0): ?>
<h1>会員登録完了画面</h1>

<p>登録完了しました</p>
<p><a href="login_form.php">ログイン画面</a></p>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<?php endif; ?>

</body>
</html>