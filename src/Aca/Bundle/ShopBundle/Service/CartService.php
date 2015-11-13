<?php

namespace Aca\Bundle\ShopBundle\Service;

use Simplon\Mysql\Mysql;
use Aca\Bundle\ShopBundle\Service\UserService;

class CartService
{
    protected $db;
    protected $user;

    public function __construct(Mysql $db, UserService $user){

        $this->db = $db;
        $this->user = $user;
    }

    public function addProduct($productID, $quantity){

        //get cart id
        $cartId = $this->getCartId();
        //Check if item is already in cart
        $inCart = $this->db->fetchRow('SELECT * FROM aca_cart_product
        where cart_id = :cartId AND product_id = :productId',
            array('cartId'=> $cartId,'productId' => $productID ));

        if($inCart){
            //this means its in the cart already
            //so the value must be incremented
            return $this->addProductQuantity($productID, $quantity, $cartId);


        } else {

            //add to cart
            $productData = array('cart_id' => $cartId,
                                'product_id' => $productID,
                                'quantity' => $quantity);

            return $this->db->insert('aca_cart_product', $productData);
        }

    }

    public function addProductQuantity($productId, $quantity, $cartId){

        $currentQuantity = $this->db->fetchColumn('SELECT quantity FROM aca_cart_product
        where cart_id = :cartId AND product_id = :productId',
            array('cartId' => $cartId, 'productId' =>$productId));

        $quantity = $quantity + $currentQuantity;

        $conds = array('cart_id' => $cartId, 'product_id' => $productId);

        $updateData = array('quantity' => $quantity);

        $result = $this->db->update('aca_cart_product', $conds, $updateData);

        return $result;
    }

    public function getCartId(){

        $userId = $this->user->getUserId();

        $cartId = $this->db->fetchColumn('SELECT id from aca_cart where user_id = :userid',
            array('userid' => $userId));

        return $cartId;
    }

    public function getCartItems(){

        $cartItems = $this->db->fetchRowMany('SELECT p.id, p.name, p.image, p.description, cp.date_added, p.price, p.uri, cp.quantity
        FROM aca_cart_product cp left JOIN aca_product p on (cp.product_id = p.id)
        WHERE cp.cart_id = :cartId', array('cartId' => $this->getCartId()));


        return $cartItems;
    }

    public function changeItemQuantity($productId, $newQuantity){

        $conds = array('cart_id' => $this->getCartId(), 'product_id' => $productId);

        $data = array('quantity' => $newQuantity);

        $this->db->update('aca_cart_product', $conds, $data );
    }

    public function removeFromCart($productId){

        $conds = array('cart_id' => $this->getCartId(), 'product_id' => $productId);

        $this->db->delete('aca_cart_product', $conds );
    }


}