<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Response;
use mysql_xdevapi\Exception;

class ApiController extends Controller
{
    private array|object|null $data;
    private bool $success;
    private int $status;
    private string $message;

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index($model = null)
    {
        $objModel = $this->_getModel($model);
        if (!is_object($objModel)){
            return $this->_outputData();
        }

        $this->data = $objModel->all();
        if ($this->_hasRecords()){
            $this->success = false;
            $this->status = 404;
            $this->message = 'No records found in '. ucfirst(strtolower($model)). ' model';
        } else {
            $this->success = true;
            $this->status = 200;
            $this->message = "OK: Returned all records from $model";
        }

        return $this->_outputData();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(ProductRequest $request)
    {
        return Product::create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param string|null $model
     * @param int|null $id
     * @return JsonResponse
     */
    public function show(string $model = null, int $id = null)
    {
        $objModel = $this->_getModel($model);
        if (!is_object($objModel)){
            return $this->_outputData();
        }

        $this->data = $objModel->all();
        if ($this->_hasRecords()){
            $this->success = false;
            $this->status = 404;
            $this->message = "No records found with id $id in the". ucfirst(strtolower($model)). ' model';
        } else {
            $this->success = true;
            $this->status = 200;
            $this->message = "OK: Returned all records from $model with id $id";
        }

        return $this->_outputData();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function update(Request $request,Product $product)
    {
        $product->update($request->all());
        return response($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        Product::destroy($id);
        return Product::all();
    }

    /**
     * Checks if the model class exists and return model object.
     *
     * @param $model
     * @return false|object
     */
    private function _getModel($model)
    {
        if ($model == null){
            $this->data = [];
            $this->success = false;
            $this->status = 400;
            $this->message = 'Bad Request: no input model given';
            return false;
        } else {
            $nsModel = 'App\Models\\'. ucfirst(strtolower((string)$model));
            if (class_exists($nsModel)){
                $objModel = new $nsModel;
                return (object)$objModel;
            } else {
                $this->data = [];
                $this->success = false;
                $this->status = 404;
                $this->message = "Not Found: given model '$model' is not found";
                return false;
            }
        }
    }

    /**
     * Checks if there are records in data.
     *
     * @return bool
     */
    private function _hasRecords()
    {
        if (empty($this->data->firstOrFail)){
            return false;
        }
        return true;
    }

    /**
     * Outputs data response
     *
     * @return JsonResponse
     */
    private function _outputData()
    {
        $meta = [
            'success' => $this->success,
            'status' => $this->status,
            'message' => $this->message,
        ];

        return \response()->json([
            'data' => $this->data,
            'meta' => $meta,
        ]);
    }
}
