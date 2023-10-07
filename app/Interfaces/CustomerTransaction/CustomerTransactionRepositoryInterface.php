<?php

namespace App\Interfaces\CustomerTransaction;

/**
 * CustomerTransaction interface
 *
 * @author Tin Nwe Aye
 * @create  2022/07/19
 */
interface CustomerTransactionRepositoryInterface
{
    public function searchCusTran($request);

    public function editCusTran($request);

    
}
