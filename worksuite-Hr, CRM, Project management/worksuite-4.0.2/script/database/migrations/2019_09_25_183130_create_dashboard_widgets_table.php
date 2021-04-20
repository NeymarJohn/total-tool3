<?php

use App\DashboardWidget;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('widget_name');
            $table->boolean('status');
            $table->timestamps();
        });

        $widgets = [
            ['widget_name' => 'total_clients', 'status' => 1],
            ['widget_name' => 'total_employees', 'status' => 1],
            ['widget_name' => 'total_projects', 'status' => 1],
            ['widget_name' => 'total_unpaid_invoices', 'status' => 1],
            ['widget_name' => 'total_hours_logged', 'status' => 1],
            ['widget_name' => 'total_pending_tasks', 'status' => 1],
            ['widget_name' => 'total_today_attendance', 'status' => 1],
            ['widget_name' => 'total_unresolved_tickets', 'status' => 1],
            ['widget_name' => 'recent_earnings', 'status' => 1],
            ['widget_name' => 'settings_leaves', 'status' => 1],
            ['widget_name' => 'new_tickets', 'status' => 1],
            ['widget_name' => 'overdue_tasks', 'status' => 1],
            ['widget_name' => 'pending_follow_up', 'status' => 1],
            ['widget_name' => 'project_activity_timeline', 'status' => 1],
            ['widget_name' => 'user_activity_timeline', 'status' => 1]
        ];

        foreach ($widgets as $widget) {
            DashboardWidget::create($widget);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dashboard_widgets');
    }
}
