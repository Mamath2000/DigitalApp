<?php

/**
 * ===========================================================================**********
 * Abstract class defining all methods to interact with database,
 * using Sql class.
 * Give public visibility to elementary methods (save, delete, copy, ...)
 * and constructor.
 */
abstract class SqlElement
{
    // List of fields that will be exposed in general user interface
    public $id;
    // every SqlElement have an id !!!

    // Define the specific field attributes
    private static $_fieldsAttributes = array("name" => "required");

    // Define the specific search fields
    private static $_searchFields = array();

    public static $_cachedQuery = array(
        'UserStatus' => array(),
        'UserProfile' => array(),
        'LinesDef' => array(),
        'AssociateStatus' => array(),
        'Associates' => array(),
        'Users' => array(),
        'Reports' => array(), 
        'Cells' => array()
    );


    private static $_relationShip = array(
        "Associates" => array("Cells" => "control", "Users" => "control"), 
        "LinesDef" => array("Cells" => "control", "Reportsitem" => "control"), 
        "Reports" => array("Reportsitem" => "control")
    );

    /**
     * =========================================================================
     * Constructor.
     * Protected because this class must be extended.
     *
     * @param $id the
     *          id of the object in the database (null if not stored yet)
     * @return void
     */
    protected function __construct($id = NULL, $withDependentObjects = false)
    {
        if (trim($id) and !is_numeric($id)) {
            $class = get_class($this);
            traceLog("SqlElement->_construct : id '$id' is not numeric for class $class");
            return;
        }
        $this->id = $id;
        if ($this->id == '') {
            $this->id = null;
        }
        $this->getSqlElement($withDependentObjects);
    }

    /**
     * =========================================================================
     * Destructor
     *
     * @return void
     */
    protected function __destruct()
    { }

    // Store the layout of the different object classes
    private static $_tablesFormatList = array();


    // ============================================================================**********
    // UPDATE FUNCTIONS
    // ============================================================================**********

    /**
     * =========================================================================
     * Give public visibility to the saveSqlElement action
     *
     * @param
     *          force to avoid controls and force saving even if controls are false
     * @return message including definition of html hiddenfields to be used
     */
    public function save()
    {
        $debugTraceUpdates = Parameter::getGlobalParameter('debugTrace');
        if (isset($debugTraceUpdates) and $debugTraceUpdates == true) {
            debugTraceLog("Start SAVE for " . get_class($this) . " #" . $this->id . '');
            $startMicroTime = microtime(true);
        }

        $result = $this->saveSqlElement();

        if (isset($debugTraceUpdates) and $debugTraceUpdates == true) {
            debugTraceLog("  End SAVE for " . get_class($this) . " #" . $this->id . '' . " => " . round((microtime(true) - $startMicroTime) * 1000000) / 1000000);
        }
        return $result;
    }

    /**
     * =========================================================================
     * Give public visibility to the deleteSqlElement action
     *
     * @return message including definition of html hiddenfields to be used
     */
    public function delete()
    {

        $debugTraceUpdates = Parameter::getGlobalParameter('debugTrace');
        // PlugIn Management
        if (isset($debugTraceUpdates) and $debugTraceUpdates == true) {
            debugTraceLog("Start DELETE for " . get_class($this) . " #" . $this->id);
            $startMicroTime = microtime(true);
        }

        $result = $this->deleteSqlElement();

        if (isset($debugTraceUpdates) and $debugTraceUpdates == true) {
            debugTraceLog("End DELETE for " . get_class($this) . " #" . $this->id . " => " . round((microtime(true) - $startMicroTime) * 1000000) / 1000000);
        }

        return $result;
    }


    /**
     * =========================================================================
     * Save an object to the database
     *
     * @return void
     */
    private function saveSqlElement()
    {

        // Control database
        $control = $this->control();
        if (count($control) != 0) {
            // errors on control => don't save, display error message
            $returnValue  = 'Controles invalides';
            $returnStatus = "INVALID";
            return (object) [
                "status" => $returnStatus,
                "message" => $returnValue,
                "data" =>  $control,
                "id" => null
            ];
        }

        //Check unicity
        $control = $this->unicity();
        if (count($control) != 0) {
            // errors on control => don't save, display error message
            $returnValue  = 'Controles invalides';
            $returnStatus = "INVALID";
            return (object) [
                "status" => $returnStatus,
                "message" => $returnValue,
                "data" =>  $control,
                "id" => null
            ];
        }

        // $old=new Project();
        if ($this->id != null) {
            $newItem = false;
            $returnValue = $this->updateSqlElement();
        } else {
            $newItem = true;
            $returnValue = $this->insertSqlElement();
        }

        return $returnValue;
    }

