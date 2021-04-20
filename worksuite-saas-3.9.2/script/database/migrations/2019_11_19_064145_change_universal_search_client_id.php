<?php

use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUniversalSearchClientId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $searchResults = \App\UniversalSearch::withoutGlobalScope(CompanyScope::class)->where('module_type', 'client')->get();

       foreach($searchResults as $searchResult){
           $clientDetail = \App\ClientDetails::where('user_id', $searchResult->searchable_id)
               ->where('company_id', $searchResult->company_id)
               ->first();
           if($clientDetail) {
               $searchResult->searchable_id = $clientDetail->id;
               $searchResult->save();
           }

           if($searchResult->route_name == 'admin.clients.projects'){
               $searchResult->delete();
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

    }
}
