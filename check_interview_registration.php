<?php
require_once("db.php");
$dbh=db_connect();

$sql="SELECT * FROM skedule";
$result=$dbh->query($sql);
while($re=$result->fetch()){
	echo $re["id"].",";
	echo $re["name"].",";
	echo $re["date"]."<br>";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>保護者面談の予約状況</title>
</head>
</html>