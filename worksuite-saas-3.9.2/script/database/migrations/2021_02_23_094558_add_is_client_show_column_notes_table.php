<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsClientShowColumnNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function(Blueprint $table){
            $table->boolean('is_client_show')->default(0);
            $table->unsignedInteger('company_id')->nullable()->after('id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->dropForeign('notes_user_id_foreign');
            $table->dropColumn('user_id');
            $table->dropForeign('notes_member_id_foreign');
            $table->dropColumn('member_id');
        });
        Schema::table('project_notes', function(Blueprint $table){
            $table->boolean('is_client_show')->default(0);
        });
        
      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['is_client_show']);
            $table->dropColumn(['company_id']); 
        });
        Schema::table('project_notes', function (Blueprint $table) {
            $table->dropColumn(['is_client_show']);
          
        });
    }
}
