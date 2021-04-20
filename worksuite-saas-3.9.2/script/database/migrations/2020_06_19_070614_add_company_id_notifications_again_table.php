<?php

use App\Contract;
use App\Estimate;
use App\Invoice;
use App\Notification;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdNotificationsAgainTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $notifications = Notification::with('user')
            ->where('type', '<>', 'App\Notifications\NewInvoice')
            ->where('type', '<>', 'App\Notifications\NewEstimate')
            ->where('type', '<>', 'App\Notifications\NewContract')
            ->get();

        foreach ($notifications as $key => $value) {
            if($value->user){
                $value->company_id = $value->user->company_id;
                $value->save();
            }
        }

        $notifications = Notification::with('user')
            ->where('type', 'App\Notifications\NewInvoice')
            ->OrWhere('type', 'App\Notifications\NewEstimate')
            ->OrWhere('type', 'App\Notifications\NewContract')
            ->get();

        foreach ($notifications as $key => $value) {
            $notifyData = json_decode($value->data);
            if(isset($notifyData->id))
            {
                $id = $notifyData->id;
                if ($value->type == "App\Notifications\NewInvoice") {
                    $invoice = Invoice::find($id);
                    if($invoice){
                        $companyId = $invoice->company_id;
                        $value->company_id = $companyId;
                        $value->save();
                    }
                } else if ($value->type == "App\Notifications\NewEstimate") {
                    $invoice = Estimate::find($id);
                    if($invoice) {
                        $companyId = $invoice->company_id;
                        $value->company_id = $companyId;
                        $value->save();
                    }
                } else if ($value->type == "App\Notifications\NewContract") {
                    $invoice = Contract::find($id);
                    if($invoice) {
                        $companyId = $invoice->company_id;
                        $value->company_id = $companyId;
                        $value->save();
                    }
                }
            }

        }
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
