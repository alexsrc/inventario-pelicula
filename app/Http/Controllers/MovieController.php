<?php

namespace App\Http\Controllers;

use App\Factories\ManagerInformationFactory;
use App\Master\ValidatorCommon;
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
                $validatorCommon = ValidatorCommon::getValidatorCommonSingleton();
                $parameters = (object)$request->all();

                $validatorCommon->validateParamFormat($parameters->name,'name',[$validatorCommon->getSTRING(),$validatorCommon->getRANGE()],true,[2,50]);
                $validatorCommon->validateParamFormat($parameters->category,'category',[$validatorCommon->getINT(),$validatorCommon->getRANGE()],true,[1,50]);
                $validatorCommon->validateParamFormat($parameters->premiere_year,'premiere_year',[$validatorCommon->getDATE(),$validatorCommon->getRANGE()],true,[4,4]);
                $validatorCommon->validateParamFormat($parameters->cost,'cost',[$validatorCommon->getFLOAT(),$validatorCommon->getRANGE()],true,[1,50]);
                $validatorCommon->validateParamFormat($parameters->quantity,'quantity',[$validatorCommon->getINT(),$validatorCommon->getRANGE()],true,[1,50]);

                $errors = $validatorCommon->getError();
                if(count($errors)>0){
                    return $this->response(400,$errors);
                }

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
    public function read(Request $request):JsonResponse{
        try {
            $validatorCommon = ValidatorCommon::getValidatorCommonSingleton();
            $parameters = (object)$request->all();

            $filter = (isset($parameters->filter) ? $parameters->filter : "empty");
            $value = (isset($parameters->value) ? $parameters->value : "empty");

            $validatorCommon->validateParamFormat($filter,'filter',[$validatorCommon->getSTRING(),$validatorCommon->getRANGE()],false,[4,8]);
            $validatorCommon->validateParamFormat($value,'value',[$validatorCommon->getSTRING(),$validatorCommon->getRANGE()],false,[1,50]);

            $errors = $validatorCommon->getError();
            if(count($errors)>0){
                return $this->response(400,$errors);
            }

            $responseCreate = ((new ManagerInformationFactory())
                ->create("Movie"))
                ->read($filter,$value);

            return $this->response(200,$responseCreate);
        }
        catch (\Exception $exception){
            return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id):JsonResponse{
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
    public function update(int $id,Request $request):JsonResponse{
        try {
            $validatorCommon = ValidatorCommon::getValidatorCommonSingleton();
            $parameters = (object)$request->all();

            $name = (isset($parameters->name) ? $parameters->name : "");
            $category = (isset($parameters->category) ? $parameters->category : "");
            $premiere_year = (isset($parameters->premiere_year) ? $parameters->premiere_year : "");
            $cost = (isset($parameters->cost) ? $parameters->cost : "");
            $quantity = (isset($parameters->quantity) ? $parameters->quantity : "");

            $validatorCommon->validateParamFormat($name,'name',[$validatorCommon->getSTRING(),$validatorCommon->getRANGE()],true,[2,50]);
            $validatorCommon->validateParamFormat($category,'category',[$validatorCommon->getINT(),$validatorCommon->getRANGE()],true,[1,50]);
            $validatorCommon->validateParamFormat($premiere_year,'premiere_year',[$validatorCommon->getDATE(),$validatorCommon->getRANGE()],true,[4,4]);
            $validatorCommon->validateParamFormat($cost,'cost',[$validatorCommon->getFLOAT(),$validatorCommon->getRANGE()],true,[1,50]);
            $validatorCommon->validateParamFormat($quantity,'quantity',[$validatorCommon->getINT(),$validatorCommon->getRANGE()],true,[1,50]);

            $errors = $validatorCommon->getError();
            if(count($errors)>0){
                return $this->response(400,$errors);
            }

            $responseCreate = ((new ManagerInformationFactory())
                ->create("Movie"))
                ->edit($id,(array)$parameters);

            return $this->response(200,$responseCreate);
        }
        catch (\Exception $exception){
            return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
        }
    }

}
