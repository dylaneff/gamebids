<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 14/03/15
 * Time: 10:41 AM
 */

class Hash {


    /**
     * Generate a hash with a string and salt
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function make($string, $salt = '') {
        return hash('sha256', $string . $salt);
    }

    /**
     * Generate a salt
     * @return string
     */
    public static function salt(){
        $fp = fopen('/dev/urandom', 'r');
        $salt = fread($fp, 32);
        fclose($fp);
        return $salt;
    }

    /**
     * Generate a unique hash
     * @return string
     */
    public static function unique() {
        return self::make(uniqid());
    }
}