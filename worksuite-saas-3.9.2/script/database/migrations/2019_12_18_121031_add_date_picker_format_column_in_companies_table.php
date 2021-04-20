<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatePickerFormatColumnInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('date_picker_format')->nullable()->after('date_format');
        });

        $companySettings = \App\Company::all();
        foreach($companySettings as $settings) {
            switch ($settings->date_format) {
                case 'd-m-Y':
                    $settings->date_picker_format = 'dd-mm-yyyy';
                    break;
                case 'm-d-Y':
                    $settings->date_picker_format = 'mm-dd-yyyy';
                    break;
                case 'Y-m-d':
                    $settings->date_picker_format = 'yyyy-mm-dd';
                    break;
                case 'd.m.Y':
                    $settings->date_picker_format = 'dd.mm.yyyy';
                    break;
                case 'm.d.Y':
                    $settings->date_picker_format = 'mm.dd.yyyy';
                    break;
                case 'Y.m.d':
                    $settings->date_picker_format = 'yyyy.mm.dd';
                    break;
                case 'd/m/Y':
                    $settings->date_picker_format = 'dd/mm/yyyy';
                    break;
                case 'm/d/Y':
                    $settings->date_picker_format = 'mm/dd/yyyy';
                    break;
                case 'Y/m/d':
                    $settings->date_picker_format = 'yyyy/mm/dd';
                    break;
                case 'd-M-Y':
                    $settings->date_picker_format = 'dd-M-yyyy';
                    break;
                case 'd/M/Y':
                    $settings->date_picker_format = 'dd/M/yyyy';
                    break;
                case 'd.M.Y':
                    $settings->date_picker_format = 'dd.M.yyyy';
                    break;
                case 'd-M-Y':
                    $settings->date_picker_format = 'dd-M-yyyy';
                    break;
                case 'd M Y':
                    $settings->date_picker_format = 'dd M yyyy';
                    break;
                case 'd F, Y':
                    $settings->date_picker_format = 'dd MM, yyyy';
                    break;
                case 'D/M/Y':
                    $settings->date_picker_format = 'D/M/yyyy';
                    break;
                case 'D.M.Y':
                    $settings->date_picker_format = 'D.M.yyyy';
                    break;
                case 'D-M-Y':
                    $settings->date_picker_format = 'D-M-yyyy';
                    break;
                case 'D M Y':
                    $settings->date_picker_format = 'D M yyyy';
                    break;
                case 'd D M Y':
                    $settings->date_picker_format = 'dd D M yyyy';
                    break;
                case 'D d M Y':
                    $settings->date_picker_format = 'D dd M yyyy';
                    break;
                case 'dS M Y':
                    $settings->date_picker_format = 'dd M yyyy';
                    break;

                default:
                    $settings->date_picker_format = 'mm/dd/yyyy';
                    break;
            }
            $settings->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
