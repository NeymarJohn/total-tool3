<?php

use App\CreditNoteItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCreditNoteItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->string('taxes')->nullable()->default(null)->after('amount');
        });

        $creditNoteItems = CreditNoteItem::all();
        if ($creditNoteItems->count() > 0){
            foreach ($creditNoteItems as $creditNoteItem){
                $arr = [];
                if ($creditNoteItem->tax_id){
                    $arr[] = (string)$creditNoteItem->tax_id;
                }
                $creditNoteItem->taxes = $arr ? json_encode($arr) : null;
                $creditNoteItem->save();
            }
        }

        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->dropForeign('credit_note_items_tax_id_foreign');
            $table->dropColumn('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->dropColumn('taxes');
        });
    }
}
