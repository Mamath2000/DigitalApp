<?php

// REST API process - for GET
//use SebastianBergmann\CodeCoverage\Report\Xml\Report;

//global $folder;
//include_once "{$folder}tools/checkAuthorization.php";

// Check if the object is owned by user
function checkOwner($obj) {
    
    if (get_class($obj) == "Users") {
        return $obj->id == getSessionValue("userId", -1);

    } else if (property_exists($obj, "idUsers")) {
        return $obj->idUsers == getSessionValue("userId", -1);

    } else {
        return true; //La class n'a pas de lien avec le User.

    }
}

function checkAuthorization($action, $class, $obj = null) {
    // On ne check pas le JWT lors d'une connection
    if ("Login" == $class) return true;
    
    $class = ucfirst(strtolower($class));

    if (isUserAdmin() && isUserActif()) {
        $profil = "admin";    
        
    } else if (isUserActif()) {
        $profil = "associate";
    } else {
        return false;
    }

    global $authorizedActions;
    $bCheckAuth =  (array_key_exists($class, $authorizedActions) && 
                    array_key_exists($action, $authorizedActions[$class]) &&
                    strpos($authorizedActions[$class][$action], $profil) !== false);

    $bOwnerAllowed = (array_key_exists($class, $authorizedActions) && 
                      array_key_exists($action, $authorizedActions[$class]) &&
                      strpos($authorizedActions[$class][$action], "owner") !== false);
    
    if ($bCheckAuth) {
        return true;

    } else if ($bOwnerAllowed && $obj) {
        // Get object only possible to Admin or Owners
        return checkOwner($obj);

    } else {
        return false;
    }

}

function getQueryRessources(&$action, &$class, &$id, &$subClass, &$param, &$content) {
    //Check URI
    $action = $_SERVER['REQUEST_METHOD'];

    if (!isset($_REQUEST['ressource'])) 
        returnError(400, "Bad Request", "Requête REST invalide");
    
    foreach ($_REQUEST as $key => $value) {
        if ("ressource" == $key) {
            $class  = ucfirst(strtolower($value));
        } else if ("uri" == $key) {
            $split = explode('/', $value);
            if (!$value) {

            } else if (count($split) == 1) {
                if (is_numeric($split[0])) $id = (int)$split[0]; else $subClass = $split[0];
            } else if (count($split) == 2 && is_numeric($split[0])) {
                $id = (int)$split[0];
                $subClass = $split[1];
            } else {
                return false;
            }
        } else {
            $param[$key] = $value;
        } 
    }

    // get posted data
    $content = json_decode(file_get_contents("php://input"));
    return true;
}