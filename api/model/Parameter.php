<?php

/**
 *
 */
class Parameter
{

    const CONFIG_FILE = "d:\config.php";
    
    /*   private static $param = array(
        'laptop' => array(
            'Api_ver'         => "1.0.1",
            'paramDbHost'     => "127.0.0.1",
            'paramDbPort'     => "3306",
            'paramDbUser'     => "test",
            'paramDbPassword' => "Test2019",
            'paramDbName'     => "api",
            'logFile'         => "d:\ajax.log",
            'logLevel'        => 3,
            'debugQuery'      => false,
            'debugTrace'      => false,
            'homeUrl'         => "http://localhost/ajax/api/",
            'jwt_key'         => "digitalassociates",
            'jwt_iss'         => "https://mamath.duckdns.org",
            'jwt_aud'         => "https://mamath.duckdns.org",
            'jwt_iat'         => 1356999524,
            'jwt_exp'         => 3600, // Le token est valable 1H
            'jwt_noExp'       => 31557600, // Le token est valable 1An
            'TZ'              => 'Europe/Paris'
        ),
        'home' => array(
            'Api_ver'         => "1.0.1",
            'paramDbHost'     => "192.168.1.245", //"127.0.0.1",
            'paramDbPort'     => "3306",
            'paramDbUser'     => "api", //"test",
            'paramDbPassword' => "api", //"Test2019",
            'paramDbName'     => "api",
            'logFile'         => "d:\ajax.log",
            'logLevel'        => 3,
            'debugQuery'      => true,
            'debugTrace'      => true,
            'homeUrl'         => "http://localhost/ajax/api/",
            'jwt_key'         => "digitalassociates",
            'jwt_iss'         => "https://mamath.duckdns.org",
            'jwt_aud'         => "https://mamath.duckdns.org",
            'jwt_iat'         => 1356999524,
            'jwt_exp'         => 3600, // Le token est valable 1H
            'jwt_noExp'       => 31557600, // Le token est valable 1An
            'TZ'              => 'Europe/Paris'
        )
    );
*/
    function __construct()
    {
        
        // code...
    }

    static function getGlobalParameter($code)
    {

        if (!isSessionValueExists("config")) {
            if (file_exists(self::CONFIG_FILE)) {
                $param = include(self::CONFIG_FILE);
            } else {
                $param = include('./api/config/config.php');
            }
            setSessionValue("config", $param);
        } else {
            $param = getSessionValue("config");
        }

        return $param[$code];
    }
}
 