<?php
$dsn = 'mysql:dbname=tt_146_99sv_coco_com;host=localhost';
$user = 'tt-146.99sv-coco';
$password = 'f9Zgk5iY';
$pdo = new PDO($dsn,$user,$password);
?>

<?php
$sql = 'SELECT * FROM member';
$results = $pdo -> query($sql);
foreach($results as $row){
  echo $row['name'].',';
  echo $row['account'].',';
  echo $row['mail'].',';
  echo $row['password'];
  echo $row['type'].'<br>';
}
?>
