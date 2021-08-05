<?php

	
	/*
	** Get All Function v2.0
	** Function To Get All Records From Any Database Table
	*/

	function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

		global $con;

		$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
		$getAll->execute();
		$all = $getAll->fetchAll();
		return $all;
	}



	/*
	** Check If Not User Activated
	** Function To Check The RegStatus Of The User
	*/


	function checkUserStatus($user) {

		global $con;

		$stmtR = $con->prepare("SELECT 
									Username, RegStatus 
								FROM 
									users 
								WHERE 
									Username = ? 
								AND 
									RegStatus = 0");
		$stmtR->execute(array($user));
		$status = $stmtR->rowCount();
		return $status;
	} 




	/*
	** Check Items Functions v1.0
	** Function To Check Item In Database [To Accept Parameters]
	** $select = The Item To Select 
	** $from = The Table To Select From
	** $value = The Value Of Select
	*/

	function checkItem($select, $from, $value) {
		global $con;

		$statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
		$statement->execute(array($value));
		$count = $statement->rowCount();
		return $count; 
	}



	/*
	** Title Function v1.0
	** Title Function That Echo The Page Title In Case The Page 
	** Have The Variable $PageTitle And Echo Default Title For Other Pages 
	*/


	function getTitle() {
		global $pageTitle;
		if(isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Default';
		}
	}

	/* 
	** Redirect Function v2.0 
	** This Function Accept Parameters
	** $TheMessage = Echo The Message [Error, Success, Warning]
	** $url = Link You Want To Redirect To
	** $second = Seconds Before Redirecting
	*/

	function redirectHome ($theMsg, $url = null, $seconds = 3) {

		if($url === null) {

			$url = 'index.php';
			$link = 'Homepage';

		} else {
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
				$url = $_SERVER['HTTP_REFERER'];
				$link = 'Previous Page';
			} else {
				$url = 'index.php';
				$link = 'Homepage';
			}
		}

		echo $theMsg;
		echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds</div>";
		header("refresh:$seconds;url=$url");
		exit();
	}


	/*
	** Count Number Of Items Function v1.0
	** Function To Count Number Of Items Rows 
	** $item = The Item To Count
	** $table = The Table To Choose From
	*/

	function countItems($item, $table) {

		global $con;

		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

		$stmt2->execute();

		return $stmt2->fetchColumn();
	}

	/*
	** Get Latest Records Function v1.0
	** Function To Get Latest Items From Database
	** $select = Field To Select
	** $table = The Table To Choose From
	** $order = The Desc Ordering
	** $limit = Number Of Records To Get
	*/

	function getLatest($select, $table, $order, $limit = 5) {

		global $con;

		$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
		$getStmt->execute();
		$rows = $getStmt->fetchAll();
		return $rows;

	}