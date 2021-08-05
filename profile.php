<?php
	ob_start();
	session_start();
	$pageTitle = 'Profile';
	include 'init.php'; 
	if(isset($_SESSION['User'])) {

	$getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");

	$getUser->execute(array($sessionUser));
	$info = $getUser->fetch();
	$userid = $info['UserID'];

?>

	<h1 class="text-center">My Profile</h1>
	
	<div class="information block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Information</div>
				<div class="panel-body">
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-unlock-alt fa-fw"></i>
							<span>Name </span>: <?php echo $info['UserName'] ?>
						</li>
						<li>
							<i class="fa fa-envelope-o fa-fw"></i>
							<span>Email </span>: <?php echo $info['Email'] ?>
						</li>
						<li>
							<i class="fa fa-user fa-fw"></i>
							<span>Full Name </span>: <?php echo $info['FullName'] ?>
						</li>
						<li>
							<i class="fa fa-calendar fa-fw"></i>
							<span>Registar Date </span>: <?php echo $info['Date'] ?>
						</li>
						<li>
							<i class="fa fa-tag fa-fw"></i>
							<span>Fav Category </span>:
						</li>
					</ul> 
				</div>
			</div>
		</div>
	</div>
	<div id="my-products" class="my-ads block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Products</div>
				<div class="panel-body">
					<?php 
						$useritems = getAllFrom("*", "items", "where User_ID = $userid", "", "item_ID");
						if (! empty($useritems)) {
							echo '<div class="row">';
							foreach($useritems as $item) {
								echo '<div class="col-sm-6 col-md-3">';
									echo '<div class="thumbnail item-box">';
										if($item['Approve'] == 0) { echo '<span class="approve-status">Not Approved</span>'; }
										echo '<span class="price-tag">$' . $item['Price'] . '</span>';
										$image = $con->prepare("SELECT image_src FROM upload WHERE Pro_image_ID = {$item['item_ID']}");
											$image->execute();
											$src = $image->fetch(PDO::FETCH_ASSOC);
											if(!empty($src)) {
											echo '<img class="img-responsive" src="'.$src['image_src'].'" alt="" />';
											} else {
												echo '<img class="img-responsive" src="img.png" alt="" />';
											}

										echo '<div class="caption">';
											echo '<h3><a href="items.php?itemid=' . $item['item_ID'] . '">' . $item['Name'] . '</a></h3>'; 
											echo '<p>' . $item['Description'] . '</p>';
											echo '<div class="date">' . $item['Add_Date'] . '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
							echo '</div>';
						} else {
							echo 'Sorry There\'s No Ads To Show, Create <a href="newad.php">New Ad </a>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="my-comments block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">Latest Comments</div>
				<div class="panel-body">
						<?php
						$userComments = getAllFrom("Comment", "comments", "where User_ID = $userid", "", "C_id");

						if(! empty($userComments)) {
							foreach ($userComments as $comment){
								echo '<p>' . $comment['Comment'] . '</p>';
							}
						} else {
							echo 'There\'s No Comments To Show';
						}
						?>
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