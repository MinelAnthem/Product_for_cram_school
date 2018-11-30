<?php
session_start();

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
//データベース接続
require_once("db.php");
$dbh=db_connect();

//前後にある半角全角スペースを削除する関数
function spaceTrim($str){
	//行頭
	$str=preg_replace('/^[ ]+/u','',$str);
	//末尾
	$str=preg_replace('/[ ]+$/u','',$str);
	return $str;
}

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
	header("Location: login_form.php");
	exit();
}else{
	$account = isset($_POST['account']) ? $_POST['account'] : NULL;
	$password = isset($_POST['password']) ? $_POST['password'] : NULL;

	//前後にある半角全角スペースを定義した関数で削除
	$account = spaceTrim($account);
	$password = spaceTrim($password);

	//アカウント入力判定
	if($account == ''):
		$errors['account'] = "アカウントが入力されていません。";
	elseif(mb_strlen($account)>10):
		$errors['account_length'] = "アカウントは10文字以内で入力してください。";
	endif;

	//パスワード入力判定
	if($password == ''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST['password'])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力してください。";
	endif;
}

//エラーがなければ実行
if(count($errors)===0){
	try{
		//例外処理を投げるように設定
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//アカウントで検索
		$statement = $dbh->prepare("SELECT * FROM member WHERE account=(:account) AND flag = 1");
		$statement -> bindValue(':account', $account, PDO::PARAM_STR);
		$statement -> execute();

		//アカウントが一致するか判定
		if($row = $statement->fetch()){
			$password_registered = $row[password];
			$type = $row[type];



			//パスワードが一致するか判定。ハッシュ化する場合要改善
			if(crypt($password, $password_registered) == $password_registered){
				//セッションハイジャック対策
				//session_regenerate_id(true);

				if($type=="生徒"){
					$_SESSION['account'] = $account;
					header("Location: login_admin_student.php");
					exit();
				}else if($type == "保護者"){
					$_SESSION['account'] = $account;
					header("Location: login_admin_parent.php");
					exit();
				}else if($type == "講師"){
					$_SESSION['account'] = $account;
					header("Location: login_admin_teacher.php");
					exit();
				}else if($type == "責任者"){
					$_SESSION['account'] = $account;
					header("Location: login_admin_manager.php");
					exit();
				}else{
					$errors['type'] = "種別が登録されていません。";
				}
			}else{
				$errors['password'] = "アカウント及びパスワードが一致しません。";
			}
		}else{
			$errors['account'] = "アカウント及びパスワードが一致しません。";
		}

		$dbh = null;

	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
}

?>

<DOCTYPE html>
<html>
<head>
<title>ログイン確認画面</title>
<meta charset = "utf-8">
</head>
<body>
<h1>ログイン確認画面<h1>

<?php if(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<input type = "butten" value = "戻る" onClick="history.back()">

<?php endif; ?>

</body>
</html>