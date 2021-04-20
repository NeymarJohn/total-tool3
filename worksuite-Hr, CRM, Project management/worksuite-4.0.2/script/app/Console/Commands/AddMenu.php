<?php

namespace App\Console\Commands;

use App\Menu;
use App\MenuSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class AddMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add-menu {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate migration file for menu seeder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $menuSettings = MenuSetting::first();
        $mainMenu = $menuSettings->getOriginal('main_menu');
        $menu = strtolower($this->argument('name'));
        $contents = '<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Menu;
use App\MenuSetting;

class $CLASS$ extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $CONTENT$
    }
}
    ';
        $content = '$menu = Menu::create([
            "menu_name" =>  "'.$menu.'",
            "translate_name" =>  "app.menu.'.$menu.'",
            "route" =>  "admin.'.$menu.'.index",
            "module" =>  "visibleToAll",
            "icon" =>  null,
            "setting_menu" =>  0,
        ]);
        
        $menuSettings = MenuSetting::first();
        $menuSettings->main_menu = \''. $mainMenu.'\';
        $menuSettings->default_main_menu = \''. $menuSettings->default_main_menu.'\';
        $menuSettings->save();
        ';


        $fileName = '_add_'.$menu.'_in_menu_table';

        $contents =  str_replace('$CLASS$', ucfirst(camel_case($fileName)), $contents);
        $contents =  str_replace('$CONTENT$', $content, $contents);

        File::put('database/migrations/'.Carbon::now()->format('Y_m_d').'_'.rand(000000, 999999).$fileName.'.php',$contents);

    }
}
