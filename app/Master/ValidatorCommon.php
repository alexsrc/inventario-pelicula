<?php

namespace App\Master;

class ValidatorCommon
{
//type validations
    const EMPTY = 'empty';
    const STRING = 'string';
    const RANGE = 'range';
    const INT = 'int';
    const FLOAT = 'float';
    const DATE = 'date';
    const NUMERIC_RANGE = 'numericRange';
    const ARRAY = 'array';

    const ERRORMESSAGES = [
        "EMPTY"=>"Error empty",
        "STRING"=>"Error string",
        "INT"=>"Error integer",
        "FLOAT"=>"Error floating",
        "DATE"=>"Error date",
        "NUMERIC_RANGE"=>"Error numeric range",
        "ARRAY"=>"Error array",
        "REQUIRED"=>"Error required",
        "RANGE"=>"Error range",
    ];


    private $errorMessage=[];
    private $totalerrors=0;

    private static $validatorCommonSingleton = null;

    public static function getValidatorCommonSingleton()
    {
        if (self::$validatorCommonSingleton === null) {
            self::$validatorCommonSingleton = new self();
        }
        return self::$validatorCommonSingleton;
    }

    private function __contruct()
    {
        // code..
    }


    /**
     * @param array $response
     * @param $param
     * @param string $name
     * @param string $validateType
     * @param bool $required
     * @param int[] $range
     */
    public function validateParamFormat(
        $param,
        string $name,
        array  $validateTypes,
               $required = true,
               $range = [0, 0]
    )
    {
        $firstValidation=false;
        foreach ($validateTypes as $validateType){
            if (isset($param)) {
                switch ($validateType) {
                    case self::EMPTY:
                        $validateParam = $this->ValidateVacio($param);
                        if (!$validateParam) {
                            $this->setError("EMPTY");
                            $firstValidation=true;
                        }
                        break;
                    case self::RANGE:
                        $validateParam = $this->ValidateStringSize($param, $range[0], $range[1]);
                        if (!$validateParam) {
                            $this->setError("RANGE");
                            $firstValidation=true;
                        }
                        break;
                    case self::STRING:
                        if (!is_string($param)) {
                            $this->setError("STRING");
                            $firstValidation=true;
                        }
                        break;
                    case self::INT:
                        if (!is_int($param)) {
                            $this->setError("INT");
                            $firstValidation=true;
                        }
                        break;
                    case self::FLOAT:
                        if (!is_float($param) || !is_numeric($param)) {
                            $this->setError("FLOAT");
                            $firstValidation=true;
                        }
                        break;
                    case self::NUMERIC_RANGE:
                        if (!$this->validateNumericRange($param, $range[0], $range[1])) {
                            $this->setError("NUMERIC_RANGE");
                            $firstValidation=true;
                        }
                        break;
                    case self::DATE:
                        if (!$this->validateYear($param)) {
                            $this->setError("DATE");
                            $firstValidation=true;
                        }
                        break;
                    case self::ARRAY:
                        if (!is_array($param)) {
                            $this->setError("ARRAY");
                            $firstValidation=true;
                        }
                        break;
                    default:
                        break;
                }

                if($firstValidation){
                    break;
                }
            }
            else {
                if ($required) {
                    $this->setError("REQUIRED");

                }
            }
        }
    }


    public function ValidateVacio($valor):bool
    {
        if (is_array($valor)){
            return count($valor) > 0;
        }
        if (is_object($valor)) {
            return $valor != null;
        }
        return strlen(trim($valor)) > 0;
    }

    public function ValidateStringSize($value,$lowerLimit,$upperLimit):bool
    {
        if (strlen($value) >= $lowerLimit and strlen($value) <= $upperLimit) {
            return true;
        } else {
            return false;
        }
    }

    private function validateNumericRange(float $value, float $min, float $max):bool
    {
        return ($min <= $value) && ($value <= $max);
    }

    public function validateYear($year):bool
    {

        if ($year != "") {
                try{
                    return checkdate("01", "01", $year);
                }catch(\Exception $e){
                    return false;
                }
        } else {
            return false;
        }

    }

    public function setError($name):void
    {
        $this->errorMessage[] = array('error' => self::ERRORMESSAGES[$name]);
        $this->totalerrors++;
    }

    public function getError():array
    {
        return $this->errorMessage;
    }

    public function getEMPTY():string
    {
        return self::EMPTY;
    }

    public function getSTRING():string
    {
        return self::STRING;
    }

    public function getRANGE():string
    {
        return self::RANGE;
    }

    public function getINT():string
    {
        return self::INT;
    }

    public function getFLOAT():string
    {
        return self::FLOAT;
    }

    public function getDATE():string
    {
        return self::DATE;
    }

    public function getNUMERIC_RANGE():string
    {
        return self::NUMERIC_RANGE;
    }

    public function getARRAY():string
    {
        return self::ARRAY;
    }
}