    /**
     * =========================================================================
     * Save an object to the database : new object
     *
     * @return void
     */
    private function insertSqlElement()
    {

        $objectClass = get_class($this);

        $queryColumnsArray = array();
        $bindArray = array();

        // initialize object definition criteria
        $databaseCriteriaList = $this->getDatabaseCriteria();
        foreach ($databaseCriteriaList as $col_name => $col_value) {
            $col_value = $this->boolVal($col_name, $col_value);
            if ($col_value != NULL and $col_value != '' and $col_value != ' ' and ($col_name != 'id')) {
                $queryColumnsArray[] = $col_name;
                $bindArray[$col_name] = $col_value;
            }
        }
        if (property_exists($this, "password") && (trim($this->password) == '' || $this->password == NULL)) {
            $this->password = User::defaultPassword();
        }
        // get all data
        foreach ($this as $col_name => $col_value) {
            if (substr($col_name, 0, 1) == "_") {
                // not a fiels, just for presentation purpose
            } else if (array_key_exists($col_name, $queryColumnsArray)) {
                // Do not overwrite the default value from databaseCriteria, and do not double-set in insert clause
            } else {
                $col_value = $this->boolVal($col_name, $col_value);
                if ($col_value != NULL and $col_value != '' and $col_value != ' ' and ($col_name != 'id')) {
                    $queryColumnsArray[] = $col_name;
                    $bindArray[$col_name] = $col_value;
                }
            }
        }

        if (property_exists($this, 'lastUpdateDateTime')) {
            $queryColumnsArray[] = "creationDateTime";
            $bindArray['creationDateTime'] = date('Y-m-d H:i:s');

            $queryColumnsArray[] = "createdBy";
            $bindArray['createdBy'] = getSessionValue("userId", 1);

            $queryColumnsArray[] = "lastUpdateDateTime";
            $bindArray['lastUpdateDateTime'] = date('Y-m-d H:i:s');

            $queryColumnsArray[] = "lastUpdateBy";
            $bindArray['lastUpdateBy'] = getSessionValue("userId", 1);
        }
        if (property_exists($this, 'startDate') && !array_key_exists("startDate", $bindArray)) {
            $queryColumnsArray[] = "startDate";
            $bindArray['startDate'] = date('Y-m-d');
        }
        if (property_exists($this, 'endDate') && !array_key_exists("endDate", $bindArray)) {
            $queryColumnsArray[] = "endDate";
            $bindArray['endDate'] = date('Y-m-d', mktime(0, 0, 0, 12, 31, 2099));
        }

        $query = "insert into " . $this->getDatabaseTableName();
        $queryColumns = join(", ", $queryColumnsArray);
        $queryValues = ":" . join(", :", $queryColumnsArray);
        $query .= " ($queryColumns) values ($queryValues)";

        // execute request
        $result = Sql::query($query, $bindArray);

        if (sql::$lastQueryErrorCode) {
            return (object) [
                "status" => "ERROR",
                "message"   => sql::$lastQueryErrorMessage,
                "id"        => NULL,
                "data"      => NULL
            ];
        }

        if ($result) {
            // save history
            $newId = Sql::$lastQueryNewid;
            $this->id = $newId;

            if (!property_exists($this, '_noHistory')) {
                $result = History::store($this, $objectClass, $newId, 'insert');
                if (!$result) {
                    $returnStatus = "ERROR";
                    $returnValue = Sql::$lastQueryErrorMessage;

                    return (object) [
                        "status" => $returnStatus,
                        "message"   => $returnValue,
                        "id"        => $this->id,
                        "data"      => NULL
                    ];
                }
            }

            $returnStatus = "OK";
            $returnValue = "l'élément {$objectClass} #{$this->id} a été inséré.";

        } else {
            $returnStatus = "ERROR";
            $returnValue = Sql::$lastQueryErrorMessage;

        }
        //array("email" => (object)["ope" => "=", "val" => $this->email]),true)
        return (object) [
            "status" => $returnStatus,
            "message"   => $returnValue,
            "action"    => "insert",
            "id"        => $this->id,
            "data" => $this->getArrayFromClass(null, true)
        ];
    }

    /**
     * Get old values (stored in session) to :
     * 1) build the smallest query
     * 2) save change history
     *
     * @param string $objectClass
     * @param string $force
     * @return Ambigous <NULL, unknown>
     */
    public static function getObject($objectClass = null, $objectId = null)
    {
        if ($objectClass && $objectId) {
            return new $objectClass($objectId, true);
        } else {
            return null;
        }
    }

