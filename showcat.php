 <?php
	ob_start();
	session_start();

	$pageTitle = 'Categories';
	include 'init.php'; 
	
?>		

	<div class="container">
		<div class="row">
			<h1 class="text-center"><?php echo $pageTitle; ?></h1>
			<?php 
				$allCats = getAllFrom('*', 'categories', 'where Parent_Cat = 0', '', 'Ordering', 'ASC'); 
				foreach($allCats as $cat) {
					echo '<div class="col-sm-6 col-md-12 test-cat">';
					echo '<div class="Test-align">';
						echo '<div class="thumbnail cat-box row">';	
							echo '<div class="col-md-6">';
							echo '<div class="caption">';
								echo '<h3><a class="cat-name" href="categories.php?pageid=' . $cat['ID'] .'">' . $cat['Name'] . '</a></h3>'; 
								echo '<h6 class="desc-box">Description : </h6>';
								if(!empty($cat['Description'])) {
									echo '<span class="more">' . $cat['Description'] . '</span>';
								} else { echo 'No Description'; }
							echo '</div>';
								echo '<div>';									
									$childCats = getAllFrom("*", "categories", "where Parent_Cat = {$cat['ID']}", "", "Ordering", "ASC");
										if(!empty($childCats)) {
											?>
											<button class="btn btn-info edit-more-cat">
												<span class="toggle-info pull-center">
													See Parent Categories<i class="fa fa-arrow-down fa-lg"></i>
												</span>
											</button>
											<?php
											echo '<div class="panel-body" id="demo">';
								      		echo "<ul class='list-unstyled child-cats'>";
								      		foreach($childCats as $c_cat) {
								      			echo "<li class='child-link'>
								      				<a href='categories.php?pageid=" . $c_cat['ID'] . "' >" . $c_cat['Name'] . "</a>";
								      			echo "<hr class='hr-box'>";
								      			echo "</li>";
								      			if(!empty($c_cat['image'])) {
								      				echo '<img class="brand-cat img-thumbnail img-circle" src="Admin/upload/imageCats/' . $c_cat['image'] . '" alt="" />';
								      			} else {
								      				echo '<img class="brand-cat img-thumbnail img-circle" src="img.png" alt="" />'; 
								      			}
								      			
								      		}
								      			
								      	echo "</ul>";
								      	echo '</div>';
								      }
								      
								echo '</div>';
								echo '</div>';
								if(!empty($cat['image'])) {
											echo '<img class="img-responsive col-md-6 cat-image" src="Admin/upload/imageCats/'.$cat['image'].'" alt="" />';
										} else { 
											echo '<img class="img-responsive col-md-6 cat-image" src="img.png" alt="" />';
										} 
						echo '</div>';
						echo '</div>';
					echo '</div>';
			}
			?>
			 
		</div>	
	</div>

<?php
	include $tpl . 'Footer.php'; 
	ob_end_flush();
?>