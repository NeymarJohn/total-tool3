<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TicketType;

class CreateTicketTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->unique();
            $table->timestamps();
        });

        $type = new TicketType();
        $type->type = 'Question';
        $type->save();

        $type = new TicketType();
        $type->type = 'Problem';
        $type->save();

        $type = new TicketType();
        $type->type = 'Incident';
        $type->save();

        $type = new TicketType();
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
        Schema::dropIfExists('ticket_types');
    }
}