    /**
     * =========================================================================
     * save an object to the database : existing object
     *
     * @return void
     */
    private function updateSqlElement()
    {
        $objectClass = get_class($this);

        $returnValue = "aucune modification de l'élément : {$objectClass} #{$this->id}";
        $returnStatus = 'NO_CHANGE';
        $arrayCols = array();
        $bindArray = array();

        $oldObject = self::getObject($objectClass, $this->id);

        // get all data, and identify if changes
        foreach ($this as $col_name => $col_new_value) {
            //$attribute = $this->getFieldAttributes($col_name);
            if (substr($col_name, 0, 1) == "_" || substr($col_name, 0, 10) == "lastUpdate" || $col_name === "creationDateTime" || $col_name === "createdBy") {
                // not a fiels, just for presentation purpose
            } else {
                $dataType = $this->getDataType($col_name);
                $dataLength = $this->getDataLength($col_name);

                // Manage numeric values
                if ($dataType == 'int' and $dataLength == 1) {
                    if ($col_new_value == NULL or $col_new_value == "") $col_new_value = '0';
                }

                //Manage decimal values
                if ($dataType == 'decimal') {
                    $col_new_value = str_replace(',', '.', $col_new_value);
                }

                $col_old_value = $oldObject->$col_name;
                $col_new_value = $col_new_value;

                // special null treatment (new value)
                if ($col_new_value == '') $col_new_value = NULL;
                // special null treatment (old value)
                if ($col_old_value == '') $col_old_value = NULL;
                // if changed
                $col_new_value=$this->boolVal($col_name, $col_new_value);
                $col_old_value=$this->boolVal($col_name, $col_old_value);

                // !!! do not insert query for last update date time unless some change is detected
                if ($col_new_value != $col_old_value && !array_key_exists($col_name, $arrayCols)) {
                    if ($col_new_value == NULL or $col_new_value == '' or $col_new_value == "''") {
                        $arrayCols[$col_name] = $col_name . " = NULL";
                    } else {
                        //$arrayCols[$col_name] = $col_name . ' = ' . Sql::str($col_new_value);
                        $arrayCols[$col_name] = $col_name . ' = :' . $col_name;
                        $bindArray[$col_name] = $col_new_value;
                    }
                    // Save change history
                    if ($objectClass != 'History' and !property_exists($this, '_noHistory') and $col_name != 'id' and $col_name != 'lastUpdateDateTime') {
                        $result = History::store($this, $objectClass, $this->id, 'update', $col_name, $col_old_value, $col_new_value);
                        if (!$result) {
                            $returnStatus = "ERROR";
                            $returnValue = Sql::$lastQueryErrorMessage;
                        }
                    }
                }
            }
        }

        if (count($arrayCols) > 0) {
            if (property_exists($this, 'lastUpdateDateTime')) {
                //$arrayCols['lastUpdateDateTime'] = "lastUpdateDateTime = " . Sql::str(date('Y-m-d H:i:s'));
                $arrayCols['lastUpdateDateTime'] = "lastUpdateDateTime = :lastUpdateDateTime";
                $bindArray['lastUpdateDateTime'] = date('Y-m-d H:i:s');
                
                //$arrayCols['lastUpdateBy'] = "lastUpdateBy = " . getSessionValue("userId", 1); ////MBE
                $arrayCols['lastUpdateBy'] = "lastUpdateBy = :lastUpdateBy"; ////MBE
                $bindArray['lastUpdateBy'] = getSessionValue("userId", 1);
            }

            // Création de la requete
            $query = "update " . $this->getDatabaseTableName();
            $query .= " set " . join(", ", $arrayCols);
            $query .= ' where id=:_id';
            $bindArray['_id'] = $this->id;

            // If changed, execute the query
            if ($returnStatus != "ERROR") {
                // Catch errors, and return error message
                $result = Sql::query($query, $bindArray);
                
                if (sql::$lastQueryErrorCode || !$result) {
                    $returnStatus = 'ERROR';
                    $returnValue  = sql::$lastQueryErrorMessage; 

                } else {
                    if (Sql::$lastQueryNbRows == 0) {
                        $test = new $objectClass($this->id);
                        if ($this->id != $test->id) {
                            $returnValue = "l'élément suivant n'existe plus : {$objectClass} #{$this->id}";
                            $returnStatus = 'ERROR';
                        } else {
                            $returnValue = "aucune modification de l'élément : {$objectClass} #{$this->id}";
                            $returnStatus = 'NO_CHANGE';
                        }
                    } else {
                        $returnValue = "l'élément {$objectClass} #{$this->id} a été mise à jour.";
                        $returnStatus = 'OK';
                    }

                } 
            }
        }
        //array("email" => (object)["ope" => "=", "val" => $this->email]),true)
        if ($returnStatus === 'ERROR') {
            $returnArray=array();
        } else {
            $returnArray=$this->getArrayFromClass(null, true);
        }

        return (object) [
            "status" => $returnStatus,
            "message" => $returnValue,
            "action" => "update",
            "id" => $this->id,
            "data" => $returnArray
        ];
    }

    /**
     * =========================================================================
     * Delete an object from the database
     *
     * @return void
     */
    private function deleteSqlElement()
    {
        if (!$this->id or $this->id < 0) {
            return;
        }
        $class = get_class($this);
        $control = $this->deleteControl();


        if (count($control) != 0) {
            // errors on control => don't save, display error message
            $returnValue  = 'Controles invalides';
            $returnStatus = "INVALID";
            return (object) [
                "status" => $returnStatus,
                "message" => $returnValue,
                "data" =>  $control,
                "id" => null
            ];
        }

        // check relartionship : if "cascade", then auto delete
        //$relationShip = self::$_relationShip;

        $query = "delete from " . $this->getDatabaseTableName() . " where id=:_id";
        $bindArray = array("_id" => $this->id);
        
        // execute request
        $result = Sql::query($query, $bindArray);

        if (sql::$lastQueryErrorCode) {
            return (object) [
                "status" => "ERROR",
                "message"   => sql::$lastQueryErrorMessage,
                "id"        => NULL,
                "data"      => NULL
            ];
        }

        // save history
        if (!property_exists($this, '_noHistory')) {
            $result = History::store($this, $class, $this->id, 'delete');
            if (!$result) {
                $returnStatus = "ERROR";
                $returnValue = Sql::$lastQueryErrorMessage;
                return (object) [
                    "status" => $returnStatus,
                    "message" => $returnValue,
                    "id" => $this->id
                ];
            }
        }
        
        $returnValue = "l'élément {$class} #{$this->id} a été supprimé.";
        $returnStatus = 'OK';
        return (object) [
            "status" => $returnStatus,
            "message" => $returnValue,
            "action" => "delete",
            "id" => null
        ];
    }

