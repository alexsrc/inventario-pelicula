<?php
namespace App\ManagerInformation\Category;

use App\Models\Categoria;

class Category {

    const codeBadRequest=400;

    public function create(string $name):array{

        $existCategory = Categoria::where("nombre",$name)->first();
        if($existCategory){
            throw new \Exception("{$name} category was already created",self::codeBadRequest);
        }

        $category = new Categoria();
        $category->nombre = $name;
        $category->save();

        $data=["id"=>$category->id,"name"=>$category->nombre];


        return $data;
    }

}
