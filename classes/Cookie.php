<?php

/***
 * Class Cookie
 */
class Cookie {

    /**
     * Check if a cookie exists
     * @param $name
     * @return bool
     */
    public static function exists($name){
        return (isset($_COOKIE[$name])) ? true : false;
    }

    /**
     * Get a cookie
     * @param $name
     * @return mixed
     */
    public static function get($name) {
        return $_COOKIE[$name];
    }

    /**
     * Create a cookie
     * @param $name
     * @param $value
     * @param $expiry
     * @return bool
     */
    public static function put($name, $value, $expiry){
        if(setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    /**
     * Delete a cookie
     * @param $name
     */
    public static function delete($name) {
        self::put($name, '', time() - 1);
    }
}