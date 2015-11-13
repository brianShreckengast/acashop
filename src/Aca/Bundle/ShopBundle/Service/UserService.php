<?php

namespace Aca\Bundle\ShopBundle\Service;

use Simplon\Mysql\Mysql;
use Symfony\Component\HttpFoundation\Session\Session;


class UserService
{
    private $db;

    private $session;


    public function __construct(Mysql $db, Session $session)
    {

        $this->db = $db;
        $this->session = $session;

    }

    public function createUser($userdata)
    {

        //Add billing address
        $billingData = array(
            'street' => $userdata['billingStreet'],
            'city' => $userdata['billingCity'],
            'state' => $userdata['billingState'],
            'zip' => $userdata['billingZip']
        );
        $billingId = $this->addAddress($billingData);
        //Add shipping address
        $shippingData = array(
            'street' => $userdata['shippingStreet'],
            'city' => $userdata['shippingCity'],
            'state' => $userdata['shippingState'],
            'zip' => $userdata['shippingZip']
        );
        $shippingId = $this->addAddress($shippingData);

        //create user in table
        $userRow = array(
            'name' => $userdata['name'],
            'username' => $userdata['username'],
            'password' => $userdata['password'],
            'shipping_address_id' => $shippingId,
            'billing_address_id' => $billingId
        );
        $userId = $this->db->insert('aca_user', $userRow);
        //create user's cart
        $this->createCart($userId);


    }

    public function fetchUserRow($userid)
    {

        $userdata = $this->db->fetchRow('SELECT * FROM aca_user WHERE user_id = :id;',
            array('id' => $userid));

        return $userdata;
    }

    private function createCart($userId)
    {

        $cartData = array('user_id' => $userId);
        $this->db->insert('aca_cart', $cartData);

    }

    private function addAddress($addressData)
    {

        $addressId = $this->db->insert('aca_address', $addressData);

        return $addressId;
    }

    public function userVerification($username, $password)
    {

        //verify correct username password combination
        if ($userId = $this->db->fetchColumn('SELECT user_id FROM aca_user WHERE username = :username AND password = :password;',
            array('username' => $username, 'password' => $password))
        ) {
            return $userId;

        } else {
            return false;
        }
    }

    public function setUserSession($userdata)
    {

        //set cookies
        $this->session->set('loggedIn', true);
        $this->session->set('name', $userdata['name']);
        $this->session->set('username', $userdata['username']);
        $this->session->set('user_id', $userdata['user_id']);
        $this->session->save();

    }

    public function getUserId(){

        $userId = $this->session->get('user_id');
        return $userId;
    }

}