<?php
/* ============================================================================
 * History reflects all changes to any object.
 */
class History extends SqlElement
{

    // extends SqlElement, so has $id
    public $id;    // redefine $id to specify its visible place
    public $refType;
    public $refId;
    public $operation;
    public $colName;
    public $oldValue;
    public $newValue;
    public $operationDate;
    public $idUsers;

    public static $_storeDate;
    public static $_storeItem;
    public $_noHistory = true; // Will never save history for this object

    /**
     * ==========================================================================
     * Constructor
     *
     * @param  $id the id of the object in the database (null if not stored yet)
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
     * ===========================================================================
     * Store a new History trace (will call ->save)
     *
     * @param  $refType type of object updated
     * @param  $refId id of object updated
     * @param  $operation
     * @param  $colName name of column updated
     * @param  $oldValue old value of column (before update)
     * @param  $newValue new value of column (after update)
     * @return boolean true if save is OK, false either
     */
    public static function store($obj, $refType, $refId, $operation, $colName = null, $oldValue = null, $newValue = null)
    {
        if (!$refType or !$refId) {
            if ($obj and $obj->id) {
                $refType = get_class($obj);
                $refId = $obj->id;
            } else {
                return true;
            }
        }

        $hist = new History();
        // Attention : History fields are not to be escaped by Sql::str because $olValue and $newValue have already been escaped
        // So other fiels (names) must be manually "quoted"
        $hist->refType = $refType;
        $hist->refId = $refId;
        $hist->operation = $operation;
        $hist->colName = $colName;
        if ($colName and strtolower(substr($obj->getDataType($colName), -4)) == 'text') {
            $hist->oldValue = mb_substr($oldValue, 0, $hist->getDataLength('oldValue'), 'UTF-8');
            $hist->newValue = mb_substr($newValue, 0, $hist->getDataLength('newValue'), 'UTF-8');
        } else {
            $hist->oldValue = $oldValue;
            $hist->newValue = $newValue;
        }
        $hist->idUsers = getSessionValue("userId");
        $hist->operationDate = self::getOperationDate($obj);
        $returnValue = $hist->save();
        // For TestCaseRun : store history for TestSession

        return ("OK"===$returnValue->status);
    }

    /**
     * getOperationDate function
     *
     * @param [type] $obj
     * @return void
     */
    private static function getOperationDate($obj)
    {
        $objRef = get_class($obj) . '#' . $obj->id;
        if (!self::$_storeDate) {
            self::$_storeDate = date('Y-m-d H:i:s');
            self::$_storeItem = $objRef;
        }
        if ($objRef != self::$_storeItem and property_exists($obj, 'refType') and property_exists($obj, 'refId')) {
            $objRef = $obj->refType . '#' . $obj->refId;
        }
        if ($objRef != self::$_storeItem) {
            self::$_storeDate = date('Y-m-d H:i:s');
            self::$_storeItem = $objRef;
        }
        return self::$_storeDate;
    }
}
