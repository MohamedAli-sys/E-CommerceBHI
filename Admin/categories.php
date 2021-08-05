<?php
	
	/*** Category Page ***/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Categories';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$sort = 'asc';
			$sort_array = array('asc', 'desc');
			if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
				$sort = $_GET['sort'];
			}

			$stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent_Cat = 0 ORDER BY ordering $sort");
			$stmt2->execute();
			$cats = $stmt2->fetchAll();
			if(! empty($cats)) {
			 
			 ?>
			
			<h1 class="text-center"> Manage Categories </h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit"></i> Manage Categories
						<div class="option pull-right">
							<i class="fa fa-sort"></i> Ordering: [
							<a class="<?php if($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> | 
							<a class="<?php if($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
							<i class="fa fa-eye"></i> View: [
							<span class="active" data-view="full">Full</span> |
							<span data-view="classic">Classic</span> ]
						</div>
					</div>
						<div class="panel-body">
			<?php	
						foreach($cats as $cat) {
							echo "<div class='cat'>";
								echo "<div class='hidden-buttons'>";
									echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
									echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
								echo "</div>";
								echo "<h3>" . $cat['Name'] . "</h3>";
								echo "<div class='full-view'>";
									echo "<p>"; if($cat['Description'] == '') { echo 'No Description'; } else { echo $cat['Description'];} echo "</p>";
									if($cat['Visibility'] == 1) { echo  '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>'; }
									if($cat['Allow_Comment'] == 1) { echo  '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>'; }
									if($cat['Allow_Ads'] == 1) { echo  '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>'; }
									// Get Parent Categories
									$childCats = getAllFrom("*", "categories", "where Parent_Cat = {$cat['ID']}", "", "ID", "ASC");
										if(!empty($childCats)) {
										echo "<ul class='list-unstyled child-cats'>";
								      	foreach($childCats as $c_cat) {
								      		echo "<li class='child-link'>
								      			<a href='categories.php?do=Edit&catid=" . $c_cat['ID'] . "' >" . $c_cat['Name'] . "</a>
								      			<a href='categories.php?do=Delete&catid=" . $c_cat['ID'] . "' class='show-delete confirm btn btn-xs btn-danger'><i class='fa fa-trash'></i> </a>
								      			</li>";
								      	}
								      	echo "</ul>";
								      	}
								echo "</div>";
							echo "</div>";
						   	echo "<hr>";
						}
			?>
						</div>
				</div>
				<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i>  New Category</a>
			</div>


	<?php  } else {
				echo '<div class="container">';

					echo '<div class="nice-message"> There\'s No Category To Show </div>';
					echo '<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> New Category</a>';
				echo '</div>';

			} 
		?>	
	<?php		

		} elseif ($do == 'Add') { ?>

					<h1 class="text-center">Add New Category</h1>
					<div class="container">
						<form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
							<!-- Start Name Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Name:</label>
									<div class="col-sm-10 col-md-8">
										<input type="text" name="name" class="form-control" autocomplete="off" required="Required" placeholder="Name Of Category" />
									</div>
							</div>
							<!-- End UserName Field -->
							
							<!-- Start Description Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Description:</label>
									<div class="col-sm-10 col-md-8">
										<textarea type="text" name="description" class="form-control" rows="4" placeholder="Descript The Category"></textarea>
									</div>
							</div>
							<!-- End Description Field -->

							<!-- Start Ordering Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Ordering:</label>
									<div class="col-sm-10 col-md-8">
										<input type="number" name="ordering" class="form-control" placeholder="Number To Arrange The Categories" />
									</div>
							</div>
							<!-- End Ordering Field -->
							<!-- Start Ordering Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Image:</label>
									<div class="col-sm-10 col-md-8">
										<input type="file" name="imagecat" class="form-control" required="required" />
									</div>
							</div>
							<!-- End Ordering Field -->
							<!-- Start Category Type -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Parent ?:</label>
									<div class="col-sm-10 col-md-8">
										<select name="parent">
											<option value="0">None</option>
											<?php
												$allCats = getAllFrom("*", "categories", "where Parent_Cat = 0", "", "ID");
												foreach($allCats as $cat) {
													echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
												}
											?>
										</select>
									</div>
							</div>
							<!-- End Category Type -->
							<!-- Start Visibility Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Visible:</label>
									<div class="col-sm-10 col-md-8">
										<div>
											<input id="vis-yes" type="radio" name="visibility" value="0" checked />
												<label for="vis-yes">Yes</label>
										</div>
										<div>
											<input id="vis-no" type="radio" name="visibility" value="1" />
												<label for="vis-no">No</label>
										</div>
									</div>
							</div>
							<!-- End Visibility Field -->

							<!-- Start Comment Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Allow Comment:</label>
									<div class="col-sm-10 col-md-8">
										<div>
											<input id="com-yes" type="radio" name="commenting" value="0" checked />
												<label for="com-yes">Yes</label>
										</div>
										<div>
											<input id="com-no" type="radio" name="commenting" value="1" />
												<label for="com-no">No</label>
										</div>
									</div>
							</div>
							<!-- End Comment Field -->

							<!-- Start Ads Field -->
							<div class="form-group form-group-lg">
								<label class="col-sm-2 control-label">Allow Ads:</label>
									<div class="col-sm-10 col-md-8">
										<div>
											<input id="ads-yes" type="radio" name="ads" value="0" checked />
												<label for="ads-yes">Yes</label>
										</div>
										<div>
											<input id="ads-no" type="radio" name="ads" value="1" />
												<label for="ads-no">No</label>
										</div>
									</div>
							</div>
							<!-- End Ads Field -->

							<!-- Start Submit Field -->
							<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="submit" value="Add Category" class="btn btn-primary btn-lg" />
									</div>
							</div>
							<!-- End Submit Field -->


						</form>
					</div>

			<?php
		} elseif ($do == 'insert') {

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Categery</h1>";
					echo "<div class='container'>";

					// Upload Variables

					$imageName 	= $_FILES['imagecat']['name'];
					$imageSize 	= $_FILES['imagecat']['size'];
					$imageTmp 	= $_FILES['imagecat']['tmp_name'];
					$imageType 	= $_FILES['imagecat']['type'];

					$imageAllowedExtension  = array("jpeg", "jpg", "png", "gif");
					$tmp = explode('.', $imageName);
					$imageExtension = strtolower(end($tmp));
					// Get Variables From The form

					$name 		= $_POST['name'];
					$desc 		= $_POST['description'];
					$parent		= $_POST['parent'];
					$order 		= $_POST['ordering'];
					$visible 	= $_POST['visibility'];
					$comment 	= $_POST['commenting'];
					$ads 		= $_POST['ads'];

					$image = rand(0, 1000000) . '_' . $imageName;

					move_uploaded_file($imageTmp, "upload\imageCats\\" . $image);

					$check = checkItem("Name", "categories", $name);

					if($check == 1) {
						$theMsg = "<div class='alert alert-danger'>" .  '<strong>Sorry This Category Is Exist</strong></div>';
						redirectHome($theMsg, 'back', 5);
					} else {

							// Insert Category Info To Database

						$stmt = $con->prepare("INSERT INTO 
													categories(Name, Description, image, Parent_Cat, Ordering, Visibility, Allow_Comment, Allow_Ads) 
												VALUES(:zname, :zdesc, :zimage, :zparent, :zorder, :zvisible, :zcomment, :zads)");
						$stmt->execute(array(

								'zname' 	=> $name,
								'zdesc' 	=> $desc,
								'zimage'	=> $image,
								'zparent' 	=> $parent,
								'zorder' 	=> $order,
								'zvisible' 	=> $visible,
								'zcomment' 	=> $comment,
								'zads' 		=> $ads 
								));

						// Echo Success Massege

						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Inserted</div>'; 
						redirectHome($theMsg, 'back', 5);
					}

			} else {
				// Insert Category Info In Database
					if(!empty($imageExtension) &&!in_array($imageExtension, $imageAllowedExtension)) {
						echo '<div class="alert alert-danger">This Extension Is Not <strong> Allowed </strong></div>';
					}
					if($imageSize > 4200000) {
						echo '<div class="alert alert-danger">Image Size Can\'t Be Larger Than <strong> 4 MB </strong></div>';
					}
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg, 'back', 5);
				echo "</div>";
			}

			echo '</div>';

		} elseif ($do == 'Edit') {
						// Check If Get Request catid Is Numric & Get The Integer Value Of It 

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			// Select All Data Depend On This ID 

			$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
			
			// Execute Query

			$stmt->execute(array($catid));
			
			// Fetch The Data 

			$cat = $stmt->fetch();
			
			 // The Row Count 

			$count = $stmt->rowCount();
			// If There's Suck ID Show The Form
				if ($count > 0) { ?>

						<h1 class="text-center">Edit Category</h1>
						<div class="container">
							<form class="form-horizontal" action="?do=Update" method="POST">
							<input type="hidden" name="catid" value="<?php echo $catid ?>" />
								<!-- Start Name Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Name:</label>
										<div class="col-sm-10 col-md-8">
											<input type="text" name="name" class="form-control" required="Required" placeholder="Name Of Category" value="<?php echo $cat['Name'] ?>" />
										</div>
								</div>
								<!-- End UserName Field -->
								
								<!-- Start Description Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Description:</label>
										<div class="col-sm-10 col-md-8">
											<input type="text" name="description" class="form-control" placeholder="Descript The Category"  value="<?php echo $cat['Description'] ?>" />
										</div>
								</div>
								<!-- End Description Field -->

								<!-- Start Ordering Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Ordering:</label>
										<div class="col-sm-10 col-md-8">
											<input type="text" name="ordering" class="form-control" placeholder="Number To Arrange The Categories"  value="<?php echo $cat['Ordering'] ?>" />
										</div>
								</div>
								<!-- End Ordering Field -->
								<!-- Start Category Type -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Parent ?: </label>
										<div class="col-sm-10 col-md-8">
											<select name="parent">
												<option value="0">None</option>
												<?php
													$allCats = getAllFrom("*", "categories", "where Parent_Cat = 0", "", "ID");
													foreach($allCats as $c_cat) {
														echo "<option value='" . $c_cat['ID'] . "'";
														if($cat['Parent_Cat'] == $c_cat['ID']) { echo ' selected';}
														echo ">" . $c_cat['Name'] . "</option>";
													}
												?>
											</select>
										</div>
								</div>
							<!-- End Category Type -->

								<!-- Start Visibility Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Visible:</label>
										<div class="col-sm-10 col-md-8">
											<div>
												<input id="vis-yes" type="radio" name="visibility" value="0" <?php if($cat['Visibility'] == 0) { echo 'checked'; } ?>/>
													<label for="vis-yes">Yes</label>
											</div>
											<div>
												<input id="vis-no" type="radio" name="visibility" value="1" <?php if($cat['Visibility'] == 1) { echo 'checked'; } ?>/>
													<label for="vis-no">No</label>
											</div>
										</div>
								</div>
								<!-- End Visibility Field -->

								<!-- Start Comment Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Allow Comment:</label>
										<div class="col-sm-10 col-md-8">
											<div>
												<input id="com-yes" type="radio" name="commenting" value="0" <?php if($cat['Allow_Comment'] == 0) { echo 'checked'; } ?> />
													<label for="com-yes">Yes</label>
											</div>
											<div>
												<input id="com-no" type="radio" name="commenting" value="1" <?php if($cat['Allow_Comment'] == 1) { echo 'checked'; } ?> />
													<label for="com-no">No</label>
											</div>
										</div>
								</div>
								<!-- End Comment Field -->

								<!-- Start Ads Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-2 control-label">Allow Ads:</label>
										<div class="col-sm-10 col-md-8">
											<div>
												<input id="ads-yes" type="radio" name="ads" value="0" <?php if($cat['Allow_Ads'] == 0) { echo 'checked'; } ?> />
													<label for="ads-yes">Yes</label>
											</div>
											<div>
												<input id="ads-no" type="radio" name="ads" value="1" <?php if($cat['Allow_Ads'] == 1) { echo 'checked'; } ?> />
													<label for="ads-no">No</label>
											</div>
										</div>
								</div>
								<!-- End Ads Field -->

								<!-- Start Submit Field -->
								<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<input type="submit" value="Save Changes" class="btn btn-primary btn-lg" />
										</div>
								</div>
								<!-- End Submit Field -->	
		<?php 

			// If There's No Such ID Show Error Message 
			} else {
				echo "<div class='container'>";
				$theMsg = '<div class="alert alert-danger">Theres No Such ID</div>' ;
				redirectHome($theMsg, 5);
				echo "</div>";
			}


		} elseif ($do == 'Update') {


			echo "<h1 class='text-center'>Update Category</h1>";
			echo "<div class='container'>";

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form

				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$parent		= $_POST['parent'];
				$order 		= $_POST['ordering'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];


				// Loop Into Errors Array And Echo It

				$stmt = $con->prepare("UPDATE 
											categories
									 	SET 
									 		Name = ?,
									 		Description = ?, 
									 		Parent_Cat = ?, 
									 		Ordering = ?, 
									 		Visibility = ?, 
									 		Allow_Comment = ?, 
									 		Allow_Ads = ? 
									 	Where ID = ?");

				$stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads, $id));

				// Echo Success Massege

				$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Updated</div>'; 
				redirectHome($theMsg, 'back', 6);

			} else {
				$theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
				redirectHome($theMsg);
			}

			echo "</div>";

			} elseif ($do == 'Delete') {

				echo "<h1 class='text-center'>Delete Category</h1>";
				echo "<div class='container'>";
			
				// Check If Get Request Catid Is Numric & Get The Integer Value Of It 

				$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

				// Select All Data Depend On This ID 

				$check = checkItem('ID', 'categories', $catid);

				// If There's Suck ID Show The Form

					if ($check > 0) {  
						$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
						$stmt->bindParam(":zid", $catid);
						$stmt->execute();
						$theMsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' Record Deleted</div>'; 

						redirectHome($theMsg);

				} else {
					$theMsg = '<div class="alert alert-danger">This ID Is Not Exist</div>' ;
					redirectHome($theMsg, 'back', 5);
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