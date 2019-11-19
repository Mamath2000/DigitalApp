<?php

class LinesDef extends SqlElement
{

    // object properties
    public $id;
    public $type; 
    public $code;
    public $name;
    public $description;
    public $sortOrder;
    public $isCalculate;
    public $calculationRule;
    public $hasAutoSum;
    public $isReadonly;
    public $isHidden;
    public $canNull;
    public $validityEndDate;

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
     * function to search LinesDef ID from code
     *
     * @param $code
     * 
     * @return long idLinesDef
     */
    public static function getIdFromCode($code)
    {
        $filter = array('code' => $code);
        $result = self::getFirstSqlElementFromCriteria("LinesDef", $filter); 
        if (property_exists($result, "_singleElementNotFound")) {
            return !$result->_singleElementNotFound; 
        } else {
            return $result->id;
        }
    }

    /**
     * ==========================================================================
     * Calcul cells
     *
     * @return object
     */
    private function _calculRowANNR($idAssociates = null, $year = null) {
        $cellObj = new Cells();
    
        $cellArray = $cellObj->getCodeFormatYearData($year, $idAssociates, $this->id);

        if (array_key_exists("ANNR", $cellArray) && array_key_exists($this->code, $cellArray['ANNR'])) {

            $annualVal = $cellArray["ANNR"][$this->code]->value;
            $Associate = new Associates($idAssociates);
            
            $startDate = new DateTime($year . "-07-01", new DateTimeZone("UTC"));
            $nbMonth   = 0;
            $cellArray = array();
            for ($i=1; $i <= 12; $i++) { 
                
                $endDate = clone $startDate;
                $endDate->add(new DateInterval("P1M"));

                if (!($Associate->startDate < $startDate && $Associate->endDate >= $endDate)) {
                    $nbMonth++;
                    $cellArray[$startDate->format('Ym')] = $startDate->format('Y-m-d');
                }

                $startDate->add(new DateInterval("P1M"));
            }
            $returnArray =  array();
            $errorArray  =  array();
            foreach ($cellArray as $dateRef=>$dateValue) {
                
                $type_arr = array();

                //Insert or Update the Value
                $type_arr["idLinesDef"]      = $this->id;
                $type_arr["idAssociates"]    = $idAssociates;
                $type_arr["refLabel"]       = $dateRef;
                $type_arr["year"]           = $year;
                $type_arr["dateValueDate"]  = $dateValue;
                $type_arr["dateRealDate"]   = $dateValue;
                $type_arr["value"]          = round($annualVal/$nbMonth, 2);
                $type_arr["source"]         = 'calculated';
                $type_arr["isReadonly"]     = $this->isReadonly;
                $type_arr["_infoCalc"] = array(
                    "status"    => "OK",
                    "message"   => "Calcul OK",
                    "rawValue"  => $annualVal/$nbMonth
                );

                //Update the Cells (Insert / Update / Delete)
                $cellObj= new Cells();
                $result = $cellObj->store($type_arr);    
                
                $type_arr["_info"] = array();
                $type_arr["_info"]['status'] = $result->status;
                $type_arr["_info"]['message'] = $result->message;

                if (property_exists($result, "action")) {
                    $type_arr["_info"]['action'] = $result->action;
                    $returnArray[$dateRef] = $type_arr;
                
                } else {
                    $type_arr["_info"]['data'] = $result->data;
                    $errorArray[$dateRef] = $type_arr;
                }
            }

            return (object)[
            "status"        => "OK",
            "message"       => "",
            $this->code     => $returnArray, 
            "error"         => $errorArray];

        }
        
    }

    /**
     * ==========================================================================
     * Calcul calculRowFormula
     *
     * @return object
     */
    private function _calculRowFormula($idAssociates = null, $year = null) {

        $cellObj = new Cells();
        $cellArray = $cellObj->getCodeFormatYearData($year, $idAssociates);

        $returnArray = array();
        $errorArray = array();
        $idx=0; 
        
        foreach ($cellArray as $month=>$cells) {
            $type_arr   = array();
            $paramArray = array();
            foreach ($cells as $code=>$Object) {
                $paramArray[$code]=$Object['value'];
            }
            
            $validityEndDate =  date_format(date_create(($year) . '-07-01'), 'Y-m-d');

            $sqlWhereArray = array();
            $sqlWhereArray['validityEndDate'] = (object) [
                    "ope" => ">=", 
                    "val" => $validityEndDate, 
                    "fx" => "DATE_FORMAT(<|fx.key|>, '%Y%m')"];

            $result = self::getSqlElementsFromCriteria($sqlWhereArray);

            foreach ($result as $key => $value) {
                if (!$value['canNull'] && !array_key_exists($value['code'], $paramArray)) {
                    $paramArray[$value['code']] = 0;
                }
            }
            
            $calTools = new fxCalculate($this->calculationRule, $paramArray);
            
            if ($month=='TOTAL') {
                $dateValue =  date_format(date_create(($year+1) . '-06-30'), 'Y-m-d');
            } else {
                $dateValue = date_format(date_create(substr($month,0,4) . '-' . substr($month,4,2) . '-01'), 'Y-m-d');
            }
            if ($calTools->calculIsOk()) { 
                
                //Insert or Update the Value
                $type_arr["idLinesDef"]     = $this->id;
                $type_arr["idAssociates"]   = $idAssociates;
                $type_arr["refLabel"]       = $month;
                $type_arr["year"]           = $year;
                $type_arr["dateValueDate"]  = $dateValue;
                $type_arr["dateRealDate"]   = $dateValue;
                $type_arr["value"]          = $calTools->getResult();
                $type_arr["source"]         = 'calculated';
                $type_arr["isReadonly"]     = $this->isReadonly;
                $type_arr["_infoCalc"]      = array(
                    "status"    => "OK",
                    "message"   => "Calcul OK",
                    "rawValue"  => $calTools->getResult(true)
                );

            } else {
                $type_arr["idLinesDef"]     = $this->id;
                $type_arr["idAssociates"]   = $idAssociates;
                $type_arr["refLabel"]       = $month;
                $type_arr["year"]           = $year;
                $type_arr["dateValueDate"]  = $dateValue;
                $type_arr["value"]          = '';
                $type_arr["_infoCalc"] = array(
                    "status"    => "KO",
                    "message"   => $calTools->getLastErrorMessage()
                );

            }

            //Update the Cells (Insert / Update / Delete)
            $cellObj= new Cells();
            $result = $cellObj->store($type_arr);    
            
            $type_arr["_info"] = array();
            $type_arr["_info"]['status'] = $result->status;
            $type_arr["_info"]['message'] = $result->message;
            if (property_exists($result, "action")) {
                $type_arr["_info"]['action'] = $result->action;
                $returnArray[$month] = $type_arr;

            } else {
                $type_arr["_info"]['data'] = $result->data;
                $errorArray[$month] = $type_arr;
            }

            $idx++;
        }

        return (object)[
            "status"        => "OK",
            "message"       => "",
            $this->code     => $returnArray, 
            "error"         => $errorArray
        ];
    }


