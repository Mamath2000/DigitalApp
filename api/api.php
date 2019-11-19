<?php
chdir(dirname(__DIR__));

//** required headers     */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

global $folder;
 
//** required to encode json web token  */
if (file_exists('api/config/core.php')) {
    $folder = 'api/';
} else if (file_exists('./config/core.php')) {
    $folder = './';
} else if (file_exists('../config/core.php')) {
    $folder = '../';
}

require_once "{$folder}config/core.php";
require_once "{$folder}tools/logTools.php";
require_once "{$folder}tools/sessionTools.php";
require_once "{$folder}tools/ApiTools.php";

//Check if action is Allowed or not for a Class
global $authorizedActions;
$authorizedActions = array(
    "Users"             => array("GET" => "admin, owner", "POST" => "admin", "PUT" => "admin","DELETE" => "admin"),
    "Associates"        => array("GET" => "admin, owner", "POST" => "admin", "PUT" => "admin","DELETE" => "admin"),
    "Associatestatus"   => array("GET" => "admin, associate"),
    "Userstatus"        => array("GET" => "admin, associate"),
    "Userprofile"       => array("GET" => "admin, associate"),
    "Linesdef"          => array("GET" => "admin"),
    "Reports"           => array("GET" => "admin, associate"),
    "Locks"             => array("POST" => "admin", "PUT" => "admin"),
    "Login"             => array("PUT" => "admin, associate"),
);
 


//decompose l'URL pour récupérer la Class l'option et les datas
if (!getQueryRessources($action, $class, $id, $subClass, $param, $content)) 
        returnError (400, "Bad Request", 'method '.$action.' not taken into acocunt in this API');

// Check if  JWT is OK !
include_once "{$folder}tools/checkJWT.php";

// Check if DB connection is OK !
include_once "{$folder}tools/checkDBConnection.php";

// Check REQUEST METHOD : only --------------------------------------------------------------------------
if ($action != 'GET' 
        && $action != 'PUT'
        && $action != 'POST'
        && $action != 'DELETE') {
    returnError (400, "Bad Request", 'method '.$action.' not taken into acocunt in this API');
}

// Check Profil for authorization to exec Action on the Class
if (!checkAuthorization($action, $class)) {
    returnError (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');
}

$returnArray = array();
if (!$param) $param = array();

$Clsfactory = $class . "Factory";
$objFacto = new $Clsfactory();

$debugQuery = Parameter::getGlobalParameter("debugQuery");
if (isset($debugQuery) and $debugQuery and $debugQuery === true) {
    debugTraceLog(" == " . $action . "|" . $class ." ================================================================");
}
if ($action == 'GET') {
    
    if ("Associates" == $class && $id && "timelines" == $subClass) {    // Query : api/$class/$id/$subClass  -->> Get a specific item subClass 
        $returnArray = $objFacto->getTimelines($id, $param);
    
    } else if ("Associates" == $class && $id && "fulltimelines" == $subClass) {    // Query : api/$class/$id/$subClass  -->> Get a specific item subClass 
        $returnArray = $objFacto->getFullTimelines($id);
    
    } else if ("Associates" == $class && $id && "cells" == $subClass) {    // Query : api/$class/$id/$subClass  -->> Get a specific item subClass 
        $returnArray = $objFacto->getReportsCells($id, $param);
    
    } else if ("Associates" == $class && $id && "graphs" == $subClass) {    // Query : api/$class/$id/$subClass  -->> Get a specific item subClass 
        $returnArray = $objFacto->getGraphData($id, $param);
    
    } else if ("Users" == $class && !$id && "check" == $subClass) {   // Query : api/$class/$subClass  -->> Get a specific subClass 
        $returnArray = $objFacto->getToken();
    
    } else if ($class && !$id && !$subClass) {         // Query : api/$class           -->> Get All items of Class 
            $returnArray = $objFacto->getItems($param);
        
    } else if ($class && $id && !$subClass) {   // Query : api/$class/$id       -->> Get a specific item of Class
            $returnArray = $objFacto->getItemFromId($id, $param);
        
    } else {
        returnError(400, "Bad Request", "Requête REST invalide");
    }
    
} else if ($action == 'POST') {
    
    if ("Locks" == $class && !$id && !$subClass) {      // Query : api/locks           -->> Create Lock 
        $returnArray = $objFacto->createLocks($content);
    
    } else if ($class && !$id && !$subClass) {          // Query : api/$class           -->> Create items of Class 
        $returnArray = $objFacto->createItem($content);
    
    } else {
        returnError(400, "Bad Request", "Requête REST invalide"); 
    }

} else if ($action == 'PUT') {
    
    if ("Associates" == $class && $id && "reportcalc" == $subClass) {// Query : api/associates/{id}/reportcalc       -->> PUT to update data Cells 
        $returnArray = $objFacto->reportCalc($id, $content);

    } else if ("Associates" == $class && $id && "rowcalc" == $subClass) {// Query : api/associates/{id}/rowssCal       -->> PUT to update data Cells 
        $returnArray = $objFacto->rowCalc($id, $content);

    } else if ("Associates" == $class && $id && "cells" == $subClass) {// Query : api/associates/{id}/cells       -->> PUT to update data Cells 
        $returnArray = $objFacto->updateCells($id, $content);

    } else if ("Users" == $class && $id && "password" == $subClass) {// Query : api/users/{id}/password       -->> PUT to update user password 
        $returnArray = $objFacto->updatePassword($id, $content);

    } else if ("Login" == $class && !$id && !$subClass) {// Query : api/$class           -->> Post All items of Class
        $returnArray = $objFacto->loginOn($content);

    } else if ("Locks" == $class && !$id && !$subClass) {// Query : api/$class           -->> Post All items of Class 
        $returnArray = $objFacto->updateLocks($content);

    } else if ($class && $id && !$subClass) {           // Query : api/$class           -->> Update items of Class 
        $returnArray = $objFacto->updateItem($id, $content);

    } else {
        returnError(400, "Bad Request", "Requête REST invalide");
    }
    
} else if ($action == 'DELETE') {
    
    if ( $class && $id && !$subClass) {     // Query : api/$class/$id                  -->> delete id items of Class 
        $returnArray = $objFacto->deleteItem($id);
    
    } else {
        returnError(400, "Bad Request", "Requête REST invalide");
    }
    
}   
http_response_code($returnArray['code']);
// show error message
echo json_encode($returnArray);

?>