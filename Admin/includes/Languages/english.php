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
				'CONTACT' 		=> 'Contact' ,
				'LOGS' 			=> 'Logs' ,

				


			 );
		return $lang[$phrase];

	}