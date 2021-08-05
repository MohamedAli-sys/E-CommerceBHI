<?php 
	ob_start();
	session_start();
	$pageTitle = 'Categories';
	include 'init.php'; 
?>
	<div class="container">
		<h1 class="text-center">Show Category Products</h1>
		<div class="row">
			<?php 
				if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
				$category = intval($_GET['pageid']);
				$allItems = getAllFrom("*", "items", "where Cat_ID = {$category}", "AND Approve = 1", "item_ID");
				foreach($allItems as $item) {
					echo '<div class="col-sm-6 col-md-3">';
						echo '<div class="thumbnail item-box">';
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
								echo '<h3><a href="items.php?itemid=' . $item['item_ID'] .'">' . $item['Name'] . '</a></h3>'; 
								echo '<p>' . $item['Description'] . '</p>';
								echo '<div class="date">' . $item['Add_Date'] . '</div>'; ?>
								<a class="btn btn-success add-cart-item" href="cartAction.php?action=addToCart&id=<?php echo $item['item_ID']; ?>">Add to cart</a>
								<?php
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			} else {
				echo 'You Must Add Page ID';
			}
			?>
		</div>	
	</div>

<?php 
	include $tpl . 'Footer.php';
	ob_end_flush();
 ?>