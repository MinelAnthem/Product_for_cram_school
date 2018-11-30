<?php
session_start();

header("Content-type: text/html; charset=utf-8");
/*不要
if($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}
*/
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//前後にある半角スペースを削除する関数。これは使っているか？
function spaceTrim($str) {
	//行頭「^」を使う　「+」は直前の要素を1回以上繰り返し存在する場合に使う。今回は空白が一回以上繰り返し使われている場合にマッチ
	$str = preg_replace('/^[ ]+/u','',$str);
	//末尾「$」を使う
	$str = preg_replace('/[ ]+$/u', '', $str); 
	return $str;
}

//エラーメッセージの初期化
$errors = array();

//空のときはやり直す
if(empty($_POST)){
	header("Location: registration_mail_form.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$name = isset($_POST['name']) ? $_POST['name'] :NULL;
	$account = isset($_POST['account']) ? $_POST['account'] :NULL;
	$password = isset($_POST['password']) ? $_POST['password'] :NULL;
	$type = isset($_POST['type']) ? $_POST['type'] :NULL;

	//氏名入力判定
	if($name == ''):
		$errors['name'] = "氏名が入力されていません。";
	elseif(mb_strlen('name')>20):
		$errors['name_length'] = "氏名は20字以内で入力してください。";
	endif;

	//アカウント入力判定
	if($account ==''):
		$errors['account'] = "アカウントが入力されていません。";
	elseif(mb_strlen($account)>10):
		$errors['account_length'] = "アカウントは10文字以内で入力してください。";
	endif;

	//パスワード入力判定
	if($password ==''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30字以内で入力してください。";
	else:
		$password_hide = str_repeat('*', strlen($password));
	endif;

	//種別の入力判定
	if(empty($type)):
		$errors['type'] = "種別が選択されていません。";
	endif;
}


//エラーがなければセッションに登録
if(count($errors)===0){
	$_SESSION['name'] = $name;
	$_SESSION['account'] = $account;
	$_SESSION['password'] = $password;
	$_SESSION['type'] = $type;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>会員登録確認画面</title>

<?php if (count($errors) === 0): ?>

<form action = "registration_insert.php" method = "post">

<p>氏名：<?=htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?></p>
<p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES) ?></p>
<p>アカウント名：<?=htmlspecialchars($account, ENT_QUOTES) ?> </p>
<p>パスワード：<?=$password_hide?></p>
<p>種別：<?=htmlspecialchars($type, ENT_QUOTES) ?></p>

<input type = "butten" value = "戻る" onClick = "history.back()">
<input type = "hidden" name = "token" value = "<?=$_POST['token']?>">
<input type = "submit" value = "登録する">
</form>

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