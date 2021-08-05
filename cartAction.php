<?php
// initialize shopping cart class
include 'Cart.php';
$cart = new Cart;

// include database configuration file
include 'Admin/connect.php';
if(isset($_SESSION['User'])) {
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = $_REQUEST['id'];
        // REQUEST product details
        $query = $con->query("SELECT * FROM items WHERE item_ID = $productID");
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $itemData = array(
            'id' => $row['item_ID'],
            'Name' => $row['Name'],
            'Price' => $row['Price'],
            'qty' => 1
        );
       
        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem?'viewCart.php':'categories.php';
        header("Location: " .$redirectLoc); 
    }elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem?'ok':'err';die;
    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: viewCart.php");
    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['User'])){
        // insert order details into database
        $insertOrder = $con->query("INSERT INTO orders (user_id, total_price, created, modified) VALUES ('".$_SESSION['uid']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");
        
        if($insertOrder){
            $orderID = $con->lastinsertid();
            $sql = '';
            // REQUEST cart items
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO order_items (order_id, product_id, quantity, Pending) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."', 0);";
            }
            // insert order items into database
            $insertOrderItems = $con->query($sql);
            
            if($insertOrderItems){
                $cart->destroy();
                header("Location: orderSuccess.php?id=$orderID");
            }else{
                header("Location: checkout.php");
            }
        }else{
           header("Location: checkout.php");
        }
    }else{
        header("Location: index.php");
    }
}else{
    header("Location: index.php");
} 
} else {
    header("Location: login.php");
}