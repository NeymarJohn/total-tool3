<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaymentGatewayCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `payment_gateway_credentials` CHANGE `paypal_status` `paypal_status` ENUM('active','deactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'deactive';");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `payment_gateway_credentials` CHANGE `stripe_status` `stripe_status` ENUM('active','deactive') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'deactive';");
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
