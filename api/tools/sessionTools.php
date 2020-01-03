<?php

// Functions to set the key (dbname) of session
function setSessionKey($key){
    global $sessionKey;
    $sessionKey = $key;
}

// Functions to Get the key (dbname) of session
function getSessionKey(){
    global $sessionKey;
    if (!isset($sessionKey)) {
        $sessionKey = "_tmp";
    } else {
        return $sessionKey;
    }
}

// Functions to set and retrieve data from SESSION : do not use direct $_SESSION
function setSessionValue($code, $value)
{
    $DASession = 'DA_'. getSessionKey();

    if (!isset($_SESSION[$DASession])) $_SESSION[$DASession]=array();
    $_SESSION[$DASession][$code]=$value;
}

//**   */
function unsetSessionValue($code)
{
    $DASession = 'DA_'. getSessionKey();

    if (!isset($_SESSION[$DASession])) return null;
    if (isset($_SESSION[$DASession][$code])) unset($_SESSION[$DASession][$code]);
}

function getSessionValue($code, $default=null)
{
    $DASession = 'DA_'. getSessionKey();
    
    if (!isset($_SESSION[$DASession])) return $default;
    if (!isset($_SESSION[$DASession][$code])) return $default;
    return $_SESSION[$DASession][$code];
}

function isSessionValueExists($code)
{
    $DASession = 'DA_'. getSessionKey();

    if (!isset($_SESSION[$DASession])) return false;
    
    return (isset($_SESSION[$DASession][$code]));
}
