<?php


class Cart
{

    function __construct()
    {
   
    }

    public function listAllProductsinCart()
    {
        $list = [];

        //check if cart is empty or not
        if(isset($_SESSION['cart']))
        {
            foreach($_SESSION['cart'] as $product_id=>$quantity){
             
                //init Products class
                $products = new Products();
                $product= $products->findProduct($product_id);

                //push $product_id and $quantity into $list array
                $list[] =[ 
                   'id' => $product_id,
                   'name' => $product['name'],
                   'price' => $product['price'],
                   'total' => $product['price']*$quantity,
                   'quantity' => $quantity
                ];
            } //end - (foreach $_SESSION['cart'])
        } // end - (isset($_SESSION['cart'])
        
        return $list;
    }

    public function total()
    {
        $cart_total = 0;
        //get all products in cart
        $list= $this->listAllProductsinCart();

        //calculate the total
        foreach($list as $product)
        {
            $cart_total+=$product['total'];
        }
        return $cart_total;
    }

    public function add( $product_id )
    {
        //check if there is existing data in $_SESSION['cart']
        if(isset($_SESSION['cart'])){
            $cart = $_SESSION['cart'];
        }else{
            //if no existing data, create an empty array
            $cart = [];
        }

        //add product id to cart
        //check if product_id already exists or not
        if(isset($cart[$product_id]))
        {
            //plus one
            //long method
            // $cart[$product_id] =  $cart[$product_id]+1;
            //short hand
            $cart[$product_id]+=1;
        }else
        {
            //assign quantity to one
            $cart[$product_id] = 1; //1 = quantity
        }
        
        //assign $cart to $SESSION
        $_SESSION['cart'] = $cart;
    }

    /**
     * remove product from cart
     */
    public function removeProductsFromCart($product_id)
    {
        if(isset($_SESSION['cart'][$product_id]))
        {
            //unset it means delete the selected product data
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function increaseProductQuantity($product_id)
    {
        if(isset($_SESSION['cart'][$product_id]))
        {
            ++$_SESSION['cart'][$product_id];
        }
    }

    public function decreaseProductQuantity($product_id)
    {
        if(isset($_SESSION['cart'][$product_id]))
        {
            if(($_SESSION['cart'][$product_id])==1){ 
                $this->removeProductsFromCart($product_id);
            } else{
               --$_SESSION['cart'][$product_id];
           }
        }
    }

    public function update($product_id)
    {
        if(isset($_SESSION['cart'][$product_id]))
        {
            $_SESSION['cart'][$product_id]=$_POST['quantity'];
            if(($_SESSION['cart'][$product_id])<1){ 
               $this->removeProductsFromCart($product_id);
            }
        }
    }

    //empty cart
    public function emptyCart()
    {
        unset($_SESSION['cart']);
    }
}