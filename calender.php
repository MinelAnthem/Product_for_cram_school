<?php
session_start();

//ログイン状態のチェック
if(!isset($_SESSION["account"])){
	header("Location: login_form.php");
	exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<html>
<head>
<meta charset='UTF-8'>
<title>カレンダー</title>
</head>
<body>

<?php
	$ym_now = date("Ym");
	$y = substr($ym_now, 0, 4);
	$m = substr($ym_now, 4, 2);
?>

<form action="skedule_registration.php" method="post">
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

	<input type="submit" value="予約">
</form>
<table border="1">
<thread>
	<tr>
		<th align="center" bgcolor="#CC3300" width="30" height="30">日</th>
		<th align="center" width="30" height="30">月</th>
		<th align="center" width="30" height="30">火</th>
		<th align="center" width="30" height="30">水</th>
		<th align="center" width="30" height="30">木</th>
		<th align="center" width="30" height="30">金</th>
		<td align="center" bgcolor="#3333FF" width="30" height="30">土</th>
	</tr>
</thread>
<tbody>

	<?php
//	$ym_now = date("Ym");
//	$y = substr($ym_now, 0, 4);
//	$m = substr($ym_now, 4, 2);
	$d = 1;

	echo "<h1>".$y."年".$m."月</h1>"; //月を表示

	echo "<tr>";

	$wd1 =  date("w", mktime(0,0,0,$m,1,$y));  //1日の曜日を取得

	for($i = 0; $i < $wd1;$i++){
		echo "<td align=\"center\" width=\"30\" height=\"30\"></td>";
	}

	while(checkdate($m,$d,$y)){
		echo "<td align=\"center\" width=\"30\" height=\"30\">".$d."</td>";

		if(date("w", mktime(0,0,0,$m,$d,$y)) == 6){  //wは曜日を返すformat
			echo "</tr>";

			if(checkdate($m, $d+1,$y)){  //if there exists a next day
				echo "<tr>";
			}
		}

		$d++;
	}

	$wdx = date("w", mktime(0,0,0,$m+1,0,$y));  //the number 0 means the end of the previous month
	for ($i=0; $i<6-$wdx;$i++){
		echo "<td></td>";
	}
	?>
</tr>
</tbody>
</table>
<!--次の月-->
<table border="1">
<thread>
	<tr>
		<th align="center" bgcolor="#CC3300" width="30" height="30">日</th>
		<th align="center" width="30" height="30">月</th>
		<th align="center" width="30" height="30">火</th>
		<th align="center" width="30" height="30">水</th>
		<th align="center" width="30" height="30">木</th>
		<th align="center" width="30" height="30">金</th>
		<td align="center" bgcolor="#3333FF" width="30" height="30">土</th>
	</tr>
</thread>
<tbody>
	<tr>
	<?php
	$m++;

	if($m>12){  //13月を次の年に
		$m = $m-12;
		$y++;
	}

	$d = 1;
	echo "<h1>".$y."年".$m."月</h1>"; //月を表示

	echo "<tr>";

	$wd1 =  date("w", mktime(0,0,0,$m,1,$y));  //1日の曜日を取得

	for($i = 0; $i < $wd1;$i++){
		echo "<td align=\"center\" width=\"30\" height=\"30\"></td>";
	}

	while(checkdate($m,$d,$y)){
		echo "<td align=\"center\" width=\"30\" height=\"30\">".$d."</td>";

		if(date("w", mktime(0,0,0,$m,$d,$y)) == 6){  //wは曜日を返すformat
			echo "</tr>";

			if(checkdate($m, $d+1,$y)){  //if there exists a next day
				echo "<tr>";
			}
		}

		$d++;
	}

	$wdx = date("w", mktime(0,0,0,$m+1,0,$y));  //the number 0 means the end of the previous month
	for ($i=0; $i<6-$wdx;$i++){
		echo "<td></td>";
	}
	?>
	</tr>
</tbody>
</table>

</body>
</html>