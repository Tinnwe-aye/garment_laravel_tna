<?php

namespace App\Http\Controllers\API\Product;

use App\Models\Tailor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Tailor\TailorRepositoryInterface;

class ProductListController extends Controller
{
    protected $tailorRepo;
    public function __construct(TailorRepositoryInterface $tailorRepo)
    {
        $this->tailorRepo = $tailorRepo;
    }
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
        //
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

    public function searchTailor()
    {
       $data = $this->tailorRepo->getTailorData();
       return   $data->pluck('tailor_id');
    }

    public function searchTailorByID(Request $request)
    {
       $data = $this->tailorRepo->getTailorData();
       $TailorData = $data->where('tailor_id',$request->tailor_id);
       return  $TailorData->pluck('name_en');
    }
}