    /**
     * ==========================================================================
     * Calcul cells
     *
     * @return object
     */
    function calculCells($idAssociates = null, $year = null) {
        if (!$this->id || !$this->isCalculate || !$this->calculationRule) return false;

        if ($this->calculationRule== 'ANNR') {
            return $this->_calculRowANNR($idAssociates, $year);

        } else {
            return $this->_calculRowFormula($idAssociates, $year);

        }
    }

    /**
     * ==========================================================================
     * updateSum
     *
     * @param year
     *
     * @param idAssociates
     *
     * @return array
     */
    public function updateSum($year, $idAssociates)
    {

        if (!$this->hasAutoSum || !$this->id || !is_numeric($year) || !$idAssociates) return false;

        $cellObj = new Cells();
        $cellRecords = $cellObj->getCellsByYear($year, $idAssociates, $this->id);
        
        $id = null;
        $Sum = (float) 0.0;
        foreach ($cellRecords as $value) {
            if ($value['refLabel'] != "TOTAL") {
                $Sum += $value['value'];
            } else {
                $id = $value['id'];
            }
        }
        
        $dateValue = new DateTime();
        $dateValue->setDate($year + 1, 6, 30);

        $sumCell = new Cells($id);
        $sumCell->idLinesDef     = (int)$this->id;
        $sumCell->idAssociates   = (int)$idAssociates;
        $sumCell->refLabel       = "TOTAL";
        $sumCell->year           = (int)$year;
        $sumCell->value          = $Sum;
        $sumCell->source         = "calculated";
        $sumCell->dateValueDate  = $dateValue->format('Y-m-d');
        $sumCell->dateRealDate   = $dateValue->format('Y-m-d');
        $sumCell->isReadonly     = 1;

        return $sumCell->save();
    }

    /**
     * ==========================================================================
     * read LinesDef
     *
     * @param validityEndDate
     *
     * @param showHidden
     * 
     * @param idReports
     * 
     * @return void
     */
    public function get($validityEndDate = null, $showHidden = 0, $idReports = null, $indexByCode = false) {
        

        $sqlWhereArray = array();
        if ($validityEndDate) {
            $sqlWhereArray['validityEndDate'] = (object) ["ope" => ">", "val" => $validityEndDate, "fx" => "DATE_FORMAT(<|fx.key|>, '%Y%m')"];
        }
        if ($showHidden == 0) {
            $sqlWhereArray['isHidden'] = $showHidden;
        }

        if ($idReports) {
            $sqlWhereArray[] = (object) ["key" => "id", "ope" => "in", "val" => $idReports, "fx" => "(SELECT idLinesDef FROM reportsitem WHERE idReports=<|fx.key|>)"];
            $sqlOrderBy = "(SELECT sortOrder FROM reportsitem WHERE idLinesDef=" . self::getStaticDatabaseTableName() . ".id) ASC";
        } else {
            $sqlOrderBy = null;
        }

        $result = self::getSqlElementsFromCriteria($sqlWhereArray, $sqlOrderBy, true);

        if ($result) {
            // reformat the result => Filter virtual fields
            $reformatResult = array();
            foreach ($result as $line) {

                $LinesDef = array();
                foreach ($line as $key => $value) {
                    if (substr($key, 0, 1) !== "_") $LinesDef[$key] = $value;
                }
                $reformatResult[$line[$indexByCode?'code':'id']] = $LinesDef;
            }

            return $reformatResult;
        } else {
            return false;
        }
    }

    /**
     * ==========================================================================
     * function to search 
     *
     * @param $filter
     * 
     * @return resource records 
     */
    public function search($filter = array(), $withDependentObjects = true)
    {

        $result = $this->getSqlElementsFromCriteria(
            $filter, null, false, $withDependentObjects);

        return $result;
    }

}
