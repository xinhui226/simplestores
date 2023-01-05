<?php

class Products
{

    public $database;

    function __construct()
    {
        try{
            // we''ll try to establish the database connection
            $this->database = connectToDB();
        }catch( Exception $error ){
            die("Database Connection Failed: " .$error->getMessage());
        }
    }

    /**
     * retrieve all products from database
    */
    public function listAllProducts()
    {
        $statement = $this->database->prepare('SELECT * FROM products');
        $statement->execute();

        /**
         * fetch all data from database
         * use PDO::FETCH_OBJ if you want object->
         * use PDO::FETCH_ASSOC if you want array ['']
         */
       return  $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    //find product by id
    public function findProduct($product_id)
    {
        $statement= $this->database->prepare('SELECT * FROM products WHERE id = :id');
        $statement->execute(['id'=>$product_id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}