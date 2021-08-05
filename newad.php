<?php
	ob_start();
	session_start();
	$pageTitle = 'Create New Ad';
	include 'init.php'; 
	if(isset($_SESSION['User'])) {

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$formErrors = array();

			$name 		= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc 		= filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 		= filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
			$country 	= filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status 	= filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
			$category 	= filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 		= filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

			if (strlen($name) < 3) {
				$formErrors[] = 'Item Title Must Be At Least 3 Characters';
			}


			if (strlen($desc) < 10) {
				$formErrors[] = 'Item Description Must Be At Least 10 Characters';
			}


			if (strlen($country) < 3) {
				$formErrors[] = 'Item Country Must Be At Least 3 Characters';
			}

			if (empty($price)) {
				$formErrors[] = 'Item Price Must Be Not Empty';
			}

			if (empty($status)) {
				$formErrors[] = 'Item Status Must Be Not Empty';
			}

			if (empty($category)) {
				$formErrors[] = 'You Should Choose Category';
			}

			
			/* End Upload */			

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
							'zcat' 		=> $category,
							'zuser'		=> $_SESSION['uid'],
							'ztags' 	=> $tags
							));
					
						$imageID = $con->lastinsertid('items');
						
							
						}
						/* Start Upload Product Image */
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					foreach($_FILES['fileToUpload']['name'] as $i => $nameimage) {
						$nameimage = $_FILES['fileToUpload']['name'][$i];
						$size = $_FILES['fileToUpload']['size'][$i];
						$type = $_FILES['fileToUpload']['type'][$i];
						$tmp = $_FILES['fileToUpload']['tmp_name'][$i];
						$explode = explode('.', $nameimage);
						$ext = end($explode);
						$pathF = 'uploads-items/';
						$pathF = $pathF . basename($explode[0] . time() .'.'. $ext); 
						$errors = array();
						if(empty($_FILES['fileToUpload']['tmp_name'][$i])) {
							$formErrors[] = 'Please Choose At Least 1 File To Be Uploaded.';
						} else {
							$allowed = array('jpg', 'jpeg', 'gif', 'bmp', 'png');
							$max_size = 4000000;
							if(in_array($ext, $allowed) === false) {
								$formErrors[] = 'The File ' . $nameimage . ' Extension In Not Allow';
							}
							if($size > $max_size) {
								$formErrors[] = 'The File ' . $nameimage . ' Size Is Too Hight.';
							}
						}
							if(empty($errors)) {
								if(!file_exists('uploads-items')) {
									mkdir('uploads-items', 0777);
								}
						 	if(empty($errors)) {
								if(move_uploaded_file($tmp, $pathF)) {
								$path = 'http://localhost/eCommerce/uploads-items/' . basename($explode[0] . time() .'.'. $ext);
								$sql2 = $con->query("INSERT INTO upload (image_src, Pro_image_ID) VALUES ('".$path."', '".$imageID."')");
								echo $pathF;
							} 
						} 
					} 
				}
								

					// Echo Success Massege
				
					if(isset($stmt, $sql2)) {
						$successMsg = 'Product Has Been Send Waiting To Approve';
					}
				}
			}

