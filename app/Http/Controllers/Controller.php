<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{


    public function response(int $status ,array $datos = [],array $headers=[]) {
        return Response()->json($datos,$status,$headers);
    }
}
