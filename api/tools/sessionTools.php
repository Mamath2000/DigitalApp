<?php

// Functions to set and retrieve data from SESSION : do not use direct $_SESSION
function setSessionValue($code, $value)
{
    global $paramDbName;
    $DASession='DA_'.$paramDbName;

    if (!isset($_SESSION[$DASession])) $_SESSION[$DASession]=array();
    $_SESSION[$DASession][$code]=$value;
}

//**   */
function unsetSessionValue($code)
{
    global $paramDbName;
    $DASession='DA_'.$paramDbName;

    if (!isset($_SESSION[$DASession])) return null;
    if (isset($_SESSION[$DASession][$code])) unset($_SESSION[$DASession][$code]);
}

function getSessionValue($code, $default=null)
{
    global $paramDbName;
    $DASession='DA_'.$paramDbName;
    
    if (!isset($_SESSION[$DASession])) return $default;
    if (!isset($_SESSION[$DASession][$code])) return $default;
    return $_SESSION[$DASession][$code];
}

function isSessionValueExists($code)
{
    global $paramDbName;
    $DASession='DA_'.$paramDbName;

    if (!isset($_SESSION[$DASession])) return false;
    
    return (isset($_SESSION[$DASession][$code]));
}
