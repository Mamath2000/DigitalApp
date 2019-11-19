<?php

class ReportsFactory extends Factory
{
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
     * =========================================================================
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

    /**
     * =========================================================================
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
    function getItems($param)
    {
        return parent::getItems($param);
    }
    
}
