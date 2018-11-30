<?php
session_start();

//文字コード指定
header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策 SAMEORIGINはフレーム内のページ表示を同一ドメインのみ許可
header('X-FRAME-OPTIONS: SAMEORIGIN');
//データベース接続
require_once("db.php");
$dbh = db_connect();
//error messageの初期化
$errors = array();

if(empty($_GET)){
	//urltokenがないならページを戻す
	header("Location: registration_mail_form.php");
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = isset($_GET[urltoken]) ? $_GET[urltoken] : NULL;
	//メール入力判定
	if($urltoken ==''){
		//空文字列ならエラーへ
		$errors['urltoken'] = "もう一度登録をやり直してください。";
	}else{
		try{
			//例外処理を投げるよう設定する
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//flagが0の未登録者・仮登録から24時間以内を取得
			$statement = $dbh->prepare("SELECT mail FROM pre_member WHERE urltoken=(:urltoken) AND date > now() - interval 24 hour");
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->execute();

			//該当したレコード件数を取得
			$row_count = $statement->rowCount();

			//24時間以内に仮登録され、本登録されていないトークンの場合
			if($row_count ==1){
				$mail_array = $statement->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が切れた可能性があります。もう一度登録をやり直してください。";
			}

			//データベース接続切断
			$dbh = null;
		}catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}

?>

<!DOCTYPE html>
<html lang = "ja">
<html>
<head>
<title>会員登録画面</title>
<meta charset = "utf-8">
</head>
<body>
<h1>会員登録画面</h1>

<?php if(count($errors)===0): ?>

<form action = "registration_check.php" method = "post">

<p>氏名：<input type = "text" name = "name"></p>
<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8') ?></p>
<p>アカウント名：<input type = "text" name = "account"></p>
<p>パスワード：<input type = "text" name = "password"></p>
<p>種別：<select name = "type">
		<option value = "生徒" selected>生徒</option>
		<option value = "保護者">保護者</option>
		<option value = "講師">講師</option>
		<option value = "責任者">責任者</option>
	</select></p>

<input type = "hidden" name = "token" value = "<?=token?>" >
<input type = "submit" value = "確認">

</form>

<?php elseif(count($errors)>0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<?php endif; ?>

</body>
</html>