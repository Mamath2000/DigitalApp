<?php

class UsersFactory extends Factory
{
    /** ==========================================================================
     * Constructor
     * 
     * @param $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null) {
        parent::__construct($id);
    }

    /** ==========================================================================
     * Destructor
     *
     * @return void
     */
    function __destruct() {
        parent::__destruct();
    }

    /** =========================================================================
     * updatePassword()
     * 
     * @param int   $id
     * @param array $payload
     *
     * @return array
     */
    function updatePassword($id, $payload) {

        $class = $this->getSqlClass();

        // get data from payload
        $password   = isset($payload->password) ? $payload->password : null;
        if (!$password) return self::setReturn(400, "Bad Request", "Requête REST invalide");        
    
        $user = new Users($id, false);
        if ($user->id !== $id) 
            return self::setReturn(400, "INVALID", "L'utilisateur n'existe plus.");
            
        // Get object only possible to Admin or Owners
        if (!checkAuthorization("PUT", $class, $user)) 
            return self::setReturn(403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        $user->password = password_hash($password, PASSWORD_BCRYPT);
        
        Sql::beginTransaction();
        $result = $user->save();

        if ($result->status==="OK") {
            sql::commitTransaction();
            return self::setReturn(200, $result->status, "");

        } else {
            sql::rollbackTransaction();
            return self::setReturn(400, "Bad Request", "Requête REST invalide");
        }
    }

    /** =========================================================================
     * updateItem()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function updateItem($id, $payload) {
        if (property_exists($payload, "password")) {
            $payload->password = password_hash($payload->password, PASSWORD_BCRYPT);
        }
        return parent::updateItem($id, $payload);
    }

    /** =========================================================================
     * deleteItem()
     * 
     * @param int $id
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function deleteItem($id) {
        return parent::deleteItem($id);
    }
    
    /** =========================================================================
     * createItem()
     * 
     * @param array $paylaod
     *
     * @return array
     * 
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function createItem($payload) {
        if (property_exists($payload, "password")) {
            $payload->password = password_hash($payload->password, PASSWORD_BCRYPT);
        }
        return parent::createItem($payload);
    }

    /** =========================================================================
     * getToken()
     * 
     * @param void
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      data : [] 
     */
    function getToken() {
        // Query : /api/users/check      -->> Check the validity of JWT token
        $class = $this->getSqlClass();

        $decoded = checkJWT($class);
            
        $User = new Users($decoded->data->id);

        $Associate = $User->getLinkIdAssociates();
        if ($Associate && $Associate['id']) {
            $link = array("idAssociates" => $Associate['id']);
            foreach ($Associate as $key => $value) {
                if ($key != "id") $link["|A|".$key] = $value;
            }

        } else {
            $link = array("idAssociates" => false);
        }

        $decoded->data = (object) array_merge( 
                    (array)$decoded->data,
                    $link,
                    array(  "created" => date(DateTime::ISO8601, $decoded->iat), 
                            "expired" => date(DateTime::ISO8601, $decoded->exp)));


        // set response code
        return self::setReturn(200, "OK","Vous êtes correctement identifié.", $decoded->data);       
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
    function getItemFromId($id = null, $param) {
        return parent::getItemFromId($id, $param);
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
    function getItems($param) {
        return parent::getItems($param);
    }
    
}
