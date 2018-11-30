<?php

function db_connect(){

	$dsn = 'データベース情報';
	$user = 'ユーザー名';
	$password = 'パスワード';

	try{
		$pdo = new PDO($dsn,$user,$password);
		return $pdo;
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
}

?>