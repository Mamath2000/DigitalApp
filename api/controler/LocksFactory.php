<?php

class LocksFactory extends Factory
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
     * updateLocks()
     * 
     * @param array $payload
     *
     * @return array
     */
    function updateLocks($payload) {

        // get Data from content-- pas de parametres
        $idAssociates   = isset($payload->idAssociates) ?   (int)$payload->idAssociates : null;
        $idReports      = isset($payload->idReports) ?      (int)$payload->idReports : null;
        $year           = isset($payload->year) ?           (int)$payload->year : null;
        $isLock         = isset($payload->isLock) ?         (int)$payload->isLock : -1;

        if (!$idReports || !$idAssociates || !$year || !($isLock == 1 || $isLock == 0)) 
                return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $obj = new Locks(null, $idAssociates, $idReports, $year);

        if (!$obj->id && $isLock == 0)  {
            return self::setReturn(202, "NO_CHANGE", "Le lock n'existe pas.");

        } else {
            if ($obj->id && $isLock == 1)  {
                Sql::beginTransaction();
                $result = $obj->setLock();

            } else if ($obj->id && $isLock == 0)  {
                Sql::beginTransaction();
                $result = $obj->removeLock();

            } else if (!$obj->id && $isLock == 1)  {
                Sql::beginTransaction();
                $result = $obj->setLock();
            }

            if ($result->status === "OK") {
                sql::commitTransaction();
                return self::setReturn(200, $result->status, $result->message);

            } else {
                sql::rollbackTransaction();
                return self::setReturn(202, $result->status, $result->message, $result->data, "errors");

            }
        }
    }

    /** =========================================================================
     * createLocks()
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
    function createLocks($payload) {

        // get Data from content-- pas de parametres
        $idAssociates   = isset($payload->idAssociates) ? (int)$payload->idAssociates : null;
        $idReports      = isset($payload->idReports) ? (int)$payload->idReports : null;
        $year           = isset($payload->year) ? (int)$payload->year : null;

        if (!$idReports || !$idAssociates || !$year) return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $obj = new Locks(null, $idAssociates, $idReports, $year);

        if ($obj->isLocked()) {
            return self::setReturn(202, "NO_CHANGE", "Le lock existe déjà.");

        } else {
            Sql::beginTransaction();
            $result = $obj->setLock();
        
            if ($result->status === "OK") {
                sql::commitTransaction();
                return self::setReturn(201, $result->status, $result->message, $result->action, "action");

            } else {
                sql::rollbackTransaction();
                return self::setReturn(202, $result->status, $result->message, $result->data, "errors");

            }
        }
    }
}
