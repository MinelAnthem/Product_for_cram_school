<?php

function db_connect(){

	$dsn = '�f�[�^�x�[�X���';
	$user = '���[�U�[��';
	$password = '�p�X���[�h';

	try{
		$pdo = new PDO($dsn,$user,$password);
		return $pdo;
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
}

?>