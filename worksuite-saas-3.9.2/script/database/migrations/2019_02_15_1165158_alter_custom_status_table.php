<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TaskboardColumn;

class AlterCustomStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('taskboard_columns', function(Blueprint $table){
            $table->string('slug')->nullable()->default(null)->after('column_name');
        });

        $maxPriority = TaskboardColumn::max('priority');

        $boardColumns = TaskboardColumn::all();
        $completedColumn = $boardColumns->filter(function ($value, $key) {
            return $value->column_name == 'Completed';
        });

        if(count($completedColumn) == 0){
           TaskboardColumn::create([
                'column_name' => 'Completed',
                'slug' => 'completed',
                'label_color' => '#679c0d',
                'priority' => ($maxPriority+1)
            ]);
            $maxPriority = $maxPriority+1;
        }

        $inCompletedColumn = $boardColumns->filter(function ($value, $key) {
            return $value->column_name == 'Incomplete';
        });

        if(count($inCompletedColumn) == 0){
            \App\TaskboardColumn::create([
                'column_name' => 'Incomplete',
                'slug' => 'incomplete',
                'label_color' => '#d21010',
                'priority' => ($maxPriority+1)
            ]);
        }

        foreach($boardColumns as $boardColumn){
            $boardColumn->slug = str_slug($boardColumn->column_name, '_');
            $boardColumn->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taskboard_columns', function(Blueprint $table){
            $table->dropColumn(['slug']);
        });
    }
}
