    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TicketCustomForm;

class AddMobileFieldInTicketCustomFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_custom_forms', function (Blueprint $table) {
            $companies =  \App\Company::withoutGlobalScope('active')->get();
            foreach ($companies as $company) {
                TicketCustomForm::firstOrCreate([
                    'field_display_name' => 'Mobile Number',
                    'field_name' => 'mobile_number',
                    'field_order' => 8,
                    'field_type' => 'text',
                    'company_id' => $company->id
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_custom_forms', function (Blueprint $table) {
            //
        });
    }
}
