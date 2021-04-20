<?php

use Illuminate\Database\Seeder;

class ProjectCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = \App\Company::first();

        $category = new \App\ProjectCategory();
        $category->company_id = $company->id;
        $category->category_name = 'Laravel';
        $category->save();

        $category = new \App\ProjectCategory();
        $category->company_id = $company->id;
        $category->category_name = 'Yii';
        $category->save();

        $category = new \App\ProjectCategory();
        $category->company_id = $company->id;
        $category->category_name = 'Zend';
        $category->save();

        $category = new \App\ProjectCategory();
        $category->company_id = $company->id;
        $category->category_name = 'CakePhp';
        $category->save();

        $category = new \App\ProjectCategory();
        $category->company_id = $company->id;
        $category->category_name = 'Codeigniter';
        $category->save();
    }
}
