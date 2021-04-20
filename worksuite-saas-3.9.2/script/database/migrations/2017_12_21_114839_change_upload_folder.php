<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

class ChangeUploadFolder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $path = public_path().'/user-uploads';

        if(!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if(File::exists(public_path().'/storage')) {

            if(File::exists(public_path().'/storage/app-logo')) {
                #1
                $sourceDir = public_path() . '/storage/app-logo';
                $destinationDir = public_path() . '/user-uploads/app-logo';
                File::copyDirectory($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/avatar')) {
                #2
                $sourceDir = public_path() . '/storage/avatar';
                $destinationDir = public_path() . '/user-uploads/avatar';
                File::copyDirectory($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/expense-invoice')) {
                #3
                $sourceDir = public_path() . '/storage/expense-invoice';
                $destinationDir = public_path() . '/user-uploads/expense-invoice';
                File::copyDirectory($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/project-files')) {
                #4
                $sourceDir = public_path() . '/storage/project-files';
                $destinationDir = public_path() . '/user-uploads/project-files';
                File::copyDirectory($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/slack-logo')) {
                #5
                $sourceDir = public_path() . '/storage/slack-logo';
                $destinationDir = public_path() . '/user-uploads/slack-logo';
                File::copyDirectory($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/.gitignore')) {
                #6
                $sourceDir = public_path() . '/storage/.gitignore';
                $destinationDir = public_path() . '/user-uploads/.gitignore';
                File::copy($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/company-logo.png')) {
                #7
                $sourceDir = public_path() . '/storage/company-logo.png';
                $destinationDir = public_path() . '/user-uploads/company-logo.png';
                File::copy($sourceDir, $destinationDir);
            }

            if(File::exists(public_path().'/storage/login-background.jpg')) {
                #8
                $sourceDir = public_path() . '/storage/login-background.jpg';
                $destinationDir = public_path() . '/user-uploads/login-background.jpg';
                File::copy($sourceDir, $destinationDir);
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
