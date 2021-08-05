<?php
	/*
	** Manage Comments Page 
	** You Can Edit, Delete, Approve Comments From Here
	*/
ob_start();
session_start();
$pageTitle = 'Orders';

	if(isset($_SESSION['Username'])) {

		include 'init.php';

		$do =  isset($_GET['do']) ? $_GET['do'] : 'Manage';

		//Start Manage Page

		if ($do == 'Manage') { // Manage Users Page 

				$query = '';
				if(isset($_GET['page']) && $_GET['page'] == 'Pending') {
					$query = 'AND Pending = 0';
				}

				//Select All Users Except Admin

				$stmt = $con->prepare("SELECT items.*, orders.*, users.* , order_items.* 
										FROM 
										items INNER JOIN orders INNER JOIN users INNER JOIN order_items
										ON 
										orders.item_ID = order_items.order_id  AND items.item_ID = order_items.product_id AND users.UserID = orders.user_id $query ORDER BY order_items.item_ID DESC");
				// Execute The Statement

				$stmt->execute();

				// Assign To Variable

				$orders = $stmt->fetchAll();
				
				if (! empty($orders)) {
			?>
			
			<h1 class="text-center">Manage Orders</h1>
			<div class="container">		
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>Done</td>
							<td>User Name</td>
							<td>Quantity Of Order</td>
							<td>Total Price</td>
							<td>Shipping</td>
							<td>Product</td>
							<td>Control</td>
						</tr>

						<?php 

							foreach($orders as $order) {
								echo "<tr>";
									echo "<td>" . $order['modified'] . "</td>";
									echo "<td>" . $order['UserName'] . "</td>";
									echo "<td>" . $order['quantity'] . "</td>";
									echo "<td>" . $order['total_price'] . "</td>";
									echo "<td>" . $order['Email'] . "</td>";
									echo "<td>" . $order['Name'] . "</td>";
									echo "<td>";
									$contact = $con->prepare("SELECT * FROM contact_us WHERE user_id = {$order['UserID']}");
									$contact->execute();
									$cont = $contact->fetchAll();
									if(!empty($cont) && $cont['user_id'] = $order['UserID']) {
										echo "<a href='contact.php?do=Reply&contid= " . $order['UserID'] . " ' class='btn btn-success'><i class='fa fa-comment'></i> Reply</a>";
									}
									if($order['Pending'] == 0) {
										echo "<a href='orders.php?do=Approve&order= " . $order['item_ID'] . " ' class='btn btn-info'><i class='fa fa-check'></i> Approve</a>";
									}
									echo "<a href='orders.php?do=Delete&order= " . $order['item_ID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
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
			} ?>

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
							<input type="hidden" name="comid" value="<?php echo $comid ?>" />
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
		} elseif ($do == 'Delete') { 
			echo "<h1 class='text-center'>Cancel Order</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request userid Is Numric & Get The Integer Value Of It 

				$ordid = isset($_GET['order']) && is_numeric($_GET['order']) ? intval($_GET['order']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('item_ID', 'order_items', $ordid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("DELETE FROM order_items WHERE item_ID = :zid");
						$stmt->bindParam(":zid", $ordid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted</div>'; 

						redirectHome($theMsg, 'back');

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;

					redirectHome($theMsg);
				}

			echo '</div>';
		} elseif ($do = 'Approve') {
			echo "<h1 class='text-center'>Approve Order</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request comid Is Numric & Get The Integer Value Of It 

				$ordid = isset($_GET['order']) && is_numeric($_GET['order']) ? intval($_GET['order']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('item_ID', 'order_items', $ordid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("UPDATE order_items SET Pending = 1 WHERE item_ID = ?");
						$stmt->execute(array($ordid));
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