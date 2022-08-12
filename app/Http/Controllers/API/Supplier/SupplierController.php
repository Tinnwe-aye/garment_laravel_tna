<?php

namespace App\Http\Controllers\API\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function store(Request $request)
    {

        return response()->json([
            'status'    =>  'OK',
            'message'   =>  'ok pr',
        ],200);
    //    die('hello');
        // dd($request);
        // $rules = [
        //     'login_id'          => 'required',
        //     'name_mm'          => 'required',
        //     'name_en'        => 'required',
        //     'phone_no'          => 'required',
        //     'nrc_no'        => 'required',
        //     'address'          => 'required',
        //     'tailor_id'        => 'required'
        // ];
        // $validator = Validator::make($request->all(), $rules);
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status'    =>  'NG',
        //         'message'   =>  $validator->errors()->all(),
        //     ],200);
        // }
        // try {
        //     //create SaveTailorData Class to save data in db
        //     $save = new SaveTailorData($request);
        //     $bool = $save->executeProcess();
        //     if ($bool) {
        //         return response()->json([
        //             'status'    =>  'OK',
        //             'message'   =>  trans('successMessage.SS001'),
        //         ],200);
        //     }else{
        //         return response()->json([
        //             'status' =>  'NG',
        //             'message' =>  trans('errorMessage.ER005'),
        //         ],200);
        //     }
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'status' =>  'NG',
        //         'message' =>  trans('errorMessage.ER005'),
        //     ],200);
        // }

    }
}
