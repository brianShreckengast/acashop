<?php
/**
 * Created by PhpStorm.
 * User: bshreckengast
 * Date: 10/27/15
 * Time: 7:28 PM
 */

namespace Aca\Bundle\ShopBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aca\Bundle\ShopBundle\Db\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginFormAction(Request $request)
    {

        $msg = null;
        $session = $this->getSession();
        $username = $request->get('username');
        $password = $request->get('password');

        if (!empty($username) && !empty($password)) {
            $query = '
            select
                *
            from
                aca_user
            where
                username = "' . $username . '"
                and password = "' . $password . '"';
            $db = new Database();
            $data = $db->fetchRowMany($query);
            if (empty($data) && $request->getMethod() == 'POST') { // Invalid login
                $msg = 'Please check your credentials';
                $session->set('loggedIn', false);
                $session->save();
            } else { // Valid login
                $row = array_pop($data);
                $name = $row['name']; // person's name
                $session->set('loggedIn', true);
                $session->set('name', $name);
            }
        }
        $session->save();
        $loggedIn = $session->get('loggedIn');
        $name = $session->get('name');
        return $this->render(
            'AcaShopBundle:LoginForm:login.form.html.twig',
            array(
                'loggedIn' => $loggedIn,
                'name' => $name,
                'msg' => $msg,
                'username' => $username,
                'password' => $password
            )
        );
    }
    /**
     * Handle logout business logic
     * @return RedirectResponse
     */
    public function logoutAction()
    {
        $session = $this->getSession();
        $session->remove('loggedIn');
        $session->remove('name');
        $session->save();
        return new RedirectResponse('/login_form');
    }
    /**
     * Get a vaid started session
     * @return Session
     */
    private function getSession()
    {
        /** @var Session $session */
        $session = $this->get('session');
        if (!$session->isStarted()) {
            $session->start();
        }
        return $session;
    }
}

