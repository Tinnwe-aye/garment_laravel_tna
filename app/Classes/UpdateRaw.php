<?php

namespace App\Classes;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Raws;
use App\Models\TailorTransaction;
use App\Models\SupplierTransaction;
use App\Models\ProductRaw;

class UpdateRaw {
    public function updateRawRegisterDta($raw1id = null,$raw2id  = null)
	{
        $inData1 = SupplierTransaction::where('raw_id',$raw1id)->sum('qty');
        $inData2 = SupplierTransaction::where('raw_id',$raw2id)->sum('qty');

        $outData1 = ProductRaw::where('raw1_id',$raw1id)->sum('raw1_qty');
        $outData2 = ProductRaw::where('raw2_id',$raw2id)->sum('raw2_qty');

        $updateRaw = Raws::where('id',$raw1id)->update(['balance' =>  $inData1 - $outData1]);
        $updateRaw = Raws::where('id',$raw2id)->update(['balance' =>  $inData2 - $outData2]);

        return $updateRaw;
    }

}