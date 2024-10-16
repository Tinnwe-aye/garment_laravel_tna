<?php
namespace App\DBTransactions\Raw;

use App\Models\Raws;
use App\Classes\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class saveRaw extends Transaction{
     //use LogTrait;
     private $request;
     //private $attendanceArray;
     public function __construct($request)
     {
         $this->request = $request;
     }

     public function process(){

        $row_count = Raws::count()+1;
        $row = Raws::create([
            "name"              => $this->request->name,
            "type"              => $this->request->type,
            "description"       => $this->request->description,
            "created_emp"       => $this->request->login_id,
            "updated_emp"       => $this->request->login_id,

        ]);
        $row['no'] = $row_count;

        return ['status' => true, 'error' => false, 'data' => $row];
     }
}