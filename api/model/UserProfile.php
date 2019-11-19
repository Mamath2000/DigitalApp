<?php

global $folder;
/*require_once "{$folder}model/TypeMain.php";*/

class UserProfile extends TypeMain
{

    private const ADMIN_PROFILE_ID     = 10;
    private const GUEST_PROFILE_ID     = 9;

    private static $_databaseCriteria = array('scope' => 'UserProfile');

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
     * Return the Id of the UserProfile
     *
     * @return the databaseTableName
     */
    public static function adminUserId()
    {
        return self::ADMIN_PROFILE_ID;
    }

    /**
     * 
     * ========================================================================
     * Return the Id of the UserProfile : Guest
     *
     * @return the databaseTableName
     */
    public static function getGuestId()
    {
        return self::GUEST_PROFILE_ID;
    }    
}
