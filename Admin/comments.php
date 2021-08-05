<?php  
	/*
	** Manage Comments Page 
	** You Can Edit, Delete, Approve Comments From Here
	*/

		session_start();
		$pageTitle = 'Comments';

	if(isset($_SESSION['Username'])) {

		include 'init.php';

		$do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';

		//Start Manage Page

		if ($do == 'Manage') { // Manage Users Page 

				//Select All Users Except Admin

				$stmt = $con->prepare("SELECT 
											comments.*, items.Name AS Item_Name, users.UserName AS User_Name
										FROM 
											comments
										INNER JOIN
											items
										ON 
											items.item_ID = comments.item_ID
										INNER JOIN 
											users
										ON
											users.UserID = comments.User_ID
										ORDER BY 
											C_id DESC");
					
				// Execute The Statement

				$stmt->execute();

				// Assign To Variable

				$comments = $stmt->fetchAll();
				if (! empty($comments)) {

			?>
			

			<h1 class="text-center">Manage Comments</h1>
			<div class="container">		
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Comment</td>
							<td>Item Name</td>
							<td>User Name</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>
						<?php 
							foreach($comments as $comment) {
								echo "<tr>";
									echo "<td>" . $comment['C_id'] . "</td>";
									echo "<td>" . $comment['Comment'] . "</td>";
									echo "<td>" . $comment['Item_Name'] . "</td>";
									echo "<td>" . $comment['User_Name'] . "</td>";
									echo "<td>" . $comment['Comment_Date'] . "</td>";
									echo "<td>
										<a href='comments.php?do=Edit&comid= " . $comment['C_id'] . " ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
										if($comment['Status'] == 0) {
										echo "<a href='comments.php?do=Approve&comid= " . $comment['C_id'] . " ' class='btn btn-info approval'><i class='fa fa-check'></i> Approve</a>";
										}
									echo "<a href='comments.php?do=Delete&comid= " . $comment['C_id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";		
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

	<?php } elseif ($do == 'Edit') { //Edit Page 

			// Check If Get Request Comment ID Is Numric & Get The Integer Value Of It 

			$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

			// Select All Data Depend On This ID 

			$stmt = $con->prepare("SELECT * FROM comments WHERE C_id = ? ");
			
			// Execute Query

			$stmt->execute(array($comid));
			
			// Fetch The Data 

			$comment = $stmt->fetch();
			
			 // The Row Count 

			$count = $stmt->rowCount();
			// If There's Suck ID Show The Form
				if ($count > 0) { ?>

					<h1 class="text-center">Edit Comment</h1>
					
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="comid" value="<?php echo $comid ?>" />
							<!-- Start Comment Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Comment:</label>
									<div class="col-sm-10 col-md-8">
										<textarea class="form-control" name="comment"><?php echo $comment['Comment'] ?></textarea>
									</div>
							</div>
							<!-- End Comment Field -->
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

			echo "<h1 class='text-center'>Update Comment</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$comid 		= $_POST['comid'];
				$comment 	= $_POST['comment'];

					// Update The DataBase With This Info

					$stmt = $con->prepare("UPDATE comments SET comment = ? WHERE C_id = ?");
					$stmt->execute(array($comment, $comid));

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

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('C_id', 'comments', $comid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("DELETE FROM comments WHERE C_id = :zid");
						$stmt->bindParam(":zid", $comid);
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