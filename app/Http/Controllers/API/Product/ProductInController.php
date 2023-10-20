<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ProductIn;
use App\Models\ProductUpload;

class ProductInController extends Controller
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
        $request = $request->all();
        $productInArr = [];
        foreach($request['product_data'] as $productData){
            $productInData = array(
                'date'=> $request['inDate'],
                'tailor_id'=> $request['tailor_id'],
                'product_id'=>$productData['name'],
                'size_id'=>$productData['size'],
                'qty'=>$productData['qty'],
                'price'=>$productData['rate'],
                'total_amount'=>$productData['price'],
            );
            array_push( $productInArr,$productInData);            
        }
        $insertproductInData = ProductIn::insert($productInArr);
        if (!$insertproductInData) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }
        
        //get unique image
        $collection = collect($request['product_data']);
        $unique = $collection->unique('product_id'); 
        $imgDatas=$unique->values()->all();
        $productInImgArr = [];
        foreach($imgDatas as $imgData){
            $productInImgData = array(
                'date'=> $request['inDate'],
                'product_id'=>$imgData['name'],
                'image_name'=>$imgData['imageName'],
                'image_link'=>$imgData['imageUrl'],
                'created_emp'=>1,
                'updated_emp'=>1
            );
            array_push( $productInImgArr,$productInImgData);          
        }
        $insertproductInImgData = ProductUpload::insert($productInImgArr);

        if (!$insertproductInImgData) {
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER005'),
            ],200);
        }

        if ($insertproductInData && $insertproductInImgData) {
            return response()->json([
                'status'    =>  'OK',
                'message'   =>  trans('successMessage.SS001'),
            ],200);
        }
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCategory  $productCategory
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
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $productCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
