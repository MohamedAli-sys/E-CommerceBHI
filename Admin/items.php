<?php
	
	/*** Items Page ***/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Items';

	if(isset($_SESSION['Username'])) {
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if($do == 'Manage') {


				//Select All Users Except Admin

				$stmt = $con->prepare("SELECT 
											items.*, 
											categories.Name AS category_name, 
											users.UserName 
										FROM 
											items
										INNER JOIN categories ON categories.ID = items.Cat_ID
										INNER JOIN users ON users.UserID = items.User_ID 
										ORDER BY item_ID DESC");
					
				// Execute The Statement

				$stmt->execute();

				// Assign To Variable

				$items = $stmt->fetchAll();

				if(! empty($items)) {

			?>
			

			<h1 class="text-center">Manage Items</h1>
			<div class="container" style="margin-bottom: 20px;">		
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Adding Date</td>
							<td>Category</td>
							<td>UserName</td>
							<td>Control</td>
						</tr>

						<?php 

							foreach($items as $item) {
								echo "<tr>";
									echo "<td>" . $item['item_ID'] . "</td>";
									echo "<td>" . $item['Name'] . "</td>";
									echo "<td class='custom-desc-manage'><span class='more'>" . $item['Description'] . "</span></td>";
									echo "<td>$" . $item['Price'] . "</td>";
									echo "<td>" . $item['Add_Date'] . "</td>";
									echo "<td>" . $item['category_name'] . "</td>";
									echo "<td>" . $item['UserName'] . "</td>";
									echo "<td>
										<a href='items.php?do=Edit&itemid= " . $item['item_ID'] . " ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";

										if($item['Approve'] == 0) {
										echo "<a href='items.php?do=Approve&itemid= " . $item['item_ID'] . " ' class='btn btn-info approval'><i class='fa fa-check'></i> Approve</a>";
										}
									echo "<a href='items.php?do=Delete&itemid= " . $item['item_ID'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
									
									echo "</td>";				
								echo "</tr>";
							}

						 ?>
					</table>
				</div>
				<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>	
			</div>	

	<?php		} else {

				echo '<div class="container">';

					echo '<div class="nice-message"> There\'s No Items To Show </div>';
					echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';	

				echo '</div>';

			} 
		?>	

	<?php 

		} elseif ($do == 'Add') { ?>

			<h1 class="text-center">Add New Item</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=insert" method="POST">
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Name:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="name" class="form-control" required="required" placeholder="Name Of Item" />
									</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="description" class="form-control" required="required" placeholder="Description Of Item" />
									</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Price Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Price:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="price" class="form-control" required="required" placeholder="Price Of Item" />
									</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Country:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made" />
									</div>
							</div>
							<!-- End Country Field -->
							<!-- Start Status Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Status:</label>
									<div class="col-sm-10 col-md-8">
										<select name="status">
											<option value="0">...</option>
											<option value="1">New</option>
											<option value="2">Like New</option>
											<option value="3">Used</option>
											<option value="4">Old</option>
										</select>
									</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Members Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">User:</label>
									<div class="col-sm-10 col-md-8">
										<select name="user">
											<option value="0">...</option>
											<?php 
												$allUsers = getAllFrom("*", "users", "", "", "UserID");
												foreach ($allUsers as $user) {
													echo "<option value='" . $user['UserID'] . "'>" . $user['UserName'] . "</option>";
												}

											?>
										</select>
									</div>
							</div>
							<!-- End Members Field -->
							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Category:</label>
									<div class="col-sm-10 col-md-8">
										<select name="category">
											<option value="0">...</option>
											<?php 
												$allCats = getAllFrom("*", "categories", "where Parent_Cat = 0", "", "ID");
												foreach ($allCats as $cat) {
													echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
													$childCats = getAllFrom("*", "categories", "where Parent_Cat = {$cat['ID']}", "", "ID");
													foreach ($childCats as $child) {
														echo "<option value='" . $child['ID'] . "'>-- " . $child['Name'] . "</option>";
													}
												}

											?>
										</select>
									</div>
							</div>
							<!-- End Categories Field -->
							
							<!-- Start Tags Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Tags:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="tags" class="form-control" placeholder="Seprate Tags With (,)" />
									</div>
							</div>
							<!-- End Tags Field -->

							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Add item" class="btn btn-primary btn-sm" />
									</div>
							</div>
							<!-- End Submit Field -->


						</form>
					</div>

			<?php


		} elseif ($do == 'insert') {

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Item</h1>";
					echo "<div class='container'>";
					// Get Variables From The Form
					$name 		= $_POST['name'];
					$desc 		= $_POST['description'];
					$price 		= $_POST['price'];
					$country 	= $_POST['country'];
					$status 	= $_POST['status'];
					$user 		= $_POST['user'];
					$cat 		= $_POST['category'];
					$tags 		= $_POST['tags'];

					// Validate The Form
					$formErrors = array();

					if (empty($name)) {
						$formErrors[] = 'Name Can\'t Be<strong> Empty</strong>';
					}
					if (empty($desc)) {
						$formErrors[] = 'Description Can\'t Be<strong> Empty</strong>';
					}
					if (empty($price)) {
						$formErrors[] = 'Price Can\'t Be<strong> Empty</strong>';
					}
					if (empty($country)) {
						$formErrors[] = 'Country Can\'t Be<strong> Empty</strong>';
					}
					if ($status == 0) {
						$formErrors[] = 'You Must Choose The <strong>Status</strong>';
					}
					if ($user == 0) {
						$formErrors[] = 'You Must Choose The <strong>User</strong>';
					}
					if ($cat == 0) {
						$formErrors[] = 'You Must Choose The <strong>Category</strong>';
					}


					// Loop Into Errors Array And Echo It
					foreach($formErrors as $error) {
						echo '<div class="alert alert-danger">' . $error . '</div>';
					}

					// Check If There's No Error Proceed The Update Operation

					if (empty($formErrors)) {

							$stmt = $con->prepare("INSERT INTO 
														items (Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, User_ID, Tags) 
													VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, now(), :zcat, :zuser, :ztags) ");
							$stmt->execute(array(

									'zname' 	=> $name,
									'zdesc' 	=> $desc,
									'zprice' 	=> $price,
									'zcountry' 	=> $country,
									'zstatus' 	=> $status,
									'zuser'		=> $user,
									'zcat' 		=> $cat,
									'ztags' 	=> $tags

									));

							// Echo Success Massege

							$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Inserted</div>'; 
							redirectHome($theMsg, 'back', 5);
						}

				} else {
					echo "<div class='container'>";
					$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
					redirectHome($theMsg);
					echo "</div>";
				}

				echo '</div>';
		} elseif ($do == 'Edit') {

					// Check If Get Request Item ID Is Numric & Get The Integer Value Of It 

			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

			// Select All Data Depend On This ID 

			$stmt = $con->prepare("SELECT * FROM items WHERE item_ID = ?");
			
			// Execute Query

			$stmt->execute(array($itemid));
			
			// Fetch The Data 

			$item = $stmt->fetch();
			
			 // The Row Count 

			$count = $stmt->rowCount();
			// If There's Suck ID Show The Form
				if ($count > 0) { ?>

					<h1 class="text-center">Edit Item</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="itemid" value="<?php echo $itemid ?>" />
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Name:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="name" class="form-control" required="required" placeholder="Name Of Item" value="<?php echo $item['Name'] ?>" />
									</div>
							</div>
							<!-- End Name Field -->
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="description" class="form-control" required="required" placeholder="Description Of Item"
										value="<?php echo $item['Description'] ?>" />
									</div>
							</div>
							<!-- End Description Field -->
							<!-- Start Price Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Price:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="price" class="form-control" required="required" placeholder="Price Of Item" value="<?php echo $item['Price'] ?>" />
									</div>
							</div>
							<!-- End Price Field -->
							<!-- Start Country Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Country:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="country" class="form-control" required="required" placeholder="Country Of Made" value="<?php echo $item['Country_Made'] ?>" />
									</div>
							</div>
							<!-- End Country Field -->
							<!-- Start Status Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Status:</label>
									<div class="col-sm-10 col-md-8">
										<select name="status">
											<option value="1" <?php if($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
											<option value="2" <?php if($item['Status'] == 2) { echo 'selected'; } ?>>Like New</option>
											<option value="3" <?php if($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
											<option value="4" <?php if($item['Status'] == 4) { echo 'selected'; } ?>>Old</option>
										</select>
									</div>
							</div>
							<!-- End Status Field -->
							<!-- Start Members Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">User:</label>
									<div class="col-sm-10 col-md-8">
										<select name="user">
											<?php 

												$stmt = $con->prepare("SELECT * FROM users");
												$stmt->execute();
												$users = $stmt->fetchAll();
												foreach ($users as $user) {
													echo "<option value='" . $user['UserID'] . "'"; 
														if($item['User_ID'] == $user['UserID']) { echo 'selected'; } 
														echo ">" . $user['UserName'] . "</option>";
												}

											?>
										</select>
									</div>
							</div>
							<!-- End Members Field -->
							<!-- Start Categories Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Category:</label>
									<div class="col-sm-10 col-md-8">
										<select name="category">
											<?php 

												$stmt2 = $con->prepare("SELECT * FROM categories");
												$stmt2->execute();
												$cats = $stmt2->fetchAll();
												foreach ($cats as $cat) {
													echo "<option value='" . $cat['ID'] . "'"; 
													if($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
													echo ">" . $cat['Name'] . "</option>";
												}

											?>
										</select>
									</div>
							</div>
							<!-- End Categories Field -->
							<!-- Start Tags Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Tags:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="tags" class="form-control" placeholder="Seprate Tags With (,)" value="<?php echo $item['Tags'] ?>" />
									</div>
							</div>
							<!-- End Tags Field -->
							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Save Changes" class="btn btn-primary btn-sm" />
									</div>
							</div>
							<!-- End Submit Field -->
						</form>

						<?php
						//Select All Users Except Admin

						$stmt = $con->prepare("SELECT 
													comments.*, users.UserName AS User_Name
												FROM 
													comments
												INNER JOIN 
													users
												ON
													users.UserID = comments.User_ID	
												WHERE
													item_ID = ?

													");
							
						// Execute The Statement

						$stmt->execute(array($itemid));

						// Assign To Variable

						$rows = $stmt->fetchAll();

						if(! empty($rows)) {

					?>
					

						<h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
						<div class="table-responsive">
							<table class="main-table text-center table table-bordered">
								<tr>
									<td>Comment</td>
									<td>User Name</td>
									<td>Added Date</td>
									<td>Control</td>
								</tr>

								<?php 

									foreach($rows as $row) {
										echo "<tr>";
											echo "<td>" . $row['Comment'] . "</td>";
											echo "<td>" . $row['User_Name'] . "</td>";
											echo "<td>" . $row['Comment_Date'] . "</td>";
											echo "<td>
												<a href='comments.php?do=Edit&comid= " . $row['C_id'] . " ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
												if($row['Status'] == 0) {
												echo "<a href='comments.php?do=Approve&comid= " . $row['C_id'] . " ' class='btn btn-info approval'><i class='fa fa-check'></i> Approve</a>";
												}
											echo "<a href='comments.php?do=Delete&comid= " . $row['C_id'] . " ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete</a>";
											
											echo "</td>";				
										echo "</tr>";
									}

								 ?>
							</table>
						</div>
						<?php } else {

								echo "<div class='container'>";
								echo '<h1 class="text-center"> Manage ';
								echo $item['Name'];
								echo ' Comments </h1>';
								echo '</div>';
								echo '<div class="pager">';
								echo '<div class="alert alert-danger"><i class="fa fa-comments comment-B"> There\'s No Comments</i></div>';
								echo '</div>';
							} ?>
 					</div>

		<?php 

			// If There's No Such ID Show Error Message 
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>' ;
				redirectHome($theMsg, 5);
				echo "</div>";
			}

		} elseif ($do == 'Update') {

			echo "<h1 class='text-center'>Update Item</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['itemid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$price 		= $_POST['price'];
				$country 	= $_POST['country'];
				$status 	= $_POST['status'];
				$cat 		= $_POST['category'];
				$user 		= $_POST['user'];
				$tags 		= $_POST['tags'];


					// Validate The Form
					$formErrors = array();

					if (empty($name)) {
						$formErrors[] = 'Name Can\'t Be<strong> Empty</strong>';
					}
					if (empty($desc)) {
						$formErrors[] = 'Description Can\'t Be<strong> Empty</strong>';
					}
					if (empty($price)) {
						$formErrors[] = 'Price Can\'t Be<strong> Empty</strong>';
					}
					if (empty($country)) {
						$formErrors[] = 'Country Can\'t Be<strong> Empty</strong>';
					}
					if ($status == 0) {
						$formErrors[] = 'You Must Choose The <strong>Status</strong>';
					}
					if ($user == 0) {
						$formErrors[] = 'You Must Choose The <strong>User</strong>';
					}
					if ($cat == 0) {
						$formErrors[] = 'You Must Choose The <strong>Category</strong>';
					}


					// Loop Into Errors Array And Echo It
					foreach($formErrors as $error) {
						echo '<div class="alert alert-danger">' . $error . '</div>';
					}

				// Check If There's No Error Proceed The Update Operation

				if (empty($formErrors)) {

					// Update The DataBase With This Info

					$stmt = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,												
												Status = ?,
												Cat_ID = ?,
												User_ID = ?,
												Tags = ?
											Where 
												item_ID = ?");
					$stmt->execute(array($name, $desc, $price, $country, $status, $cat, $user, $tags, $id));

					// Echo Success Massege

					$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>'; 
					redirectHome($theMsg, 'back');

				}

			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
			}

			echo "</div>";

		} elseif ($do == 'Delete') {

			echo "<h1 class='text-center'>Delete Item</h1>";
			echo "<div class='container'>";
			
				// Check If Get Request ItemID Is Numric & Get The Integer Value Of It 

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('item_ID', 'items', $itemid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("DELETE FROM items WHERE item_ID = :zid");
						$stmt->bindParam(":zid", $itemid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted</div>'; 

						redirectHome($theMsg, 'back');

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;

					redirectHome($theMsg);
				}

			echo '</div';

		} elseif ($do == 'Approve') {
				echo "<h1 class='text-center'>Approve Item</h1>";
				echo "<div class='container'>";
			
				// Check If Get Request userid Is Numric & Get The Integer Value Of It 

				$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('item_ID', 'items', $itemid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  

						$stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
						$stmt->execute(array($itemid));
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Approval</div>'; 

						redirectHome($theMsg, 'back');

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;

					redirectHome($theMsg);
				}

			echo '</div';
		} 
		include $tpl . 'footer.php'; 
	} else {
		header('Location: index.php');
		exit();
	}
	ob_end_flush(); // Release The Output
?>

