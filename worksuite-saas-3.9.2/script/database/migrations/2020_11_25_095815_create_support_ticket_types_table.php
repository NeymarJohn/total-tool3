<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SupportTicketType;

class CreateSupportTicketTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_ticket_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->unique();
            $table->timestamps();
        });

        $type = new SupportTicketType();
        $type->type = 'Question';
        $type->save();

        $type = new SupportTicketType();
        $type->type = 'Problem';
        $type->save();

        $type = new SupportTicketType();
        $type->type = 'Incident';
        $type->save();

        $type = new SupportTicketType();
        $type->type = 'Feature Request';
        $type->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_ticket_types');
    }
}
