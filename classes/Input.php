<?php

/**
 * A class representing form date input
 */
class Input {
    /**
     * Check to see if input exists
     * @param string $type
     * @return bool
     */
    public static function exists($type = 'post') {
        switch($type) {
            case 'post':
                return(!empty($_POST)) ? true : false;
            break;
            case 'get':
                return (!empty($_GET)) ? true : false;
            break;
            default:
                return false;
            break;
        }
    }

    /**
     * Get the input for a given item
     * @param $item
     * @return string
     */
    public static function get($item) {
        if(!empty($_POST[$item]) || !empty($_GET[$item])) {
            if(isset($_POST[$item])) {
                return $_POST[$item];
            } else if (isset($_GET[$item])) {
                return $_GET[$item];
            }
        }
        return 'sdfdfs';
    }
}