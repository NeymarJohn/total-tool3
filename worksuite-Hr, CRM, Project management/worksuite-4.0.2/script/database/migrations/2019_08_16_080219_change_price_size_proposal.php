<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePriceSizeProposal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `invoice_items` CHANGE `unit_price` `unit_price` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `invoice_items` CHANGE `amount` `amount` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `invoice_items` CHANGE `quantity` `quantity` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `estimate_items` CHANGE `amount` `amount` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `estimate_items` CHANGE `unit_price` `unit_price` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `estimate_items` CHANGE `quantity` `quantity` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `proposal_items` CHANGE `amount` `amount` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `proposal_items` CHANGE `unit_price` `unit_price` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `proposal_items` CHANGE `quantity` `quantity` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `invoices` CHANGE `total` `total` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `invoices` CHANGE `sub_total` `sub_total` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `estimates` CHANGE `sub_total` `sub_total` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `estimates` CHANGE `total` `total` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `proposals` CHANGE `sub_total` `sub_total` DOUBLE(16,2) NOT NULL");
        DB::statement("ALTER TABLE `proposals` CHANGE `total` `total` DOUBLE(16,2) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
