<?php

use Illuminate\Database\Seeder;

class EstimateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estimate = new \App\Estimate();
        $estimate->client_id = 3;
        $estimate->valid_till = \Carbon\Carbon::parse((date('m')).'/03/2017')->format('Y-m-d');
        $estimate->sub_total = 1200;
        $estimate->total = 1200;
        $estimate->currency_id = 1;
        $estimate->note = null;
        $estimate->status = 'waiting';
        $estimate->save();

        $items = ['item 1', 'item 2'];
        $cost_per_item = ['500', '700'];
        $quantity = ['1', '1'];
        $amount = ['500', '700'];
        $type = ['item', 'item'];

        foreach ($items as $key => $item):
            if(!is_null($item)){
                \App\EstimateItem::create(['estimate_id' => $estimate->id, 'item_name' => $item, 'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
            }
        endforeach;


        $estimate = new \App\Estimate();
        $estimate->client_id = 3;
        $estimate->valid_till = \Carbon\Carbon::parse((date('m')).'/08/2017')->format('Y-m-d');
        $estimate->sub_total = 4100;
        $estimate->total = 4100;
        $estimate->currency_id = 1;
        $estimate->note = null;
        $estimate->status = 'accepted';
        $estimate->save();

        $items = ['item 3', 'item 4'];
        $cost_per_item = ['1200', '1700'];
        $quantity = ['2', '1'];
        $amount = ['2400', '1700'];
        $type = ['item', 'item'];

        foreach ($items as $key => $item):
            if(!is_null($item)){
                \App\EstimateItem::create(['estimate_id' => $estimate->id, 'item_name' => $item, 'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null, 'type' => $type[$key], 'quantity' => $quantity[$key], 'unit_price' => $cost_per_item[$key], 'amount' => $amount[$key]]);
            }
        endforeach;
    }
}
