<?php

use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;

class fxCalculate
{

    // object properties
    private $_formula;
    private $_paramArray;
    private $_paramArrayReverse;
    private $_errorMessage;
    Private $_result;
    private $_rawValue;

    public $formula;
    public $paramValuesArray;
    

    /**
     * ==========================================================================
     * Constructor
     *
     * @param  $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($formula = null, $paramArray = null)
    {
        $this->_formula = $formula;

        $re = '/\[([[:word:]]+)\]/m';
        
        preg_match_all($re, $this->_formula, $matches, PREG_SET_ORDER, 0);


        $charNum = ord("a");
        $this->_paramArray = array();
        $this->_paramArrayReverse = array();
        $this->paramValuesArray = array();
        $search=array();
        $replace=array();
        foreach ($matches as $param) {
            $this->_paramArrayReverse[chr($charNum)]=$param[1];
            $this->_paramArray[$param[1]]=chr($charNum);
            $this->paramValuesArray[$param[1]]="";
            $search[]=$param[0];
            $replace[]=" ".chr($charNum)." ";
            $charNum++;
            if ('e'==chr($charNum)) $charNum++;
        }

        $this->formula = str_replace($search, $replace, $this->_formula);

        if ($paramArray) {
            $this->paramValuesArray = $paramArray;
            $this->calculate();
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
    }
    

    /**
     * ==========================================================================
     * Read products by search term
     *
     * @return boolean
     */
    public function calculIsOk()
    {
        return (is_numeric($this->_result) && trim($this->_errorMessage)==="");
    }
    
    /**
     * ==========================================================================
     * Read products by search term
     *
     * @return string
     */
    public function getFormula()
    {

        return $this->formula;
    }

    /**
     * ==========================================================================
     * Return an array with param list
     *
     * @return array
     */
    public function getParamList()
    {

        return $this->_paramArray;
    }

    /**
     * ==========================================================================
     * Return an array with reverse param list
     *
     * @return array
     */
    public function getParamListReverse()
    {

        return $this->_paramArrayReverse;
    }

    /**
     * ==========================================================================
     * Return the last error message
     *
     * @return string
     */
    public function getLastErrorMessage()
    {

        return $this->_errorMessage;
    }

    /**
     * ==========================================================================
     * Return the result of the calcul
     *
     * @return string
     */
    public function getResult($__raw = false)
    {

        return (trim($this->_errorMessage)==='')? (($__raw)? $this->_rawValue : $this->_result) : null;
    }

    /**
     * ==========================================================================
     * Return Calcul the equation
     *
     * @return boolean
     */
    public function calculate($paramArray = null, $roundDigit = 2)
    {

        if (!$paramArray) $paramArray = $this->paramValuesArray;

        $newParamArray = array();
        foreach ($this->_paramArray as $key=>$variable) {
            if (array_key_exists($key, $paramArray)) {
                $newParamArray[$variable] = $paramArray[$key];
            }
        }

        $parser = new StdMathParser();
        
        // Generate an abstract syntax tree
        $AST = $parser->parse($this->formula);
        
        // Do something with the AST, e.g. evaluate the expression:
        $evaluator = new Evaluator( );
        
        $evaluator->setVariables($newParamArray);
        try {
            $this->_rawValue = $AST->accept($evaluator);
            $this->_result = round($this->_rawValue, $roundDigit);
            $this->_errorMessage = "";

        } catch (\Throwable $th) {

            $this->_result = null;
            $this->_errorMessage = "Error on calculation step : " . $th->getMessage();
            return false;
        }
       
        return true;
    }


}
