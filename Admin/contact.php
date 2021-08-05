<?php
	/*
	** Manage Comments Page 
	** You Can Edit, Delete, Approve Comments From Here
	*/

		session_start();
		$pageTitle = 'Contact';

	if(isset($_SESSION['Username'])) {

		include 'init.php';

		$do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';

		//Start Manage Page

		if ($do == 'Manage') { // Manage Users Page 

				//Select All Users Except Admin

				$stmt = $con->prepare("SELECT 
											contact_us.*, users.UserName AS UserName
										FROM 
											contact_us
										INNER JOIN
											users
										ON 
											users.UserID = contact_us.user_id
										ORDER BY 
											id DESC");
					
				// Execute The Statement
				$stmt->execute();
				// Assign To Variable

				$contacts = $stmt->fetchAll();
				if (! empty($contacts)) {

			?>
			<h1 class="text-center">Manage Message</h1>
			<div class="container">		
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Message</td>
							<td>User Name</td>
							<td>Email</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>
						<?php 
							foreach($contacts as $contact) {
								echo "<tr>";
									echo "<td>" . $contact['id'] . "</td>";
									echo "<td>" . $contact['message'] . "</td>";
									echo "<td>" . $contact['UserName'] . "</td>";
									echo "<td>" . $contact['email'] . "</td>";
									echo "<td>" . $contact['date'] . "</td>";
									echo "<td>";
									echo "<a href='contact.php?do=Reply&contid= " . $contact['id'] . " ' class='btn btn-success'><i class='fa fa-comment'></i> Reply</a>";
									echo "<a href='contact.php?do=Delete&contid= " . $contact['id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
									echo "</td>";				
								echo "</tr>";
							}
						 ?>
					</table>
				</div>
			</div>	
			<?php 

			} else {
				echo '<div class="container">';
					echo '<div class="nice-message"> There\'s No Comments To Show </div>';
				echo '</div>';
			}

			?>

	<?php } elseif ($do == 'Reply') { //Edit Page 

			// Check If Get Request Comment ID Is Numric & Get The Integer Value Of It 

			$contid = isset($_GET['contid']) && is_numeric($_GET['contid']) ? intval($_GET['contid']) : 0;

			// Select All Data Depend On This ID 

			$stmt = $con->prepare("SELECT * FROM contact_us WHERE id = ? ");
			
			// Execute Query

			$stmt->execute(array($contid));
			
			// Fetch The Data 

			$contact = $stmt->fetch();
			
			 // The Row Count 

			$count = $stmt->rowCount();
			// If There's Suck ID Show The Form
				if ($count > 0) { ?>

					<h1 class="text-center">Reply Message</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="conR" value="<?php echo $contid ?>" />
							<!-- Start Comment Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Message:</label>
									<div class="col-sm-10 col-md-8">
										<textarea class="form-control" name="comment"><?php echo $contact['message'] ?></textarea>
									</div>
							</div>
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Reply:</label>
									<div class="col-sm-10 col-md-8">
										<textarea class="form-control" placeholder="Reply The Message..." name="replymsg"></textarea>
									</div>
							</div>
							<!-- End Comment Field -->
							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Send" class="btn btn-primary btn-lg" />
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

			echo "<h1 class='text-center'>Message Sent</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form
				$uid 		= '1';
				$fname 		= 'Admin';
				$lname 		= 'Mohamed';
				$contid 	= $_POST['conR'];
				$ContR 		= $_POST['replymsg'];
				$Aemail		= 'Good.Heart@Gmail.com';
					// Update The DataBase With This Info
					$stmt = $con->prepare("INSERT INTO contact_us (user_id, first_name, last_name, message, email, date)
												VALUES (:zusid, :zfname, :zlname, :zmsg, :zmail, now())");
					$stmt->execute(array(
						'zusid'		=> 	$uid,
						'zfname'	=>	$fname,
						'zlname'	=>	$lname,
						'zmsg'		=>	$ContR,
						'zmail'		=>	$Aemail
						));
					// Echo Success Massege
					$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>'; 
					redirectHome($theMsg, 'back');
			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
			}
			echo "</div>";
		} elseif ($do == 'Delete') { // Delete Users Page
			echo "<h1 class='text-center'>Delete Comment</h1>";
			echo "<div class='container'>";
				// Check If Get Request userid Is Numric & Get The Integer Value Of It 
				$contid = isset($_GET['contid']) && is_numeric($_GET['contid']) ? intval($_GET['contid']) : 0;
				// Select All Data Depend On This ID 
				$check = checkItem('id', 'contact_us', $contid);
				// If There's Suck ID Show The Form
					if ($check > 0) {  
						$stmt = $con->prepare("DELETE FROM contact_us WHERE id = :zid");
						$stmt->bindParam(":zid", $contid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted</div>'; 

						redirectHome($theMsg, 'back');

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;
					redirectHome($theMsg);
				}

			echo '</div>';
		} elseif ($do = 'Approve') {
			echo "<h1 class='text-center'>Approve Comment</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request comid Is Numric & Get The Integer Value Of It 

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('C_id', 'comments', $comid);
				// If There's Suck ID Show The Form
					if ($check > 0) {  
						$stmt = $con->prepare("UPDATE comments SET Status = 1 WHERE C_id = ?");
						$stmt->execute(array($comid));
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Approved</div>'; 
						redirectHome($theMsg, 'back');
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