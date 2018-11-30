<?php
//メールアドレスはアップするときに消す。
session_start();

header("Content-type: text/html; charset=UTF-8");

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベース接続
require_once("db.php");
$dbh = db_connect();




//エラーメッセージの初期化
$errors = array();

//空のとき、前のページに戻す
if(empty($_POST)){
	header("Location: registration_mail_form.php");
	exit();
}else{
	//三項演算子trueなら?の直後を返し、falseなら２つ目を返す。値を受け取った。
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

	//メール入力判定
	if($mail == ''){
		$errors['mail'] = "メールアドレスが入力されていません。";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
		}
		else{
		//既に登録されているmailかチェック
		$re=$dbh->query('SELECT * FROM member');
 			while($results = $re->fetch()){
				if($mail == $results['e_mail']){
				$errors['mail_check'] = "このメールアドレスは既に登録されています。";
				}
			}
		}
	}
}

if(count($errors) === 0){

	$urltoken = hash('sha256',uniqid(rand(),1));
	//メールに送信するURLをセット。ログインで実行するファイル
	$url = "http://tt-146.99sv-coco.com/registration_form.php"."?urltoken=".$urltoken;

	try{
		//例外処理を投げるようにする
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$statement = $dbh->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUE (:urltoken,:mail,now() )");
		
		$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();

		//データベース接続切断
		$dbh = null;

	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		//エラーが出たらmessageを表示してスクリプトを終了
		die();

	}

	$mailTo = $mail;
	//メールの宛先を代入

	//Return-Pathに指定するメールアドレス
	$returnMail = 'リターン先のメールアドレス';//未設定

	$name = "--塾";
	//自分のアドレス
	$mail = '送信主のメールアドレス';

	$subject = "【--塾】会員登録用URL";

//EOMはヒアドキュメント
$body = <<<EOM
24時間以内に下記のURLからご登録ください。
{$url}
EOM;

	mb_language('ja');
	mb_internal_encoding('UTF-8');

	//Fromヘッダーを作成
	$header = 'From; ' . mb_encode_mimeheader($name).' <' . $mail. '>';

	if(mb_send_mail($mailTo, $subject, $body, $header, '-f'. $returnMail)){
		//セッション変数を全て削除
		$_SESSION = array();

		//クッキーの削除。
		if(isset($_COOKIE["PHPSESSID"])){
			//有効期限を過去に変更することで、クッキーを削除する
			setcookie("PHPSESSID", '', time() - 1800,'/');
		}

		//セッションを破壊する
		session_destroy();

		$message = "メールを送信しました。24時間以内にメールに記載されたURLからご登録ください。";
	}else{
		$errors['mail_error'] = "メールの送信に失敗しました。";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
<title>メール確認画面</title>
<meta charset = "UTF-8">
</head>
<body>
<h1>メール確認画面</h1>

<?php if (count($errors) === 0): ?>

<p><?=$message?></p>
/*
<p>↓このURLが記載されたメールが届きます。</p>
<a href = "<?=$url?>" ></a>
*/
<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<input type = "butten" value = "戻る" onClick = "history.back()">

<?php endif; ?>

</body>
</html>