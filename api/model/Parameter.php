<?php

/**
 *
 */
class Parameter
{

    const CONFIG_FILE = "./api/config/config.php";
    
    function __construct()
    {
        
        // code...
    }

    private static function getParamObj(){
        if (!isSessionValueExists("config")) {
//            if (file_exists(self::CONFIG_FILE)) {
                $param = include(self::CONFIG_FILE);
//            } else {
                $param = include('./api/config/config.php');
//            }
            setSessionValue("config", $param);
        } else {
            $param = getSessionValue("config");
        }
        return $param;
    }

    static function getGlobalParameter($code)
    {
        return  self::getParamObj()[$code];
    }

    static function getDbParameter($code, $dbServer)
    {
        return  self::getParamObj()['sqlServers'][$dbServer][$code];
    }
}
 