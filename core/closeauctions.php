<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 16/03/15
 * Time: 12:11 PM
 */
require_once 'init.php';



//Add this cron job
// */1 * * * * root php5 /var/www/html/core/closeauctions.php



$_db = DB::getInstance();

$_db->query('UPDATE listings SET active = 0 WHERE active = 1 AND end_time <= NOW()', []);