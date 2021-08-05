<?php 
	ob_start();
	session_start();
	$pageTitle = 'Search';
	include 'init.php'; 


	$output = '';
	if(isset($_POST['search'])) {
		$searchq = $_POST['search'];
		$searchq = preg_replace("#[^0-9a-z]#i", "", $searchq);

		$stmt = $con->prepare("SELECT * FROM items WHERE Name LIKE '%$searchq%' OR Description LIKE '%$searchq%'");
		$stmt->execute();
		$count = $stmt->rowCount();
			$searchR = $stmt->fetchAll(); ?>
				<div class="container">
					<div class="custom-search">
						<div class="panel panel-primary">
							<div class="panel-heading"><span class="custom-result"> Search Result .. </span><span class="badge"> <?php echo $count?></span></div>
							<div class="panel-body">
							<?php
							if($count > 0) {
								foreach ($searchR as $seaR) {
									$item_name = $seaR['Name'];
									$item_desc = $seaR['Description'];
									$item_id = $seaR['item_ID'];
									echo '<div class="alert alert-info">';
										echo '<h3 class="result-name"><a href="items.php?itemid=' . $seaR['item_ID'] .'">' . $item_name . '</a></h3>';
										echo '<h5> Description </h5>';
										echo '<div class="">' .$item_desc . '</div>';
									echo '</div>';
								} 
							} else {
								echo '<div class="alert alert-warning msg-result"> No Result ...! </div>';
							}
							?>
							</div>
						</div>
					</div>
				</div>
<?php 	 
		}

	include $tpl . 'Footer.php';
	ob_end_flush();
 ?>