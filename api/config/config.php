<?php
    return [
        'Api_ver'         => "1.0.2",
        'sqlServers'      => 
            ['dev1' => 
                ['paramDbHost'     => "127.0.0.1", //"127.0.0.1",
                'paramDbPort'     => "3306",
                'paramDbUser'     => "digital", //"test",
                'paramDbPassword' => "digital", //"Test2019",
                'paramDbName'     => "DA"],
            'dev2' => 
                ['paramDbHost'     => "127.0.0.1", //"127.0.0.1",
                'paramDbPort'     => "3306",
                'paramDbUser'     => "digital", //"test",
                'paramDbPassword' => "digital", //"Test2019",
                'paramDbName'     => "digital"],
            'int1' => 
                ['paramDbHost'     => "192.168.1.196", //"127.0.0.1",
                'paramDbPort'     => "3306",
                'paramDbUser'     => "digital", //"test",
                'paramDbPassword' => "digital", //"Test2019",
                'paramDbName'     => "DA"],
            'prod' => 
                ['paramDbHost'     => "192.168.1.196", //"127.0.0.1",
                'paramDbPort'     => "3306",
                'paramDbUser'     => "digital", //"test",
                'paramDbPassword' => "digital", //"Test2019",
                'paramDbName'     => "DA"],
            ],
        'logFile'         => "/var/log/da/da.log",
        'logLevel'        => 3,
        'debugQuery'      => true,
        'debugTrace'      => true,
        'homeUrl'         => "",//"http://192.168.1.183/da/api/",
        'jwt_key'         => "digitalassociates",
        'jwt_iss'         => "https://da.mamath.duckdns.org",
        'jwt_aud'         => "https://da.mamath.duckdns.org",
        'jwt_iat'         => 1356999524,
        'jwt_exp'         => 3600, // Le token est valable 1H
        'jwt_noExp'       => 31557600, // Le token est valable 1An
        'TZ'              => 'Europe/Paris'
    ];
?>