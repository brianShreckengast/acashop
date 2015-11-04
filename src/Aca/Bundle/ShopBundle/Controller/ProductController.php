<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Aca\Bundle\ShopBundle\Db\Database;

class ProductController extends Controller
{

    public function showAllAction(){

        $db = new Database;

        $query = "select * from aca_product;";
        $productRows = $db->fetchRowMany($query);

        return $this->render('AcaShopBundle:Product:showAll.html.twig',
            array('products' => $productRows));

    }

    public function productPageAction($product){

        $db = new Database;
        $query = "select * from aca_product where uri = '$product' LIMIT 1;";
        $prod = $db->fetchRowMany($query);
        $prod =array_pop($prod);

        return $this->render('AcaShopBundle:Product:productPage.html.twig',
            array('product' => $prod));
    }
}