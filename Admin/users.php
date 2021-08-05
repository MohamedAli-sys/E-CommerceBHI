<?php
	/*
	** Manage Users Page 
	** You Can Add, Edit, Delete Users From Here
	*/

		session_start();
		$pageTitle = 'Users';

	if(isset($_SESSION['Username'])) {

		include 'init.php';

		$do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';

		//Start Manage Page

		if ($do == 'Manage') { // Manage Users Page 

			$query = '';
			if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
				$query = 'AND RegStatus = 0';
			}


				//Select All Users Except Admin

				$stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
					
				// Execute The Statement

				$stmt->execute();

				// Assign To Variable

				$rows = $stmt->fetchAll();

				if(! empty($rows)) {
			?>
			

			<h1 class="text-center">Manage Users</h1>
			<div class="container">		
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>UserName</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Register Date</td>
							<td>Control</td>
						</tr>

						<?php 

							foreach($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>" . $row['UserName'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['FullName'] . "</td>";
									echo "<td>" . $row['Date'] . "</td>";
									echo "<td>
										<a href='users.php?do=Edit&userid= " . $row['UserID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
										if($row['RegStatus'] == 0) {

										echo "<a href='users.php?do=Approval&userid= " . $row['UserID'] . " ' class='btn btn-info approval'><i class='fa fa-check'></i> Approval</a>";
										}
									echo "<a href='users.php?do=Delete&userid= " . $row['UserID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
									
									echo "</td>";				
								echo "</tr>";
							}

						 ?>
					</table>
				</div>
				<a href="users.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New User</a>	
			</div>	

			<?php 

			} else {

				echo '<div class="container">';

					echo '<div class="nice-message"> There\'s No Record To Show </div>';
					echo '<a href="users.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> New User</a>';

				echo '</div>';

			}

			?>
	<?php	} elseif ($do == 'Add') {	// Add Members Page ?>
					<h1 class="text-center">Add New User</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=insert" method="POST">
							<!-- Start UserName Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">UserName:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="username" class="form-control" autocomplete="off" required="Required" placeholder="User Name To Login Into Shop" />
									</div>
							</div>
							<!-- End UserName Field -->
							
							<!-- Start Password Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Password:</label>
									<div class="col-sm-10 col-md-8">
										<input type="password" name="password" class="password form-control" autocomplete="new-password" required="Required" placeholder="Password Must Be Hard" />
										<i class="show-pass fa fa-eye fa-2x"></i>
									</div>
							</div>
							<!-- End Password Field -->

							<!-- Start Email Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Email:</label>
									<div class="col-sm-10 col-md-8">
										<input type="email" name="email" class="form-control" required="Required" placeholder="Email Must Be Valid" />
									</div>
							</div>
							<!-- End Email Field -->

							<!-- Start Full Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Full Name:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="full" class="form-control" required="Required" placeholder="Full Name In Your Profile Page" />
									</div>
							</div>
							<!-- End Full Name Field -->

							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Add User" class="btn btn-primary btn-lg" />
									</div>
							</div>
							<!-- End Submit Field -->


						</form>
					</div>

	<?php 	

			} elseif ($do == 'insert') {
				// Insert Member Page
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Member</h1>";
					echo "<div class='container'>";
					// Get Variables From The Form
					$user 	= $_POST['username'];
					$pass 	= $_POST['password'];
					$email 	= $_POST['email'];
					$name 	= $_POST['full'];
					$hashPass = sha1($_POST['password']);
					// Validate The Form
					$formErrors = array();

					if (strlen($user) < 4) {
						$formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
					}
					if (strlen($user) > 20) {
						$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
					}
					if (empty($user)) {
						$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
					}
					if (empty($pass)) {
						$formErrors[] = 'Password Cant Be <strong>Empty</strong>';
					}
					if (empty($name)) {
						$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
					}
					if (empty($email)) {
						$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
					}
					// Loop Into Errors Array And Echo It
					foreach($formErrors as $error) {
						echo '<div class="alert alert-danger">' . $error . '</div>';
					}

					// Check If There's No Error Proceed The Update Operation

					if (empty($formErrors)) {

						// Insert User Info In Database

						$check = checkItem("UserName", "users", $user);

						if($check == 1) {
							$theMsg = "<div class='alert alert-danger'>" .  '<strong>Sorry This User Is Exist</strong></div>';
							redirectHome($theMsg, 'back', 5);
						} else {

							$stmt = $con->prepare("INSERT INTO 
														users (UserName, Password, Email, FullName, RegStatus, Date) 
													VALUES(:zname, :zpass, :zmail, :zname, 1, now()) ");
							$stmt->execute(array(

									'zname' => $user,
									'zpass' => $hashPass,
									'zmail' => $email,
									'zname' => $name 
									));

							// Echo Success Massege

							$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Inserted</div>'; 
							redirectHome($theMsg, 'back', 5);
						}
					}

				} else {
					echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
					redirectHome($theMsg);
					echo "</div>";
				}

				echo '</div>';

			} elseif ($do == 'Edit') { //Edit Page 

			// Check If Get Request userid Is Numric & Get The Integer Value Of It 

			$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

			// Select All Data Depend On This ID 

			$stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
			
			// Execute Query

			$stmt->execute(array($userid));
			
			// Fetch The Data 

			$row = $stmt->fetch();
			
			 // The Row Count 

			$count = $stmt->rowCount();
			// If There's Suck ID Show The Form
				if ($count > 0) { ?>

					<h1 class="text-center">Edit User</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="userid" value="<?php echo $userid ?>" />
							<!-- Start UserName Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">UserName:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="username" class="form-control" value="<?php echo $row['UserName']; ?>" autocomplete="off" required="Required" />
									</div>
							</div>
							<!-- End UserName Field -->
							
							<!-- Start Password Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Password:</label>
									<div class="col-sm-10 col-md-8">
										<input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>" />
										<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
									</div>
							</div>
							<!-- End Password Field -->

							<!-- Start Email Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Email:</label>
									<div class="col-sm-10 col-md-8">
										<input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>"  required="Required" />
									</div>
							</div>
							<!-- End Email Field -->

							<!-- Start Full Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Full Name:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="full" class="form-control" value="<?php echo $row['FullName']; ?>"  required="Required" />
									</div>
							</div>
							<!-- End Full Name Field -->

							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Save" class="btn btn-primary btn-lg" />
									</div>
							</div>
							<!-- End Submit Field -->


						</form>
					</div>

		<?php 

			// If There's No Such ID Show Error Message 
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>' ;
				redirectHome($theMsg, 5);
				echo "</div>";
			}
		} elseif ($do == 'Update') { // Update Page

			echo "<h1 class='text-center'>Update User</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 	= $_POST['userid'];
				$user 	= $_POST['username'];
				$email 	= $_POST['email'];
				$name 	= $_POST['full'];

				// Password Trick	
				$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

				// Validate The Form

				$formErrors = array();

				if(strlen($user) < 6 ) {
					$formErrors[] = 'Username Cant Be Less Than <strong>6 Characters</strong>';
				}

				if(strlen($user) > 20 ) {
					$formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
				}
				if (empty($user)) {
					$formErrors[] = 'Username Cant Be <strong>Empty</strong>';
				}
				if (empty($name)) {
					$formErrors[] = 'Full Name Cant Be <strong>Empty</strong>';
				}
				if (empty($email)) {
					$formErrors[] = 'Email Cant Be <strong>Empty</strong>';
				}

				// Loop Into Errors Array And Echo It

				foreach ($formErrors as $error) {
					echo '<div class="alert alert-danger">' . $error . '</div>' ;
				}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					$stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
					$stmt2->execute(array($user, $id));
					$count = $stmt2->rowCount();

					if ($count == 1) {
						echo '<div class="alert alert-danger">  Sorry This User Is Exist</div>';
						redirectHome($theMsg, 'back');
					} else {

					// Update The DataBase With This Info

					$stmt = $con->prepare("UPDATE users SET UserName = ?, Email = ?, FullName = ?, Password = ? Where UserID = ?");
					$stmt->execute(array($user, $email, $name, $pass, $id));

					// Echo Success Massege

					$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>'; 
					redirectHome($theMsg, 'back', 6);
					}
				}

			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
			}

			echo "</div>";

		} elseif ($do == 'Delete') { // Delete Users Page
			echo "<h1 class='text-center'>Delete User</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request userid Is Numric & Get The Integer Value Of It 

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('userid', 'users', $userid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("DELETE FROM users WHERE UserID = :userid");
						$stmt->bindParam(":userid", $userid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted</div>'; 

						redirectHome($theMsg, 'back');

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;

					redirectHome($theMsg);
				}

			echo '</div';
		} elseif ($do = 'Approval') {
			echo "<h1 class='text-center'>Approval User</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request userid Is Numric & Get The Integer Value Of It 

				$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('userid', 'users', $userid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
						$stmt->execute(array($userid));
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Approval</div>'; 

						redirectHome($theMsg);

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;

					redirectHome($theMsg);
				}

			echo '</div';
		}

		include $tpl . 'Footer.php'; 
	} else {
		header('Location: index.php');
		exit(); 
	}