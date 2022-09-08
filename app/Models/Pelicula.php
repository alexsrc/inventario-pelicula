<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    /**
     * @var string
     */

    protected $table = "peliculas";

    protected $primaryKey = "id";

    protected $fillable  = [
        'nombre',
        'id_categoria_fk',
        'ano_estreno'
    ];

    public function inventario(){
        return $this->belongsTo(Inventario::class);
    }

    public function categoria(){
        return $this->hasOne(Categoria::class);
    }

    public function estado(){
        return $this->hasOne(Estado::class);
    }

    public $timestamps = false;

}
