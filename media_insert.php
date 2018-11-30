<?php
session_start();
//アカウントの授業コードを取得
$account=$_SESSION['account'];
$sql="SELECT code FROM member WHERE account='$account'";
$result=$dbh->query($sql);
$re=$result->fetch();
$code = $re['code'];

try{
	//データベース接続
	require_once("db.php");
	$dbh=db_connect();
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//ファイルアップロードがあったとき
	if(isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES['upfile']['name'] !==""){
		//エラーチェック
		switch($_FILES['upfile']['error']){
			case UPLOAD_ERR_OK:  //異常なし
			//echo "test";表示OK
				break;
			case UPLOAD_ERR_NO_FILE:  //ファイル未選択
				//echo "test2";表示なし⇒breakできている。
				//エラーを投げるRuntimeExceptionはExceptionの継承クラス
				throw new RuntimeException('ファイルが選択されていません',400);
			case UPLOAD_ERR_INI_SIZE:  //php.ini定義の最大サイズ超過
				throw new RuntimeException('ファイルサイズが大きすぎます',500);
			default:
				throw new RuntimeException('その他のエラーが発生しました',500);
		}

		//画像・動画をバイナリデータにする
		$raw_data = file_get_contents($_FILES['upfile']['tmp_name']);
		//echo $raw_data; //文字化けでの表示成功

		//拡張子を見る
		$tmp = pathinfo($_FILES['upfile']['name']);  //すべての情報を配列として取得

		$extension = $tmp['extension'];

		//jpg,jpeg,JPG,JPEG
		if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
			$extension = "jpeg";
		}else if($extension ==="png" || $extension === "PNG"){
			$extension = "png";
		}else if($extension === "gif" || $extension === "GIF"){
			$extension = "gif";
		}else if($extension === "mp4" || $extension === "MP4"){
			$extension = "mp4";
		}else{
			echo "非対応ファイルです。"."<br>";
			echo "<a href='media_insert.php'>戻る</a>";
			exit(1);  //エラーコードつきの終了
		}


		//データベースに格納するファイルネーム設定
		//サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256を掛ける
		$date = getdate();
		$fname = $_FILES['upfile']['tmp_name'].$date['year'].$date['mon'].$date['mday'].$date['hours'].$date['minutes'].$date['seconds'];
		$fname = hash("sha256",$fname);

		//画像・動画をデータベースに格納
		$sql = "INSERT INTO media(fname, extension, raw_data, code) VALUES (:fname,:extension,:raw_data, :code)";

		$stmt = $dbh->prepare($sql);
		$stmt -> bindValue(':fname', $fname, PDO::PARAM_STR);
		$stmt -> bindValue(':extension', $extension, PDO::PARAM_STR);
		$stmt -> bindValue(':raw_data', $raw_data, PDO::PARAM_STR);
		$stmt -> bindValue(':code', $code, PDO::PARAM_STR);
		$stmt -> execute();
	}  //if節
}catch(PDOException $e){
	echo "<p>500 Inertal Server Error</p>";
	exit($e->getmessage());  //終了直後にメッセージを返す
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>media</title>
</head>

<body>
	<!--ファイルを送信するためにenctype multipart/form-dataが必要 -->
	<form action="message.php" enctype="multipart/form-data" method="post">
		<label>画像/動画アップロード</label>
		<input type="file" name = "upfile">
		<br>
		※画像はjpeg、png、gifに対応しています。動画はmp4のみ対応しています。<br>
		<input type="submit" value="アップロード">
	</form>
</body>
</html>


<?php

//データベースから取得して表示
$sql="SELECT * FROM media ORDER BY id";
$stmt = $dbh->prepare($sql);
$stmt -> execute();
while($row = $stmt->fetch()){

	//codeが一致するときのみ表示
	if($row['code']==$code){
		echo $row['id']."<br>";
		//動画と画像で場合分け
		$target = $row['fname'];
		if($row['extension'] == "mp4"){
			echo "<video src=\"import_media.php?target=$target\" width='426' height='240' controls></video>";
		}else if($row['extension'] == "jpeg" || $row['extension'] == "png" || $row['extension'] == "gif"){
			echo "<img src=\"import_media.php?target=".$target."\" width='314' height='229' alt='投稿画像'>";
		}
		echo "<br><br>";
	}
}
?>
