<?php

class Locks extends SqlElement
{

    // object properties
    public $id;
    public $idAssociates;
    public $idReports;
    public $year;
    public $isLock;
    public $creationDateTime;
    public $createdBy;
    public $_createdByName;

    //  public $_noHistory;

    private static $_fieldsAttributes = array(
        "id" => "required,readonly",
        "idAssociates" => "required,readonly",
        "idReports" => "required,readonly",
        "year" => "required,readonly",
        "isLock" => "required",
        "creationDateTime" => "readonly",
        "createdBy" => "readonly"
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
    function __construct($id = null, $idAssociates = null, $idReports = null, $year = null, $withDependentObjects = false)
    {

        if ($idAssociates !== null 
                && $idReports !== null 
                && $year !== null) {

            $crit = array(
                "idAssociates"  => $idAssociates,
                "idReports"     => $idReports, 
                "year"          => $year
            );
    
            $record = self::getFirstSqlElementFromCriteria("Locks", $crit);
            
            if ($record && $record['id']) {
                parent::__construct($record['id'], $withDependentObjects);
            } else {
                parent::__construct(null, $withDependentObjects);
                $this->idAssociates = $idAssociates;
                $this->idReports    = $idReports;
                $this->year         = $year;
            }
        } else {
            parent::__construct($id, $withDependentObjects);
        }
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
     * create a record in locks table      
     * 
     * @return boolean
     */
    function isLocked()
    {
        return (bool)($this->isLock == 1); 
    }

    /**
     * ==========================================================================
     * create a record in locks table      
     * 
     * @return boolean
     */
    function setLock()
    {
        $this->isLock = 1;

        return $this->save();
    }

    /**
     * ==========================================================================
     * remove the lock from locks table   
     * 
     * @return boolean
     */
    function removeLock()
    {
        if (!$this->id) return false; 
        $this->isLock = 0;

        return $this->save();
    }

    /**
     * ==========================================================================
     * Save object in database
     * 
     * @return resource boolean
     */
    public function save()
    {
/*
        if (trim($this->id) and !is_numeric($this->id)) {
            return (object) [
                "status" => "INVALID",
                "message" => "id '$this->id' is not numeric for class"
            ];
        }
        if ($this->id == '') $this->id = null;


        if (!$this->id && !$this->idUserProfile) $this->idUserProfile = UserProfile::getGuestId();
        if (!$this->id && !$this->idUserStatus) $this->idUserStatus = UserStatus::getRegStatusId();
  */      
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

}
