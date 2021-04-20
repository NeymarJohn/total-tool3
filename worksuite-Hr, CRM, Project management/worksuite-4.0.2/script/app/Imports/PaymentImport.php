<?php

namespace App\Imports;

use App\Payment;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PaymentImport implements ToModel, WithStartRow
{
    public function __construct($request, $global)
    {
        $this->request = $request;
        $this->global = $global;
    }
    /**
     * @param array $row
     *
     * @return Payment|null
     */
    public function model(array $row)
    {
        return new Payment([
           'paid_on'     => Carbon::parse($row[0]),
           'amount'    => $this->request->currency_character ? substr($row[1], 1) : $row[1], 
           'currency_id' => $this->global->currency_id,
           'status' => 'complete',
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}