<?php
/**
 * ===========================================================================
 * Static method defining all persistance methods
 */
class Sql
{

    private static $_connexion = null;

    // Database informations
    private static $_dbHost;
    private static $_dbPort;
    private static $_dbUser;
    private static $_dbPassword;
    private static $_dbName;

    // Visible Information
    public static $lastQuery = null;           // the string of the last executed query
    public static $lastQueryType = null;       // the type of the last executed query : SELECT or UPDATE
    public static $lastQueryResult = null;     // the result of the last executed query
    public static $lastQueryNbRows = null;     // the number of rows returns of affected by the last executed query
    public static $lastQueryNewid = null;      // the new id of the last executed query, if it was an INSERT query
    public static $lastQueryNewObjectId = null;
    public static $lastQueryErrorMessage = null;
    public static $lastQueryErrorCode = null;
    public static $lastConnectError = null;
    public static $lastCopyId = null;
    public static $maintenanceMode = false;

    /**
     * ========================================================================
     * Constructor (private, because static access only)
     * => no destructor for this class
     *
     * @return void
     */
    private function __construct()
    { }

    /**
     * =========================================================================
     * Execute a query on database and return the result
     *
     * @param  $sqlRequest the resquest to be executed. Can be SELECT, UPDATE, INSERT, DELETE or else
     * 
     * @return resource of result if query is SELECT, false either
     */
    static function query($sqlRequest = null, $bindArray = array())
    {
        $debugQuery = Parameter::getGlobalParameter("debugQuery");
        if ($sqlRequest == null) {
            echo "SQL WARNING : empty query";
            traceLog("SQL WARNING : empty query");
            return false;
        }
      
        // Execute query
        $cnx = self::getConnection();
        if (!$cnx) {
            self::$lastQueryErrorMessage = "Internal Server Error : " . self::$lastConnectError;
            self::$lastQueryErrorCode = 500;
            return false;
        }
        self::$lastQueryErrorMessage = null;
        self::$lastQueryErrorCode = null;
      
        // enableCatchErrors();
        $result = new PDOStatement();
        $checkResult = "OK";
      
        try {
            $startMicroTime = microtime(true);
            $result = $cnx->prepare($sqlRequest);
            $result->execute($bindArray);

            //debug
            if (isset($debugQuery) and $debugQuery and $debugQuery === true) {
                $sqlDebugRequest = str_replace(preg_filter('/^/', ':', array_keys($bindArray)), $bindArray, $sqlRequest);
                debugTraceLog(round((microtime(true) - $startMicroTime) * 1000000) / 1000000 . ";" . $sqlDebugRequest);
            }

            if (!$result) {
                $sqlDebugRequest = str_replace(preg_filter('/^/', ':', array_keys($bindArray)), $bindArray, $sqlRequest);
                self::$lastQueryErrorMessage = 'sqlError : ' . $cnx->errorCode() . "<br/><br/>" . $sqlDebugRequest;
                self::$lastQueryErrorCode = $cnx->errorInfo();
                errorLog('Error-[' . self::$lastQueryErrorCode . '] ' . self::$lastQueryErrorMessage);
                $checkResult = "ERROR";
            }

        } catch (PDOException $e) {

            $checkResult = "EXCEPTION";

            $sqlDebugRequest = str_replace(preg_filter('/^/', ':', array_keys($bindArray)), $bindArray, $sqlRequest);
            self::$lastQueryErrorMessage = $e->getMessage();
            self::$lastQueryErrorCode = $e->getCode();
            errorLog('Exception-[' . self::$lastQueryErrorCode . '] ' . self::$lastQueryErrorMessage);
            errorLog('   For query : ' . $sqlDebugRequest);
            errorLog('   Strack trace :');
            $traces = debug_backtrace();
            foreach ($traces as $idTrace => $arrayTrace) {
                errorLog(
                    "   #$idTrace "
                        . ((isset($arrayTrace['class'])) ? $arrayTrace['class'] . '->' : '')
                        . ((isset($arrayTrace['function'])) ? $arrayTrace['function'] . ' called at ' : '')
                        . ((isset($arrayTrace['file'])) ? '[' . $arrayTrace['file'] : '')
                        . ((isset($arrayTrace['line'])) ? ':' . $arrayTrace['line'] : '')
                        . ((isset($arrayTrace['file'])) ? ']' : '')
                );
            }
            return false;
        }

        // store informations about last query
        self::$lastQuery = $sqlRequest;
        self::$lastQueryResult = $result;
        self::$lastQueryType = (is_resource($result)) ? "SELECT" : "UPDATE";
        self::$lastQueryNbRows = (self::$lastQueryType == "SELECT") ? $result->rowCount() : $result->rowCount();
        self::$lastQueryNewid = null;
        if (self::$lastQueryType == "UPDATE") {
            self::$lastQueryNewid = ($cnx->lastInsertId()) ? $cnx->lastInsertId() : null;
        }
        if ($checkResult != 'OK') {
            return false;
        }
        return $result;
    }

