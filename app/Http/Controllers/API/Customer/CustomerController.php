<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\DBTransactions\Customer\SaveCustomer;

class CustomerController extends Controller
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
        $rules = [
            'customer_id' => 'required',
            'name_mm' => 'required',
            'name_en' => 'required',
            'phone_no' => 'required | unique:customers',
            'addresses' => 'required',
            'nrc_no' => 'required | unique:customers',
            'township_name' => 'required',
            'statuses' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'    =>  'NG',
                'message'   =>  $validator->errors()->all(),
            ],200);
        }
       
        try {
            $save = new SaveCustomer($request);            
            $bool = $save->process();
            if ($bool['status']) {
                return response()->json([
                    'status'    =>  'OK',
                    'message'   =>  trans('successMessage.SS001'),
                ],200);
            }else{
                return response()->json([
                    'status' =>  'NG',
                    'message' =>  trans('errorMessage.ER005'),
                ],200);
            }
        } catch (\Throwable $th) {
            log::info($th);
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCustomerId(){
        try {
            $cus_id       = Customer::all()->last();         
            $cus_id  = ( $cus_id == null ) ?  0 : $cus_id->id;
            return response()->json([
                'status' =>  'OK',
                'data'   =>   'Cus_'.($cus_id+1),
            ], 200);
        } catch (\Throwable $th) {
            log::info($th);
        }
    }
}
