<?php


namespace Aca\Bundle\ShopBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    public function showCartAction()
    {
        $user = $this->get('user');
        //make sure user is logged in
        if(!$user->getUserId()){
            //user isn't logged in
            return $this->render('AcaShopBundle:Cart:show.cart.html.twig', array('notLoggedIn' => true));
        }
        $cart =  $this->get('cart');
        if($cartItems = $cart->getCartItems()){

            return $this->render('AcaShopBundle:Cart:show.cart.html.twig', array('cartItems' => $cartItems));
        } else {

            return $this->render('AcaShopBundle:Cart:show.cart.html.twig', array('emptyCart' => true));
        }



    }
    public function addToCartAction(Request $request)
    {
        $user = $this->get('user');
        //make sure user is logged in
        if(!$user->getUserId()){
            //user isn't logged in
            return $this->render('AcaShopBundle:Cart:show.cart.html.twig', array('notLoggedIn' => true));
        }
        $cart =  $this->get('cart');

        $quantity = $request->get('quantity');
        $product = $request->get('product_id');

        //Add product to cart
        $result = $cart->addProduct($product, $quantity);
        //redirect user to cart page
        return $this->redirect('/cart');

    }
    public function changeItemQuantityAction(Request $request){

        $quantity = $request->get('new_quantity');
        $product = $request->get('product_id');

        $cart =  $this->get('cart');

        $cart->changeItemQuantity($product, $quantity);

        return $this->redirect('/cart');

    }

    public function removeCartItemAction(Request $request){

        $product = $request->get('product_id');

        $cart =  $this->get('cart');

        $cart->removeFromCart($product);

        return $this->redirect('/cart');

    }


}