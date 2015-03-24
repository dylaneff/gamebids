<?php
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 19/03/15
 * Time: 9:07 PM
 */
include'Hash.php';
class HashTest extends PHPUnit_Framework_TestCase {
    public function test(){
        $this->assertTrue(Hash::unique() !== Hash::unique());
        $this->assertTrue(Hash::salt() !== Hash::salt());
        $salt = Hash::salt();
        $this->assertTrue(Hash::make("password", $salt) === Hash::make("password", $salt) );
        $this->assertTrue(Hash::make("password", $salt) !== Hash::make("pssword", $salt) );

    }
}
