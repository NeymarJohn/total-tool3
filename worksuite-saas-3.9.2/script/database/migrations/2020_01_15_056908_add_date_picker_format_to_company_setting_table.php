<?php

use Illuminate\Database\Migrations\Migration;

class AddDatePickerFormatToCompanySettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = \App\Company::all();


        if($companies){
            foreach($companies as $company){
                if ($company->date_picker_format == '' || $company->date_picker_format == null){
                    switch ($company->date_format) {
                        case 'd-m-Y':
                            $company->date_picker_format = 'dd-mm-yyyy';
                            break;
                        case 'm-d-Y':
                            $company->date_picker_format = 'mm-dd-yyyy';
                            break;
                        case 'Y-m-d':
                            $company->date_picker_format = 'yyyy-mm-dd';
                            break;
                        case 'd.m.Y':
                            $company->date_picker_format = 'dd.mm.yyyy';
                            break;
                        case 'm.d.Y':
                            $company->date_picker_format = 'mm.dd.yyyy';
                            break;
                        case 'Y.m.d':
                            $company->date_picker_format = 'yyyy.mm.dd';
                            break;
                        case 'd/m/Y':
                            $company->date_picker_format = 'dd/mm/yyyy';
                            break;
                        case 'm/d/Y':
                            $company->date_picker_format = 'mm/dd/yyyy';
                            break;
                        case 'Y/m/d':
                            $company->date_picker_format = 'yyyy/mm/dd';
                            break;
                        case 'd-M-Y':
                            $company->date_picker_format = 'dd-M-yyyy';
                            break;
                        case 'd/M/Y':
                            $company->date_picker_format = 'dd/M/yyyy';
                            break;
                        case 'd.M.Y':
                            $company->date_picker_format = 'dd.M.yyyy';
                            break;
                        case 'd-M-Y':
                            $company->date_picker_format = 'dd-M-yyyy';
                            break;
                        case 'd M Y':
                            $company->date_picker_format = 'dd M yyyy';
                            break;
                        case 'd F, Y':
                            $company->date_picker_format = 'dd MM, yyyy';
                            break;
                        case 'D/M/Y':
                            $company->date_picker_format = 'D/M/yyyy';
                            break;
                        case 'D.M.Y':
                            $company->date_picker_format = 'D.M.yyyy';
                            break;
                        case 'D-M-Y':
                            $company->date_picker_format = 'D-M-yyyy';
                            break;
                        case 'D M Y':
                            $company->date_picker_format = 'D M yyyy';
                            break;
                        case 'd D M Y':
                            $company->date_picker_format = 'dd D M yyyy';
                            break;
                        case 'D d M Y':
                            $company->date_picker_format = 'D dd M yyyy';
                            break;
                        case 'dS M Y':
                            $company->date_picker_format = 'dd M yyyy';
                            break;

                        default:
                            $company->date_picker_format = 'mm/dd/yyyy';
                            break;
                    }
                    $company->save();
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
    }
}
