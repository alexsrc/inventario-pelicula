<?php

namespace App\ManagerInformation\Movie;

use App\Master\Master;
use App\Models\Categoria;
use App\Models\Estado;
use App\Models\Inventario;
use App\Models\Pelicula;

class Movie {

    const codeBadRequest = 400;

    public function create(string $name,  int $category, string $premiere_year, float $cost, int $quantity):array{
        $category = Categoria::find($category);
        if(!$category){
            throw new \Exception("No found the category with the ID {$category}",self::codeBadRequest);
        }

        $statusActive = $this->idStatusActive();

        $existMovie = Pelicula::where("nombre",$name)->where("id_categoria_fk",$statusActive)->get();
        if(count($existMovie)>0){
            throw new \Exception("{$name} movie was already created",self::codeBadRequest);
        }

        $movie = new Pelicula();
        $movie->nombre = $name;
        $movie->id_categoria_fk = $category->id;
        $movie->ano_estreno = $premiere_year;
        $movie->id_estado_fk = $statusActive;
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
            throw new \Exception("No found the movie with the ID {$id}",self::codeBadRequest);
        }

        return $this->update($movie, $properties);
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id){
        $movie = Pelicula::where("id",$id)->where("id_estado_fk",$this->idStatusActive())->first();

        if(!$movie){
            throw new \Exception("No found the movie with the ID {$id}",self::codeBadRequest);
        }

        $state = Estado::where("nombre","eliminado")->first();
        if(!$state){
            throw new \Exception("The state is incorrect",self::codeBadRequest);
        }

        $movie->id_estado_fk = $state->id;
        $movie->save();

        return ["message" => "Ok, the movie deleted"];
    }

    /**
     * @throws \Exception
     */
    public function update(Pelicula $movie, array $properties):array{

        $masterInstance = Master::getMasterSingleton();
        if(is_array($properties) && count($properties)>0){
            $aux = false;

            foreach ($properties as $field=>$fieldValue){
                if($field==="category"){
                    $category=Categoria::findOrFail($fieldValue);
                    if(!$category){
                        throw new \Exception("No found the category with the ID {$fieldValue}",self::codeBadRequest);
                    }

                    $field = "id_categoria_fk";
                    $movie->$field = $fieldValue;
                }
                else if($field === "quantity" || $field === "cost"){
                    if(!$aux){
                        $stockTaking = Inventario::where("id_pelicula_fk",$movie->id)->first();
                        $aux=true;
                    }
                    $fieldTranslate=$masterInstance->translateFieldsMovie($field);
                    $stockTaking->$fieldTranslate=$fieldValue;
                }
                else{
                    $fieldTranslate = $masterInstance->translateFieldsMovie($field);
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
            throw new \Exception("The fields are required",self::codeBadRequest);
        }
    }

    public function read(string $parameterConsult,string $value):array{
        $statusActive = $this->idStatusActive();

        switch ($parameterConsult){
            case $parameterConsult=="name":
                $movies = Pelicula::where("nombre",$value)->where("id_estado_fk",$statusActive)->get();
                if(!$movies){
                    throw new \Exception("No found the movie with the name {$parameterConsult}",self::codeBadRequest);
                }
                break;
            case $parameterConsult=="category":
                $category = Categoria::where("nombre",$value)->first();
                if(!$category) {
                    throw new \Exception("No found the category {$value}",self::codeBadRequest);
                }
                $movies = Pelicula::where("id_categoria_fk",$category->id)->where("id_estado_fk",$statusActive)->get();
                if(!$movies){
                    throw new \Exception("No found the movies with the category {$parameterConsult}",self::codeBadRequest);
                }
                break;
            default:
                $movies = Pelicula::where("id_estado_fk",$statusActive)->get();
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


}
