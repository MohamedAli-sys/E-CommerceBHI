<?php
	ob_start();
	session_start();
	$pageTitle = 'Show Item';
	include 'init.php'; 
	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
	$stmt = $con->prepare("SELECT 
								items.*, 
								categories.Name AS category_name,
								users.UserName 
							FROM 
								items 
							INNER JOIN
								categories
							ON
								categories.ID = items.Cat_ID
							INNER JOIN
								users
							ON
								users.UserID = items.User_ID
							WHERE 
								item_ID = ?
							AND 
								Approve = 1
							");
	$stmt->execute(array($itemid));
	$count = $stmt->rowCount();
	if($count > 0) {
	$item = $stmt->fetch();

?>

	<h1 class="text-center"><?php echo $item['Name'] ?></h1>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
			<?php
				$image = $con->prepare("SELECT image_src FROM upload WHERE Pro_image_ID = {$item['item_ID']}");
				$image->execute();
				$src = $image->fetchAll();
				$img_string = "";
					foreach($src as $sr) {
							$img_string .= '<a class="thumbnailss edit-small-image" href="#" data-image-id="" data-toggle="modal" data-image="'.$sr['image_src'].'" data-target="#image-gallery">
							<img src="'.$sr['image_src'].'"></a>'; 
					} ?>
			<div id="gHolder">
				<div id="theBigImageHolder">
					<?php 
						if(!empty($sr)) {
						echo '<a class="thumbnailss edit-big-image" href="#" data-image-id="" data-toggle="modal" data-image="'.$sr['image_src'].'" data-target="#image-gallery">
						<img src="'.$sr['image_src'].'" id="bigImage"></a>';
					} else {
						echo '<img src="img.png">';
						
					}
					?>
				</div>
				<div id="thumbnailsHolder">
					<?php 
						echo $img_string;
					?>
				</div>
			</div>
			</div>
			<div class="modal fade" id="image-gallery" role="dialog">
		    	<div class="modal-dialog modal-lg">
		      		<div class="modal-content">
		        		<div class="modal-header">
				          <button type="button" class="close" data-dismiss="modal">&times;</button>
				          <h4 class="modal-title"><?php echo $item['Name']; ?></h4>
		        		</div>
				        <div class="modal-body image-zoom">	
				          <img id="image-gallery-image" src="" >
				        </div>
				        <div class="modal-footer">
				         	<div class="col-md-2">
		                    	<button type="button" class="btn btn-primary" id="show-previous-image">Previous</button>
			                </div>
			                <div class="col-md-8 text-justify" id="image-gallery-caption">
			                </div>
			                <div class="col-md-2">
			                    <button type="button" id="show-next-image" class="btn btn-default">Next</button>
			                </div>				
				        </div>
		     		</div>
		    	</div>
		  	</div>
			<div class="col-md-6 item-info">
				<h2><?php echo $item['Name'] ?> </h2>
				<p><span class="more"><?php echo $item['Description'] ?> </span></p>
				<ul class="list-unstyled">
					<li><i class="fa fa-calendar fa-fw"></i><span> Added Date</span> :  <?php echo $item['Add_Date'] ?> </li>
					<li><i class="fa fa-money fa-fw"></i><span> Price</span> :  $<?php echo $item['Price'] ?> </li>
					<li><i class="fa fa-globe fa-fw"></i><span> Made In</span> :  <?php echo $item['Country_Made'] ?> </li>
					<li><i class="fa fa-tags fa-fw"></i><span> Category</span> :  <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a></li>
					<li><i class="fa fa-user fa-fw"></i><span> Added By</span> :  <a href="#"><?php echo $item['UserName'] ?></a></li>
					<li class="tags-items"><i class="fa fa-tags fa-fw"></i><span> Tags</span> :
						<?php
							$allTags = explode(",", $item['Tags']);
							foreach ($allTags as $tag) {
								$tag = str_replace(' ', '', $tag);
								$lowertag = strtolower($tag);
								if (! empty($tag)) {
									echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
								}
							}
						?>
					</li>
				</ul>
				<div class="pull-right input-edit-item">
					<a href="#Add-Com" class="btn btn-primary"><i class="fa fa-comment"></i> Comment ... </a>
					<a class="btn btn-success" href="cartAction.php?action=addToCart&id=<?php echo $item['item_ID']; ?>"><i class="fa fa-shopping-cart"></i>Add To Cart</a>
				</div>
			</div>
		</div>
		<hr class="custom-hr">
		<?php 	if(isset($_SESSION['User'])) { ?>
		<!-- Start Add Comment -->
		<div class="row">
			<div class="col-md-offset-3 col-md-3">
				<div class="add-comment">
					<h3>Add Your Comment</h3>
					<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['item_ID'] ?>" method="POST">
						<textarea name="comment" id="Add-Com" required="required"></textarea>
						<input class="btn btn-primary" type="submit" value="Add Comment">
					</form>
					<?php

						if($_SERVER['REQUEST_METHOD'] == 'POST') {

							$comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
							$itemid  	= $item['item_ID'];
							$userid   	= $_SESSION['uid'];

							if(! empty($comment)) {
								$stmt = $con->prepare("INSERT INTO 
															comments(Comment, Status, Comment_Date, item_ID, User_ID)
														VALUES (:zcomment, 1, Now(), :zitemid, :zuserid)");
								$stmt->execute(array(
										'zcomment' => $comment,
										'zitemid'  => $itemid,
										'zuserid'  => $userid
									));
								if($stmt) {
									echo '<div class="alert alert-success">Your Comment Added</div>';
								}
							}

						}

					?>
				</div>
			</div>
		</div>
		<!-- End Add Comment -->
		<?php } else {
			echo '<a href="login.php">Login</a> Or <a href="login.php">Register</a> To Add Comment';
		} ?>
		<hr class="custom-hr">
		<?php
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
										AND
											status = 1
										ORDER BY 
											C_id DESC");
					
				// Execute The Statement

				$stmt->execute(array($item['item_ID']));
				// Assign To Variable
				$comments = $stmt->fetchAll();	

			?>
			<?php 
			if(!empty($comments)) {
			foreach ($comments as $comment) { ?>
				<div class="comment-box">
					<div class="row">
						<div class="col-sm-2 text-center">
							<?php /*
								if(empty($comment['image'])) {
									echo '<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />';
								} else {
									echo '<img class="img-responsive img-thumbnail img-circle center-block" src="'$comment['image']'" alt="" />'
								}
							*/?>
							<?php 
								$getUser = $con->prepare("SELECT * FROM users WHERE UserID = {$comment['User_ID']}");
								$getUser->execute(array($sessionUser));
								$info = $getUser->fetch();
								$userid = $info['UserID'];
								$userimage = $info['image_src']; 

								if(! empty($userimage)) {
									echo '<img class="img-responsive img-thumbnail img-circle center-block" src="' . $info['image_src'] . '" alt="" />';
								} else { 
									echo '<img class="img-responsive img-thumbnail img-circle center-block" src="img.png" alt="" />'; 
								}
							?>
							<?php echo $comment['User_Name'] ?>
						</div>
						<div class="col-sm-10">
							<p class="lead"><?php echo $comment['Comment'] ?></p>
						</div>
					</div>
				</div>
				<hr class="custom-hr">
			<?php }} else {
				echo '<div class="alert alert-danger comment-empty"><i class="fa fa-comments"> There Are No Comments To Show</i></div>';
				} ?>
		</div>
	</div>
<?php		
	} else {
		echo '<div class="container">';
			echo '<div class="alert alert-danger"> There\'s No Such ID Or This Product Is Waiting Approve </div>';
		echo '</div>';
	}
	include $tpl . 'Footer.php';
	ob_end_flush();
 ?> 
