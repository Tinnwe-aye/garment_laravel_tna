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
        // 'tailor_id' => 1,
        // 'date' => '2024-01-23',
        // 'product_id' => 1,
        // 'size_id' => 1,
        // 'category_id' => 1,
        // 'raw1_id' => 1,
        // 'raw2_id' => 2,
        // 'raw_qty' => '2',
        // 'product_per_raws' => '22',
        // 'total_product_qty' => 44,
        // 'checkBox' => true,
        // 'startDate' => '2024-01-23',
        // 'endDate' => '2024-01-23',
        // 'language' => 'en',
        // )  
        # get caller file name
		$callerFile = debug_backtrace()[0]['file'];
		# get error occur line number from caller file
		$errorLineNo = debug_backtrace()[0]['line'];
        DB::beginTransaction(); 
        try {   
            $tailor_id = $request->input("tailor_id");
            $date = $request->input("date");
            $product_id = $request->input("product_id");
            $size_id = $request->input("size_id");
            $category_id = $request->input("category_id");
            $raw1_id = $request->input("raw1_id");
            $raw2_id = $request->input("raw2_id");
            $raw_qty = $request->input("raw_qty");
            $product_per_raws = $request->input("product_per_raws");
            $total_product_qty = $request->input("total_product_qty");
            $checkBox = $request->input("checkBox");
            $des = $request->input("des");

        $productsRaw =  array(
            "raw1_id"=> $raw1_id,
            "raw2_id"=> $raw2_id,
            "raw_combination"=> ($raw1_id && $raw2_id) ? "pairs" : "single",
            "product_per_raws"=> $product_per_raws
        );
        $productsRawCheck = array(
            "product_id"=> $product_id,
            "size_id"=> $size_id,
        );

        $insertProductsRaw = ProductRaw::updateOrCreate($productsRawCheck,$productsRaw);

        $productCategory = array(
            "product_id"=> $product_id,
            "category_id"=> $category_id,
        );
        $insertProductCategory = ProductCategory::updateOrCreate($productCategory);

        $productRawId = ProductRaw::where(["product_id"=>$product_id,"size_id"=>$size_id])->first()->id;
        $tailorRaw = array(
            "date"=> $date,
            "tailor_id"=> $tailor_id,
            "products_raw_id"=> $productRawId,
            "raw_qty"=> $raw_qty,
            "total_product_qty"=> $total_product_qty,
            "descrption"=> $des,
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
        );
        
        $insertTailorTransaction = TailorTransaction::insert($tailorTransaction);

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
            $condition[] = ['tailor_raws.tailor_id',$request['tailor_id']];
        }
        if($request['product_id']){
            $filter[] =  ['product_id',$request['product_id']];
        }
        if($request['size_id']){
            $filter[] =  ['size_id',$request['size_id']];
        }

       // DB::enableQueryLog();
        $tailorRawTrans = ProductRaw::with('productRaw1')
        ->with('productRaw2')
        ->with('productSize')
        ->with('productProduct')
        ->join('tailor_raws','tailor_raws.products_raw_id','=','products_raw.id')
        ->join('tailor_transaction',function($query) use($filter) {
            $query->on('tailor_transaction.created_at','=','tailor_raws.created_at');
            $query->where( $filter);
        })   
        ->whereBetween('tailor_raws.date', [$request['start_date'], $request['end_date']])
        ->where($condition)
        ->get();
        //log::info(DB::getQueryLog());

        $tailorRawTrans=$tailorRawTrans->toArray();

        $results = [];
        foreach($tailorRawTrans as $key => $tran){
            $results[$key]['no'] = $key + 1;
            $results[$key]['date'] = $tran['date'];
            $results[$key]['code'] = $tran['product_product']['product_code'];
            $results[$key]['name'] = $tran['product_product']['product_name'];
            $results[$key]['size'] = $tran['product_size']['size'];
            $results[$key]['rawQty'] = $tran['raw_qty'];
            $results[$key]['rawCombine'] = $tran['raw_combination'];
            $results[$key]['productPerRaw'] = $tran['product_per_raws'];
            $results[$key]['totalProductQty'] = $tran['total_product_qty'];
            $results[$key]['raw1'] = $tran['product_raw1']['name'];
            $results[$key]['raw2'] = $tran['product_raw2']['name'];
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
}
