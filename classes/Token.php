<?php

/**
 * A class representing CSRF Token
 */
class Token {

    /**
     * Generate a token
     * @return mixed
     */
    public static function generate() {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }

    /**
     * Is the generated token the same as the session token?
     * @param $token
     * @return bool
     */
    public static function check($token) {
        $tokenName = Config::get('session/token_name');

        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}