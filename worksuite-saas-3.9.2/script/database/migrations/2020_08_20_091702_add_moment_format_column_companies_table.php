<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMomentFormatColumnCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('moment_format')->nullable()->after('date_picker_format');
        });


        $companies = \App\Company::all();

        if ($companies) {
            foreach ($companies as $company) {
                switch ($company->date_format) {
                    case 'd-m-Y':
                        $company->moment_format = 'DD-MM-YYYY';
                        break;
                    case 'm-d-Y':
                        $company->moment_format = 'MM-DD-YYYY';
                        break;
                    case 'Y-m-d':
                        $company->moment_format = 'YYYY-MM-DD';
                        break;
                    case 'd.m.Y':
                        $company->moment_format = 'DD.MM.YYYY';
                        break;
                    case 'm.d.Y':
                        $company->moment_format = 'MM.DD.YYYY';
                        break;
                    case 'Y.m.d':
                        $company->moment_format = 'YYYY.MM.DD';
                        break;
                    case 'd/m/Y':
                        $company->moment_format = 'DD/MM/YYYY';
                        break;
                    case 'Y/m/d':
                        $company->moment_format = 'YYYY/MM/DD';
                        break;
                    case 'd-M-Y':
                        $company->moment_format = 'DD-MMM-YYYY';
                        break;
                    case 'd/M/Y':
                        $company->moment_format = 'DD/MMM/YYYY';
                        break;
                    case 'd.M.Y':
                        $company->moment_format = 'DD.MMM.YYYY';
                        break;
                    case 'd M Y':
                        $company->moment_format = 'DD MMM YYYY';
                        break;
                    case 'd F, Y':
                        $company->moment_format = 'DD MMMM, YYYY';
                        break;
                    case 'D/M/Y':
                        $company->moment_format = 'ddd/MMM/YYYY';
                        break;
                    case 'D.M.Y':
                        $company->moment_format = 'ddd.MMM.YYYY';
                        break;
                    case 'D-M-Y':
                        $company->moment_format = 'ddd-MMM-YYYY';
                        break;
                    case 'D M Y':
                        $company->moment_format = 'ddd MMM YYYY';
                        break;
                    case 'd D M Y':
                        $company->moment_format = 'DD ddd MMM YYYY';
                        break;
                    case 'D d M Y':
                        $company->moment_format = 'ddd DD MMMM YYYY';
                        break;
                    case 'dS M Y':
                        $company->moment_format = 'Do MMM YYYY';
                        break;
                    default:
                        $company->moment_format = 'MM/DD/YYYY';
                        break;
                }
                $company->save();
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
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['moment_format']);
        });
    }
}
