<?php 

	function lang($phrase) {

		static $lang = array(

				// Navbar Links
				'Home_Admin'	=> 'Home',
				'CATEGORIES' 	=> 'Categories',
				'EDIT PROFILE' 	=> 'Edit Profile',
				'SETTINGS' 		=> 'Setiings',
				'LOGOUT' 		=> 'Logout',
				'ITEMS' 		=> 'Items' ,
				'MEMBERS' 		=> 'Members' ,
				'COMMENTS'  	=> 'Comments',
				'STATISTICS' 	=> 'Staticstics' ,
				'LOGS' 			=> 'Logs' ,

				


			 );
		return $lang[$phrase];

	}