<?php
	
	include 'connect.php';

	//Routes 

	$tpl 	= 'includes/Templates/'; // Themes Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory
	$lang 	= 'includes/Languages/'; // Language Directory
	$func 	= 'includes/Functions/'; // Functions Directory


	// Include The Important Files

	include $func . 'functions.php';
	include $lang . 'english.php';
	include $tpl . 'Header.php';

	// Include Navbar On All Pages Expect The One With $noNavbar Variable
	if (!isset($noNavbar)) { include $tpl . 'Navbar.php'; }
	 
	
