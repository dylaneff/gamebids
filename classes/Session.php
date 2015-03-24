<?php


class Session {
    /**
     * Does a session exist?
     * @param $name
     * @return bool
     */
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }

    /**
     *  Set the session
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function put($name, $value) {
        return $_SESSION[$name] = $value;
    }

    /**
     * Get the session for the user
     * @param $name
     * @return mixed
     */
    public static function get($name) {
        return $_SESSION[$name];
    }

    /**
     * Delete the users session
     * @param $name
     */
    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Flash data
     * @param $name
     * @param string $message
     * @return mixed
     */
    public static function flash($name, $message = ''){
        if(self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $message);
        }
    }


}