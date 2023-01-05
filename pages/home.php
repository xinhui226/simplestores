<?php

session_start();

//require (from other file)
require "includes/functions.php";
require "includes/class-products.php";

//call the Product class
$products = new Products();
$products_list = $products->listAllProducts();

require "parts/header.php";

?>

    <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px;">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h1">My Store</h1>
          <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="/cart" class="btn btn-success">My Cart</a>
          </div>
        </div>

        <!-- products -->
        <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach($products_list as $product): ?>
          <div class="col">
            <div class="card h-100">
              <img
                src="<?=$product['image_url']?>"
                class="card-img-top"
                alt="<?=$product['name']?>"
              />
              <div class="card-body text-center">
                <h5 class="card-title"><?=$product['name']?></h5>
                <p class="card-text">$<?=$product['price']?></p>
              <form 
              action="/cart" 
              method="POST">
                <button class="btn btn-primary">Add to cart</button>
                <input 
                type="hidden" 
                name="product_id" 
                value="<?=$product['id'];?>">
              </form>   
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>

      <!-- footer -->
      <div class="d-flex justify-content-between align-items-center pt-4 pb-2">
        <div class="text-muted small">
          © 2022 <a href="/" class="text-muted">My Store</a>
        </div>
        <div class="d-flex align-items-center gap-3">
        <?php if (isLoggedIn()) : ?>
            <a href="/orders" class="btn btn-light btn-sm">My Orders</a>
          <a href="/logout" class="btn btn-light btn-sm">Log Out</a>
          <?php else:?>
          <a href="/login" class="btn btn-light btn-sm">Login</a>
          <a href="/signup" class="btn btn-light btn-sm">Sign Up</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

 <?php
require "parts/footer.php";