    // ============================================================================**********
    // GET AND FETCH OBJECTS FUNCTIONS
    // ============================================================================**********


    /**
     * =========================================================================
     * Transform an filter in array form to SQL Where expression
     *
     * @param array $filter Array
     *          the critera as an array
     *
     * @return string (Sql Where Expression)
     */
    protected function getSqlWhereFromFilter($filter, &$whereArray, &$bindArray)
    {

        $paramIdx = 0;
        foreach ($filter as $key => $value) {
            
            if (!property_exists($this, $key) && is_object($value) && property_exists($value, "key")) {
                $curKey = $value->key;
            } else {
                $curKey = $key;
            }
            if (property_exists($this, $curKey)) {

                if (is_object($value)) {
                    $ope = $value->ope;
                    $val = $value->val;
                    $fx = (property_exists($value, "fx"))? $value->fx : "";
                } else {
                    $val = $value;
                    $ope = "=";
                    $fx = "";
                }

                switch ($ope) {
                    case '<>':
                    case '<':
                    case '>':
                    case '<=':
                    case '>=':
                    case '=':
                        if ($val === null || trim($val) === '') {
                            $whereArray[] = (($ope === "<>")?"!(" : "(") . $this->getDatabaseTableName() . ".{$curKey} is null)";
                        } else { 
                            if ($fx) {
                                $whereArray[] = str_replace("<|fx.key|>", $this->getDatabaseTableName() . ".{$curKey}", $fx) . " {$ope} :" . $curKey . $paramIdx;
                            } else {
                                $whereArray[] = $this->getDatabaseTableName() . ".{$curKey} {$ope} :" . $curKey . $paramIdx;
                            }
                            $bindArray[$curKey . $paramIdx] = $val;
                            $paramIdx += 1;
                        }
                        break;

                    case 'in':
                        if ($fx) {
                            $whereArray[] = $this->getDatabaseTableName() . ".{$curKey}" . " {$ope} " . str_replace("<|fx.key|>", ":" . $curKey . $paramIdx, $fx);
                            $bindArray[$curKey . $paramIdx] = $val ;
                            $paramIdx += 1;
                        } 
                        break;
                    case 'like':
                        $whereArray[] = $this->getDatabaseTableName() . ".{$curKey} like :" . $curKey . $paramIdx;
                        $bindArray[$curKey . $paramIdx] = "%" . $val . "%";
                        $paramIdx += 1;
                    
                        break;
                    default: 
                    $whereArray[] = "1 = 2";                    
                }
            } else {
                $whereArray[] = "1 = 2";
            }
        }
    }

