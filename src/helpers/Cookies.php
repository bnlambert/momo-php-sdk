<?php
/**
 * Created by IntelliJ IDEA.
 * User: HP
 * Date: 12/23/2019
 * Time: 1:35 AM
 */

namespace BNLambert\Phalcon\Auth\helpers;


class Cookies
{
    protected $cookies;
    protected $duration;
    protected $token;
    protected $request;
    protected $response;

    public function __construct($cookies, $request, $response)
    {
        $this->cookies = $cookies;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    public function setRememberMe($user, $duration)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $this->duration = $duration;
        $this->token = $token;

        return $token;

    }

    public function confirm($userId)
    {
        $this->cookies->set('RMU', (string) $userId, $this->duration, '/');
        $this->cookies->set('RMT', $this->token, $this->duration, '/');
        $this->cookies->send();

    }

    public function hasExpired($startDate, $duration)
    {
        return ((time() - strtotime($startDate)) / (86400 * 8)) < ( $duration / 86400 );
    }

    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    public function forget()
    {

        if ($this->hasRememberMe()) {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->hasToken()) {
            $this->cookies->get('RMT')->delete();
        }


        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();
            $this->token = $token;

            $userId = $this->findFirstByToken($token);
            if ($userId) {
                $this->deleteToken($userId);
            }

            $this->cookies->get('RMT')->delete();
        }

    }

    public function hasToken()
    {
        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();
            $this->token = $token;

            return true;
        }

        return false;
    }

    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();



        $user = User::findFirstById($userId);

        if ($user) {

            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {

                $remember = RememberToken::findFirst([
                    'user_id = ?0 AND token = ?1',
                    'bind' => [
                        $user->id,
                        $token
                    ]
                ]);


                if ($remember) {


                    // Check if the cookie has not expired
                    if (((time() - strtotime($remember->created_at)) / (86400 * 8)) < 9) {

                        // Check if the user was flagged
                        // $this->checkUserFlags($user);

                        // Register identity
                        $this->session->set('auth-identity', [
                            'id' => $user->id,
                            'name' => $user->email,
                        ]);

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('users');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('auth');
    }

    public function getSessionParams()
    {
        if ($this->hasRememberMe() && $this->hasToken()){
            $userId = $this->cookies->get('RMU')->getValue();
            $token = $this->cookies->get('RMT')->getValue();

            return [
                'token' => $token,
                'userId' => $userId
            ];
        }

        return null;
    }
}