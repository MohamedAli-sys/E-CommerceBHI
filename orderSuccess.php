<?php
ob_start();
session_start();
include 'init.php';
if(!isset($_REQUEST['id'])){
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Success - Shopping Cart</title>
    <meta charset="utf-8">
    <style>
    
    p{color: #34a853;font-size: 18px;}
    </style>
</head>
</head>
<body>
<div class="container">
    <h1>Order Status</h1>
    <p><?php
    	$theMsg = 'Your order has submitted successfully. Order ID is #' . $_GET['id']; 
    	redirectHome($theMsg, '', '15');
        ?></p>

        <?php
        include $tpl . 'Footer.php';
        ob_end_flush(); 
        ?>
</div>
</body>
</html>