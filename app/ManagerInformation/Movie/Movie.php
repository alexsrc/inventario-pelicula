<?php

namespace App\ManagerInformation\Movie;

use App\Models\Categoria;
use App\Models\Estado;
use App\Models\Inventario;
use App\Models\Pelicula;

class Movie {

    private $fields=[
        "name" => "nombre",
        "premiere_year" => "ano_estreno",
        "category" => "categoria",
        "quantity" => "unidad",
        "cost" => "precio"
    ];

    private $codeBadRequest=400;

    public function create(string $name,  int $category, string $premiere_year, float $cost, int $quantity):array{
        $category = Categoria::find($category);
        if(!$category){
            throw new \Exception("No found the category with the ID {$category}",$this->codeBadRequest);
        }

        $existMovie = Pelicula::where("nombre",$name)->where("id_categoria_fk")->get();
        if(count($existMovie)>0){
            throw new \Exception("{$name} movie was already created",$this->codeBadRequest);
        }

        $movie = new Pelicula();
        $movie->nombre = $name;
        $movie->id_categoria_fk = $category->id;
        $movie->ano_estreno = $premiere_year;
        $movie->id_estado_fk = $this->idStatusActive();
        $movie->save();

        $stockTaking = new Inventario();
        $stockTaking->precio = $cost;
        $stockTaking->unidad = $quantity;
        $stockTaking->fecha_creacion = new \DateTime("now");
        $stockTaking->id_pelicula_fk = $movie->id;

        $stockTaking->save();

        return $this->response($movie,$category,$stockTaking);
    }

    /**
     * @throws \Exception
     */
    public function edit(int $id, array $properties):array{
        $movie = Pelicula::findOrFail($id);

        if(!$movie){
            throw new \Exception("No found the movie with the ID {$id}",$this->codeBadRequest);
        }

        return $this->update($movie, $properties);
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id){
        $movie = Pelicula::where("id",$id)->where("id_estado_fk",$this->idStatusActive())->first();

        if(!$movie){
            throw new \Exception("No found the movie with the ID {$id}",$this->codeBadRequest);
        }

        $state = Estado::where("nombre","eliminado")->first();
        if(!$state){
            throw new \Exception("The state is incorrect",$this->codeBadRequest);
        }

        $movie->id_estado_fk = $state->id;
        $movie->save();

        return ["message" => "Ok, the movie deleted"];
    }

    /**
     * @throws \Exception
     */
    public function update(Pelicula $movie, array $properties):array{
        if(is_array($properties) && count($properties)>0){
            $aux = false;

            foreach ($properties as $field=>$fieldValue){
                if($field==="category"){
                    $category=Categoria::findOrFail($fieldValue);
                    if(!$category){
                        throw new \Exception("No found the category with the ID {$fieldValue}",$this->codeBadRequest);
                    }

                    $field = "id_categoria_fk";
                    $movie->$field = $fieldValue;
                }
                else if($field === "quantity" || $field === "cost"){
                    if(!$aux){
                        $stockTaking = Inventario::where("id_pelicula_fk",$movie->id)->first();
                        $aux=true;
                    }
                    $fieldTranslate=$this->translateFieldsMovie($field);
                    $stockTaking->$fieldTranslate=$fieldValue;
                }
                else{
                    $fieldTranslate = $this->translateFieldsMovie($field);
                    $movie->$fieldTranslate = $fieldValue;
                }

            }

            if(!isset($category)){
                $category=Categoria::find($movie->id_categoria_fk);
            }

            if(!isset($stockTaking)){
                $stockTaking=Inventario::where("id_pelicula_fk",$movie->id)->first();
            }else{
                $stockTaking->save();
            }


            $movie->save();

            return $this->response($movie,$category,$stockTaking);

        }
        else{
            throw new \Exception("The fields are required",$this->codeBadRequest);
        }
    }

    public function read(string $parameterConsult,string $value):array{
        switch ($parameterConsult){
            case $parameterConsult=="name":
                $movies = Pelicula::where("nombre",$value)->where("id_estado_fk",$this->idStatusActive())->get();
                if(!$movies){
                    throw new \Exception("No found the movie with the name {$parameterConsult}",$this->codeBadRequest);
                }
                break;
            case $parameterConsult=="category":
                $category = Categoria::where("nombre",$value)->first();
                if(!$category) {
                    throw new \Exception("No found the category {$value}");
                }
                $movies = Pelicula::where("id_categoria_fk",$category->id)->where("id_estado_fk",$this->idStatusActive())->get();
                if(!$movies){
                    throw new \Exception("No found the movies with the category {$parameterConsult}",$this->codeBadRequest);
                }
        }

        $returnMovies = [];
        foreach ($movies as $movie){
            $category = Categoria::find($movie->id_categoria_fk);

            $stockTaking = Inventario::where("id_pelicula_fk",$movie->id)->first();

            array_push($returnMovies, $this->response($movie, $category, $stockTaking));
        }

        return  $returnMovies;
    }

    public function response(Pelicula $movie, Categoria $category, Inventario $stockTaking):array{

        $dataResponse = array();

        $dataResponse["name"] = $movie->nombre;
        $dataResponse["premiere_year"] = $movie->ano_estreno;
        $dataResponse["category"] = $category->nombre;
        $dataResponse["quantity"] = $stockTaking->unidad;
        $dataResponse["cost"] = $stockTaking->precio;

        return $dataResponse;
    }

    public function idStatusActive():int{
        return (Estado::where("nombre","Activo")->first())->id;
    }

    public function translateFieldsMovie($field,$returnLanguageTranslate="spanish"){
        $fields = $this->fields;

        switch ($returnLanguageTranslate){
            case $returnLanguageTranslate=="spanish":
                if(isset($fields[$field])){
                    return $fields[$field];
                }
                throw new \Exception("The field is incorrect",$this->codeBadRequest);
            case $returnLanguageTranslate=="english":
                foreach ($fields as $key => $value){
                    if($value === $field){
                        return $key;
                    }
                }
                throw new \Exception("The field is incorrect",$this->codeBadRequest);
            default:
                throw new \Exception("The field is incorrect",$this->codeBadRequest);
        }
    }

}
