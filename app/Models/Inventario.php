<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = "inventarios";

    protected $primaryKey = "id";

    protected $fillable  = [
        'precio',
        'unidad',
        'fecha_creacion',
        'fecha_actualizacion',
        'fecha_eliminacion',
        'id_estado_fk',
        'id_pelicula_fk'
    ];



    public function pelicula(){
        return $this->hasOne(Pelicula::class);
    }

    public $timestamps = false;

}
