<?php

class Orders
{
    public $database;

    public function __construct()
    {
        try{
            $this->database = connectToDB();
        }catch( Exception $error){
            die('Database Connection Failed');
        }
    }

    public function createNewOrder(
        $user_id, //find who make the order
        $total_amount =0, //find total amount 
        $products_in_cart=[] //get the products in the order
        )
    {
        //step 1 :insert new order into database
        $statement=$this->database->prepare(
            'INSERT INTO orders (user_id, total_amount, transaction_id)
            VALUES (:user_id, :total_amount, :transaction_id)'
        );
        $statement->execute([
            'user_id' => $user_id,
            'total_amount' => $total_amount,
            'transaction_id' => ''
        ]);

        //step 2 :retreive order id using lastInsertId()
        //lastInsertId() is a function to let us get the id of the new data that we just created
        $order_id = $this->database->lastInsertId();

        //step 3 :create orders_products_bridge
        foreach($products_in_cart as $product_id => $quantity)
        {
            //insert each product in cart as new row in the link_bridge
            $statement = $this->database->prepare(
                'INSERT INTO orders_products (order_id, product_id, quantity)
                VALUES (:order_id, :product_id, :quantity)'
            );
            $statement->execute([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'quantity' => $quantity
            ]);
        }

        //step 4 :create bill url
        $bill_url = '';

           //create a bill in billplz using API
            //whenever we call API, there will be some response data
          $response = callAPI(
            BILLPLZ_API_URL.'v3/bills',
            'POST',
            [
                'collection_id' => BILLPLZ_COLLECTION_ID,
                'email' => $_SESSION['user']['email'],
                'name' => $_SESSION['user']['email'],
                'amount' => $total_amount*100,
                'callback_url' => 'http://simplestores.local:52089/payment-callback',
                'description' => 'Order #'.$order_id,
                'redirect_url' => 'http://simplestores.local:52089/payment-verification'
            ],
            [
                'Content-Type: application/json',
                'Authorization: Basic '. base64_encode(BILLPLZ_API_KEY.':')
            ]
          );

        //step 5: if the response is successful ,update the order with bill_id
        if(isset($response->id))
        {
            $statement = $this->database->prepare(
                'UPDATE orders SET transaction_id = :transaction_id WHERE id= :order_id'
            );
            $statement->execute([
                'transaction_id' => $response->id,
                'order_id' => $order_id
            ]);
        }

        //step 6: set bill_url
        if(isset($response->url))
        {
            $bill_url = $response->url;
        }

        return $bill_url;
    }

    //update order after payment
    public function updateOrder($transaction_id,$status)
    {
        $statement = $this->database->prepare(
            'UPDATE orders SET status = :status WHERE transaction_id = :transaction_id'
        );
        $statement->execute([
            'status' => $status,
            'transaction_id' => $transaction_id
        ]);
    }

    //list all orders by the logged-in user
    public function listOrders($user_id)
    {
        //load the order data from database based on given user_id
        $statement = $this->database->prepare('SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC');
        $statement->execute([
            'user_id' => $user_id
        ]);

        //fetch all orders data
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //list out all the products inside a single order
    public function listProductsinOrder($order_id)
    {
        //retrieve data from database
        $statement = $this->database->prepare(
            'SELECT products.id,products.name,orders_products.order_id,orders_products.quantity
            FROM orders_products
            JOIN products
            ON products.id = orders_products.product_id
            WHERE orders_products.order_id = :order_id'
        );
        $statement->execute([
            'order_id' =>$order_id
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


}