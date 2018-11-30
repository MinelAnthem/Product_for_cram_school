<?php
session_start();
$account = $_SESSION["account"];
//echo $account;

if(!empty($_POST)){
	$y = $_POST["year"];
	$m = $_POST["month"];
	$d = $_POST["day"];
	$time =$_POST["time"];

	if(checkdate($m, $d, $y)){
		
		$date = $y."/".$m."/".$d."/".$time;
		//echo $date."で予約しました";

		//データベース接続
		require_once("db.php");
		$dbh=db_connect();

		$sql="SELECT name FROM member WHERE account='$account'";
		$result=$dbh->query($sql);
		$row=$result->fetch();
		$name = $row["name"];
	//	print_r($row);



		$sql="SELECT * FROM skedule WHERE date='$date'";
		$result=$dbh->query($sql);

		if(!($row2=$result->fetch() )){
			//echo "OK";
			echo $row["name"]."様".$date."で予約しました";
			$sql="INSERT INTO skedule (name, date) VALUES(:name, :date)";
			$stmt=$dbh->prepare($sql);
			$stmt->bindValue(':name',$name, PDO::PARAM_STR);
			$stmt->bindValue(':date',$date, PDO::PARAM_STR);
			$stmt->execute();
		}else{
			echo "申し訳ございません。この時間は受付できませんでした。";
		}
	}else{
		echo "日付が正しくありません<br>";
		echo "<a href='calender.php'>戻る</a>";
	}
}else{
	header("Location: calender,php");
	exit();
}

?>
<html
<meta charset="utf-8">
</html>