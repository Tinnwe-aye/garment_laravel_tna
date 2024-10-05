<?php

namespace App\Http\Controllers\API\SupplierTransaction;

use App\Models\SupplierTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Classes\UpdateRaw;
use Illuminate\Support\Facades\Validator;

class SupplierTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, SupplierTransaction::validationRules());
        $rules = [
            "supplier_id"=>"required",
            "raw_id"=>"required",
            "qty_pkg"=>"required|integer",
            "qty"=>"required|integer",
            "price"=>"required|integer",
            "total_amount"=>"required|integer",
        ];
       
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                "status"=>"NG",
                "message"=>$validator->errors()->all()
            ],200);
        }
        
        $insertSupTran = SupplierTransaction::insert($request->all());
        
        //Update balance in raws table        
        $updateRaw = new UpdateRaw();
        $updateRawData =  $updateRaw->updateRawRegisterDta($request['raw_id']);
        
        if($insertSupTran and $updateRawData){
            return response()->json([
                'status'    =>  'OK',
                'message'   =>  trans('successMessage.SS001'),
            ],200); 
        } else {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER002'),
            ],200);
        }           
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierTransaction  $supplierTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierTransaction  $supplierTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupplierTransaction  $supplierTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierTransaction $supplierTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierTransaction  $supplierTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupplierTransaction $supplierTransaction)
    {
        //
    }

}
