<?php

namespace App\Http\Controllers\API\Size;

use App\Models\sizes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizesController extends Controller
{
    public function index()
    {
        try {
            $data       = sizes::select('id','size')->orderby('id')->get();
            return response()->json([
                'status' =>  'OK',
                'row_count' => count($data),
                'data'   =>   $data,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
