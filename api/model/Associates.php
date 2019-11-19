<?php

class Associates extends SqlElement
{

    // object properties
    public $id;
    public $firstname;
    public $name;
    public $trig;
    public $idUsers;
    public $email;
    public $contactNumber;
    public $address;
    public $idAssociateStatus;
    public $startDate;
    public $endDate;
    public $creationDateTime;
    public $createdBy;
    public $_createdByName;
    public $lastUpdateDateTime;
    public $lastUpdateBy;
    public $_lastUpdateByName;

    public $_Users;
    public $_AssociateStatus;

//    public $_noHistory;

    // Define the specific search fields
    private static $_searchFields = array(
        "id"                => "=",
        "name"              => "like",
        "trig"              => "=",
        "idUsers"           => "=",
        "idAssociateStatus" => "=",
        "idUserStatus"      => "=",
    );

    private static $_fieldsAttributes = array(
        "id" => "required,readonly",
        "firstname" => "required",
        "name" => "required",
        "trig" => "required, unique",
        "idUsers" => "",
        "email" => "",
        "contactNumber" => "",
        "address" => "",
        "idAssociateStatus" => "required",
        "startDate" => "", 
        "endDate" => "",
        "creationDateTime" => "readonly",
        "createdBy" => "readonly",
        "modificationDateTime" => "",
        "modifiedBy" => "",
    );


    /**
     * ==========================================================================
     * Constructor
     *
     * @param $id the id of the object in the database (null if not stored yet)
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
     * function to search associate ID from trig
     *
     * @param $Trig
     * 
     * @return long idAssociates 
     */
    public static function getIdFromTrigram($trig)
    {
        $filter = array('trig' => $trig);
        $result = self::getFirstSqlElementFromCriteria("Associate", $filter); 
        if (property_exists($result, "_singleElementNotFound")) {
            return !$result->_singleElementNotFound; 
        } else {
            return $result->id;
        }
    }

    /**
     * ==========================================================================
     * function to get RefLabel for the period
     *
     * @param string $year
     * 
     * @return array of refLabel
     */
    public function getRefLabel($year) {

        $startDate = new DateTime($year . "-07-01", new DateTimeZone("UTC"));
        
        $cellArray = array();
        for ($i=1; $i <= 12; $i++) { 
            $endDate = clone $startDate;
            $endDate->add(new DateInterval("P1M"));

            if (!($this->startDate < $startDate && $this->endDate >= $endDate)) {
                $cellArray[$startDate->format('Ym')] = $startDate->format('Y-m-d');
            }
            $startDate->add(new DateInterval("P1M"));
        }
        return $cellArray;
    }


    /**
     * ==========================================================================
     * function to search associate ID from trig
     *
     * @param $idReports
     * 
     * @return array of years 
     */
    public function getReportsTimeline($idReports)
    {

        if (!$this->id) return false;

        $query = "SELECT distinct(year) 
                  FROM cells 
                  WHERE idAssociates=:idAssociates ";
        
        $bindArray = array();
        $bindArray["idAssociates"] = $this->id; 

        if ($idReports) {
            $query .= "AND idLinesDef in (
                        select idLinesDef 
                        from reportsitem 
                        where idReports = :idReports)";

            $bindArray["idReports"] = $idReports; 
        }
        
        // execute request
        $result = Sql::query($query, $bindArray);

        if (sql::$lastQueryErrorCode) {
            errorLog(sql::$lastQueryErrorMessage);
            return false;
        }
        $objects = array();
        if (Sql::$lastQueryNbRows > 0) {
            $line = Sql::fetchLine($result);
            while ($line) {
                $objects[] = array(
                    "id"    => $line["year"],
                    "name"  => (string)$line["year"] . "/" . (string)((int)substr($line["year"],2,2) + 1)
                );

                $line = Sql::fetchLine($result);
            }
        }
      
        return $objects;
    }

    /**
     * ==========================================================================
     * function to search associate
     *
     * @param $filter
     * 
     * @return resource records 
     */
    public function search($filter = array(), $withDependentObjects = true)
    {

        return self::getSqlElementsFromCriteria($filter, 'name asc ', true, $withDependentObjects);
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


}
