<?php

// required to encode json web token
global $folder;
require_once "{$folder}libs/php-jwt-master/src/BeforeValidException.php";
require_once "{$folder}libs/php-jwt-master/src/ExpiredException.php";
require_once "{$folder}libs/php-jwt-master/src/SignatureInvalidException.php";
require_once "{$folder}libs/php-jwt-master/src/JWT.php";

use \Firebase\JWT\JWT;

function checkJWT($class) {

    // On ne check pas le JWT lors d'une connection
    if ("Login" == $class) {
        global $DbServer;
        $content = json_decode(file_get_contents("php://input"));

        if (!isset($content->server)) 
            returnError (400, "Bad Request", "Requête REST invalide");


        setSessionKey($content->server);
        setSessionValue("dbServer", $content->server);
        return true;
    }

    if (!array_key_exists('HTTP_JWT', $_SERVER) || !$_SERVER['HTTP_JWT']) 
            returnError(401, "Unauthorized", "Une authentification est nécessaire pour accéder à la ressource.");

    // get parameters
    $jwt = $_SERVER['HTTP_JWT'];

    try {

        // variables used for jwt
        $key = Parameter::getGlobalParameter('jwt_key');
        $Api_ver = Parameter::getGlobalParameter('Api_ver');
        
        $key .= "[{$Api_ver}]";
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        
        setSessionKey($decoded->data->dbServer);
        setSessionValue("userId", $decoded->data->id);
        setSessionValue("JWT_data", $decoded->data);
        setSessionValue("dbServer", $decoded->data->dbServer);

        return $decoded;
    }
    // if decode fails, it means jwt is invalid
    catch (Exception $e) {
        
        returnError(403, $e->getMessage(), "Access denied.");
    }
}

function isUserAdmin()
{
    $data = getSessionValue("JWT_data");
    return (property_exists($data, "idUserProfile")
                && $data->idUserProfile == UserProfile::adminUserId());
}

function isUserActif()
{
    $data = getSessionValue("JWT_data");
    return (property_exists($data, "idUserStatus")
                && $data->idUserStatus == UserStatus::actifUserId());
}

checkJWT($class);