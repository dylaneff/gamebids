<?php

/**
 * Class Config
 */
class Config {


    /**
     * @param null $path
     * @return get the global config
     */
    public static function get($path = null) {
        $config = $GLOBALS['config'];
        $path = explode('/', $path);

        foreach($path as $bit) {
            if(isset($config)) {
                $config = $config[$bit];
            }
        }
        return $config;
    }

}