<?php
	ob_start();
	session_start();
	$pageTitle = 'Login';

	if(isset($_SESSION['User'])) {
		header('Location: index.php'); 
	}
	include 'init.php';

	// Check If User Coming From HTTP Post Request 

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		if(isset($_POST['login'])) {

			$user = $_POST['username'];
			$pass = $_POST['password'];
			$hashedPass = sha1($pass);

			// Check If The User Exist In Database

			$stmt = $con->prepare("SELECT
										UserID, Username, Password 
									FROM 
										users 
									WHERE 
										Username = ? 
									AND 
										Password = ?");
			$stmt->execute(array($user, $hashedPass));
			$get = $stmt->fetch();
			$count = $stmt->rowCount();


			// If Count > 0 This Mean The Database Contain Information About This Username

			if ($count > 0) {
				$_SESSION['User'] = $user; // Register Session name
				$_SESSION['uid'] = $get['UserID'];
				header('Location: index.php'); 
				exit();
			}
		} else {

			$formErrors = array();

			$username 	= $_POST['username'];
			$password 	= $_POST['password'];
			$password2 	= $_POST['password2'];
			$email 		= $_POST['email'];
			$funame 	= $_POST['FullName'];


			if (isset($username)) {

				$filterdUser = filter_var($username, FILTER_SANITIZE_STRING);
				if(strlen($filterdUser) < 4) {
					$formErrors[] = 'User Name Must Be Larger Than 4 Characters';
				}
			}

			if (isset($password) && isset($password2)) {
				if (empty($password)) {
					$formErrors[] = 'Sorry Password Can\'t Be Empty';
				}

				if (sha1($password) !== sha1($password2)) {
					$formErrors[] = 'Sorry Password Is Not Match';
				}

			}

			if (isset($email)) {

				$filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
				if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {
					$formErrors[] = 'This Email Is Not Valid';
				}
			}

/* Start Upload Users Photo */


	//if ($_SERVER['REQUEST_METHOD'] == 'POST') {


		foreach($_FILES['fileToUpload']['name'] as $i => $name) {
			$name = $_FILES['fileToUpload']['name'][$i];
			$size = $_FILES['fileToUpload']['size'][$i];
			$type = $_FILES['fileToUpload']['type'][$i];
			$tmp = $_FILES['fileToUpload']['tmp_name'][$i];
			
			$explode = explode('.', $name);

			$ext = end($explode);

			$pathF = 'uploads/';
			$pathF = $pathF . basename($explode[0] . time() .'.'. $ext); 

			$errors = array();

			if(empty($_FILES['fileToUpload']['tmp_name'][$i])) {
				$errors[] = 'Please Choose At Least 1 File To Be Uploaded.';
			} else {
				$allowed = array('jpg', 'jpeg', 'gif', 'bmp', 'png');
				$max_size = 4000000;
				if(in_array($ext, $allowed) === false) {
					$errors[] = 'The File ' . $name . ' Extension In Not Allow';
				}
				if($size > $max_size) {
					$errors[] = 'The File ' . $name . ' Size Is Too Hight.';
				}
			}
				if(empty($errors)) {
					if(!file_exists('uploads')) {
						mkdir('uploads', 0777);
					}
			 	if(empty($errors)) {
					if(move_uploaded_file($tmp, $pathF)) {
					
					$path = 'http://localhost/eCommerce/uploads/' . basename($explode[0] . time() .'.'. $ext);
					}
				} 
			}
		} 
	//}
					
/* End Upload */			


				// Check If There's No Error Proceed The User Add
				if (empty($formErrors)) {

						$check = checkItem("UserName", "users", $username);

						if ($check == 1) {
							$formErrors[] = 'Sorry This User Is Exist';
						} else {
							
							$stmt = $con->prepare("INSERT INTO 
														users(UserName, Password, Email, FullName, image_src, RegStatus, Date) 
													VALUES(:zname, :zpass, :zmail, :zfuname, :zpath, 1, now())");
							$stmt->execute(array(
									'zname' 	=> $username,
									'zpass' 	=> sha1($password),
									'zmail' 	=> $email,
									'zfuname'	=> $funame,
									'zpath' 	=> $path 
									));

							// Echo Success Massege

							$successMsg = 'Congrats You Are Now Registered';
							$theMsg = '';
						}

					}	
			
				}
			}
				
?>

<div class="container login-page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | 
		<span data-class="signup">SignUp</span>
	</h1>
	<!-- Start Login Form -->
	<div class="custom-form">
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<div class="input-container">
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName"  required="required" /></div>
		<div class="input-container">
			<input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required" /></div>

			<input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
	</form>
	<!-- End Login Form -->

	<!-- Start Signup Form -->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
		<div class="input-container">
			<input pattern=".{4,16}" title="User Name Must Be Between 4 & 16 Chars" class="form-control" type="text" name="username" autocomplete="off" placeholder="UserName" required="required" /></div>
		<div class="input-container">
			<input pattern=".{4,25}" title="User Name Must Be Between 4 & 16 Chars" class="form-control" type="text" name="FullName" autocomplete="off" placeholder="Full Name" required="required" /></div>
		<div class="input-container">
			<input minlength="7" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Password" required="required"/></div>
		<div class="input-container">
			<input minlength="7" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Confirm Password" required="required" /></div>
		<div class="input-container">
			<input class="form-control" type="email" name="email" placeholder="Valid Email" required="required"/></div>
		<div class="image-div-signup">	
			<label for="fileToUpload" class="file-label-signup"><i class="fa fa-upload"></i>
				<span id="label-span">Select Your Avatar</span></label>
				<input type="file" name="fileToUpload[]" id="fileToUpload" required="required" />
		</div>
			<input class="btn btn-success btn-block" name="signup" type="submit" value="Signup" />

				

	</form>
	</div>
	<!-- End Signup Form -->
	<div class="the-errors text-center">
		<?php 
			if(! empty($formErrors)) {
				foreach ($formErrors as $error) {
					echo '<div class="msg error">' . $error . '</div>';
				}
			}

			if (isset($successMsg)) {
				echo '<div class="msg success">' . $successMsg  . '</div>';
				header ("location: index.php", "5");
				echo $theMsg;
			}


		 ?>
	</div>
</div>

<?php 
	include $tpl . 'Footer.php';
	ob_end_flush();
?>