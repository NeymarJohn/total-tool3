<?php

use App\CreditNotes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterCreditNoteStatusInCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            //
        });

        $creditNotes = CreditNotes::withoutGlobalScopes()->select('id', 'status')->get();

        DB::statement("ALTER TABLE `credit_notes` CHANGE `status` `status` ENUM('closed','open') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'closed'");

        foreach ($creditNotes as $creditNote) {
            if ($creditNote->status == 'paid') {
                $creditNote->status = 'closed';
            }
            else {
                $creditNote->status = 'open';
            }
            $creditNote->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_notes', function (Blueprint $table) {
            //
        });

        $creditNotes = CreditNotes::select('id', 'status')->get();

        DB::statement("ALTER TABLE `credit_notes` CHANGE `status` `status` ENUM('paid','unpaid') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unpaid'");

        foreach ($creditNotes as $creditNote) {
            if ($creditNote->status == 'closed') {
                $creditNote->status = 'paid';
            }
            else {
                $creditNote->status = 'unpaid';
            }
            $creditNote->save();
        }
    }
}
