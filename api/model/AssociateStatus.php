<?php

class AssociateStatus extends TypeMain
{

    private static $_databaseCriteria = array('scope' => 'AssociateStatus');

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
