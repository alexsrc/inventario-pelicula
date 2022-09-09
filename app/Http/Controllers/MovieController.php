<?php

namespace App\Http\Controllers;

use App\Factories\ManagerInformationFactory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
            try {
                $parameters = (object)$request->all();

                $responseCreate = ((new ManagerInformationFactory())
                    ->create("Movie"))
                    ->create($parameters->name, $parameters->category, $parameters->premiere_year, $parameters->cost, $parameters->quantity);

                return $this->response(200,$responseCreate);
            }
            catch (\Exception $exception){
                return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
            }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function read(Request $request){


            $parameters = (object)$request->all();

            $responseCreate = ((new ManagerInformationFactory())
                ->create("Movie"))
                ->read($parameters->filter,$parameters->value);

            return $this->response(200,$responseCreate);

    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id){
        try {
            $responseCreate = ((new ManagerInformationFactory())
                ->create("Movie"))
                ->delete($id);

            return $this->response(200,$responseCreate);
        }
        catch (\Exception $exception){
            return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id,Request $request){
        try {
            $parameters = $request->all();


            $responseCreate = ((new ManagerInformationFactory())
                ->create("Movie"))
                ->edit($id,$parameters);

            return $this->response(200,$responseCreate);
        }
        catch (\Exception $exception){
            return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
        }
    }

}
