<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Aca\Bundle\ShopBundle\Db\Database;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AcaShopBundle:Default:index.html.twig');
    }




}
