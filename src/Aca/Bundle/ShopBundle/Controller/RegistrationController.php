<?php


namespace Aca\Bundle\ShopBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class RegistrationController extends Controller
{
    public function registrationFormAction()
    {
        return $this->render('AcaShopBundle:Registration:registration.form.html.twig');

    }

    public function registerUserAction(Request $request){

        $username = $request->get('username');
        $password = $request->get('password');
        $first_name = $request->get('first_name');
        $billingStreet = $request->get('billing_street');
        $billingCity = $request->get('billing_city');
        $billingState = $request->get('billing_state');
        $billingZIP = $request->get('billing_zip');
        $shippingStreet = $request->get('shipping_street');
        $shippingCity = $request->get('shipping_city');
        $shippingState = $request->get('shipping_state');
        $shippingZIP = $request->get('shipping_zip');


        if(empty($username)||empty($password)||empty($first_name)||empty($billingStreet)||empty($billingCity)
            ||empty($billingState)||empty($billingZIP)||empty($shippingStreet)||empty($shippingCity)
            ||empty($shippingState)||empty($shippingZIP)){

            return $this->render('AcaShopBundle:Registration:registration.form.html.twig',
                array('missingValue' => true));
        }
        else{
            //instantiate user service
            $userService = $this->get('user');

            $db = $this->get('acadb');
            //check whether or not user exists
            $query = 'SELECT username FROM aca_user WHERE username = :username;';
            $row = $db->fetchRow($query, array('username' => $username));

            if($row){
                //username already exists
                return $this->render('AcaShopBundle:Registration:registration.form.html.twig',
                    array('userExists' => true));
            }
            else {
                //submission is good, create the user
                //Create array to pass to user class
                $userdata = array(
                    'name' => $first_name,
                    'username'  => $username,
                    'password' => $password,
                    'billingStreet' => $billingStreet,
                    'billingCity' => $billingCity,
                    'billingState' => $billingState,
                    'billingZip' => $billingZIP,
                    'shippingStreet' => $shippingStreet,
                    'shippingCity' => $shippingCity,
                    'shippingState' => $shippingState,
                    'shippingZip' => $shippingZIP
                );
                $userService->createUser($userdata);

                //log user in

                return $this->render('AcaShopBundle:Registration:registration.form.html.twig',
                    array('created' => true));
            }

        }

    }
}