    /**
     * =========================================================================
     * Fetch the next line in a result set
     *
     * @param  $result
     * 
     * @return array of data, or false if no more line
     */
    static function fetchLine($result)
    {
        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * =========================================================================
     * Begin a transaction
     *
     * @return void
     */
    public static function beginTransaction()
    {
        $cnx = self::getConnection();
        if ($cnx != null) {
            error_reporting(E_ALL ^ E_WARNING);
            if (!$cnx->beginTransaction()) {
                echo "SQL ERROR : Error on Begin Transaction";
                errorLog("SQL ERROR : Error on Begin Transaction");
                exit;
            }
            error_reporting(E_ALL ^ E_WARNING);
        }
    }


    /**
     * =========================================================================
     * Commit a transaction (validate the changes)
     *
     * @return void
     */
    public static function commitTransaction()
    {
        $cnx = self::getConnection();
        if ($cnx != null) {
            error_reporting(E_ALL ^ E_WARNING);
            if (!$cnx->commit()) {
                echo "SQL ERROR : Error on Commit Transaction";
                errorLog("SQL ERROR : Error on Commit Transaction");
                exit;
            }
            error_reporting(E_ALL ^ E_WARNING);
        }
    }


    /**
     * =========================================================================
     * RoolBack a transaction (cancel the changes)
     *
     * @return void
     */
    public static function rollbackTransaction()
    {
        $cnx = self::getConnection();
        if ($cnx != null) {
            error_reporting(E_ALL ^ E_WARNING);
            if (!$cnx->rollBack()) {
                echo "SQL ERROR : Error on Rollback Transaction";
                errorLog("SQL ERROR : Error on Rollback Transaction");
                exit;
            }
        }
    }


    /**
     * =========================================================================
     * Return the connexion. Private. Only for internal use.
     *
     * @return resource connexion to database
     */
    public static function getConnection()
    {
        if (self::$_connexion != null) {
            return self::$_connexion;
        }

        if (!self::$_dbHost or !self::$_dbName or !self::$_dbPort) {
            self::$_dbHost = Parameter::getGlobalParameter('paramDbHost');
            self::$_dbPort = Parameter::getGlobalParameter('paramDbPort');
            self::$_dbUser = Parameter::getGlobalParameter('paramDbUser');
            self::$_dbPassword = Parameter::getGlobalParameter('paramDbPassword');
            self::$_dbName = Parameter::getGlobalParameter('paramDbName');

            global $paramDbName;
            $paramDbName = Parameter::getGlobalParameter('paramDbName');
        }

        ini_set('mysql.connect_timeout', 10);

        try {
            $dsn = 'mysql:host=' . self::$_dbHost .
                ';port=' . self::$_dbPort .
                ';dbname=' . self::$_dbName .
                ''; //;charset=utf8
            self::$_connexion = new PDO($dsn, self::$_dbUser, self::$_dbPassword);
            self::$_connexion->setAttribute(
                PDO::ATTR_EMULATE_PREPARES,
                false
            );
            self::$_connexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            // Could solve some erroneous default storing in non utf8 format
            self::$_connexion->query("SET NAMES utf8");
        } catch (PDOException $e) {
            if ($e->getCode() == 2002) {
                self::$lastConnectError =  "Aucune connecxion n'a pu être établie avec la base de donnée.";
            } else {
                self::$lastConnectError = mb_convert_encoding($e->getMessage(), "UTF-8", "ISO-8859-1");
                //mb_convert_encoding( $e->getMessage(), "UTF-8", "ISO-8859-1") ou utf8_encode($e->getMessage())
            }
            return false;
        }

        ini_set('mysql.connect_timeout', 60);

        self::$lastConnectError = null;
        return self::$_connexion;
    }

    /**
     * =========================================================================
     * Return the connexion. Only for internal use.
     *
     * @return resource connexion to database
     */
    public static function reconnect()
    {
        self::$_connexion = null;
        self::getConnection();
    }
}
