<?php

class Reports extends SqlElement
{

    public $id;
    public $name;
    public $description;
    
    public $_idAssociates;
    public $_year;
    public $_linesArray;

    /**
     * 
     * ==========================================================================
     * Constructor
     * 
     * @param $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null, $idAssociates = null, $year = null, $withDependentObjects = false)
    {
        parent::__construct($id, false);

        $this->_idAssociates = $idAssociates; 
        $this->_year        = $year; 


        if ($id && $idAssociates && $year & $withDependentObjects) {
            $this->_get();
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
     * check if given records according to the filter     
     * 
     * @return resource records
     */
    public function search($filter = array(), $withDependentObjects = true)
    {

        $result = $this->getSqlElementsFromCriteria($filter,  'name asc ', true,  $withDependentObjects);

        return $result;
    }

    
    /**
     * ==========================================================================
     * Get Report Data with Structure
     *
     * @return void
     */
    static function getReportsCalcPath($idReports)
    {

        $filter = array();
        $filter['isCalculate'] = 1;
        $filter[] = (object) ["key" => "id", "ope" => "in", "val" => $idReports, "fx" => "(SELECT idLinesDef FROM reportsitem WHERE idReports=<|fx.key|>)"];


        $linesDef = new LinesDef(); 
        $linesArray = $linesDef->getSqlElementsFromCriteria($filter);

        //reformat Array
        $LinesCodeArray = array();
        foreach ($linesArray as $key => $obj) $LinesCodeArray[$obj['code']] = $obj;
        
        $sortList = array();
        $followList = array();
        if (self::_findPrevParam($LinesCodeArray, $sortList, $followList)) {
            return $sortList; 
        } else {
            return false;
        };

    }

    private static function _findPrevParam($ParamList, &$sortParamList, &$followList = array()) {

        foreach ($ParamList as $code => $obj) {

            if (!array_key_exists($code, $sortParamList)) {
                if (array_key_exists($code, $followList)) return false;

                $dependance = self::_extractFuncParam($obj['calculationRule'], $ParamList);
                    
                if (count($dependance)>0) {
                    $followList[$code]=$obj;
                    if (!self::_findPrevParam($dependance, $sortParamList, $followList)) return false;
                }
                $sortParamList[$code]=$obj;
            }
        }
        return true;
    }

    private static function _extractFuncParam($formula, $restricList = array()) {
        $re = '/\[([[:word:]]+)\]/m';
        preg_match_all($re, $formula, $matches, PREG_SET_ORDER, 0);
        $funcDep = array();
        
        foreach ($matches as $match) {
            if (array_key_exists($match[1], $restricList)) $funcDep[$match[1]] = $restricList[$match[1]];
        } 

        return $funcDep;
    }

    /**
     * ==========================================================================
     * Get Report Data with Structure
     *
     * @return void
     */
    function getData()
    {
        if (!$this->id || !$this->_idAssociates || !$this->_year) return null;

        // --------------------------------------------------------------------------------
        // instantiate user object
        $objCells = new Cells();
        $records = $objCells->getCellsByYear($this->_year, $this->_idAssociates, -1, false,  false);

        $records = ($records) ? $records : array();

        // --------------------------------------------------------------------------------
        // Récupération des lignes de références
        $objLines = new LinesDef();

        $yearStart = $this->_year * 100 + 07;
        $linesDef = $objLines->get($yearStart, 0, $this->id);

        $linesDef = ($linesDef) ? $linesDef : array();

        foreach ($records as $key => $cell) {
            
            if (array_key_exists($cell['idLinesDef'], $linesDef)) {
                if (!array_key_exists("cells", $linesDef[$cell['idLinesDef']]))
                            $linesDef[$cell['idLinesDef']]['cells'] = array();
                
                $linesDef[$cell['idLinesDef']]['cells'][$cell['refLabel']] = $cell;
            }
        }
        $this->_linesArray = $linesDef;

        // --------------------------------------------------------------------------------
        // Get Lock Information
        $objLocks = new Locks(null, $this->_idAssociates, $this->id, $this->_year, true);


        $type_arr = $this->getArrayFromClass();
        if ($objLocks && $objLocks->isLocked()) {
            $type_arr['isLocked'] = true;    
            $type_arr['_locks'] = $objLocks;    
        } else {
            $type_arr['isLocked'] = false;    
        }
        $type_arr['_year'] = $this->_year;
        $type_arr['_idAssociates'] = $this->_idAssociates;
        $type_arr["_linesArray"] = $linesDef;

        return $type_arr;

    }


}
