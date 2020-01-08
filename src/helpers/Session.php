<?php
/**
 * Created by IntelliJ IDEA.
 * User: HP
 * Date: 12/23/2019
 * Time: 1:35 AM
 */

namespace BNLambert\Phalcon\Auth\helpers;


class Session
{
    protected  $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function register($user)
    {
        $this->session->set('user', $user);
    }

    public function clear()
    {
        $this->session->remove('user');
    }

    public function getUser()
    {
        return $this->session->get('user');
    }
}