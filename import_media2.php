<?php
if(isset($_GET['target']) && ($_GET['target'] !== "")){
	$target = $_GET['target'];
}
/*
else{
	header("Location: media_insert.php");
}*/

$MIMETypes = array(
	'png' => 'image/png',
	'jpeg' => 'image/jpeg',
	'gif' => 'image/gif',
	'mp4'=>'video/mp4'
);

try{
	require_once("db.php");
	$dbh=db_connect();
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql="SELECT * FROM media WHERE fname = :target";
	$stmt=$dbh->prepare($sql);
	$stmt->bindValue(':target', $target, PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch();
	header("Content-Type:".$MIMETypes[$row['extension']]);
	echo $row['raw_data'];
}catch (PDOException $e){
	echo "<p>500Inertal Server Error</p>";
	exit($e->getMessage());
}
?>