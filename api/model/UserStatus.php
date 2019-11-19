<?php

class UserStatus extends TypeMain
{

    private const ACTIF_STATUS = 1;
    private const REG_STATUS = 3;

    private static $_databaseCriteria = array('scope' => 'UserStatus');

    /**
     * 
     * ==========================================================================
     * Constructor
     * 
     * @param $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null)
    {
        parent::__construct($id);
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
     * 
     * ========================================================================
     * Return the specific database criteria
     * 
     * @return the databaseTableName
     */
    protected function getStaticDatabaseCriteria()
    {
        return self::$_databaseCriteria;
    }

    /**
     * 
     * ========================================================================
     * Return the Id of the Actif UserStatus
     * 
     * @return the databaseTableName
     */
    public static function actifUserId()
    {
        return self::ACTIF_STATUS;
    }

    /**
     * 
     * ========================================================================
     * Return the Id of the Register UserStatus
     * 
     * @return the databaseTableName
     */
    public static function getRegStatusId()
    {
        return self::REG_STATUS;
    }

}
