<?php

namespace App\Master;

class Master
{
    const fields=[
        "name" => "nombre",
        "premiere_year" => "ano_estreno",
        "category" => "categoria",
        "quantity" => "unidad",
        "cost" => "precio",
        "id" => "id"
    ];

    const codeBadRequest = 400;
    private static $masterSingleton = null;

    public static function getMasterSingleton()
    {
        if (self::$masterSingleton === null) {
            self::$masterSingleton = new self();
        }
        return self::$masterSingleton;
    }

    private function __contruct()
    {
        // code..
    }

    public function translateFieldsMovie($field,$returnLanguageTranslate="spanish"){
        $fields = self::fields;

        switch ($returnLanguageTranslate){
            case $returnLanguageTranslate=="spanish":
                if(isset($fields[$field])){
                    return $fields[$field];
                }
                throw new \Exception("The field is incorrect",self::codeBadRequest);
            case $returnLanguageTranslate=="english":
                foreach ($fields as $key => $value){
                    if($value === $field){
                        return $key;
                    }
                }
                throw new \Exception("The field is incorrect",self::codeBadRequest);
            default:
                throw new \Exception("The field is incorrect",self::codeBadRequest);
        }
    }

}
