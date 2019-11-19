<?php

class factory
{
    /** =========================================================================
     * Constructor.
     * Protected because this class must be extended.
     *
     * @param $id the
     *          id of the object in the database (null if not stored yet)
     * @return void
     */
    protected function __construct()
    { }

    /** =========================================================================
     * Destructor
     *
     * @return void
     */
    protected function __destruct() 
    { }

    /** =========================================================================
     * updateItem()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    protected function updateItem($id, $payload) {

        //Check Id from url
        $class = $this->getSqlClass();
        $obj = new $class($id);
        if ($id && $obj->id != $id) return self::setReturn(410, "Gone", "L'élément ne peut pas être mise à jour, il n'existe plus.");

        foreach ($payload as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }

        Sql::beginTransaction();
        $result = $obj->save();
        
        if ($result->status === "OK") {
            sql::commitTransaction();
            return self::setReturn(($result->action == 'insert') ? 201 : 200, $result->status, $result->message, $result->data, "items"); 

        } else {
            sql::rollbackTransaction();
            return self::setReturn(404, $result->status, $result->message, $result->data, "errors"); 
        }

    }

    /** =========================================================================
     * deleteItem()
     * 
     * @param array $data
     *
     * @return array
     */
    protected function deleteItem($id) {

        $class = $this->getSqlClass();

        $obj = new $class();
        $obj->id = $id;
    
        Sql::beginTransaction();
        $result = $obj->delete();
        
        if ($result->status === "OK") {
            sql::commitTransaction();
            return self::setReturn(204, "", ""); 

        } else {
            sql::rollbackTransaction();
            return self::setReturn(404, $result->status, $result->message, $result->data, "errors"); 
        }
    }

    /** =========================================================================
     * createItem()
     * 
     * @param array $data
     *
     * @return array
     */
    protected function createItem($data) {

        //Get Id from query
        $id = isset($data->id) ? $data->id : null;
        if ($id) return self::setReturn(400, "Bad Request", "Requête REST invalide"); 
    
        $class = $this->getSqlClass();

        $obj = new $class();
    
        foreach ($data as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
        
        Sql::beginTransaction();
        $result = $obj->save();
        
        if ($result->status === "OK") {
            sql::commitTransaction();
            return self::setReturn(($result->action == 'insert') ? 201 : 200, $result->status, $result->message, $result->data, "items");                 

        } else {
            sql::rollbackTransaction();
            return self::setReturn(400, $result->status, $result->message, $result->data, "errors");                 
        }
    }

    /** =========================================================================
     * getItemFromId()
     * 
     * @param int $id
     * @param array $param
     *
     * @return array
     * 
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    protected function getItemFromId($id = null, $param) {

        // Query : api/$class/$id       -->> Get a specific item of Class
        $class = $this->getSqlClass();

        if ($class && $id) {

            // get Dependency Param
            $withDependency = count($param) > 0 ? (array_key_exists("dependency", $param) ? (bool) ($param['dependency'] == 1) : false) : false;

            $obj = new $class($id, $withDependency);

            // Check Profil for authorization to exec Action on the Class
            if (!checkAuthorization("GET", $class, $obj)) {
                return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');
            } else {
                //check if elements exist
                if ($obj->id != $id) {
                    return self::setReturn(404, "Not Found", 'Ressource non trouvée.');
                } else {
                    $returnArray = $obj->getArrayFromClass($id, $withDependency);
                    return self::setReturn(200, "OK","", $returnArray, "item");                 
                }
            }
        }
    }

    /** =========================================================================
     * getItems()
     * 
     * @param array $param
     *
     * @return array
     * 
     *      code : 
     *      status : 
     *      message : 
     *      data : 
     *          count :
     *          items []
     */
    protected function getItems($param) {
        // Query : api/$class/$id       -->> Get a specific item of Class
        $class = $this->getSqlClass();

        // get Dependency Param
        $withDependency = array_key_exists("dependency", $param)? (bool)($param['dependency'] == 1) : false;

        $obj = new $class();

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("GET", $class, $obj)) {
            return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');
        } 

        $filter = array();
        foreach ($obj->getStaticSearchFields() as $key => $value) {
            if (array_key_exists($key, $param)) {
                switch ($value) {
                    case 'like':
                        $filter[] = (object) ["key" => $key, "ope" => "like", "val" => $param[$key]];
                        break;
                        
                    default:
                        $filter[$key] =  $param[$key];
                        break;
                };
            }
        }

        // query products
        $result = $obj->search($filter, $withDependency);
        $rowCnt = count($result);

        $returnArray["count"] = $rowCnt;
        $returnArray["items"] = $result;

        if ($rowCnt > 0) {
            $message = "La recherche a trouvé " . $rowCnt . " enregistrement(s)";
        } else {
            $message = "Aucun enregistrement trouvé";
        }
    
        return self::setReturn(200, "OK", $message, $returnArray);
    }

    /** =========================================================================
     * getSqlClass()
     * 
     * @param void
     *
     * @return string
     */
    protected function getSqlClass() {
        $class = get_class($this);
        return str_replace("Factory", "", $class);
    }

    /** =========================================================================
     * setReturn()
     * 
     * @param int $code
     * @param string $status
     * @param string $message
     * @param string $dataTag
     * @param object $data
     * 
     * @return array
     */
    protected static function setReturn($code, $status, $message, $data = null, $dataTag = "data") {
        $returnArray = array(
                "code"      => (int)$code, 
                "status"    => $status, 
                "message"   => $message);

        if ($data) {
            if (is_object($data)) {
                // convert $data too Array if needed
                $returnArray[$dataTag] = get_object_vars($data);
            } else {
                $returnArray[$dataTag] = $data;
            }
        }

        return $returnArray;
    }

}
