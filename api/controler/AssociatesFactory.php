<?php

class AssociatesFactory extends Factory
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
     * updateCells()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function updateCells($id, $payload) {

        // Get object only possible to Admin or Owners
        $class = $this->getSqlClass();

        $obj = new $class($id);

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("PUT", $class, $obj)) 
            return self::setReturn(403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        $cells = isset($payload->cells) ? $payload->cells : array();

        $serviceResult = array();
        $sumArray = array();
        $keyArray = array();
        
        foreach ($cells as $itemId => $element) {

            $cell = new Cells();
            $refId = $itemId;
            $element->idAssociates = $id;
            $serviceResult[$refId] = array();
                
            //Check the Key Of  the record : The Query MUST have : "idAssociates || trig "/ "idLinesDef" / "refLabel" 
            // the "id" is  not mandatory
            if ((!property_exists($element, 'idLinesDef') && !property_exists($element, '_LinesCode'))
                    || !property_exists($element, 'refLabel') 
                    || !property_exists($element, 'year')) {
                
                $serviceResult[$refId]['_info'] = array(
                    "status" => "Bad Request",
                    "message" => "Enregistrement invalide");

            } else {
                $hastKey = cells::getLineHashKey($element, $keyArray);

                Sql::beginTransaction();
                
                // store record to db : check if insert, update or delete
                $result = $cell->store($element);
                
                if ($result->status <> "OK" && $result->status <> "NO_CHANGE") {
                    Sql::rollbackTransaction();

                    $serviceResult[$refId] = array();
                    $serviceResult[$refId]['_info'] = array(
                        "status" => $result->status,
                        "message" => $result->message,
                        "errors" => $result->data);
                    
                } else {
                    Sql::commitTransaction();

                    if (!array_key_exists($hastKey, $sumArray)) $sumArray[$hastKey] = $keyArray;

                    if ($result->action==="delete") {
                        $serviceResult[$refId]['id'] = "";
                        $serviceResult[$refId]['_info'] = array(
                            "status" => $result->status,
                            "action" => $result->action,
                            "message" => "Suppression effectuée");
                        
                    } else {
                        $_Cell = new Cells();

                        $serviceResult[$refId] = $_Cell->getArrayFromClass($result->id);
                        $serviceResult[$refId]['_info'] = array(
                            "status" => $result->status,
                            "action" => $result->action,
                            "message" => (($result->action=="update")? "Mise à jour effectuée" : "Création effectuée"));
                        
                    }
                    
                }
            }
        }

        //Calcul nessary Sum 
        foreach ($sumArray as $keyArray) {
            $linesDef = new LinesDef($keyArray['idLinesDef']); 
            if ($linesDef->hasAutoSum) {
                
                Sql::beginTransaction();

                $result = $linesDef->updateSum($keyArray['year'], $keyArray['idAssociates']);   

                if ($result->status == "OK" || $result->status == "NO_CHANGE") {
                    Sql::commitTransaction();

                    $_Cell = new Cells();
                    $serviceResult[$result->id] = $_Cell->getArrayFromClass($result->id);
                    $serviceResult[$result->id]['_info'] = array(
                        "status" => $result->status,
                        "action" => $result->action,
                        "message" => (($result->action=="update")? "Mise à jour effectuée" : "Création effectuée"));

                } else {
                    Sql::rollbackTransaction();
                }
            }
        }

        return self::setReturn(200, "OK", "Mise à jour réussi.", $serviceResult);
    }

    /** =========================================================================
     * rowCalc()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function rowCalc($id, $payload) {

        $year          = isset($payload->year) ?         (int)$payload->year : null;
        $idLinesDef    = isset($payload->idLinesDef) ?   (int)$payload->idLinesDef : null;

        if (!$year || !$idLinesDef) return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $object = new LinesDef($idLinesDef);

        if ($object->id != $idLinesDef) {
            return self::setReturn(400, "INVALID", "L'objet n'existe plus.");

        } else {
            $returnArray = $object->calculCells($id, $year);
            return self::setReturn(200, 'OK', "", $returnArray);
        }
    }

    /** =========================================================================
     * reportCalc()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function reportCalc($id, $payload) {

        $year          = isset($payload->year) ?        (int)$payload->year : null;
        $idReports     = isset($payload->idReports) ?   (int)$payload->idReports : null;
    
        if (!$year || !$id || !$idReports) return self::setReturn(400, "Bad Request", "Requête REST invalide");
    
        $LineCalcPath = Reports::getReportsCalcPath($idReports);
    
        $returnArray = array();
        foreach ($LineCalcPath as $LineObj) {
            $object = new LinesDef($LineObj['id']);
            $tempArray = $object->calculCells($id, $year);
            $returnArray[$object->code] = $tempArray->{$object->code};
        }
        return self::setReturn(200, 'OK', "", $returnArray);
    }

    /** =========================================================================
     * updateItem()
     * 
     * @param int $id
     * @param array $data
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function updateItem($id, $payload) {
        return parent::updateItem($id, $payload);
    }

    /** =========================================================================
     * deleteItem()
     * 
     * @param int $id
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      item : [] 
     */
    function deleteItem($id) {
        return parent::deleteItem($id);
    }

    /** =========================================================================
     * createItem()
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
    function createItem($payload) {
        return parent::createItem($payload);
    }

    /** =========================================================================
     * getGraphData()
     * 
     * @param int $id
     * @param array $param //needed idReports & year
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      data : [] 
     */
    function getGraphData($id, $param) {
        // Query : /api/associates/{idx}/cells?idReports={idx}&year={0000}
        if (!$param) $param=array();
            
        //$idGraph = array_key_exists("idGraph", $param)? (int)$param['idGraph'] : -1;
        $year      = array_key_exists("year", $param)? (int)$param['year'] : -1;

        if ($year == -1 || !is_numeric($id)) return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $obj = new Associates($id, false);
        //check if elements exist
        if ($obj->id != $id) return self::setReturn (404, "Not Found", 'Ressource non trouvée.');

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("GET", $this->getSqlClass(), $obj))
            return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        $refLabelArray = $obj->getRefLabel($year);
        $associatesObjet = $obj->getArrayFromClass();

        //[PROD_TOTAL] - ([ACHAT_FO]+[CHARGE_EXT]+[TAXES]+[CHARGE_PERSO]) 
        $dataLine = array(
            "PROD_TOTAL"    => array("coef" => 1, "color" => "0,255,0"),
            "ACHAT_FO"      => array("coef" => -1, "color" => "255,255,0"),
            "CHARGE_EXT"    => array("coef" => -1, "color" => "255,180,0"),
            "TAXES"         => array("coef" => -1, "color" => "255,100,0"),
            "CHARGE_PERSO"  => array("coef" => -1, "color" => "255,0,0"));

         /*   type: 'bar',
            label: 'Charges',
            backgroundColor: "rgba(255,99,132,0.2)",
            borderColor: "rgba(255,99,132,1)",
            borderWidth: 1,
            hoverBackgroundColor: "rgba(255,0,0,0.4)",
            hoverBorderColor: "rgba(255,0,0,1)",
            data: [-2, -3, -9, 0, -3, -4, -7]*/


        // --------------------------------------------------------------------------------
        // instantiate user object
        $objCells = new Cells();
        $records = $objCells->getCellsByYear($year, $id, -1, false,  false);

        // Récupération des lignes de références
        $objLines = new LinesDef();
        $yearStart = $year * 100 + 07;
        $linesDef = $objLines->get($yearStart , true);

        $cells = array();
        foreach ($records as $key => $cell) {
            $idLinesDef = $cell['idLinesDef'];
            $code = $linesDef[$idLinesDef]['code'];
            if (array_key_exists($code, $dataLine) && is_numeric($cell['refLabel'])) {
                if (!array_key_exists($code, $cells)) $cells[$code] = array();
                $cells[$code][$cell['refLabel']] = $cell;
            }
        }

        $label = array(); 
        $prevYear= 0;
        foreach ($refLabelArray  as $key => $value) {
            $labelYear = (int)substr($key, 0, 4);
            $labelMonth = (int)substr($key, -2);
            $date = new DateTime($value, new DateTimeZone("UTC"));
            if ($labelYear == $prevYear) {
                $label[] = $date->format('F');
            } else {
                $label[] = array($date->format('F'), $labelYear);
                $prevYear = $labelYear;
            }
        }
        $graphLineData = array();
        $graphData = array();
        foreach ($linesDef as $key => $line) {
            $code = $line['code'];
            if (array_key_exists($code, $dataLine)) {
                $serie = array(
                    "type"                  => 'bar',
                    "stack"                 => 'updown_bar',
                    "label"                 => ucwords(strtolower ($line['name'])),
                    "backgroundColor"       => "rgba(" . $dataLine[$line['code']]["color"] . ", 0.2)",
                    "borderColor"           => "rgba(" . $dataLine[$line['code']]["color"] . ", 1)",
                    "hoverBackgroundColor"  => "rgba(" . $dataLine[$line['code']]["color"] . ", 0.4)",
                    "hoverBorderColor"      => "rgba(" . $dataLine[$line['code']]["color"] . ", 1)",
                    "borderWidth"           => "1", 
                    "data"                  => array());
                
                foreach ($refLabelArray as $key => $value) {
                    if (array_key_exists($code, $cells) 
                            && array_key_exists($key, $cells[$code])) {
                        $serie['data'][] = round($cells[$code][$key]['value']*$dataLine[$code]['coef'],2);
                        
                        $graphLineData[$key] = round(((array_key_exists($key, $graphLineData))? $graphLineData[$key] : 0) + ($cells[$code][$key]['value']*$dataLine[$code]['coef']),2);
                    } else {
                        $serie['data'][] = 0;
                    }
                }
                $graphData[] = $serie;
            }
        }

        /*$serie1 = array(
            "type"                  => 'line',
            "stack"                 => 'sum_line',
            "label"                 => 'Equilibre mens.',
            "borderColor"           => "rgba(0, 0, 255, 1)",
            "borderWidth"           => "2", 
            "fill"                  => false,
            "data"                  => array());*/
        $serie2 = array(
            "type"                  => 'line',
            "stack"                 => 'treso_line',
            "label"                 => 'Trésorerie',
            "borderColor"           => "rgba(0, 255, 255, 1)",
            "borderWidth"           => "2", 
            "fill"                  => false,
            "data"                  => array());
        
        $prevValue = 0;
        foreach ($graphLineData as $key => $value) {
            $serie1['data'][] = round($value,2);
            $serie2['data'][] = round($prevValue + $value,2);
            $prevValue = $prevValue + $value;
        }
        //$graphData[] = $serie1;
        $graphData[] = $serie2;

        $data = array(
            "associates" => $associatesObjet,
            "labels" => $label,
            "datasets" => $graphData        );

        // set response code
        return self::setReturn(200, "OK","", $data);       
    }

    /** =========================================================================
     * getFullTimelines()
     * 
     * @param int $id
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      items : [] 
     */
    function getFullTimelines($id) {
        // Query : /api/associates/{idx}/fulltimelines

        $obj = new Associates($id, false);

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("GET", $this->getSqlClass(), $obj))
            return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        $records = $obj->getReportsTimeline(null);
        $return = array(
            "count"     => count($records),
            "items"     => $records);

        // set response code
        return self::setReturn(200, "OK","", $return);       
    }
   
    /** =========================================================================
     * getTimelines()
     * 
     * @param int $id
     * @param array $param //needed idReports & year
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      items : [] 
     */
    function getTimelines($id, $param) {
        // Query : /api/associates/{idx}/timelines?idReports={idx}
        if (!$param) $param=array();

        $idReports = array_key_exists("idReports", $param)? (int)$param['idReports'] : -1;

        if (!$idReports || $idReports == -1) return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $obj = new Associates($id, false);

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("GET", $this->getSqlClass(), $obj))
            return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        $records = $obj->getReportsTimeline($idReports);
        $return = array(
            "count"     => count($records),
            "items"     => $records);

        // set response code
        return self::setReturn(200, "OK","", $return);       
    }
    
    /** =========================================================================
     * getReportsCells()
     * 
     * @param int $id
     * @param array $param //needed idReports & year
     *
     * @return array
     *      code : 
     *      status : 
     *      message : 
     *      data : [] 
     */
    function getReportsCells($id, $param) {
        // Query : /api/associates/{idx}/cells?idReports={idx}&year={0000}
        if (!$param) $param=array();
            
        $idReports = array_key_exists("idReports", $param)? (int)$param['idReports'] : -1;
        $year      = array_key_exists("year", $param)? (int)$param['year'] : -1;

        if ($idReports == -1  || $year == -1) return self::setReturn(400, "Bad Request", "Requête REST invalide");

        $obj = new Associates($id, false);
        //check if elements exist
        if ($obj->id != $id) return self::setReturn (404, "Not Found", 'Ressource non trouvée.');

        // Check Profil for authorization to exec Action on the Class
        if (!checkAuthorization("GET", $this->getSqlClass(), $obj))
            return self::setReturn (403, "Forbidden", 'L\'utilisateur n\'est pas authorisé a effectuer cette action');

        // Récupération des cells
        $obj = new Reports($idReports, $id, $year);
        //check if elements exist
        if ($obj->id != $idReports) return self::setReturn (404, "Not Found", 'Ressource non trouvée.');

        $report = $obj->getData();

        // set response code
        return self::setReturn(200, "OK","", $report, "reports");       
    }

    /** =========================================================================
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

    /** =========================================================================
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
    function getItems($param) {
        return parent::getItems($param);
    }
    
}