?>

	<h1 class="text-center"><?php echo $pageTitle ?></h1> 
	<div class="create-ad block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $pageTitle ?></div>
				<div class="panel-body">
				<!-- Start Looping Through Errors -->
						<?php
							if(! empty($formErrors)) {
								foreach ($formErrors as $error) {
									echo '<div class="alert alert-danger">' . $error . '</div>';
								}
							}
							if (isset($successMsg)) {
								echo '<div class="alert alert-success">' . $successMsg  . '</div>';
							}
						?>
						<!-- End Looping Through Errors -->
					<div class="row">
						<div class="col-md-8">
							<form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">

								<!-- Start Name Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Name:</label>
										<div class="col-sm-10 col-md-9">
											<input pattern=".{4,}" title="This Field Require At Least 4 Characters" type="text" name="name" class="form-control live name-newad" required="required" placeholder="Name Of Item" data-class=".live-title" required="required" />
											<div class="alert alert-danger custom-alert">
												Name Of Product Must Be Larger Than <strong><ins>3</ins></strong> Characters...
											</div>
										</div>
								</div>
								<!-- End Name Field -->
								<!-- Start Description Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Description:</label>
										<div class="col-sm-10 col-md-9">
											<textarea pattern=".{10,}" title="This Field Require At Least 10 Characters" type="text" name="description" class="form-control live desc-newad" required="required" placeholder="Description Of Item" rows="6" data-class=".live-desc" required="required"></textarea>
											<div class="alert alert-danger custom-alert">
												Description Must Be Larger Than <strong><ins>10</ins></strong> Characters...
											</div>
										</div>
								</div>
								<!-- End Description Field -->
								<!-- Start Price Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Price:</label>
										<div class="col-sm-10 col-md-9">
											<input type="number" class="form-control live price-newad" placeholder="0.00" required name="price" min="0" value="0" step="0.01" title="Currency" pattern="^\d+(?:\.\d{1,2})?$">
											<div class="alert alert-danger custom-alert">
												Price Can't Be <strong><ins>Empty</ins></strong>...
											</div>
										</div>
								</div>
								<!-- End Price Field -->
								<!-- Start Country Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Country:</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="country" class="form-control country-newad" required="required" placeholder="Country Of Made" required="required" />
											<div class="alert alert-danger custom-alert">
												Country Must Be Larger Than <strong><ins>2</ins></strong> Characters...
											</div>
										</div>
								</div>
								<!-- End Country Field -->
								<!-- Start Status Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Status:</label>
										<div class="col-sm-10 col-md-9">
											<select name="status" class="stat-newad" required="required">
												<option value="0">...</option>
												<option value="1">New</option>
												<option value="2">Like New</option>
												<option value="3">Used</option>
												<option value="4">Old</option>
											</select>
											<div class="alert alert-danger custom-alert">
												Status Can't Be <strong><ins>Empty</ins></strong>...
											</div>
										</div>
								</div>
								<!-- End Status Field -->
								
								<!-- Start Categories Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Category:</label>
										<div class="col-sm-10 col-md-9">
											<select name="category" class="cat-newad" required="required">
												<option value="0">...</option>
												<?php 
													$cats = getAllFrom('*', 'categories', '', '', 'ID');
													foreach ($cats as $cat) {
														echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
													}

												?>
												<div class="alert alert-danger custom-alert">
													First Name Must Be Larger Than <strong><ins>3</ins></strong> Characters...
												</div>
											</select>
										</div>
								</div>
								<!-- End Categories Field -->
								<!-- Start Tags Field -->
								<div class="form-group form-group-lg">
									<label class="col-sm-3 control-label">Tags:</label>
										<div class="col-sm-10 col-md-9">
											<input type="text" name="tags" class="form-control" placeholder="Seprate Tags With (,)" />
										</div>
								</div>
								<!-- End Tags Field -->
								<div class="form-group form-group-lg">
									<div class="col-sm-10 col-md-9">
										<div class="image-div">	
											<label for="fileToUpload" class="file-label-newad new-ad-file" required ><i class="fa fa-upload"></i>
												<span id="label-span">Select Images Of Product</span></label>
												<input type="file" name="fileToUpload[]" id="fileToUpload" pattern=".{4,}" title="Please Choose At Least 1 File To Be Uploaded." multiple="multiple" required="required" />
										</div>
										<div class="notice-image">
											<h3>Notice :</h3>
											<ul>
												<li>Photo is Required you Should upload <span style="font-weight: bold;"> 1 limit </span> image.</li>
												<li>Max size of image is <span style="font-weight: bold;"> 4 MB. </span></li>

											</ul>
										</div>
									</div>
								</div>
								<!-- Start Submit Field -->
								<div class="form-group">
										<div class="col-sm-offset-3 col-sm-10">
											<input type="submit" value="Add item" class="btn btn-primary btn-sm" />
										</div>
								</div>
								<!-- End Submit Field -->
							</form>
						</div>
						<div class="col-md-4">
								<div class="thumbnail item-box live-preview">
									<span class="price-tag">
										$<span class="live-price">0</span>
									</span>
									<img class="img-responsive" src="img.png" alt="" />
									<div class="caption">
										<h3 class="live-title">Title</h3> 
										<p class="live-desc">Description</p>
									</div>
								</div>
							</div>
						</div>
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