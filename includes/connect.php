<?php
	$dsn='mysql:host=localhost;dbname=todo'; //Data source name
	$user='root';
	$password='';
	$options=array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

	try {
		$connect=new PDO($dsn,$user,$password,$options);
		$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}