<?php

	ob_start();  // Output Buffering Start

	session_start();

	if(isset($_SESSION['Username'])) {
		$pageTitle = 'Dashboard';
		include 'init.php';
		
		/* Start Dashboard Page */
	
  		$numUsers = 10; // Number Of The Latest Users 
		$latestUsers = getLatest("*", "users", "UserID", $numUsers); // Latest Users Array

		$numItems = 10; // Number Of Latest Items
		$latestItems = getLatest("*", "items", "item_ID", $numItems);

		$numComments = 10; 


		?>
		<div class="home-stats">
			<div class="container text-center">
				<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-users">
							<i class="fa fa-users"></i>
							<div class="info">
								Total Users
								<span><a href="users.php"><?php echo countItems('UserID', 'users') ?></a></span>
						
							</div>
  						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-pending">
							<i class="fa fa-user-plus"></i>
							<div class="info">
									Pending Users
								<span><a href="users.php?do=Manage&page=Pending">
									<?php echo checkItem("RegStatus", "users", 0) ?>
								</a></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-items">
							<i class="fa fa-tag"></i>
							<div class="info">
								Total Items
							<span><a href="items.php"><?php echo countItems('item_ID', 'items') ?></a></span>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								Total Comments
							<span>
							<a href="comments.php"><?php echo countItems('C_id', 'comments') ?></a>
							</span>
							</div>
						</div>
					</div>
				</div>
				<div class="next-row">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-3">
						<div class="stat st-orders">
							<i class="fa fa-cart-plus" aria-hidden="true"></i>
							<div class="info">
									Pending Orders
								<span><a href="orders.php?do=Manage&page=Pending">
									<?php echo checkItem("Pending", "order_items", 0) ?>
								</a></span>
							</div>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="stat st-orders2">
							<i class="fa fa-shopping-cart"></i>
							<div class="info">
								Total Orders
							<span>
							<a href="orders.php"><?php echo countItems('item_ID', 'order_items') ?></a>
							</span>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>

		<div class="latest">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">

							<div class="panel-heading">
								<i class="fa fa-users"></i> Latest <?php echo $numUsers ?> Registered Users
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									<?php 	
											if (! empty($latestUsers))
											foreach($latestUsers as $user) {
												echo '<li>';
													echo $user['UserName'];
													echo '<a href="users.php?do=Edit&userid=' . $user['UserID'] . '">';
														echo '<span class="btn btn-success pull-right">';
															echo '<i class="fa fa-edit"></i> Edit';
																if($user['RegStatus'] == 0) {
																echo "<a href='users.php?do=Approval&userid= " . $user['UserID'] . " ' class='btn btn-info pull-right approval'><i class='fa fa-check'></i> Approval</a>";
																}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											} else {
											echo 'There\'s No Users To Show';
										}
									?>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Items
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
								<ul class="list-unstyled latest-users">
									<?php 
											if(! empty($latestItems)) {
											foreach($latestItems as $item) { 
												echo '<li>';
													echo $item['Name'];
													echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '">';
														echo '<span class="btn btn-success pull-right">';
															echo '<i class="fa fa-edit"></i> Edit';
																if($item['Approve'] == 0) {
																echo "<a href='items.php?do=Approve&itemid= " . $item['item_ID'] . " ' class='btn btn-info pull-right approval'><i class='fa fa-check'></i> Approve</a>";
																}
														echo '</span>';
													echo '</a>';
												echo '</li>';
											}
										} else {
											echo 'There\'s No Items To Show';
										}
									?>
								</ul>
							</div>
						</div>	
					</div>
				</div>

				<!-- Start Latest Comment -->
				<div class="row">
					<div class="col-sm-6">
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-comments-o"></i> Latest <?php echo $numComments ?> Comments
								<span class="toggle-info pull-right">
									<i class="fa fa-minus fa-lg"></i>
								</span>
							</div>
							<div class="panel-body">
							<?php 


								$stmt = $con->prepare("SELECT 
														comments.*, users.UserName AS User_Name
													FROM 
														comments
													INNER JOIN 
														users
													ON
														users.UserID = comments.User_ID
													ORDER BY 
														C_id DESC
													LIMIT 
														$numComments
														");
								$stmt->execute();
								$comments = $stmt->fetchAll();
									if(! empty($comments)) {		
									foreach ($comments as $comment) {
										echo '<div class="comment-box">';

											echo '<span class="user-n"><button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<a href="users.php?do=Edit&userid=' . $comment['User_ID'] . '">
															' . $comment['User_Name'] . '</a></button>
															<ul class="dropdown-menu">
																<li><a href="users.php?do=Edit&userid=' . $comment['User_ID'] . '">Edit User</a></li>
																<li><a href="comments.php?do=Edit&comid= ' . $comment['C_id'] . '">Edit Comment</a></li>
																<li><a href="users.php?do=Delete&userid=' . $comment['User_ID'] . '">Delete User</a></li>
																<li><a href="comments.php?do=Delete&comid= ' . $comment['C_id'] . '">Delete Comment</a></li>
															</ul></span>';

											echo '<p class="user-c">' . $comment['Comment'] . '</p>';
										echo '</div>';
										} 
									} else {
										echo 'There\'s No Comments To Show';
									}
							?>
							</div>
						</div>
					</div>
				</div>
				<!-- End Latest Comment -->

			</div>
		</div>

		<?php

		/* End Dashboard Page */

		include $tpl . 'Footer.php'; 
	} else {
		header('Location: index.php');
		exit();
	}
 	ob_end_flush();
?>