<?php
	ob_start();
	session_start();
	$pageTitle = 'About Us';
	include 'init.php';
?>

        <!-- Start About Us Intro -->
        
        <section class="about-us text-center">
            <div class="container">
                <h1 class="about">About Us</h1>
                <hr>
                <p class="lead">We Create Your Digital Dreams Just Think And Tell Us What You Need <br> Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                <img class="img-thumbnail" src="images/company_team.jpg" alt="Company Team">
            </div>
        </section>
        
        <!-- End About Us Intro -->
        
        <!-- Start About Features -->
        
        <section class="about-features text-center">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <i class="fa fa-code fa-5x"></i>
                        <h3>We Love Code</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-child fa-5x"></i>
                        <h3>We Are Happy</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                    <div class="col-sm-4">
                        <i class="fa fa-group fa-5x"></i>
                        <h3>We Are Social</h3>
                        <p>We Create Your Digital Dreams Just Think And Tell Us What You Need Or Leave The Inspiration To Us And Just Watch, Just Believe In Our Professionals</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- End About Features -->
        
        <!-- Start About CEO -->
        
        <section class="about-ceo">
            <div class="container">
                <div class="row">
                    <div class="col-sm-5">
                        <img class="img-responsive" src="images/ceo.png" alt="CEO">
                    </div>
                    <div class="col-sm-7">
                        <h2 class="h1">We Are Happy To Help You</h2>
                        <p class="lead">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        <a href="contact.php" class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </section>
        
<?php 
	include $tpl . 'Footer.php';
	ob_end_flush();
?>