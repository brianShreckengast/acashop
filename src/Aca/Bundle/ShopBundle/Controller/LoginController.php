<?php
/**
 * Created by PhpStorm.
 * User: bshreckengast
 * Date: 10/27/15
 * Time: 7:28 PM
 */

namespace Aca\Bundle\ShopBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
    public function loginFormAction()
    {

        return $this->render('AcaShopBundle:LoginForm:login.form.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        if (!empty($username) && !empty($password)) {

            $userService = $this->get('user');

            $userId = $userService->userVerification($username, $password);

            if ($userId) {

                //fetch user data
                $userdata = $userService->fetchUserRow($userId);
                //get session object
                $session = $this->getSession();

                $userService->setUserSession($userdata);

                return $this->render('AcaShopBundle:LoginForm:login.form.html.twig',
                    array('user' => $userdata, 'loggedIn' => true));

            } else { // Invalid login

                return $this->render('AcaShopBundle:LoginForm:login.form.html.twig',
                    array('message' => "Invalid Login!", 'username' => $username, 'password' => $password));

            }
        }
        else { //one of fields was empty

            return $this->render('AcaShopBundle:LoginForm:login.form.html.twig',
                array('username' => $username, 'password' => $password, 'message' => "You're missing something!"));

        }


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
        $session->remove('username');
        $session->remove('user_id');
        $session->save();
        return new RedirectResponse('/login');
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

