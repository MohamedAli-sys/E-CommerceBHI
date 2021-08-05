<?php
	
	// Error Reporting 

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	

	include 'Admin/connect.php';

	$sessionUser = '';
	if(isset($_SESSION['User'])) {
		$sessionUser = $_SESSION['User'];
	}

	//Routes 

	$tpl 	= 'includes/Templates/'; // Themes Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory
	$lang 	= 'includes/Languages/'; // Language Directory
	$func 	= 'includes/Functions/'; // Functions Directory
	$upload = 'includes/uploads/';


	// Include The Important Files

	include $func . 'functions.php';
	include $lang . 'english.php';
	include $tpl . 'Header.php';
	include $upload . 'upload.php';
?>
	
