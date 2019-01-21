<?php
session_start();

header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

?>

<!DOCTYPE html>
<html>
<head>
<meta charset = "UTF-8">
<title>メール登録</title>
</head>
<body>
<div>
<h1>メール登録画面</h1>

<form action = "registration_mail_check.php" method = "post">
<input type = "text" name = "mail" size = "50" placeholder = "メールアドレス">
<br>
<input type = "submit" value = "登録する">

<!-- <input type = "hidden" name = "token" value = "<?=$token?>"> -->

</form>

</div>
</body>
</html>
