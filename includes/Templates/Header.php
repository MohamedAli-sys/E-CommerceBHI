<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?php getTitle() ?> </title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
		<link href="http://fonts.googleapis.com/css?family=Cookie" rel="stylesheet" type="text/css">
		<link rel='shortcut icon' href='layout/images/icon.ico' type='image/x-icon' />
		<link rel='icon' href='layout/images/icon.png' type='image/png' />
		<link rel="stylesheet" href="<?php echo $css; ?>frontend.css" />
	</head>
	<body>
		<div class="upper-bar">
			<div class="container">
				<div class="logo">						
					<div class="layoutlogo">
						<a href="index.php">
							<h3 class="Sun">Sun<i class="" aria-hidden="true">$</i>
								<span class="glyphicon glyphicon-shopping-cart logo-cart" aria-hidden="true"></span>
							<span class="Moon">Moon</span></h3>
							
						</a>
					</div>
				</div>
				<div class="social">
			        <ul class="social-icons icon-circle icon-rotate list-unstyled list-inline list-social"> 
				      <li> <a href="#"><i class="fa fa-facebook"></i></a></li> 
				      <li> <a href="#"><i class="fa fa-twitter"></i></a></li>
				      <li> <a href="#"><i class="fa fa-instagram"></i></a></li>
				      <li> <a href="#"><i class="fa fa-skype"></i></a></li>
				      <li> <a href="#"><i class="fa fa-dropbox"></i></a></li> 				      			
				      <li> <a href="#"><i class="fa fa-youtube"></i></a></li>  
				  	</ul>

				</div>

				<?php
					if(isset($_SESSION['User'])) { ?>
					<div class="pull-right user-info"> 
					<?php 
						$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
						$getUser->execute(array($sessionUser));
						$info = $getUser->fetch();
						$userid = $info['UserID'];
						$userimage = $info['image_src']; 

						if(! empty($userimage)) {
							echo '<img class="my-image img-thumbnail img-circle" src="' . $info['image_src'] . '" alt="" />';
						} else { 
							echo '<img class="my-image img-thumbnail img-circle" src="img.png" alt="" />'; 
						}
					?>
						<div class="btn-group my-info">
							<span class="btn btn-default dropdown-toggle user-drop" data-toggle="dropdown">
									<?php echo $sessionUser ?>
									<span class="caret"></span>
								</span>
								<ul class="dropdown-menu">
									<li><a href="profile.php">My Profile</a></li>
									<li><a href="invoices.php">Purchase Invoices</a></li>
									<li><a href="newad.php">New Product</a></li>
									<li><a href="profile.php#my-products">My Products</a></li>
									<li><a href="logout.php">Logout</a></li>
								</ul>
						</div>	
					</div>
				<?php
					} else {
				?>
				<a href="login.php"><button class="btn btn-default btn-login pull-right"> 
				<span class="pull-right login-btn">Login/SignUp</span></button>
				</a>
				<?php } ?>
			</div>
		</div>
		<nav class="navbar navbar-inverse">
	 	 <div class="container">
	    	<div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
	      <a class="navbar-brand" href="index.php">Homepage</a>
	    </div>
	    <div class="collapse navbar-collapse" id="app-nav">
	      	<ul class="nav navbar-nav">
		        <li class="dropdown">
		        	<a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
		                Categories<span class="caret"></span> </a>
		            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
		            	<li class='dropdown-submenu'><a href='showcat.php'>All Categories</a></li>
		            	<?php
		            	$allCats = getAllFrom("*", "categories", "", "", "Ordering", "ASC");
		            		$id = '';
		            		foreach($allCats as $cat) {
		            			if($cat['Parent_Cat'] == 0 ){
		            				echo "<li class='dropdown-submenu'><a href='categories.php?pageid=" . $cat['ID'] . "'> - " . $cat['Name'] . "</a>";
		            				$id = $cat['ID'];
		            				sub($cat, $id);
		            				echo "</li>";
		            			}
		            		}
		            		echo "</ul>";
		            	function sub($cat, $id){
		            		$allCats = getAllFrom("*", "categories", "", "", "Ordering", "ASC");
		            		echo "<ul class='dropdown-menu'>";
		            		foreach($allCats as $cat){
		            			if($cat['Parent_Cat'] == $id) {
		            				echo "<li><a href='categories.php?pageid=" . $cat['ID'] . "'> -- " . $cat ['Name'] . "</a>";
		            				sub($allCats, $cat['ID']);
		            				echo "</li>";
		            			}
		            		}
		            		echo "</ul>";
		            	}
		            	 ?>
		        </li>
		        <li><a href="contact.php">Contact Us</a></li>
		        <li><a href="about.php">About</a></li>
		        <li><a href="faq.php">FAQ</a></li>
		        <li><a href=""></a></li>
          	</ul>
          	<ul>
				<form action="search.php" name="search" method="post">	
					<div class="search-input">
						<i class="fa fa-search"></i><input type="text" name="search" class="searchinput" placeholder="Search..">
					</div>
				</form>
          	</ul>
		</div>
	 	 </div>
		</nav>
			<div class="sidenav1">
			<div id="mySidenav" class="sidenav">
			  <a href="index.php" id="about">Homepage<i class="fa fa-home test pull-right"></i></a>
			  <a href="profile.php" id="userprofile">My Profile<i class="fa fa-user test pull-right"></i></a>
			  <a href="showcat.php" id="blog">Categories<i class="fa fa-list test pull-right"></i></a>
			  <a href="viewCart.php" id="projects">My Cart<i class="fa fa-shopping-bag test pull-right"></i></a>
			  <a href="about.php" id="contact">About Us<i class="fa fa-book test pull-right"></i></a>
			  <a href="contact.php" id="contact2">Contact Us<i class="fa fa-phone test pull-right"></i></a>
			</div>
			</div>
