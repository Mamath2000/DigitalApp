<?php

global $folder, $GEO;


//$GEO ='home';
$GEO ='laptop';

//** required to encode json web token  */
if (file_exists('./config/core.php')) {
    $folder = './';
} else if (file_exists('../config/core.php')) {
    $folder = '../';
} else if (file_exists('./api/config/core.php')) {
    $folder = './api/';
}
require_once "{$folder}tools/logTools.php";
require_once "{$folder}tools/sessionTools.php";

spl_autoload_register(function ($class_name) {
    global $folder;
    $class_name= ucfirst($class_name);
    
    if (substr($class_name, 0, strlen("MathParser")) === "MathParser") {
        //require_once "{$folder}libs/" . $class_name . '.php';
        require_once "{$folder}libs/" . str_replace("\\","/",$class_name) . '.php';
        
    } else if (substr($class_name, 0, strlen("Sql")) === "Sql") {
        require_once "{$folder}model/SqlElement/" . $class_name . '.php';

    } else if (substr($class_name, 0, strlen("SebastianBergmann"))=== "SebastianBergmann") {
        if (file_exists($class_name . '.php')) require_once $class_name . '.php';

    } else if (stristr($class_name, 'Factory') !== false) {
        require_once "{$folder}controler/" . $class_name . '.php';

    } else {
        require_once "{$folder}model/" . $class_name . '.php';

    }
});

// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set(Parameter::getGlobalParameter('TZ'));

function returnError($code, $error, $message) {
    http_response_code($code);
	echo json_encode(
        array(
            "code"      => $code,
            "message"   => $message, 
            "error"     => $error));
      
	exit(0);
}

function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}
