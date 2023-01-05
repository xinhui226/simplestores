<?php

session_start();

require "includes/functions.php";
require "includes/class-products.php";
require "includes/class-cart.php";

$cart = new Cart();

//make sure POST request
if($_SERVER['REQUEST_METHOD']=='POST'){
    //if 'action' is 'remove' then triggle the function
    if(isset($_POST['action']))
    {
        switch ($_POST['action']){
            case 'remove' :
                $cart->removeProductsFromCart($_POST['delete_id']);
                break;
            case 'increase' :
                $cart->increaseProductQuantity($_POST['increase_id']);
                break;
            case 'decrease' :
                $cart->decreaseProductQuantity($_POST['decrease_id']);
                break;
            case 'update' :
                $cart->update($_POST['update_id']);
                break;
        }
    }else
    {

          //make sure product_id is available
    if (isset($_POST['product_id']))
    { 
        //add product_id into cart
        $cart->add($_POST['product_id']);
        Header('Location:/');
        exit;
    }
    }
    
}
require "parts/header.php"; 

?>

        <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
            
            <div class="min-vh-100">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h1">My Cart</h1>
                </div>
    
                <?php if (empty($cart->listAllProductsinCart())): ?>
                    <h1>There is no item in your cart !</h1>
                <?php else : ?>    
                <!-- List of products user added to cart -->
                <table class="table table-hover table-bordered table-striped table-light">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Product</th>
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                  
                        <?php foreach($cart->listAllProductsinCart() as $product): ?>
                        <tr>
                            <td><?=$product['id'];?></td>
                            <td><?=$product['name'];?></td>
                            <td><?=$product['price'];?></td>

                            <td class="d-flex justify-content-center align-items-center">        
                            <form 
                                action="<?=$_SERVER['REQUEST_URI']?>" 
                                method="POST">
                                <button class="btn btn-warning btn-sm">
                                    <i class="bi bi-dash-lg"></i>
                                </button>
                                <input 
                                type="hidden" 
                                name="action" 
                                value="decrease">
                                <input 
                                type="hidden" 
                                name="decrease_id" 
                                value="<?=$product['id'];?>">
                                </form>

                                <form action="<?= $_SERVER['REQUEST_URI'];?>" method="post">
                                <input type="number" class="form-control" name="quantity" value="<?=$product['quantity'];?>">
                                <input type="hidden" name="update_id" value="<?=$product['id'];?>">
                                <input type="hidden" name="action" value="update">
                                </form>

                            <form 
                                action="<?=$_SERVER['REQUEST_URI']?>" 
                                method="POST">
                                <button class="btn btn-success btn-sm">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <input 
                                type="hidden" 
                                name="action" 
                                value="increase">
                                <input 
                                type="hidden" 
                                name="increase_id" 
                                value="<?=$product['id'];?>">
                                </form>
                            </td>

                            <td><?=$product['total'];?></td>
                            <td>
                                <form 
                                action="<?=$_SERVER['REQUEST_URI']?>" 
                                method="POST">
                                <button class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <input 
                                type="hidden" 
                                name="action" 
                                value="remove">
                                <input 
                                type="hidden" 
                                name="delete_id" 
                                value="<?=$product['id'];?>">
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                        <tr>
                            <td 
                            colspan="3" 
                            class="text-end">
                                Total
                            </td>
                            <td></td>
                            <td>$ <?=$cart->total()?></td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center my-3">
                    <a href="/" class="btn btn-light btn-sm">Continue Shopping</a>
                    <?php if(!empty($cart->listAllProductsinCart())): ?>
                        <form action="/checkout" method="POST">
                            <button class="btn btn-primary">Checkout</a>
                        </form>
                    <?php endif; ?>
                </div>

            </div>

            <!-- footer -->
            <div class="d-flex justify-content-between align-items-center pt-4 pb-2">
                <div class="text-muted small">© 2022 <a href="/" class="text-muted">My Store</a></div>
                <div class="d-flex align-items-center gap-3">
                    <a href="/login" class="btn btn-light btn-sm">Login</a>
                    <a href="/signup" class="btn btn-light btn-sm">Sign Up</a>
                    <a href="/orders" class="btn btn-light btn-sm">My Orders</a>
                </div>
            </div>

        </div>

<?php require "parts/footer.php"; 