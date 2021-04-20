<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class AddStorageColumnsInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->enum('storage_unit', ['gb', 'mb'])->default('mb');
        });

        Schema::create('file_storage', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('path');
            $table->string('name');
            $table->string('type', 50)->nullable();
            $table->unsignedInteger('size');
            $table->timestamps();
        });
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE `packages` CHANGE `max_storage_size` `max_storage_size` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '-1';");
        } catch (\Exception $e) {
        }

        $packages = \App\Package::all();
        foreach($packages as $package) {
            $package->max_storage_size = -1;
            $package->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('social_token')->nullable();
        });

        Artisan::call('set-existing-companies-storage-data');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('storage_unit');
        });

        Schema::dropIfExists('file_storage');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('social_token');
        });
    }
}
