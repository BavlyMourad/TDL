<?php

	/*
	-- Function to get the page title v1.0

	** $pageTitle is the title of each page v1.0
	*/
	function getTitle() {
		global $pageTitle;

		if(isset($pageTitle)) {
			echo $pageTitle;
		}
	}

	/*
	-- Function to update the data base v1.0

	** $table is the table you update v1.0
	** $setOne is the first column you update v1.0
	** $setTwo is the second column you update v1.0
	** $where is to specify a row with the ID v1.0
	** $txtDeco is the updated value of the first column v1.0
	** $time is the updated value of the second column v1.0
	** $id is the ID of each row v1.0
	*/
	function updateDB($table,$setOne,$setTwo,$where,$txtDeco,$time,$id) {
		global $connect;

		if(isset($_GET['date'])) {
			$stmt=$connect->prepare("UPDATE $table SET $setOne=?,$setTwo=? WHERE $where=?");
			$stmt->execute(array($txtDeco,$time,$id));
			header("Location:index.php?date=" . $_GET['date']);
			exit();
		}
		else {
			$stmt=$connect->prepare("UPDATE $table SET $setOne=?,$setTwo=? WHERE $where=?");
			$stmt->execute(array($txtDeco,$time,$id));
			header("Location:index.php");
			exit();
		}
	}

	/*
	-- Function to insert into the data base v1.0
	
	** $table is the table you update v1.0
	** $insertOne is the first column you insert in v1.0
	** $insertTwo is the second column you insert in v1.0
	** $insertThree is the third column you insert in v1.0
	** $valueOne is the first value you insert v1.0
	** $valueTwo is the second value you insert v1.0
	** $valueThree is the third value you insert v1.0
	** $header is the header function to teleport to the required location v1.0
	*/
	function insertDB($table,$insertOne,$insertTwo,$insertThree,$valueOne,$valueTwo,$valueThree,$header) {
		global $connect;
		
		$stmt=$connect->prepare("INSERT INTO $table($insertOne,$insertTwo,$insertThree) VALUES(?,?,?)");
		$stmt->execute(array($valueOne,$valueTwo,$valueThree));
		header("Location:$header");
		exit();
	}