<?php

class Users extends SqlElement
{

    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
    public $idUserProfile;      //1=Associé,2=invité,9=Admin
    public $idUserStatus;       //1=actif,2=inactif,3=register,9=archive
    public $creationDateTime;
    public $createdBy;
    public $_createdByName;
    public $lastUpdateDateTime;
    public $lastUpdateBy;
    public $_lastUpdateByName;

    public $_UserProfile;
    public $_UserStatus;

    //  public $_noHistory;

    // Define the specific search fields
    private static $_searchFields = array(
        "name"          => "like",
        "email"         => "=",
        "idUserProfile" => "=",
        "idUserStatus"  => "="
    );

    private static $_fieldsAttributes = array(
        "id" => "required,readonly",
        "name" => "required",
        "email" => "required, unique",
        "password" => "required, hidden",
        "idUserProfile" => "required",
        "idUserStatus" => "required",
        "creationDateTime" => "readonly",
        "createdBy" => "readonly",
        "lastUpdateDateTime" => "",
        "lastUpdateBy" => "",
    );



    /**
     * 
     * ==========================================================================
     * Constructor
     *
     * @param  $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null, $withDependentObjects = false)
    {
        parent::__construct($id, $withDependentObjects);
    }

    /**
     * ==========================================================================
     * Destructor
     *
     * @return void
     */
    function __destruct()
    {
        parent::__destruct();
    }

    /**
     * ==========================================================================
     * check if given email exist in the database     
     * 
     * @return array Associate
     */
    function getLinkIdAssociates()
    {

        $record = self::getFirstSqlElementFromCriteria( 
            "Associates",
            array("idUsers" => (object) ["ope" => "=", "val" => $this->id])
        );

        if ($record && $record['id']) {
            // return true because email exists in the database
            return (array)$record;
        }

        // return false if email does not exist in the database
        return false;
    }

    /**
     * ==========================================================================
     * check if given email exist in the database     
     * 
     * @return boolean
     */
    function emailExists()
    {

        $record = self::getFirstSqlElementFromCriteria( 
            get_class($this),
            array("email" => (object) ["ope" => "=", "val" => $this->email])
        );
        // $object = (object) ['property' => 'Here we go'];
        if ($record && $record['id']) {
            // return true because email exists in the database
            return $record['id'];
        }

        // return false if email does not exist in the database
        return false;
    }

    /**
     * ==========================================================================
     * check if given records according to the filter     
     * 
     * @return resource records
     */
    public function search($filter = array(), $withDependentObjects = true)
    {

        $result = $this->getSqlElementsFromCriteria($filter,  'name asc ', true,  $withDependentObjects);

        return $result;
    }


    /**
     * ==========================================================================
     * Save object in database
     * 
     * @return resource boolean
     */
    public function save()
    {

        if (trim($this->id) and !is_numeric($this->id)) {
            return (object) [
                "status" => "INVALID",
                "message" => "id '$this->id' is not numeric for class"
            ];
        }
        if ($this->id == '') $this->id = null;


        if (!$this->id && !$this->idUserProfile) $this->idUserProfile = UserProfile::getGuestId();
        if (!$this->id && !$this->idUserStatus) $this->idUserStatus = UserStatus::getRegStatusId();
        
        $result = parent::save();

        return $result;
    }

    /**
     * ==========================================================================
     * Get records
     * 
     * @return resource records
     */
    public function get($withDependentObjects = true)
    {

        return self::getSqlElementsFromCriteria(array(), null, false, $withDependentObjects);
    }

    public function isActifUser()
    {
        if ($this->id) {
            return ($this->idUserStatus == UserStatus::actifUserId());
        }
        return false;
    }
    public function isAdminUser()
    {
        if ($this->id) {
            return ($this->idUserProfile == UserProfile::adminUserId());
        }
        return false;
    }

    /**
     * ==========================================================================
     * Return the generic databaseTableName
     *
     * @return the layout
     */
    /*protected function getStaticDatabaseTableName()
    {
        return self::$_databaseTableName;
    }*/

    /**
     * ==========================================================================
     * Return the specific fieldsAttributes
     *
     * @return the fieldsAttributes
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

    protected static function defaultPassword()
    {
        return password_hash("digit@2019", PASSWORD_BCRYPT);
    }
}
