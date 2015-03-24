<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 14/03/15
 * Time: 10:18 AM
 */

/**
 * Sanitize a string
 * @param $string
 * @return The escaped string
 */
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

