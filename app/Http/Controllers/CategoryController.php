<?php

namespace App\Http\Controllers;

use App\Factories\ManagerInformationFactory;
use App\Master\ValidatorCommon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
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

                $errors = $validatorCommon->getError();
                if(count($errors)>0){
                    return $this->response(400,$errors);
                }

                $responseCreate = ((new ManagerInformationFactory())
                    ->create("Category"))
                    ->create($parameters->name);

                return $this->response(200,$responseCreate);
            }
            catch (\Exception $exception){
                return $this->response($exception->getCode(),["error" => $exception->getMessage()]);
            }
    }


}