    /**
     * ========================================================================
     * Retrieve a list of objects from the Database
     * Called from an empty object of the expected class
     *
     * @param array $critArray
     *          the critera as an array
     * @param string $clauseOrderBy
     *          Sql Order By clause
     * @param boolean $getIdInKey
     * @return SqlElement[] an array of objects
     */
    public function getSqlElementsFromCriteria($critArray, 
            $clauseOrderBy = null, $getIdInKey = false, $withDependentObjects = false, $maxElements = null)
    {
        // Build where clause from criteria
        $objects = array();
        $whereClause = '';

        //data base criteria management First
        $whereArray = array();
        $bindArray = array();
        foreach ($this->getDatabaseCriteria() as $colCrit => $valCrit) {
            $whereArray[] = $this->getDatabaseTableName() . '.' . $colCrit . " = :" . $colCrit;
            $bindArray[$colCrit] = $valCrit; 
        }

        if ($critArray) {
            $this->getSqlWhereFromFilter($critArray, $whereArray, $bindArray);
        
        }
        $whereClause = join(" AND ", $whereArray);

        // If $whereClause is set, get the element from Database
        $whereClause = ($whereClause != '') ? ' where ' . $whereClause : '';
        $query = 'select * from ' . $this->getDatabaseTableName() . $whereClause;

        // ORDER BY 
        if ($clauseOrderBy) {
            $query .= ' order by ' . $clauseOrderBy;
        } else if (property_exists($this, 'sortOrder')) {
            $query .= ' order by ' . $this->getDatabaseTableName() . '.sortOrder';
        }

        // LIMIT
        if ($maxElements) {
            $query .= ' LIMIT ' . $maxElements;
        }

        $result = Sql::query($query, $bindArray);

        if (sql::$lastQueryErrorCode) {
            errorLog(sql::$lastQueryErrorMessage);
            return false;
        }
        if (Sql::$lastQueryNbRows > 0) {
            $line = Sql::fetchLine($result);
            while ($line) {
                $rowArray = array();

                // get all data fetched
                $keyId = null;
                foreach ($this as $col_name => $col_value) {
                    if (substr($col_name, 0, 1) == "_" 
                            || $this->isAttributeSetToField($col_name, 'hidden')) {
                        // not a field, just for presentation purpose

                    } else if ($withDependentObjects && substr($col_name, 0, 2) == "id"  && class_exists(substr($col_name, 2))) {
                        $rowArray[$col_name] = $line[$col_name];
                        $subClass = substr($col_name, 2);
                        if ($line[$col_name] && property_exists($this, "_" . $subClass)) {
                            $subItem = new $subClass($line[$col_name]);
                            $rowArray["_" . $subClass] = $subItem;
                            unset($subItem);
                        }
                    } else if ($withDependentObjects and "By" == substr($col_name, -2)) {
                        $rowArray[$col_name] = $line[$col_name];
                        if (property_exists($this, "_{$col_name}Name")) {
                            $subItem = new Users($line[$col_name], false);
                            if (property_exists($subItem, "name")) 
                                    $rowArray["_" . $col_name . "Name"] = $subItem->name;
                            unset($subItem);
                        }
                    } else {
                        if (array_key_exists($col_name, $line)) {
                            $rowArray[$col_name] = $line[$col_name];

                        } else if (array_key_exists(strtolower($col_name), $line)) {
                            $rowArray[$col_name] = $line[strtolower($col_name)];

                        } else {
                            errorLog("Error on SqlElement to get '" . $col_name . "' for Class '" . get_class($this) . "' " . " : field '" . $col_name . "' not found in Database.");

                        }
                        if ($col_name == 'id' and $getIdInKey) {
                            $keyId = '#' . $line[$col_name];
                        }
                    }
                    // FOR PHP 7.1 COMPATIBILITY
                    $dataType = $this->getDataType($col_name);
                    if (array_key_exists($col_name, $rowArray)
                            && $rowArray[$col_name] === null 
                            && (($dataType == 'decimal') or ($dataType == 'numeric'))) {
                        $rowArray[$col_name] = 0;
                    }
                }
                if ($getIdInKey) {
                    $objects[$keyId] = $rowArray;
                } else {
                    $objects[] = $rowArray;
                }

                $line = Sql::fetchLine($result);
            }
        }
      
        return $objects;
    }

    /**
     * ========================================================================
     * Retrieve the count of a list of objects from the Database
     * Called from an empty object of the expected class
     *
     * @param array $critArray the
     *          critera asd an array
     * @return an array of objects
     */
    public function countSqlElementsFromCriteria($critArray)
    {
        // Build where clause from criteria
        $whereArray = array();
        $bindArray = array();

        $whereClause = '';

        foreach ($this->getDatabaseCriteria() as $colCrit => $valCrit) {
            $whereArray[] = $this->getDatabaseTableName() . '.' . $colCrit . " = :" . $colCrit;
            $bindArray[$colCrit] = $valCrit; 
        }

        if ($critArray) {
            $this->getSqlWhereFromFilter($critArray, $whereArray, $bindArray);

        }

        $whereClause = join(" AND ", $whereArray);
        $whereClause = ($whereClause != '') ? ' where ' . $whereClause : '';

        // If $whereClause is set, get the element from Database
        $query = "select count(id) as cpt from " . $this->getDatabaseTableName() . $whereClause;

        $result = Sql::query($query, $bindArray);

        if (sql::$lastQueryErrorCode) {
            errorLog(sql::$lastQueryErrorMessage);
            return -1;
        }

        if (Sql::$lastQueryNbRows > 0) {
            $line = Sql::fetchLine($result);
            return $line['cpt'];
        }
        return 0;
    }

    /**
     * ==========================================================================
     * Retrieve the first object from the Database
     * Called from an empty object of the expected class
     *
     * @param string $class
     *          The object class
     * @param array $critArray
     *                the critera as an array
     * @return array : The object find - If not found set attribut _singleElementNotFound
     */
    public static function getFirstSqlElementFromCriteria($class, $critArray, $clauseOrderBy = null, $withDependentObjects = false)
    {
        //$critArray, 
        $obj = new $class();
        $objList = $obj->getSqlElementsFromCriteria($critArray, $clauseOrderBy, false, $withDependentObjects, 2);
        if (!$objList) return null;

        if (count($objList) == 0) {
            $obj->_singleElementNotFound = true;
            return (array)$obj;
        } else {
            return (array)$objList[0];
        }
    }

