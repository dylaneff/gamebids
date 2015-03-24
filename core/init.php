<?php
//Start Session
session_start();

error_reporting(E_ALL);
ini_set("display_errors", 1);

//page title variable
$pageTitle= null;
//variable will hide certain elements on mobile when true
$hide = false;


//Config settings
$GLOBALS['config'] = array (
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'auction_db'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array (
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

//Load classes
spl_autoload_register(function($class) {
    //absolute path given
    require_once '/var/www/html/classes/' . $class . '.php';
});

// require
//absolute path given
require_once '/var/www/html/functions/general.php';

//Check cookie
if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
    }


}