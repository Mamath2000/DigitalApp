<?php


class Cells extends SqlElement
{

    public $id;
    public $idLinesDef;
    public $_LinesDef;
    public $idAssociates;
    public $_Associates;
    public $value;
    public $source;
    public $refLabel; //ex : 201804 201901 SUM TOTAL
    public $year;
    public $_LinesCode;
    public $dateValueDate;
    public $dateRealDate;
    public $isReadonly;
    public $isHidden;
    public $creationDateTime;
    public $createdBy;
    public $_createdByName;
    public $lastUpdateDateTime;
    public $lastUpdateBy;
    public $_lastUpdateByName;
    //    public $_noHistory;

    private static $_fieldsAttributes = array(
        "id" => "required,readonly",
        "idLinesDef" => "required,readonly",
        "idAssociates" => "required,readonly",
        "refLabel" => "required,readonly",
        "value" => "required",
        "source" => "required",
        "year" => "required",
        "dateValueDate" => "required,readonly",
        "dateRealDate" => "",
        "isReadonly" => "",
        "creationDateTime" => "",
        "createdBy" => "",
        "lastUpdateDateTime" => "",
        "lastUpdateBy" => ""
    );


    /**
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
     * ==========================================================================
     * getHashKey
     *
     * @return string hash
     */
    public static function getLineHashKey($dataObject, &$keyArray)
    {
        // convert $data too Array if needed
        if (is_object($dataObject)) {
            $dataArray = get_object_vars($dataObject);
        } else {
            $dataArray = $dataObject;
        }
        
        // Add year
        $year = $dataArray['year'];

        // Add id associate from TRIG
        if (!array_key_exists('idAssociates', $dataArray) && array_key_exists('trig', $dataArray)) {
            $idAssociates = Associates::getIdFromTrigram($dataArray['trig']);
        } else {
            $idAssociates = $dataArray['idAssociates'];
        }

        // Add id Line Def from line Code 
        if (!array_key_exists('idLinesDef', $dataArray) && array_key_exists('_LinesCode', $dataArray)) {
            $idLinesDef = LinesDef::getIdFromCode($dataArray['_LinesCode']);
        } else {
            $idLinesDef = $dataArray['idLinesDef'];
        }
        $keyArray = array("idLinesDef" => (int)$idLinesDef, 'year' => (int)$year, 'idAssociates' => (int)$idAssociates);

        return md5(serialize($keyArray));;
    }

    /**
     * ==========================================================================
     * getYearData
     *
     * @return void
     */
    public function cellExist($idAssociates, $refLabel, $idLinesDef, $year)
    {
        $sqlWhereArray = array();
        $sqlWhereArray['idAssociates'] = $idAssociates;
        $sqlWhereArray['idLinesDef'] = $idLinesDef;
        $sqlWhereArray['refLabel'] = $refLabel;
        $sqlWhereArray['year'] = $year;

        return $this->getSqlElementsFromCriteria($sqlWhereArray);
    }



