<?php

namespace App\Http\Controllers\API\TailorRaw;

use Exception;
use App\Models\Tailor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\DB;
use App\Models\ProductRaw;
use App\Models\TailorRaw;
use App\Models\ProductCategory;
use App\Models\TailorTransaction;
use App\Interfaces\Tailor\TailorRepositoryInterface;
use App\Classes\UpdateRaw;

class TailorRawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd("index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Sample input
        // array (
        //     'tailor_id' => 1,
        //     'date' => '2024-01-25',
        //     'product_id' => 1,
        //     'productName' => 'pname',
        //     'size_id' => 1,
        //     'sizeName' => 'SS',
        //     'category_id' => 1,
        //     'raw1_id' => 1,
        //     'raw1' => 'Chipon',
        //     'raw2_id' => 2,
        //     'raw2' => 'Inlay',
        //     'raw_qty' => '4',
        //     'product_per_raws' => '10',
        //     'total_product_qty' => 40,
        //     'checkBox' => true,
        //     'startDate' => '2024-01-25',
        //     'endDate' => '2024-01-25',
        //     'des' => 'test',
        //     'language' => 'en',
        //   )  
        # get caller file name
		$callerFile = debug_backtrace()[0]['file'];
		# get error occur line number from caller file
		$errorLineNo = debug_backtrace()[0]['line'];
        DB::beginTransaction(); 
        try {   
            $tailor_id = $request->input("tailor_id");
            $date = $request->input("date");
            $product_id = $request->input("product_id");
            $product_name = $request->input("productName");
            $size_id = $request->input("size_id");
            $size_name = $request->input("sizeName");
            $category_id = $request->input("category_id");
            $raw1_id = $request->input("raw1_id");
            $raw1_qty = $request->input("raw1_qty");
            $raw1_name = $request->input("raw1");
            $raw2_id = $request->input("raw2_id");
            $raw2_qty = $request->input("raw2_qty");
            $raw2_name = ($request->input("raw2")) ? $request->input("raw2") : '';
            $raw_name = $raw1_name .','. $raw2_name;
            $raw_id = $raw1_id .','. $raw2_id;
            $raw_qty = $request->input("raw_qty");
            $product_per_raws = $request->input("product_per_raws");
            $total_product_qty = $request->input("total_product_qty");
            $checkBox = $request->input("checkBox");
            $des = $request->input("des");
            $raw_combination = ($raw1_id && $raw2_id) ? "pairs" : "single";

        $productsRaw =  array(
            "product_id"=> $product_id,
            "size_id"=> $size_id,
            "raw1_id"=> $raw1_id,
            "raw2_id"=> $raw2_id,
            "raw1_name"=> $raw1_name,
            "raw2_name"=> $raw2_name,
            "raw1_qty"=> $raw1_qty,
            "raw2_qty"=> $raw2_qty,
            "raw_combination"=> $raw_combination,
            "product_per_raws"=> $product_per_raws
        );
        
        // $productsRawCheck = array(
        //     "product_id"=> $product_id,
        //     "size_id"=> $size_id,
        // );
        // $insertProductsRaw = ProductRaw::updateOrCreate($productsRawCheck,$productsRaw);

        $insertProductsRaw = ProductRaw::insert($productsRaw);

        $productCategory = array(
            "product_id"=> $product_id,
            "category_id"=> $category_id,
        );
        $insertProductCategory = ProductCategory::updateOrCreate($productCategory);

        $productRawId = ProductRaw::where(["product_id"=>$product_id,"size_id"=>$size_id])->latest('id')->first()->id;
        $tailorRaw = array(
            "date"=> $date,
            "tailor_id"=> $tailor_id,
            "products_raw_id"=> $productRawId,
            "raw_qty"=> $raw_qty,
            "total_product_qty"=> $total_product_qty,
            "description"=> $des,
        );
        $insertTailorRaw = TailorRaw::insert($tailorRaw);

        $getLeftQty = TailorTransaction::where(['tailor_id'=>$tailor_id,'product_id'=>$product_id,'size_id'=>$size_id])->orderBy('created_at', 'desc')->first();
        $getLeftQty = ($getLeftQty) ? $getLeftQty->left_qty : 0 ;
        $tailorTransaction = array(
            "date"=> $date,
            "tailor_id"=> $tailor_id,
            "product_id"=> $product_id,
            "size_id"=> $size_id,
            "out_qty"=> $total_product_qty,
            "in_qty"=> 0,
            "left_qty"=> $total_product_qty + $getLeftQty, // need to Modified
            "products_raw_id"=> $productRawId,
            "raw_qty"=> $raw_qty,
            "total_product_qty"=> $total_product_qty,
            "description"=> $des,
            "product_name" => $product_name,
            "size_name" => $size_name,
            "raw_name" => $raw_name,
            "raw_id" => $raw_id,
        );
        
        $insertTailorTransaction = TailorTransaction::insert($tailorTransaction);

       //Update balance in raws table        
       $updateRaw = new UpdateRaw();
       $updateRawData =  $updateRaw->updateRawRegisterDta($raw1_id,$raw2_id);

        DB::commit();
        return response()->json([
            'status'    =>  'OK',
            'message'   =>  trans('successMessage.SS001'),
        ],200);        
    }catch(Exception $e){
            DB::rollBack();
			Log::debug($e->getMessage().' in file '.$callerFile.' at line '.$errorLineNo.' that instantiate the class '.get_called_class());
        return response()->json([
            'status'=> 'NG',
            'message'=> trans('errorMessage.ER005'),
        ],500);
    }
}

     /**
     * Show the form for searching a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $request=$request->all();

        $condition = [];
        $filter = [];
        if($request['tailor_id']){
            $condition[] = ['tailor_transaction.tailor_id',$request['tailor_id']];
        }
        if($request['product_id']){
            $condition[] =  ['product_id',$request['product_id']];
        }
        if($request['size_id']){
            $condition[] =  ['size_id',$request['size_id']];
        }

        $tailorRawTrans = TailorTransaction::with('ProductsRaw')->where($condition)
        ->whereBetween('date', [$request['startDate'],$request['endDate']])->get();
        $tailorRawTrans=$tailorRawTrans->toArray();

        $results = [];
        foreach($tailorRawTrans as $key => $tran){
            $results[$key]['no'] = $key + 1;
            $results[$key]['date'] = $tran['date'];
            // $results[$key]['code'] = $tran['product_code'];
            $results[$key]['code'] = '';
            $results[$key]['name'] = $tran['product_name'];
            $results[$key]['size'] = $tran['size_name'];
            $results[$key]['rawQty'] = ($tran['raw_qty']) ? $tran['raw_qty'] : '';
            $results[$key]['rawCombine'] = ($tran['products_raw']) ? $tran['products_raw']['raw_combination'] : '';
            $results[$key]['productPerRaw'] = ($tran['products_raw']) ? $tran['products_raw']['product_per_raws'] : '';
            $results[$key]['totalProductQty'] = $tran['total_product_qty'];
            $results[$key]['rawName'] = $tran['raw_name'];
            $results[$key]['outQty'] = $tran['out_qty'];
            $results[$key]['inQty'] = $tran['in_qty'];
            $results[$key]['leftQty'] = $tran['left_qty'];
            $results[$key]['description'] = $tran['description'];
        }



        if(!empty($results)){
            return response()->json([
                'status'    =>  'OK',
                'data'      => $results,
            ],200);
        }else{
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER009'),
            ],200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd("store");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd("show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd("edit");
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
        dd("update");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd("destroy");
    }

    public function searchTailorRaw(Request $request){
        $request=$request->all();

        $condition = [];
        if($request['tailor_id']){
            $condition[] = ['tailor_raws.tailor_id',$request['tailor_id']];
        }

        $tailorRawTrans = TailorRaw::join('products_raw','tailor_raws.products_raw_id','=','products_raw.id')
        ->join('tailors','tailors.tailor_id','=','tailor_raws.tailor_id')
        ->where($condition)
        ->whereBetween('date', [$request['startDate'],$request['endDate']])->get();
        $tailorRawTrans=$tailorRawTrans->toArray();

        if(!empty($tailorRawTrans)){
            return response()->json([
                'status'    =>  'OK',
                'data'      => $tailorRawTrans,
            ],200);
        }else{
            return response()->json([
                'status' =>  'NG',
                'message' =>  trans('errorMessage.ER009'),
            ],200);
        }

    }


}
