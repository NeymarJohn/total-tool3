<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeadProposalEmailSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rec = \App\EmailNotificationSetting::where('setting_name', 'Lead notification')->first();
        if(is_null($rec)){
            // When new expense added by admin
            \App\EmailNotificationSetting::create([
                'setting_name' => 'Lead notification',
                'send_email' => 'yes',
                'slug' => str_slug('Lead notification'),
            ]);
        }

        Schema::table('proposals', function (Blueprint $table) {
            $table->text('client_comment')->nullable();
            $table->boolean('signature_approval')->default(1);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\EmailNotificationSetting::where('setting_name', 'Lead notification')->delete();

        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['client_comment']);
            $table->dropColumn(['signature_approval']);
        });
    }
}
