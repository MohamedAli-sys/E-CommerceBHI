<?php 
	ob_start();
	session_start();
	$pageTitle = 'Purchase Invoices';
	include 'init.php';	
	if(isset($_SESSION['User'])) { ?>


		<div class="ord-user">
			<h2 class="Custom-Name"> Customer Name : <a href="profile.php"><span><?php echo $_SESSION['User']; ?></span></a></h2>
			<?php 
				$detail = $con->prepare("SELECT * FROM orders WHERE user_id = {$_SESSION['uid']}");
				$detail->execute();
				$details = $detail->fetchAll();
				$countR = $detail->rowCount();
				echo  '<h3 class="Custom-Count">Total Orders : ' . $countR . '</h3>';
				
			?>
		</div>

	<?php		$stmt = $con->prepare("SELECT order_items.*, items.*, users.* , orders.*
										FROM 
										order_items INNER JOIN items INNER JOIN users INNER JOIN orders 
										ON 
										orders.item_ID = order_items.order_id  AND items.item_ID = order_items.product_id AND users.UserID = orders.user_id  ORDER BY order_items.item_ID DESC");
				// Execute The Statement
				$stmt->execute();
				// Assign To Variable
				$orders = $stmt->fetchAll();
				
				if (!empty($orders)) {
						foreach($orders as $order){ 
							if($order['UserID'] == $_SESSION['uid'] ) { 
								
?>
	<div class="invoices-style" id='DivIdToPrint'>
		<div class="invo-info">
			<div class="invo-logo">
				<h3>Sun<span>Moon</span></h3>	
			</div>
				<h3 class="text-center">Final Details for Order #<?php echo $order['item_ID']?></h3>
				<ul class="list-unstyled invo-order">
					<li>Order Placed : <span class="ord-info"><?php echo $order['modified'] ?></span></li>
					<li>Order Number : <span class="ord-info"><?php echo $order['item_ID'] ?></span></li>
					<li>Order Total Price : <span class="ord-info">$<?php echo $order['total_price'] ?></span></li>
				</ul>
				<h3 class="text-center">Shipped On #<?php echo $order['created']?></h3>
				<h4 class="text-left item-order">Items Ordered</h4>
				<h4 class="text-right item-price">Price</h4>
				<h4 class="text-center item-invo-qty">Quantity</h4>
				<ul class="list-unstyled item-invo-name">
					<li>- <?php echo $order['Name'];?></li>
				</ul>
				<ul class="list-unstyled item-invo-sub">
					<li>Item(s) SubTotal</li>
				</ul>
				<ul class="list-unstyled item-invo-price">
					<li> $<?php echo $order['Price']; ?></li>
						<hr>
					<li> $<?php echo $order['total_price']; ?></li>
				</ul>
				<ul class="list-unstyled text-center item-invo-qty2">
					<li><?php echo $order['quantity']?></li>
				</ul>
				<h3 class="text-center ship-detal">Shipping Details <?php if($order['Pending'] == 1){ echo '<div class="alert alert-success"> Your Order Started To Send </div>'; }
				 else { echo '<div class="alert alert-warning"> Your Order Not Accepted Yet..! </div>'; }
				 ?></h3>
		</div>
		<div class="invo-footer">
			<p>
				Purchase Invoice made by <a href="/" target="_blank">[ Admin ] </a>
			</p>
		</div>
	</div>
<?php

} 

}
	
} else {
	echo 'Wrong';
}
} else {
	header("Location: login.php");
}
	include $tpl . 'Footer.php';
	ob_end_flush();
?>