    /**
     * ==========================================================================
     * Retrieve an object from the Database
     * 
     * @param boolean $withDependentObjects
     *
     * @return void
     */
    private function getSqlElement($withDependentObjects = true)
    {
        $curId = $this->id;
        if (!trim($curId)) {
            $curId = null;
        }

        // Cache management --------------------------------------------------------
        if ($curId and array_key_exists(get_class($this), self::$_cachedQuery)) {
            $whereClause = '#id=' . $curId;
            $class = get_class($this);
            if (array_key_exists($whereClause, self::$_cachedQuery[$class])) {
                $obj = self::$_cachedQuery[$class][$whereClause];
                foreach ($obj as $fld => $val) {
                    $this->$fld = $obj->$fld;
                }
                return;
            }
        }
        // -------------------------------------------------------------------------

        // If id is set, get the element from Database
        if ($curId != NULL) {
            $bindArray = array("id" => $curId);
            $query = "select * from " . $this->getDatabaseTableName() . ' where id=:id';
            foreach ($this->getDatabaseCriteria() as $critFld => $critVal) {
                $query .= ' and ' . $critFld . ' = :' . $critFld;
                $bindArray[$critFld] = $critVal;
            }
            $result = Sql::query($query, $bindArray);

            if (sql::$lastQueryErrorCode) {
                errorLog(sql::$lastQueryErrorMessage);
                return false;
            }
            if (Sql::$lastQueryNbRows > 0) {
                $line = Sql::fetchLine($result);
                // get all data fetched
                foreach ($this as $col_name => $col_value) {
                    if (substr($col_name, 0, 1) == "_") {
                        //$colName = substr ( $col_name, 1 );
                    } else if ($withDependentObjects && substr($col_name, 0, 2) == "id" && class_exists(substr($col_name, 2))                    ) {
                        $subClass = substr($col_name, 2);
                        $subItem = new $subClass ($line[$col_name], false);
                        if (property_exists($this, "_" . $subClass)) {
                            $this->{"_" . $subClass} = $subItem;
                        }
                        unset($subItem);
                        $this->{$col_name} = $line[$col_name];
                    } else if ($withDependentObjects and "By" == substr($col_name, -2)) {
                        $this->{$col_name} = $line[$col_name];
                        if (property_exists($this, "_{$col_name}Name")) {
                            $subItem = new Users($line[$col_name], false);
                            if (property_exists($subItem, "name")) $this->{"_" . $col_name . "Name"} = $subItem->name;
                            unset($subItem);
                        }
                    } else {
                        if (array_key_exists($col_name, $line)) {
                            $this->{$col_name} = $line[$col_name];
                        } else if (array_key_exists(strtolower($col_name), $line)) {
                            $this->{$col_name} = $line[strtolower($col_name)];
                        } else {
                            errorLog("Error on SqlElement to get '" . $col_name . "' for Class '" . get_class($this) . "' " . " : field '" . $col_name . "' not found in Database.");
                        }
                        // FOR PHP 7.1 COMPATIBILITY
                        $dbType = $this->getDataType($col_name);
                        if ($this->{$col_name} === '' and (($dbType == 'numeric') or ($dbType == 'decimal'))) {
                            $this->{$col_name} = 0;
                        }
                    }
                }
            } else {
                $this->id = null;
            }
        }
        if ($curId and array_key_exists(get_class($this), self::$_cachedQuery)) {
            $whereClause = '#id=' . $curId;
            $class = get_class($this);
            self::$_cachedQuery[get_class($this)][$whereClause] = clone ($this);
        }
    }


    // ============================================================================**********
    // GET STATIC DATA FUNCTIONS
    // ============================================================================**********

    /**
     * ========================================================================
     * return the type of a column depending on its name
     *
     * @param $colName the
     *          name of the column
     * @return string the type of the data
     */
    public function getDataType($colName)
    {
        $colName = strtolower($colName);
        $formatList = self::getFormatList(get_class($this));

        if (!array_key_exists($colName, $formatList))  return 'undefined';
        
        $fmt = $formatList[$colName];
        $split = preg_split('/[()\s]+/', $fmt, 2);
        return $split[0];
    }

    /**
     * ========================================================================
     * return the length (max) of a column depending on its name
     *
     * @param $colName the
     *          name of the column
     * @return int the type of the data
     */
    public function getDataLength($colName)
    {
        $colName = strtolower($colName);
        $formatList = self::getFormatList(get_class($this));
        if (!array_key_exists($colName, $formatList)) return '';

        $fmt = $formatList[$colName];
        $split = preg_split('/[()\s]+/', $fmt, 3);
        $type = $split[0];
        if ($type == 'date') {
            return 10;
        } else if ($type == 'time') {
            return 5;
        } else if ($type == 'timestamp' or $type == 'datetime') {
            return 19;
        } else if ($type == 'double') {
            return 2;
        } else if ($type == 'text') {
            return 65535;
        } else if ($type == 'mediumtext') {
            return 16777215;
        } else if ($type == 'longtext') {
            return 4294967295;
        } else {
            if (count($split) >= 2) {
                if (is_numeric($split[1])) {
                    return ($split[1]*1);
                } else {
                    return $split[1];
                }
            } else {
                return 0;
            }
        }
    }

