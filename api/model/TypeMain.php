<?php
class TypeMain extends SqlElement
{

    // object properties
    public $id;
    public $scope;
    public $name;
    public $sortOrder;
    public $color;
    public $description;

    private static $_databaseTableName = 'type';

    private static $_databaseCriteria = array();

    private static $_fieldsAttributes = array(
        "id" => "required",
        "scope" => "required",
        "name" => "required",
        "sortOrder" => "",
        "color" => "",
        "description" => ""
    );

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
     * Constructor
     *
     * @param  $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null)
    {
        parent::__construct($id, true);
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
     * Get records
     * 
     * @return resource records
     */
    public function get($withDependentObjects = true)
    {

        return self::getSqlElementsFromCriteria(array(), 'sortOrder asc ', false, $withDependentObjects);
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

        $result = $this->getSqlElementsFromCriteria(
            $filter, 'sortOrder asc ', false, $withDependentObjects);

        return $result;
    }

    /**
     * ==========================================================================
     * Return the generic databaseTableName
     *
     * @return the layout
     */
    protected function getStaticDatabaseTableName()
    {
        return self::$_databaseTableName;
    }

    /**
     * ========================================================================
     * Return the specific database criteria
     *
     * @return the databaseTableName
     */
    protected function getStaticDatabaseCriteria()
    {
        return self::$_databaseCriteria;
    }
}
