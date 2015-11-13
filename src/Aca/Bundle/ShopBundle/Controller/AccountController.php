<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AccountController extends Controller
{
    public function showAccountPageAction()
    {

        return $this->render('AcaShopBundle:Account:account.mgmt.page.html.twig');
    }
}

?>