    /**
     * =========================================================================
     * Give public visibility to the saveSqlElement action
     *
     * @param array
     *          force to avoid controls and force saving even if controls are false
     * @return message including definition of html hiddenfields to be used
     */
    public function store($data)
    {

        // convert $data too Array if needed
        if (is_object($data)) {
            $dataArray = get_object_vars($data);
        } else {
            $dataArray = $data;
        }

        // Add id associate from TRIG
        if (!array_key_exists('idAssociates', $dataArray) && array_key_exists('trig', $dataArray)) 
            $dataArray['idAssociates'] = Associates::getIdFromTrigram($dataArray['trig']);

        // Add id Line Def from line Code 
        if (!array_key_exists('idLinesDef', $dataArray) && array_key_exists('_LinesCode', $dataArray)) 
            $dataArray['idLinesDef'] = LinesDef::getIdFromCode($dataArray['_LinesCode']);

        $id = null;
        if (array_key_exists("id", $dataArray) && is_numeric($dataArray['id'])) {
            $id = $dataArray['id'];
        } else if (
            array_key_exists("idAssociates", $dataArray) &&
            array_key_exists("refLabel", $dataArray) &&
            array_key_exists("idLinesDef", $dataArray) &&
            array_key_exists("year", $dataArray)
        ) {

            $result = self::cellExist(
                $dataArray['idAssociates'],
                $dataArray['refLabel'],
                $dataArray['idLinesDef'],
                $dataArray['year']
            );

            if ((sizeof($result) > 0) && ($result[0]['id'])) $id = $result[0]['id'];
        }

        if ($id && is_numeric($id)) {
            self::__construct($id);
        }

        foreach ($dataArray as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === "source") {
                    if ($this->source==='manual' && $value !== 'manual' ) { //manual change are priorize
                        self::$_fieldsAttributes['source'] .= ",readonly";
                    }
                }
                $this->$key = $value;
            }
        }
        return $this->save();
    }


    /**
     * =========================================================================
     * Give public visibility to the saveSqlElement action
     *
     * @param
     *          force to avoid controls and force saving even if controls are false
     * @return message including definition of html hiddenfields to be used
     */
    public function save()
    {

        if ($this->value === "") {

            if ($this->id) {    // suppression
                return parent::delete();

            } else {            //no action required
                return (object) [
                    "status" => "NO_ACTION",
                    "message" => "Aucune action effectuée",
                    "data" =>  array()
                ];

            }
        }
        return parent::save();
    }

    /**
     * ==========================================================================
     * _getYearData
     *
     * @return void
     */
    protected function _getYearData($year, 
                $idAssociates, 
                $idLinesDef = -1, 
                $showHidden = false, 
                $withDependentObjects = false)
    {

        if (!$year || !is_numeric($year) || !$idAssociates || !is_numeric($idAssociates)) {
            return array("status" => "INVALID", "message" => "Bad Request");
        }

        $yearStart = $year * 100 + 07;
        $yearEnd = ($year + 1) * 100 + 06;

        $sqlWhereArray = array();
        $sqlWhereArray['idAssociates'] = $idAssociates;
        $sqlWhereArray[] = (object) ["key" => "dateValueDate", "ope" => ">=", "val" => $yearStart, "fx" => "DATE_FORMAT(<|fx.key|>, '%Y%m')"];
        $sqlWhereArray[] = (object) ["key" => "dateValueDate", "ope" => "<=", "val" => $yearEnd, "fx" => "DATE_FORMAT(<|fx.key|>, '%Y%m')"];

        if ($idLinesDef != -1 && $idLinesDef !== null) {
            $sqlWhereArray['idLinesDef'] = $idLinesDef;
        }

        if (!$showHidden) {
            $sqlWhereArray['isHidden'] = 0;
        }    

        $sqlOrder = self::getDatabaseTableName() . ".dateValueDate ASC";

        $result = $this->getSqlElementsFromCriteria($sqlWhereArray, $sqlOrder, false, $withDependentObjects);

        return $result;
    }

    /**
     * ==========================================================================
     * getCellsByYear
     *
     * @return array
     */
    public function getCellsByYear(
                $year, 
                $idAssociates, 
                $idLinesDef = -1, 
                $showHidden = false, 
                $withDependentObjects = false)
    {
        return $this->_getYearData($year, $idAssociates, $idLinesDef, $showHidden, $withDependentObjects);
    }

    /**
     * ==========================================================================
     * getCodeFormatYearData
     *
     * @return array
     */
    public function getCodeFormatYearData($year, $idAssociates, $idLinesDef = -1)
    {

        $result = $this->_getYearData($year, $idAssociates, $idLinesDef,  true);

        // reformat the result => Code first...
        $reformatResult = array();
        foreach ($result as $line) {

            $LinesDef = new linesDef($line['idLinesDef']);
            $lineCode = $LinesDef->code;
            unset($LinesDef);

            if (!array_key_exists($line['refLabel'], $reformatResult)) $reformatResult[$line['refLabel']] = array();

            $reformatResult[$line['refLabel']][$lineCode] = $line;
        }
        return $reformatResult;
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