    /**
     * ========================================================================
     * return the generic attributes (required, disabled, .
     * ..) for a given field
     * 
     * @param $colName the
     *
     * @return string an array of fields with specific attributes
     */
    public function getFieldAttributes($fieldName)
    {
        $fieldsAttributes = $this->getStaticFieldsAttributes();
        if (array_key_exists($fieldName, $fieldsAttributes)) {
            return $fieldsAttributes[$fieldName];
        } else {
            return '';
        }
    }

    /**
     * ========================================================================
     * return if a field has specific attribute
     *
     * @param $fieldName the field
     * 
     * @param $attribute the attribute
     *
     * @return boolean an array of fields with specific attributes
     */
    public function isAttributeSetToField($fieldName, $attribute)
    {
        if (strpos($this->getFieldAttributes($fieldName), $attribute) !== false) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * ========================================================================
     * Return the name of the table in the database
     * Default is the name of the class (lowercase)
     * May be overloaded for some classes, who reference a table different
     * from class name
     *
     * @return string the name of the data table
     */
    public function getDatabaseTableName()
    {
        return $this->getStaticDatabaseTableName();
    }

    /**
     * ========================================================================
     * Return the additional criteria to select class elements in the database
     * Default is empty string
     * May be overloaded for some classes, which reference a table different
     * from class name
     *
     * @return array listing criteria
     */
    public function getDatabaseCriteria()
    {
        return $this->getStaticDatabaseCriteria();
    }

    /**
     * ========================================================================
     * return if a field has specific attribute
     *
     * @param $id of item
     * 
     * @return array of property class
     */
    public function getArrayFromClass($id = null, $withDependentObjects = false)
    {

        $currId = ($id) ? $id : $this->id;
        $className = get_class($this);
        // clear Cache
        if (array_key_exists($className, self::$_cachedQuery)) {
            $whereClause = "#id=" . $currId ;
            unset(self::$_cachedQuery[$className][$whereClause]);
        }

        //get full Object
        $obj = new $className();
        $arrayFields = $obj->getFirstSqlElementFromCriteria(
            $className,
            array("id" => (object) ["ope" => "=", "val" => $currId]), null,
            $withDependentObjects);

        return $arrayFields;
    }

    /**
     * =========================================================================
     * Return the list of fields format and store it in static array of formats
     * to be able to fetch it again without requesting it from database
     *
     * @param  $class the
     *          class of the object
     * @return array
     */
    private static function getFormatList($class)
    {
        if (count(self::$_tablesFormatList) == 0) { // if static value not initalized, try and retrieve from session
            $fromSession = getSessionValue('_tablesFormatList');
            if ($fromSession == null) {
                setSessionValue('_tablesFormatList', self::$_tablesFormatList);
            } else {
                self::$_tablesFormatList = $fromSession;
            }
        }
        if (array_key_exists($class, self::$_tablesFormatList)) {
            return self::$_tablesFormatList[$class];
        }
        $obj = new $class();
        $formatList = array();
        $query = "desc " . $obj->getDatabaseTableName();

        $result = Sql::query($query);
        while ($line = Sql::fetchLine($result)) {
            $fieldName = (isset($line['Field'])) ? $line['Field'] : $line['field'];
            $type = (isset($line['Type'])) ? $line['Type'] : $line['type'];
            $from = array();
            $to = array();

            $from[] = 'mediumtext';
            $to[] = 'varchar(16777215)';
            $from[] = 'longtext';
            $to[] = 'varchar(4294967295)';
            $from[] = 'text';
            $to[] = 'varchar(65535)';
            $from[] = 'bigint';
            $to[] = 'int';

            $type = str_ireplace($from, $to, $type);
            $formatList[strtolower($fieldName)] = $type;
        }
        self::$_tablesFormatList[$class] = $formatList;
        setSessionValue('_tablesFormatList', self::$_tablesFormatList); // store session value (as initalized)
        return $formatList;
    }

    /**
     * ==========================================================================
     * Return the generic fieldsAttributes
     *
     * @return array the layout
     */
    protected function getStaticFieldsAttributes()
    {
        return self::$_fieldsAttributes;
    }

    /**
     * ==========================================================================
     * Return the generic SearchFields
     *
     * @return array the searchFields
     */
    public function getStaticSearchFields()
    {
        return self::$_searchFields;
    }    
    
    /**
     * ==========================================================================
     * Return the generic defaultValues
     *
     * @return the layout
     */
    protected function getStaticDefaultValues()
    {
        return self::$_defaultValues;
    }

    /**
     * ==========================================================================
     * Return the generic databaseTableName
     *
     * @return the layout
     */
    protected function getStaticDatabaseTableName()
    {
        return strtolower(get_class($this));
    }

    /**
     * ========================================================================
     * Return the generic database criteria
     *
     * @return the databaseTableName
     */
    protected function getStaticDatabaseCriteria()
    {
        return array();
    }

    // ============================================================================**********
    // GET VALIDATION SCRIPT
    // ============================================================================**********

    /**
     * =========================================================================
     * unicity data corresponding to Model constraints, before saving an object
     *
     * @param  void
     * 
     * @return array "OK" if unicity are good or an error message
     *               must be redefined in the inherited class
     */
    public function unicity()
    {

        $resultArray = array();
        $fieldArray = array();
        foreach ($this as $fld => $fldVal) {
            if ($this->isAttributeSetToField($fld, 'unique')) {
                $fieldArray[$fld] = (object) ["ope" => "=", "val" => $fldVal];
            }
        }
        if (sizeof($fieldArray) == 0 ) return $resultArray;
        if ($this->id) $fieldArray["id"] = (object) ["ope" => "<>", "val" => $this->id];

        if (count($fieldArray) > 0) {
            if ($this->countSqlElementsFromCriteria($fieldArray)) {
                $resultArray[] = "unicité : règle d'unicité non valide.";
            }
        }
        return $resultArray;
    }


    /**
     * =========================================================================
     * control data corresponding to Model constraints, before saving an object
     *
     * @param  void
     *          
     * @return array "OK" if controls are good or an error message
     *               must be redefined in the inherited class
     */
    public function control()
    {
        // traceLog('control (for ' . get_class($this) . ' #' . $this->id . ')');
        $resultArray = array();
        ($this->id != "");
        if ($this->id) {
            $checkReadonly = true;
            $class = get_class($this);
            $old = new $class($this->id);
        } else {
            $checkReadonly = false;
        }

        foreach ($this as $col => $val) {
            if ((substr($col, 0, 1) == '_') || ("id" == $col) || ("password" == $col)) { } else {
                $dataType = $this->getDataType($col);
                $dataLength = $this->getDataLength($col);
                // check if required
                if ($checkReadonly && $this->isAttributeSetToField($col, 'readonly')) {
                    if ($val != $old->$col)
                        $resultArray[] = "{$col} : Cette information est en lecture seule";
                }
                if ($this->isAttributeSetToField($col, 'required')) {
                    if ((!$val and $val !== 0) or trim($val) == '')
                        $resultArray[] = "{$col} : Valeur obligatoire";
                }
                if (substr($col, 0, 2) === "id" && class_exists(substr($col, 2)) && $val) {
                    $subClass = substr($col, 2);
                    $subItem = new $subClass($val);
                    if (!$subItem->id)
                        $resultArray[] = "{$col} : La valeur [{$val}] n'existe pas dans la table connexe. {$subClass}";
                }
                if ($dataType == 'datetime') {
                    if (strlen($val) == 9)
                        $resultArray[] = "{$col} : Format DateTime invalide [{$val}]";
                }
                if ($dataType == 'date' and $val != '') {
                    if (strlen($val) != 10 or substr($val, 4, 1) != '-' or substr($val, 7, 1) != '-')
                        $resultArray[] = "{$col} : Format date invalide [{$val}]";
                }
                if ($dataType == 'varchar') {
                    if (strlen($val) > $dataLength)
                        $resultArray[] = "{$col} : Texte trop long, la longueur ne doit pas excéder {$dataLength}";
                } else if ($dataType == "int" or $dataType == "tinyint" or $dataType == "decimal") {
                    if (trim($val) and !is_numeric($val))
                        $resultArray[] = "{$col} : Format de nombre invalide [{$val}]";
                }
            }
        }

        return $resultArray;
    }

    /**
     * =========================================================================
     * control data corresponding to Model constraints, before deleting an object
     *
     * @param   void
     * 
     * @return array "OK" if controls are good or an error message
     *               must be redefined in the inherited class
     */
    public function deleteControl()
    {
        $className = get_class($this);
        $resultArray = array();
        $relationShip = self::$_relationShip;


        $obj = new $className($this->id);
        if (!$obj->id) {
            $resultArray[] = "L'élément {$className} #{$this->id} n'existe plus.";
        } else if (array_key_exists(get_class($this), $relationShip)) {
            $relations = $relationShip[get_class($this)];
            foreach ($relations as $object => $mode) {
                $obj = new $object();
                $crit = array('id' . get_class($this) => $this->id);

                $nb = $obj->countSqlElementsFromCriteria($crit);
                if ($nb > 0) {
                    if ($mode == "control")
                        $resultArray[] = "Impossible de supprimer {$className} : il y  a des élements restant {$object}.";
                }
            }
        }
        return $resultArray;
    }

    /**
     * =========================================================================
     * Check if class exist or not 
     *
     * @param   $className  
     * 
     * @return array "true or false" 
     */
    public static function class_exists($className)
    {
        //global $hideAutoloadError;
        //$hideAutoloadError = true; // Avoid error message in autoload
        $result = class_exists($className, true);
        //$hideAutoloadError = false;
        return $result;
    }

    /**
     * =========================================================================
     * Check boolean value against empty
     *
     * @param   $col_name  
     * 
     * @param   $col_value  
     * 
     * @return boolean "true or false" 
     */
    protected function boolVal($col_name, $col_value)
    {
        $dataType = $this->getDataType($col_name);
        $dataLength = $this->getDataLength($col_name);

        if (($dataType == 'int' and $dataLength == 1) || ($dataType == 'tinyint' and $dataLength == 4)) {
            if ($col_value == NULL or $col_value == "") return '0';
        }
        return $col_value;
    }
}
