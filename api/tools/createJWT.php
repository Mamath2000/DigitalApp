<?php

// required to encode json web token
global $folder;
include_once "{$folder}libs/php-jwt-master/src/BeforeValidException.php";
include_once "{$folder}libs/php-jwt-master/src/ExpiredException.php";
include_once "{$folder}libs/php-jwt-master/src/SignatureInvalidException.php";
include_once "{$folder}libs/php-jwt-master/src/JWT.php";

use \Firebase\JWT\JWT;

function createToken($user, $noExpire = false, $server)
{

    // variables used for jwt
    $key = Parameter::getGlobalParameter('jwt_key');
    $Api_ver = Parameter::getGlobalParameter('Api_ver');
    $iss = Parameter::getGlobalParameter('jwt_iss');
    $aud = Parameter::getGlobalParameter('jwt_aud');
    $iat = time(); //Parameter::getGlobalParameter('jwt_iat');
    $exp = time() + Parameter::getGlobalParameter(($noExpire) ? 'jwt_noExp' :  'jwt_exp');

    $key .= "[{$Api_ver}]";
    $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "exp" => $exp,
        "data" => array(
            "id" => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "idUserProfile" => $user->idUserProfile,
            "idUserStatus" =>  $user->idUserStatus, 
            "dbServer" => $server,
            "Api_version" => $Api_ver
        )
    );

    return JWT::encode($token, $key);
}
