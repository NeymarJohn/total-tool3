<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('menu_name',100);
            $table->string('translate_name')->nullable();
            $table->string('route',100)->nullable();
            $table->string('module')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('setting_menu')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('main_menu')->nullable();
            $table->longText('default_main_menu')->nullable();
            $table->longText('setting_menu')->nullable();
            $table->longText('default_setting_menu')->nullable();
            $table->timestamps();
        });

        $menus =
            [
                [
                    'menu_name' => 'dashboard',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'icon-speedometer',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'customers',
                    'module' => 'customers',
                    'translate_name' => 'app.menu.customers',
                    'route' => null,
                    'icon' => 'icon-people',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'clients',
                    'module' => 'clients',
                    'translate_name' => 'app.menu.clients',
                    'route' => 'admin.clients.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'leads',
                    'module' => 'leads',
                    'translate_name' => 'app.menu.lead',
                    'route' => 'admin.leads.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'module' => 'hr',
                    'menu_name' => 'hr',
                    'translate_name' => 'app.menu.hr',
                    'route' => null,
                    'icon' => 'ti-user',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'employees',
                    'module' => 'employees',
                    'translate_name' => 'app.menu.employeeList',
                    'route' => 'admin.employees.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'department',
                    'module' => 'employees',
                    'translate_name' => 'app.department',
                    'route' => 'admin.department.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'designation',
                    'module' => 'employees',
                    'translate_name' => 'app.menu.designation',
                    'route' => 'admin.designations.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'attendance',
                    'module' => 'attendance',
                    'translate_name' => 'app.menu.attendance',
                    'route' => 'admin.attendances.summary',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'holidays',
                    'module' => 'holidays',
                    'translate_name' => 'app.menu.holiday',
                    'route' => 'admin.holidays.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'leaves',
                    'module' => 'leaves',
                    'translate_name' => 'app.menu.leaves',
                    'route' => 'admin.leaves.pending',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'work',
                    'module' => 'work',
                    'translate_name' => 'app.menu.work',
                    'route' => null,
                    'icon' => 'icon-layers',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'contracts',
                    'module' => 'contracts',
                    'translate_name' => 'app.menu.contracts',
                    'route' => 'admin.contracts.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'projects',
                    'module' => 'projects',
                    'translate_name' => 'app.menu.projects',
                    'route' => 'admin.projects.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'tasks',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.tasks',
                    'route' => 'admin.all-tasks.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskBoard',
                    'module' => 'tasks',
                    'translate_name' => 'modules.tasks.taskBoard',
                    'route' => 'admin.taskboard.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskCalendar',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.taskCalendar',
                    'route' => 'admin.task-calendar.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'timelogs',
                    'module' => 'timelogs',
                    'translate_name' => 'app.menu.timeLogs',
                    'route' => 'admin.all-time-logs.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],

                [
                    'menu_name' => 'finance',
                    'module' => 'finance',
                    'translate_name' => 'app.menu.finance',
                    'route' => null,
                    'icon' => 'fa fa-money',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'estimates',
                    'module' => 'estimates',
                    'translate_name' => 'app.menu.estimates',
                    'route' => 'admin.estimates.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'invoices',
                    'module' => 'invoices',
                    'translate_name' => 'app.menu.invoices',
                    'route' => 'admin.all-invoices.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'payments',
                    'module' => 'payments',
                    'translate_name' => 'app.menu.payments',
                    'route' => 'admin.payments.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'expenses',
                    'module' => 'expenses',
                    'translate_name' => 'app.menu.expenses',
                    'route' => 'admin.expenses.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'credit-note',
                    'module' => 'invoices',
                    'translate_name' => 'app.menu.credit-note',
                    'route' => 'admin.all-credit-notes.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'products',
                    'module' => 'products',
                    'translate_name' => 'app.menu.products',
                    'route' => 'admin.products.index',
                    'icon' => 'icon-basket',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'tickets',
                    'module' => 'tickets',
                    'translate_name' => 'app.menu.tickets',
                    'route' => 'admin.tickets.index',
                    'icon' => 'ti-ticket',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'messages',
                    'module' => 'messages',
                    'translate_name' => 'app.menu.messages',
                    'route' => 'admin.user-chat.index',
                    'icon' => 'icon-envelope',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'events',
                    'module' => 'events',
                    'translate_name' => 'app.menu.Events',
                    'route' => 'admin.events.index',
                    'icon' => 'icon-calender',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'noticeBoard',
                    'module' => 'notices',
                    'translate_name' => 'app.menu.noticeBoard',
                    'route' => 'admin.notices.index',
                    'icon' => 'ti-layout-media-overlay',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'reports',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.reports',
                    'route' => null,
                    'icon' => 'ti-pie-chart',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'taskReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.taskReport',
                    'route' => 'admin.task-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'timeLogReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.timeLogReport',
                    'route' => 'admin.time-log-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'financeReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.financeReport',
                    'route' => 'admin.finance-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'incomeVsExpenseReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.incomeVsExpenseReport',
                    'route' => 'admin.income-expense-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'leaveReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.leaveReport',
                    'route' => 'admin.leave-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'attendanceReport',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.attendanceReport',
                    'route' => 'admin.attendance-report.index',
                    'icon' => null,
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'settings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.settings',
                    'route' => 'admin.settings.index',
                    'icon' => 'ti-settings',
                    'setting_menu' => 0,
                ],
                [
                    'menu_name' => 'accountSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.accountSettings',
                    'route' => 'admin.settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'profileSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.profileSettings',
                    'route' => 'admin.profile-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'notificationSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.notificationSettings',
                    'route' => 'admin.email-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'emailSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.emailSettings',
                    'route' => 'admin.email-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'slackSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.slackSettings',
                    'route' => 'admin.slack-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'pushNotifications',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.pushNotifications',
                    'route' => 'admin.push-notification-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'pusherSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.pusherSettings',
                    'route' => 'admin.pusher-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'currencySettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.currencySettings',
                    'route' => 'admin.currency.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'paymentGatewayCredential',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.paymentGatewayCredential',
                    'route' => 'admin.payment-gateway-credential.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'onlinePayment',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.onlinePayment',
                    'route' => 'admin.payment-gateway-credential.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'offlinePaymentMethod',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.offlinePaymentMethod',
                    'route' => 'admin.offline-payment-setting.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'financeSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.financeSettings',
                    'route' => 'admin.invoice-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'ticketSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.ticketSettings',
                    'route' => 'admin.ticket-agents.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'ticketAgents',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.ticketAgents',
                    'route' => 'admin.ticket-agents.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'ticketTypes',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.ticketTypes',
                    'route' => 'admin.ticketTypes.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'ticketChannel',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.ticketChannel',
                    'route' => 'admin.ticketChannels.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'replyTemplates',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.replyTemplates',
                    'route' => 'admin.replyTemplates.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'projectSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.projectSettings',
                    'route' => 'admin.project-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'attendanceSettings',
                    'module' => 'attendance',
                    'translate_name' => 'app.menu.attendanceSettings',
                    'route' => 'admin.attendance-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'leaveSettings',
                    'module' => 'leaves',
                    'translate_name' => 'app.menu.leaveSettings',
                    'route' => 'admin.leaves-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'customFields',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.customFields',
                    'route' => 'admin.custom-fields.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'menuSetting',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.menuSetting',
                    'route' => 'admin.menu-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'moduleSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.moduleSettings',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'adminModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.adminModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'employeeModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.employeeModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'clientModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.clientModule',
                    'route' => 'admin.module-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'customModule',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.customModule',
                    'route' => 'admin.custom-modules.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rolesPermission',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rolesPermission',
                    'route' => 'admin.role-permission.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'messageSettings',
                    'module' => 'messages',
                    'translate_name' => 'app.menu.messageSettings',
                    'route' => 'admin.message-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'storageSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.storageSettings',
                    'route' => 'admin.storage-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'languageSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.languageSettings',
                    'route' => 'admin.language-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'leadSettings',
                    'module' => 'leads',
                    'translate_name' => 'app.menu.leadSettings',
                    'route' => 'admin.lead-source-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'leadSource',
                    'module' => 'leads',
                    'translate_name' => 'app.menu.leadSource',
                    'route' => 'admin.lead-source-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'leadStatus',
                    'module' => 'leads',
                    'translate_name' => 'app.menu.leadStatus',
                    'route' => 'admin.lead-status-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'leadAgent',
                    'module' => 'leads',
                    'translate_name' => 'modules.lead.leadAgent',
                    'route' => 'admin.lead-agent-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'timeLogSettings',
                    'module' => 'timelogs',
                    'translate_name' => 'app.menu.timeLogSettings',
                    'route' => 'admin.log-time-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'taskSettings',
                    'module' => 'tasks',
                    'translate_name' => 'app.menu.taskSettings',
                    'route' => 'admin.task-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'gdpr',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.gdpr',
                    'route' => 'admin.gdpr.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'general',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.general',
                    'route' => 'admin.gdpr.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rightToDataPortability',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rightToDataPortability',
                    'route' => 'admin.gdpr.right-to-data-portability',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rightToErasure',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rightToErasure',
                    'route' => 'admin.gdpr.right-to-erasure',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rightToBeInformed',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rightToBeInformed',
                    'route' => 'admin.gdpr.right-to-informed',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'rightOfRectification',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.rightOfRectification',
                    'route' => 'admin.gdpr.right-of-access',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'consent',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.consent',
                    'route' => 'admin.gdpr.consent',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'updates',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.updates',
                    'route' => 'admin.update-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
                [
                    'menu_name' => 'themeSettings',
                    'module' => 'visibleToAll',
                    'translate_name' => 'app.menu.themeSettings',
                    'route' => 'admin.theme-settings.index',
                    'icon' => null,
                    'setting_menu' => 1,
                ],
            ];

        foreach($menus as $menu) {
            $menuData = new \App\Menu();
            $menuData->menu_name = $menu['menu_name'];
            $menuData->translate_name = $menu['translate_name'];
            $menuData->route = $menu['route'];
            $menuData->module = $menu['module'];
            $menuData->icon = $menu['icon'];
            $menuData->setting_menu = $menu['setting_menu'];
            $menuData->save();
        }

        $mainMenu = [
            ['id' => 1],
            [
                'id' => 2,
                'children' => [
                    ['id' => 3],
                    ['id' => 4],
                ]
            ],
            [
                'id' => 5,
                'children' => [
                    ['id' => 6],
                    ['id' => 7],
                    ['id' => 8],
                    ['id' => 9],
                    ['id' => 10],
                    ['id' => 11],
                ]
            ],

            [
                'id' => 12,
                'children' => [
                    ['id' => 13],
                    ['id' => 14],
                    ['id' => 15],
                    ['id' => 16],
                    ['id' => 17],
                    ['id' => 18],
                ]
            ],

            [
                'id' => 19,
                'children' => [
                    ['id' => 20],
                    ['id' => 21],
                    ['id' => 22],
                    ['id' => 23],
                    ['id' => 24],
                ]
            ],
            ['id' => 25],
            ['id' => 26],
            ['id' => 27],
            ['id' => 28],
            ['id' => 29],
            [
                'id' => 30,
                'children' => [
                    ['id' => 31],
                    ['id' => 32],
                    ['id' => 33],
                    ['id' => 34],
                    ['id' => 35],
                    ['id' => 36],
                ]
            ],
            ['id' => 37],
        ];

        $settingMenu = [
            ['id' => 38],
            ['id' => 39],
            [
                'id' => 40,
                'children' => [
                    ['id' => 41],
                    ['id' => 42],
                    ['id' => 43],
                    ['id' => 44],
                ]
            ],
            ['id' => 45],
            [
                'id' => 46,
                'children' => [
                    ['id' => 47],
                    ['id' => 48],
                ]
            ],
            ['id' => 49],
            [
                'id' => 50,
                'children' => [
                    ['id' => 51],
                    ['id' => 52],
                    ['id' => 53],
                    ['id' => 54]
                ]
            ],
            ['id' => 55],
            ['id' => 56],
            ['id' => 57],
            ['id' => 58],
            ['id' => 59],
            [
                'id' => 60,
                'children' => [
                    ['id' => 61],
                    ['id' => 62],
                    ['id' => 63],
                    ['id' => 64]
                ]
            ],
            ['id' => 65],
            ['id' => 66],
            ['id' => 67],
            ['id' => 68],
            [
                'id' => 69,
                'children' => [
                    ['id' => 70],
                    ['id' => 71],
                    ['id' => 72]
                ]
            ],
            ['id' => 73],
            ['id' => 74],
            [
                'id' => 75,
                'children' => [
                    ['id' => 76],
                    ['id' => 77],
                    ['id' => 78],
                    ['id' => 79],
                    ['id' => 80],
                    ['id' => 81],
                ]
            ],
            ['id' => 82],
            ['id' => 83],
        ];

        $menuSetting = new \App\MenuSetting();
        $menuSetting->main_menu = json_encode($mainMenu);
        $menuSetting->default_main_menu = json_encode($mainMenu);
        $menuSetting->setting_menu = json_encode($settingMenu);
        $menuSetting->default_setting_menu = json_encode($settingMenu);
        $menuSetting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
