<?php
session_start();
//$account = $_SESSION["account"];
//生徒のアカウントを取得
$account=$_POST['account'];

		
require_once("db.php");
$dbh=db_connect();

//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}

if(!empty($_POST)){
	$y = $_POST["year"];
	$m = $_POST["month"];
	$d = $_POST["day"];
	$time =$_POST["time"];

	if(checkdate($m, $d, $y)){
		
		$date = $y."/".$m."/".$d."/".$time;
		//echo $date."で予約しました";

		//データベース接続
		//require_once("db.php");
		//$dbh=db_connect();

		$sql="SELECT name FROM member WHERE account='$account'";
		$result=$dbh->query($sql);
		$row=$result->fetch();
		$name = $row["name"];
	//	print_r($row);



		$sql="SELECT * FROM attendence_student WHERE date='$date'";
		$result=$dbh->query($sql);

		if(!($row2=$result->fetch() )){
			//echo "OK";
			echo $row["name"]."さん".$date."で入力しました";
			$sql="INSERT attendence_student (name, date) VALUES(:name, :date)";
			$stmt=$dbh->prepare($sql);
			$stmt->bindValue(':name',$name, PDO::PARAM_STR);
			$stmt->bindValue(':date',$date, PDO::PARAM_STR);
			$stmt->execute();
		}else{
			echo "この時間は既に入力されています";
		}
	}else{
		echo "日付が正しくありません<br>";
		echo "<a href='calender.php'>戻る</a>";
	}

}
/*
else{
	header("Location: calender,php");
	exit();
}
*/


//表示
$sql="SELECT * FROM attendence_student";
$result=$dbh->query($sql);
while($re=$result->fetch()){
	echo $re["name"].",".$re["date"]."<br>";
}


?>

<!DOCTYPE html>
<html lang="ja">
<html>
<head>
<meta charset='UTF-8'>
<title>出勤簿</title>
</head>
<body>

<?php
	$ym_now = date("Ym");
	$y = substr($ym_now, 0, 4);
	$m = substr($ym_now, 4, 2);
?>

<form action="attendence_of_students.php" method="post">
	<input type="text" name="account" placeholder="生徒アカウント名"><br>
	<select name="year">
		<option value = "<?=$y?>">
			<?php echo $y; ?>
		</option>
		<option value = "<?=$y+1?>">
			<?php echo $y+1; ?>
		</option>
	</select>
	<select name="month">
		<option value ="<?=$m?>">
			<?php echo $m; ?>
		</option>
		<option value ="<?=$m+1?>">
			<?php
				if($m+1>=12){  //13月は1月に直す
					echo $m-12;
				}else{
					echo $m+1;
				}
			?>
		</option>
	</select>
	<select name="day">
		<?php for($i=0; $i<31;$i++){ ?>
			<option value="<?=$i+1?>">
				<?php echo $i+1; ?>
			</option>
		<?php } ?>
	</select>
	<select name="time">
		<option value="15:00-16:00">15:00-16:00</option>
		<option value="16:30-17:30">16:30-17:30</option>
		<option value="18:00-19:00">18:00-19:00</option>
		<option value="19:30-20:30">19:30-20:30</option>
		<option value="21:00-21:30">21:00-21:30</option>
	</select>

	<input type="submit" value="入力">
</form>
</body>
</html>