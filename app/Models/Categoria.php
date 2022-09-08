<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    /**
     * @var string
     */

    protected $table = "categorias";

    protected $primaryKey = "id";

    protected $fillable  = [
        'nombre'
    ];

    public function pelicula(){
        return $this->belongsTo(Pelicula::class);
    }

    public $timestamps = false;

}
