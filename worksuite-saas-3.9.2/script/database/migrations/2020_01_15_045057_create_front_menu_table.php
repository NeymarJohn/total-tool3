<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_menu_buttons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('home', 20)->nullable()->default('home');
            $table->string('feature', 20)->nullable()->default('feature');
            $table->string('price', 20)->nullable()->default('price');
            $table->string('contact', 20)->nullable()->default('contact');
            $table->string('get_start', 20)->nullable()->default('get_start');
            $table->string('login', 20)->nullable()->default('login');
            $table->string('contact_submit', 20)->nullable()->default('contact_submit');
            $table->timestamps();
        });

        $frontMenu = new \App\FrontMenu();
        $frontMenu->home           = 'Home';
        $frontMenu->price          = 'Pricing';
        $frontMenu->contact        = 'Contact';
        $frontMenu->feature        = 'Features';
        $frontMenu->get_start      = 'Get Started';
        $frontMenu->login          = 'Login';
        $frontMenu->contact_submit = 'Submit Enquiry';
        $frontMenu->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('front_menu_buttons');
    }
}
