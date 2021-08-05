<?php
	ob_start();
	session_start();
	$pageTitle = 'Contact Us';
	include 'init.php'; 

	if(isset($_SESSION['User'])) {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$formErrors = array();

			$fname 		= filter_var($_POST['first_name'], FILTER_SANITIZE_STRING);
			$lname 		= filter_var($_POST['last_name'], FILTER_SANITIZE_STRING);
			$message	= filter_var($_POST['message'], FILTER_SANITIZE_STRING);
			$email	 	= filter_var($_POST['email'], FILTER_SANITIZE_STRING);

			if (empty($fname)) {
				$formErrors[] = 'You Must Enter Your First Name';
			}


			if (empty($lname)) {
				$formErrors[] = 'You Must Enter Your Last Name';
			}


			if (empty($message)) {
				$formErrors[] = 'You Must Enter Your Message';
			}

			if (empty($email)) {
				$formErrors[] = 'You Must Enter Your Email';
			}

			$headers = 'From: ' . $email . '\r\n';
			$myEmail = 'good.heart52244@gmail.com';
			$subject = 'Contact Form';

			if (empty($formErrors)) {
				/*
				mail($myEmail, $subject, $message, $headers);
				ini_set("SMTP","nyu.smtp.edu");
				ini_set("smtp_port","587");
				*/
				$stmt = $con->prepare("INSERT INTO 
											contact_us (user_id, first_name, last_name, message, email, date) 
										VALUES(:zuser, :zfname, :zlname, :zmsg, :zmail, now()) ");
				$stmt->execute(array(

						'zuser' 	=> $_SESSION['uid'],
						'zfname' 	=> $fname,
						'zlname' 	=> $lname,
						'zmsg' 		=> $message,
						'zmail' 	=> $email
						));

				// Echo Success Massege
				if($stmt) {
					$successMsg = 'Your Message Has Been Sent Waiting For Reply At Your Email : ' . $email . '.';
					$fname = '';
					$lname = '';
					$message = '';
					$email = '';
					
				}
			}
		}

?>

	<h1 class="text-center"><?php echo $pageTitle ?></h1>
	<img class="img-thumbnail contact-image" src="images/contact.jpg" alt="Company Team">
	<div class="create-ad block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $pageTitle ?></div>
				<div class="panel-body">
					<div class="row">
						<?php
							if(! empty($formErrors)) {
								foreach ($formErrors as $error) {
									echo '<div class="col-md-12">';
									echo '<div class="alert alert-danger alert-msg">' . $error . '</div>';
									echo '</div>';
								}
							}
							if (isset($successMsg)) {
								echo '<div class="col-md-12">';
								echo '<div class="alert alert-success alert-msg">' . $successMsg  . '</div>';
								echo '</div>';
							}
						?>
						<div class="col-md-10">
							<form class="form-horizontal main-form contact-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
								<!-- Start Name Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">First Name:</label>
										<div class="col-sm-10 col-md-9">
											<input pattern=".{3,}" title="This Field Require At Least 3 Characters" type="text" name="first_name" class="form-control live fname" required="required" placeholder="Your Name" data-class=".live-title" required="required" value="<?php if(isset($fname)) { echo $fname; } ?>" /><i class="fa fa-user fa-fw"></i>
											<div class="alert alert-danger custom-alert">
												First Name Must Be Larger Than <strong><ins>3</ins></strong> Characters...
											</div>	
										</div>
								</div>
								<!-- End Name Field -->
								<!-- Start Description Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Last Name:</label>
										<div class="col-sm-10 col-md-9">
											<input pattern=".{3,}" title="This Field Require At Least 3 Characters" type="text" name="last_name" class="form-control lname" required="required" placeholder="Your Last Name" data-class=".live-desc" required="required" value="<?php if(isset($lname)) { echo $lname; } ?>" /><i class="fa fa-user fa-fw"></i>
											<div class="alert alert-danger custom-alert">
												First Name Must Be Larger Than <strong><ins>3</ins></strong> Characters...
											</div>	
										</div>
								</div>
								<!-- End Description Field -->
								
								<!-- Start Country Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Email:</label>
										<div class="col-sm-10 col-md-9">
											<input type="email" name="email" class="form-control email" required="required" placeholder="Your Email For Contact" required="required" value="<?php if(isset($email)) { echo $email; } ?>" /><i class="fa fa-envelope-o fa-fw"></i>
											<div class="alert alert-danger custom-alert">
												Email Can't Be <strong>Empty</strong> ...
											</div>	
										</div>
								</div>
								<!-- End Country Field -->
								<!-- Start Price Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Message:</label>
										<div class="col-sm-10 col-md-9">
											<textarea pattern=".{10,}" type="text" name="message" class="form-control message" rows="6" required="required" placeholder="Write Your Message Here..." data-class=".live-price" required="required" value="<?php if(isset($message)) { echo $message; } ?>" ></textarea>
											<div class="alert alert-danger custom-alert">
												Message Can't Be Less Than <strong><ins>10</ins></strong> Characters...
											</div>	
										</div>
								</div>
								<!-- End Price Field -->

								<!-- Start Submit Field -->
								<div class="form-group">
										<div class="col-sm-offset-3 col-sm-10">
											<input type="submit" value="Send Message" class="btn btn-primary btn-sm cont-send" />
											<i class="fa fa-send fa-fw send-icon"></i>
										</div>
								</div>
								<!-- End Submit Field -->
							</form>
						</div>
						
						<!-- Start Looping Through Errors -->
						
						<!-- End Looping Through Errors -->
					</div>
				</div>
			</div>
		</div>
<?php	
	} else {
		header('Location: login.php');
		exit();
	}
	include $tpl . 'Footer.php';
	ob_end_flush();
 ?>