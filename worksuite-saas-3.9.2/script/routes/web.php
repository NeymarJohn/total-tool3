<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::post('/consent/remove-lead-request', ['uses' => 'PublicLeadGdprController@removeLeadRequest'])->name('front.gdpr.remove-lead-request');
Route::post('/consent/l/update/{lead}', ['uses' => 'PublicLeadGdprController@updateConsent'])->name('front.gdpr.consent.update');
Route::post('/consent/l/update/{lead}', ['uses' => 'PublicLeadGdprController@updateConsent'])->name('front.gdpr.consent.update');
Route::get('/consent/l/{lead}', ['uses' => 'PublicLeadGdprController@consent'])->name('front.gdpr.consent');
Route::post('/forms/l/update/{lead}', ['uses' => 'PublicLeadGdprController@updateLead'])->name('front.gdpr.lead.update');
Route::get('/forms/l/{lead}', ['uses' => 'PublicLeadGdprController@lead'])->name('front.gdpr.lead');
Route::get('/contract/{id}', ['uses' => 'Front\PublicUrlController@contractView'])->name('front.contract.show');
Route::get('/contract/download/{id}', ['uses' => 'Front\PublicUrlController@contractDownload'])->name('front.contract.download');
Route::get('contract/sign-modal/{id}', ['uses' => 'Front\PublicUrlController@contractSignModal'])->name('front.contract.sign-modal');
Route::post('contract/sign/{id}', ['uses' => 'Front\PublicUrlController@contractSign'])->name('front.contract.sign');
Route::get('/estimate/{id}', ['uses' => 'Front\PublicUrlController@estimateView'])->name('front.estimate.show');
Route::post('/estimate/decline/{id}', ['uses' => 'Front\PublicUrlController@decline'])->name('front.estimate.decline');
Route::get('/estimate/accept/{id}', ['uses' => 'Front\PublicUrlController@acceptModal'])->name('front.estimate.accept');
Route::post('/estimate/accept/{id}', ['uses' => 'Front\PublicUrlController@accept'])->name('front.accept-estimate');
Route::get('/estimate/download/{id}', ['uses' => 'Front\PublicUrlController@estimateDownload'])->name('front.estimateDownload');
Route::post('/invoices/stripe-modal/', ['uses' => 'Front\HomeController@stripeModal'])->name('front.stripe-modal');
Route::get('/invoice/download/{id}', ['uses' => 'Front\HomeController@downloadInvoice'])->name('front.invoiceDownload');
Route::get('/task-files/{id}', ['uses' => '\App\Http\Controllers\Front\HomeController@taskFiles'])->name('front.task-files');
Route::get('/task-share/{id}', ['uses' => '\App\Http\Controllers\Front\HomeController@taskShare'])->name('front.task-share');
Route::get('/invoice/{id}', ['uses' => '\App\Http\Controllers\Front\HomeController@invoice'])->name('front.invoice');
Route::get('/', ['uses' => '\App\Http\Controllers\Front\HomeController@index'])->name('front.home')->middleware('disable-frontend');
Route::get('page/{slug?}', ['uses' => '\App\Http\Controllers\Front\HomeController@page'])->name('front.page');
Route::get('/gantt-chart-data/{id}', ['uses' => 'Front\HomeController@ganttData'])->name('front.gantt-data');
Route::get('/gantt-chart/{id}', ['uses' => 'Front\HomeController@gantt'])->name('front.gantt');
Route::get('/lead-form/{id}', ['uses' => 'Front\HomeController@leadForm'])->name('front.leadForm');
Route::get('/ticket-form/{id}', ['uses' => 'Front\HomeController@ticketForm'])->name('front.ticketForm');
Route::post('/lead-form/leadStore', ['uses' => 'Front\HomeController@leadStore'])->name('front.leadStore');
Route::get('/proposal/{id}', ['uses' => 'Front\HomeController@proposal'])->name('front.proposal');
Route::get('/proposal/download/{id}', ['uses' => 'Front\HomeController@downloadProposal'])->name('front.download-proposal');
Route::get('/proposal-action/{id}', ['uses' => 'Front\HomeController@proposalAction'])->name('front.proposal-action');
Route::post('/proposal-action-post/{id}', ['uses' => 'Front\HomeController@proposalActionStore'])->name('front.proposal-action-post');
Route::post('/ticket-form/ticketStore', ['uses' => 'Front\HomeController@ticketStore'])->name('front.ticketStore');
Route::post('public/pay-with-razorpay', array('as' => 'public.pay-with-razorpay', 'uses' => 'Client\RazorPayController@payWithRazorPay',));
Route::get('/email-verification/{code}', '\App\Http\Controllers\Front\RegisterController@getEmailVerification')->name('front.get-email-verification');
Route::group(
    ['namespace' => 'Front', 'as' => 'front.', 'middleware' => 'disable-frontend'],
    function () {
        Route::post('/contact-us', 'HomeController@contactUs')->name('contact-us');
        Route::get('/contact', 'HomeController@contact')->name('contact');
        Route::resource('/signup', 'RegisterController', ['only' => ['index', 'store']]);
        Route::get('/features', ['uses' => 'HomeController@feature'])->name('feature');
        Route::get('/pricing', ['uses' => 'HomeController@pricing'])->name('pricing');
        Route::get('language/{lang}', ['as' => 'language.lang', 'uses' => 'HomeController@changeLanguage']);
    }
);
//Route::get('/mollie/payment/callback', 'MollieController@handleGatewayCallback')->name('payments.mollie.callback');

Route::group(
    ['namespace' => 'Client', 'prefix' => 'client', 'as' => 'client.'],
    function () {

        Route::post('stripe/{invoiceId}', array('as' => 'stripe', 'uses' => 'StripeController@paymentWithStripe',));
        Route::post('stripe-public/{invoiceId}', array('as' => 'stripe-public', 'uses' => 'StripeController@paymentWithStripePublic',));
        // route for post request
        Route::get('paypal-public/{invoiceId}', array('as' => 'paypal-public', 'uses' => 'PaypalController@paymentWithpaypalPublic',));
        Route::get('paypal/{invoiceId}', array('as' => 'paypal', 'uses' => 'PaypalController@paymentWithpaypal',));
        // route for check status responce
        Route::get('paypal', array('as' => 'status', 'uses' => 'PaypalController@getPaymentStatus',));
        Route::get('paypal-recurring', array('as' => 'paypal-recurring', 'uses' => 'PaypalController@payWithPaypalRecurrring',));

        //paystack payment
        Route::get('paystack-public/{invoiceId}', array('as' => 'paystack-public', 'uses' => 'PaystackController@redirectToGateway',));
        Route::get('/paystack/callback', 'PaystackController@handleGatewayCallback')->name('paystack.callback');

        //mollie payment
        Route::get('mollie-public/{invoiceId}', array('as' => 'mollie-public', 'uses' => 'MollieController@redirectToGateway',));
        Route::get('/mollie/callback', 'MollieController@handleGatewayCallback')->name('mollie.callback');

        //Authorize.net payment
        Route::post('/checkout/pay/submit', 'AuthorizeController@handleOnlinePay')->name('authorize.pay-submit');
    }
);

//Paypal IPN
Route::post('verify-ipn', array('as' => 'verify-ipn', 'uses' => 'PaypalIPNController@verifyIPN'));
Route::post('verify-billing-ipn', array('as' => 'verify-billing-ipn', 'uses' => 'PaypalIPNController@verifyBillingIPN'));
Route::post('/verify-webhook', ['as' => 'verify-webhook', 'uses' => 'StripeWebhookController@verifyStripeWebhook']);
Route::post('/save-invoices', ['as' => 'save_webhook', 'uses' => 'StripeWebhookController@saveInvoices']);
Route::post('/save-razorpay-invoices', ['as' => 'save_razorpay-webhook', 'uses' => 'RazorpayWebhookController@saveInvoices']);
Route::get('/check-razorpay-invoices', ['as' => 'check_razorpay-webhook', 'uses' => 'RazorpayWebhookController@checkInvoices']);

Route::post('/save-paystack-invoices', ['as' => 'save_paystack-webhook', 'uses' => 'PaystackWebhookController@saveInvoices']);

// Authorize.net webhook
Route::any('/save-authorize-invoices', ['as' => 'save_authorize_webhook', 'uses' => '\App\Http\Controllers\Webhook\AuthorizeWebhookController@saveInvoices']);

// Social Auth
Route::get('/redirect/{provider}', ['uses' => 'Auth\LoginController@redirect', 'as' => 'social.login']);
Route::get('/callback/{provider}', ['uses' => 'Auth\LoginController@callback', 'as' => 'social.login-callback']);

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {

    // Super admin routes
    Route::group(
        ['namespace' => 'SuperAdmin', 'prefix' => 'super-admin', 'as' => 'super-admin.', 'middleware' => ['super-admin']],
        function () {

            Route::get('/dashboard', 'SuperAdminDashboardController@index')->name('dashboard');
            Route::get('/dashboard/stripe-pop-up-close', 'SuperAdminDashboardController@stripePopUpClose')->name('dashboard.stripe-pop-up-close');
            Route::post('profile/updateOneSignalId', ['uses' => 'SuperAdminProfileController@updateOneSignalId'])->name('profile.updateOneSignalId');
            Route::resource('/profile', 'SuperAdminProfileController', ['only' => ['index', 'update']]);

            // Faq routes
            Route::post('faq/file-store', ['uses' => 'SuperAdminFaqController@fileStore'])->name('faq.file-store');
            Route::post('faq/file-destroy/{id}', ['uses' => 'SuperAdminFaqController@fileDelete'])->name('faq.file-destroy');
            Route::get('faq/download/{id}', ['uses' => 'SuperAdminFaqController@download'])->name('faq.download');
            Route::get('faq/data', ['uses' => 'SuperAdminFaqController@data'])->name('faq.data');
            Route::resource('/faq', 'SuperAdminFaqController');

            // Faq Category routes
            Route::get('faq-category/data', ['uses' => 'SuperAdminFaqCategoryController@data'])->name('faq-category.data');

            Route::resource('/faq-category', 'SuperAdminFaqCategoryController');

            // Packages routes
            Route::get('packages/data', ['uses' => 'SuperAdminPackageController@data'])->name('packages.data');
            Route::resource('/packages', 'SuperAdminPackageController');

            // Companies routes
            Route::get('companies/data', ['uses' => 'SuperAdminCompanyController@data'])->name('companies.data');
            Route::get('companies/editPackage/{companyId}', ['uses' => 'SuperAdminCompanyController@editPackage'])->name('companies.edit-package.get');
            Route::get('companies/default-language/', ['uses' => 'SuperAdminCompanyController@defaultLanguage'])->name('companies.default-language');
            Route::post('companies/default-language-save/', ['uses' => 'SuperAdminCompanyController@defaultLanguageUpdate'])->name('companies.default-language-save');
            Route::put('companies/editPackage/{companyId}', ['uses' => 'SuperAdminCompanyController@updatePackage'])->name('companies.edit-package.post');
            Route::post('companies/verify-user', ['uses' => 'SuperAdminCompanyController@verifyUser'])->name('companies.verifyUser');
            Route::post('/companies', ['uses' => 'SuperAdminCompanyController@store']);
            Route::post('/companies/{id}/login', 'SuperAdminCompanyController@loginAsCompany')->name('companies.loginAsCompany');

            Route::resource('/companies', 'SuperAdminCompanyController');
            Route::get('invoices/data', ['uses' => 'SuperAdminInvoiceController@data'])->name('invoices.data');
            Route::resource('/invoices', 'SuperAdminInvoiceController', ['only' => ['index']]);
            Route::get('paypal-invoice-download/{id}', array('as' => 'paypal.invoice-download', 'uses' => 'SuperAdminInvoiceController@paypalInvoiceDownload',));
            Route::get('billing/invoice-download/{invoice}', 'SuperAdminInvoiceController@download')->name('stripe.invoice-download');
            Route::get('billing/razorpay-download/{invoice}', 'SuperAdminInvoiceController@razorpayInvoiceDownload')->name('razorpay.invoice-download');
            Route::get('billing/offline-download/{invoice}', 'SuperAdminInvoiceController@offlineInvoiceDownload')->name('offline.invoice-download');
            Route::get('billing/paystack-download/{id}', 'SuperAdminInvoiceController@paystackInvoiceDownload')->name('paystack.invoice-download');
            Route::get('billing/mollie-download/{id}', 'SuperAdminInvoiceController@mollieInvoiceDownload')->name('mollie.invoice-download');
            Route::get('billing/authorize-download/{id}', 'SuperAdminInvoiceController@authorizeInvoiceDownload')->name('authorize.invoice-download');

            // Storage settings


            Route::resource('/settings', 'SuperAdminSettingsController', ['only' => ['index', 'update']]);

            Route::get('super-admin/data', ['uses' => 'SuperAdminController@data'])->name('super-admin.data');
            Route::resource('/super-admin', 'SuperAdminController');

            Route::get('offline-plan/data', ['uses' => 'OfflinePlanChangeController@data'])->name('offline-plan.data');
            Route::post('offline-plan/verify', ['uses' => 'OfflinePlanChangeController@verify'])->name('offline-plan.verify');
            Route::post('offline-plan/reject', ['uses' => 'OfflinePlanChangeController@reject'])->name('offline-plan.reject');
            Route::resource('/offline-plan', 'OfflinePlanChangeController', ['only' => ['index', 'update']]);

            Route::get('support-ticketTypes/createModal', ['uses' => 'SupportTicketTypesController@createModal'])->name('support-ticketTypes.createModal');
            Route::resource('support-ticketTypes', 'SupportTicketTypesController');

            //Support Ticket routes
            Route::get('support-tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'SupportTicketsController@export'])->name('support-tickets.export');
            Route::get('support-tickets/reply-delete/{id?}', ['uses' => 'SupportTicketsController@destroyReply'])->name('support-tickets.reply-delete');
            Route::post('support-tickets/updateOtherData/{id}', ['uses' => 'SupportTicketsController@updateOtherData'])->name('support-tickets.updateOtherData');
            Route::resource('support-tickets', 'SupportTicketsController');

            // Support ticket file routes
            Route::get('support-ticket-files/download/{id}', ['uses' => 'SupportTicketFilesController@download'])->name('support-ticket-files.download');
            Route::resource('support-ticket-files', 'SupportTicketFilesController');

            Route::group(
                ['prefix' => 'front-settings'],
                function () {

                    Route::get('front-theme-settings', ['uses' => 'SuperAdminFrontSettingController@themeSetting'])->name('theme-settings');
                    Route::post('front-theme-update', ['uses' => 'SuperAdminFrontSettingController@themeUpdate'])->name('theme-update');
                    Route::get('auth-settings', ['uses' => 'SuperAdminFrontSettingController@authSetting'])->name('auth-settings');
                    Route::post('auth-update', ['uses' => 'SuperAdminFrontSettingController@authUpdate'])->name('auth-update');
                    Route::post('front-settings/update-detail', 'SuperAdminFrontSettingController@updateDetail')->name('front-settings.updateDetail');
                    Route::get('front-settings/change-form', 'SuperAdminFrontSettingController@changeForm')->name('front-settings.changeForm');
                    Route::resource('front-settings', 'SuperAdminFrontSettingController', ['only' => ['index', 'update']]);
                    Route::resource('seo-detail', 'SuperAdminSeoDetailController', ['only' => ['edit', 'update', 'index']]);

                    Route::get('feature-settings/change-form', ['uses' => 'SuperAdminFeatureSettingController@changeForm'])->name('feature-settings.changeForm');
                    Route::post('feature-settings/title-update', ['uses' => 'SuperAdminFeatureSettingController@updateTitles'])->name('feature-settings.title-update');
                    Route::resource('feature-settings', 'SuperAdminFeatureSettingController');

                    Route::resource('front-feature-settings', 'FrontFeatureSettingController');

                    Route::get('testimonial-settings/change-form', ['uses' => 'TestimonialSettingController@changeForm'])->name('testimonial-settings.changeForm');
                    Route::post('testimonial-settings/title-update', ['uses' => 'TestimonialSettingController@updateTitles'])->name('testimonial-settings.title-update');
                    Route::resource('testimonial-settings', 'TestimonialSettingController');

                    Route::get('client-settings/change-form', ['uses' => 'FrontClientSettingController@changeForm'])->name('client-settings.changeForm');
                    Route::post('client-settings/title-update', ['uses' => 'FrontClientSettingController@updateTitles'])->name('client-settings.title-update');
                    Route::resource('client-settings', 'FrontClientSettingController');

                    Route::get('faq-settings/change-form', ['uses' => 'FrontFaqSettingController@changeForm'])->name('faq-settings.changeForm');
                    Route::post('faq-settings/title-update', ['uses' => 'FrontFaqSettingController@updateTitles'])->name('faq-settings.title-update');
                    Route::resource('faq-settings', 'FrontFaqSettingController');


                    Route::get('cta-settings/change-form', 'CtaSettingController@changeForm')->name('cta-settings.changeForm');
                    Route::post('cta-settings/title-update', 'CtaSettingController@updateTitles')->name('cta-settings.title-update');
                    Route::resource('cta-settings', 'CtaSettingController', ['only' => ['index', 'update']]);

                    Route::get('front-menu-settings/change-form', 'FrontMenuSettingController@changeForm')->name('front-menu-settings.changeForm');
                    Route::post('front-menu-settings/title-update', 'FrontMenuSettingController@updateTitles')->name('front-menu-settings.title-update');
                    Route::resource('front-menu-settings', 'FrontMenuSettingController', ['only' => ['index', 'update']]);

                    Route::get('footer-settings/change-footer-text-form', ['uses' => 'SuperAdminFooterSettingController@changeFooterTextForm'])->name('footer-settings.changeFooterTextForm');
                    Route::get('footer-settings/footer-text}', ['uses' => 'SuperAdminFooterSettingController@footerText'])->name('footer-settings.footer-text');
                    Route::post('footer-settings/copyright-text', ['uses' => 'SuperAdminFooterSettingController@updateText'])->name('footer-settings.copyright-text');
                    Route::post('footer-settings/video-upload', ['uses' => 'SuperAdminFooterSettingController@videoUpload'])->name('footer-settings.video-upload');
                    Route::resource('footer-settings', 'SuperAdminFooterSettingController');

                    Route::get('price-settings/change-price-form', ['uses' => 'SuperAdminFrontSettingController@changePriceForm'])->name('price-settings.changePriceForm');
                    Route::post('price-settings-update', ['uses' => 'SuperAdminFrontSettingController@priceUpdate'])->name('price-setting-update');
                    Route::get('price-settings', ['uses' => 'SuperAdminFrontSettingController@price'])->name('price-settings');

                    Route::post('contactus-setting-update', ['uses' => 'SuperAdminFrontSettingController@contactUpdate'])->name('contactus-setting-update');
                    Route::get('contact-settings', ['uses' => 'SuperAdminFrontSettingController@contact'])->name('contact-settings');

                    Route::resource('front-widgets', 'FrontWidgetsController');
                }
            );
            Route::group(
                ['prefix' => 'settings'],
                function () {
                    Route::get('email-settings/sent-test-email', ['uses' => 'SuperAdminEmailSettingsController@sendTestEmail'])->name('email-settings.sendTestEmail');
                    Route::resource('/email-settings', 'SuperAdminEmailSettingsController', ['only' => ['index', 'update']]);
                    Route::resource('/security-settings', 'SuperAdminSecuritySettingsController');
                    Route::post('security-settings/show-modal', 'SuperAdminSecuritySettingsController@showModal')->name('security-settings.show-modal');
                    Route::post('/stripe-method-change', 'SuperAdminStripeSettingsController@changePaymentMethod')->name('stripe.method-change');
                    Route::get('offline-payment-setting/createModal', ['uses' => 'OfflinePaymentSettingController@createModal'])->name('offline-payment-setting.createModal');
                    Route::get('offline-payment/method', ['uses' => 'OfflinePaymentSettingController@offlinePaymentMethod'])->name('offline-payment-method.create');
                    Route::resource('offline-payment-setting', 'OfflinePaymentSettingController');
                    Route::resource('/payment-settings', 'SuperAdminStripeSettingsController', ['only' => ['index', 'update']]);

                    //
                    Route::resource('/social-auth-settings', 'SuperAdminSocialAuthSettingsController', ['only' => ['index', 'update']]);

                    Route::get('push-notification-settings/sent-test-notification', ['uses' => 'SuperAdminPushSettingsController@sendTestEmail'])->name('push-notification-settings.sendTestEmail');
                    Route::get('push-notification-settings/sendTestNotification', ['uses' => 'SuperAdminPushSettingsController@sendTestNotification'])->name('push-notification-settings.sendTestNotification');
                    Route::resource('/push-notification-settings', 'SuperAdminPushSettingsController', ['only' => ['index', 'update']]);

                    Route::get('currency/exchange-key', ['uses' => 'SuperAdminCurrencySettingController@currencyExchangeKey'])->name('currency.exchange-key');
                    Route::post('currency/exchange-key-store', ['uses' => 'SuperAdminCurrencySettingController@currencyExchangeKeyStore'])->name('currency.exchange-key-store');
                    Route::resource('currency', 'SuperAdminCurrencySettingController');
                    Route::get('currency/exchange-rate/{currency}', ['uses' => 'SuperAdminCurrencySettingController@exchangeRate'])->name('currency.exchange-rate');
                    Route::get('currency/update/exchange-rates', ['uses' => 'SuperAdminCurrencySettingController@updateExchangeRate'])->name('currency.update-exchange-rates');
                    Route::resource('currency', 'SuperAdminCurrencySettingController');

                    Route::post('update-settings/deleteFile', ['uses' => 'UpdateDatabaseController@deleteFile'])->name('update-settings.deleteFile');
                    Route::get('update-settings/install', ['uses' => 'UpdateDatabaseController@install'])->name('update-settings.install');
                    Route::get('update-settings/manual-update', ['uses' => 'UpdateDatabaseController@manual'])->name('update-settings.manual');
                    Route::resource('update-settings', 'UpdateDatabaseController');

                    Route::post('storage-settings-awstest', ['uses' => 'StorageSettingsController@awsTest'])->name('storage-settings.awstest');
                    Route::resource('storage-settings', 'StorageSettingsController');

                    // Language Settings
                    Route::post('language-settings/update-data/{id?}', ['uses' => 'SuperAdminLanguageSettingsController@updateData'])->name('language-settings.update-data');
                    Route::resource('language-settings', 'SuperAdminLanguageSettingsController');

                    Route::resource('package-settings', 'SuperAdminPackageSettingController', ['only' => ['index', 'update']]);

                    // Custom Modules
                    Route::post('custom-modules/verify-purchase', ['uses' => 'CustomModuleController@verifyingModulePurchase'])->name('custom-modules.verify-purchase');
                    Route::resource('custom-modules', 'CustomModuleController');


                    Route::post('theme-settings/activeTheme', ['uses' => 'SuperAdminThemeSettingsController@activeTheme'])->name('theme-settings.activeTheme');
                    Route::resource('theme-settings', 'SuperAdminThemeSettingsController');
                }
            );
        }
    );
    // Admin routes
    Route::group(
        ['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin']],
        function () {
            Route::group(['middleware' => ['account-setup', 'license-expire']], function () {
                Route::get('/dashboard', 'AdminDashboardController@index')->name('dashboard');
                Route::get('/dashboard/stripe-pop-up-close', 'AdminDashboardController@stripePopUpClose')->name('dashboard.stripe-pop-up-close');
                //                Route::post('/dashboard/widget', 'AdminDashboardController@widget')->name('dashboard.widget');
                Route::get('/client-dashboard', 'AdminDashboardController@clientDashboard')->name('clientDashboard');
                Route::get('/finance-dashboard', 'AdminDashboardController@financeDashboard')->name('financeDashboard');
                Route::get('/finance-dashboard/estimate', 'AdminDashboardController@financeDashboardEstimate')->name('financeDashboardEstimate');
                Route::get('/finance-dashboard/invoice', 'AdminDashboardController@financeDashboardInvoice')->name('financeDashboardInvoice');
                Route::get('/finance-dashboard/expense', 'AdminDashboardController@financeDashboardExpense')->name('financeDashboardExpense');
                Route::get('/finance-dashboard/payment', 'AdminDashboardController@financeDashboardPayment')->name('financeDashboardPayment');
                Route::get('/finance-dashboard/proposal', 'AdminDashboardController@financeDashboardProposal')->name('financeDashboardProposal');
                Route::get('/hr-dashboard', 'AdminDashboardController@hrDashboard')->name('hrDashboard');
                Route::get('/project-dashboard', 'AdminDashboardController@projectDashboard')->name('projectDashboard');
                Route::get('/ticket-dashboard', 'AdminDashboardController@ticketDashboard')->name('ticketDashboard');
                Route::post('/dashboard/widget/{dashboardType}', 'AdminDashboardController@widget')->name('dashboard.widget');


                Route::get('designations/quick-create', ['uses' => 'ManageDesignationController@quickCreate'])->name('designations.quick-create');
                Route::post('designations/quick-store', ['uses' => 'ManageDesignationController@quickStore'])->name('designations.quick-store');
                Route::resource('designations', 'ManageDesignationController');


                // FAQ
                Route::get('faqs/{id}', ['uses' => 'FaqController@details'])->name('faqs.details');
                Route::get('faqs', ['uses' => 'FaqController@index'])->name('faqs.index');

                // Employee Faq routes
                Route::post('employee-faq/file-store', ['uses' => 'AdminEmployeeFaqController@fileStore'])->name('employee-faq.file-store');
                Route::post('employee-faq/file-destroy/{id}', ['uses' => 'AdminEmployeeFaqController@fileDelete'])->name('employee-faq.file-destroy');
                Route::get('employee-faq/download/{id}', ['uses' => 'AdminEmployeeFaqController@download'])->name('employee-faq.download');

                Route::get('employee-faq/data', ['uses' => 'AdminEmployeeFaqController@data'])->name('employee-faq.data');
                Route::resource('/employee-faq', 'AdminEmployeeFaqController');

                // Faq Category routes
                Route::get('employee-faq-category/data', ['uses' => 'AdminEmployeeFaqCategoryController@data'])->name('employee-faq-category.data');

                Route::resource('/employee-faq-category', 'AdminEmployeeFaqCategoryController');


                Route::get('clients/export/{status?}/{client?}', ['uses' => 'ManageClientsController@export'])->name('clients.export');
                Route::get('clients/create/{clientID?}', ['uses' => 'ManageClientsController@create'])->name('clients.create');
                Route::resource('clients', 'ManageClientsController', ['except' => ['create']]);
                Route::post('clients/getSubcategory', ['uses' => 'ManageClientsController@getSubcategory'])->name('clients.getSubcategory');

                Route::get('leads/kanban-board', ['uses' => 'LeadController@kanbanboard'])->name('leads.kanbanboard');
                Route::get('leads/kanban-board', ['uses' => 'LeadController@kanbanboard'])->name('leads.kanbanboard');
                Route::get('leads/gdpr/{leadID}', ['uses' => 'LeadController@gdpr'])->name('leads.gdpr');
                Route::get('leads/export/{followUp?}/{client?}', ['uses' => 'LeadController@export'])->name('leads.export');
                Route::post('leads/change-status', ['uses' => 'LeadController@changeStatus'])->name('leads.change-status');
                Route::get('leads/follow-up/{leadID}', ['uses' => 'LeadController@followUpCreate'])->name('leads.follow-up');
                Route::get('leads/followup/{leadID}', ['uses' => 'LeadController@followUpShow'])->name('leads.followup');
                Route::post('leads/follow-up-store', ['uses' => 'LeadController@followUpStore'])->name('leads.follow-up-store');
                Route::get('leads/follow-up-edit/{id?}', ['uses' => 'LeadController@editFollow'])->name('leads.follow-up-edit');
                Route::post('leads/follow-up-update', ['uses' => 'LeadController@UpdateFollow'])->name('leads.follow-up-update');
                Route::post('leads/follow-up-delete/{id}', ['uses' => 'LeadController@deleteFollow'])->name('leads.follow-up-delete');
                Route::get('leads/follow-up-sort', ['uses' => 'LeadController@followUpSort'])->name('leads.follow-up-sort');
                Route::post('leads/save-consent-purpose-data/{lead}', ['uses' => 'LeadController@saveConsentLeadData'])->name('leads.save-consent-purpose-data');
                Route::get('leads/consent-purpose-data/{lead}', ['uses' => 'LeadController@consentPurposeData'])->name('leads.consent-purpose-data');
                Route::post('leads/updateIndex', ['as' => 'leads.updateIndex', 'uses' => 'LeadController@updateIndex']);
                Route::resource('leads', 'LeadController');

                Route::post('lead-form/sortFields', ['as' => 'lead-form.sortFields', 'uses' => 'LeadCustomFormController@sortFields']);
                Route::resource('lead-form', 'LeadCustomFormController');
                Route::get('leadCategory/create-cat', ['uses' => 'LeadCategoryController@createCat'])->name('leadCategory.create-cat');
                Route::post('leadCategory/store-cat', ['uses' => 'LeadCategoryController@storeCat'])->name('leadCategory.store-cat');
                Route::resource('leadCategory', 'LeadCategoryController');
                Route::resource('events-type', 'EventTypeController');
                Route::resource('events-category', 'EventCategoryController');

                Route::get('clientCategory/create-cat', ['uses' => 'ClientCategoryController@createCat'])->name('clientCategory.create-cat');
                Route::post('clientCategory/store-cat', ['uses' => 'ClientCategoryController@storeCat'])->name('clientCategory.store-cat');
                //subcategory
                Route::resource('clientSubCategory', 'ClientSubCategoryController');
                Route::resource('clientCategory', 'ClientCategoryController');
                // Lead Files
                Route::get('lead-files/download/{id}', ['uses' => 'LeadFilesController@download'])->name('lead-files.download');
                Route::get('lead-files/thumbnail', ['uses' => 'LeadFilesController@thumbnailShow'])->name('lead-files.thumbnail');
                Route::resource('lead-files', 'LeadFilesController');

                // Proposal routes
                Route::get('proposals/data/{id?}', ['uses' => 'ProposalController@data'])->name('proposals.data');
                Route::get('proposals/download/{id}', ['uses' => 'ProposalController@download'])->name('proposals.download');
                Route::get('proposals/create/{leadID?}', ['uses' => 'ProposalController@create'])->name('proposals.create');
                Route::get('proposals/send/{id?}', ['uses' => 'ProposalController@sendProposal'])->name('proposals.send');
                Route::get('proposals/convert-proposal/{id?}', ['uses' => 'ProposalController@convertProposal'])->name('proposals.convert-proposal');

                Route::get('proposals/send/{id?}', ['uses' => 'ProposalController@sendProposal'])->name('proposals.send');
                Route::resource('proposals', 'ProposalController', ['except' => ['create']]);

                // Holidays
                Route::get('holidays/calendar-month', 'HolidaysController@getCalendarMonth')->name('holidays.calendar-month');
                Route::get('holidays/view-holiday/{year?}', 'HolidaysController@viewHoliday')->name('holidays.view-holiday');
                Route::get('holidays/mark_sunday', 'HolidaysController@Sunday')->name('holidays.mark-sunday');
                Route::get('holidays/calendar/{year?}', 'HolidaysController@holidayCalendar')->name('holidays.calendar');
                Route::get('holidays/mark-holiday', 'HolidaysController@markHoliday')->name('holidays.mark-holiday');
                Route::post('holidays/mark-holiday-store', 'HolidaysController@markDayHoliday')->name('holidays.mark-holiday-store');
                Route::resource('holidays', 'HolidaysController');

                Route::get('/impersonate/stop', 'AdminProfileSettingsController@stopImpersonate')->name('impersonate.stop');

                Route::group(
                    ['prefix' => 'employees'],
                    function () {

                        Route::get('employees/free-employees', ['uses' => 'ManageEmployeesController@freeEmployees'])->name('employees.freeEmployees');
                        Route::get('employees/docs-create/{id}', ['uses' => 'ManageEmployeesController@docsCreate'])->name('employees.docs-create');
                        Route::get('employees/tasks/{userId}/{hideCompleted}', ['uses' => 'ManageEmployeesController@tasks'])->name('employees.tasks');
                        Route::get('employees/time-logs/{userId}', ['uses' => 'ManageEmployeesController@timeLogs'])->name('employees.time-logs');
                        Route::get('employees/export/{status?}/{employee?}/{role?}', ['uses' => 'ManageEmployeesController@export'])->name('employees.export');
                        Route::post('employees/assignRole', ['uses' => 'ManageEmployeesController@assignRole'])->name('employees.assignRole');
                        Route::post('employees/assignProjectAdmin', ['uses' => 'ManageEmployeesController@assignProjectAdmin'])->name('employees.assignProjectAdmin');
                        Route::get('employees/leaveTypeEdit/{id}', ['uses' => 'ManageEmployeesController@leaveTypeEdit'])->name('employees.leaveTypeEdit');
                        Route::post('employees/leaveTypeUpdate/{id}', ['uses' => 'ManageEmployeesController@leaveTypeUpdate'])->name('employees.leaveTypeUpdate');
                        Route::resource('employees', 'ManageEmployeesController');

                        Route::get('department/quick-create', ['uses' => 'ManageTeamsController@quickCreate'])->name('teams.quick-create');
                        Route::post('department/quick-store', ['uses' => 'ManageTeamsController@quickStore'])->name('teams.quick-store');
                        Route::resource('teams', 'ManageTeamsController');
                        Route::resource('employee-teams', 'ManageEmployeeTeamsController');

                        Route::get('employee-docs/download/{id}', ['uses' => 'EmployeeDocsController@download'])->name('employee-docs.download');
                        Route::resource('employee-docs', 'EmployeeDocsController');
                    }
                );

                Route::post('projects/gantt-task-update/{id}', ['uses' => 'ManageProjectsController@updateTaskDuration'])->name('projects.gantt-task-update');
                Route::get('projects/ajaxCreate/{columnId?}', ['uses' => 'ManageProjectsController@ajaxCreate'])->name('projects.ajaxCreate');
                Route::get('projects/archive-data', ['uses' => 'ManageProjectsController@archiveData'])->name('projects.archive-data');
                Route::get('projects/archive', ['uses' => 'ManageProjectsController@archive'])->name('projects.archive');
                Route::get('projects/archive-restore/{id?}', ['uses' => 'ManageProjectsController@archiveRestore'])->name('projects.archive-restore');
                Route::get('projects/archive-delete/{id?}', ['uses' => 'ManageProjectsController@archiveDestroy'])->name('projects.archive-delete');
                Route::get('projects/export/{status?}/{clientID?}', ['uses' => 'ManageProjectsController@export'])->name('projects.export');
                Route::get('projects/ganttData/{projectId?}', ['uses' => 'ManageProjectsController@ganttData'])->name('projects.ganttData');
                Route::get('projects/gantt/{projectId?}', ['uses' => 'ManageProjectsController@gantt'])->name('projects.gantt');
                Route::get('projects/burndown/{projectId?}', ['uses' => 'ManageProjectsController@burndownChart'])->name('projects.burndown-chart');
                Route::post('projects/updateStatus/{id}', ['uses' => 'ManageProjectsController@updateStatus'])->name('projects.updateStatus');
                Route::get('projects/discussion-replies/{projectId}/{discussionId}', ['uses' => 'ManageProjectsController@discussionReplies'])->name('projects.discussionReplies');
                Route::get('projects/discussion/{projectId}', ['uses' => 'ManageProjectsController@discussion'])->name('projects.discussion');
                Route::get('projects/template-data/{templateId}', ['uses' => 'ManageProjectsController@templateData'])->name('projects.template-data');
                Route::get('projects/pinned-project', ['uses' => 'ManageProjectsController@pinnedItem'])->name('projects.pinned-project');
                Route::resource('projects', 'ManageProjectsController');

                Route::get('project-template/data', ['uses' => 'ProjectTemplateController@data'])->name('project-template.data');
                Route::get('project-template/detail/{id?}', ['uses' => 'ProjectTemplateController@taskDetail'])->name('project-template.detail');
                Route::resource('project-template', 'ProjectTemplateController');

                Route::post('project-template-members/save-group', ['uses' => 'ProjectMemberTemplateController@storeGroup'])->name('project-template-members.storeGroup');
                Route::resource('project-template-member', 'ProjectMemberTemplateController');

                Route::get('project-template-task/data/{templateId?}', ['uses' => 'ProjectTemplateTaskController@data'])->name('project-template-task.data');
                Route::get('project-template-task/detail/{id?}', ['uses' => 'ProjectTemplateTaskController@taskDetail'])->name('project-template-task.detail');
                Route::resource('project-template-task', 'ProjectTemplateTaskController');

                Route::resource('project-template-sub-task', 'ProjectTemplateSubTaskController');

                Route::post('projectCategory/store-cat', ['uses' => 'ManageProjectCategoryController@storeCat'])->name('projectCategory.store-cat');
                Route::get('projectCategory/create-cat', ['uses' => 'ManageProjectCategoryController@createCat'])->name('projectCategory.create-cat');
                Route::resource('projectCategory', 'ManageProjectCategoryController');

                Route::post('expenseCategory/store-cat', ['uses' => 'ManageExpenseCategoryController@storeCat'])->name('expenseCategory.store-cat');
                Route::get('expenseCategory/create-cat', ['uses' => 'ManageExpenseCategoryController@createCat'])->name('expenseCategory.create-cat');
                Route::resource('expenseCategory', 'ManageExpenseCategoryController');

                Route::post('taskCategory/store-cat', ['uses' => 'ManageTaskCategoryController@storeCat'])->name('taskCategory.store-cat');
                Route::get('taskCategory/create-cat', ['uses' => 'ManageTaskCategoryController@createCat'])->name('taskCategory.create-cat');
                Route::resource('taskCategory', 'ManageTaskCategoryController');

                Route::resource('productCategory', 'ManageProductCategoryController');
                Route::resource('productSubCategory', 'ProductSubCategoryController');

                Route::resource('pinned', 'ManagePinnedController', ['only' => ['store', 'destroy']]);

                Route::post('task-label/store-label', ['uses' => 'ManageTaskLabelController@storeLabel'])->name('task-label.store-label');
                Route::get('task-label/create-label', ['uses' => 'ManageTaskLabelController@createLabel'])->name('task-label.create-label');
                Route::resource('task-label', 'ManageTaskLabelController');

                Route::get('notices/export/{startDate}/{endDate}', ['uses' => 'ManageNoticesController@export'])->name('notices.export');
                Route::resource('notices', 'ManageNoticesController');

                Route::get('settings/change-language', ['uses' => 'OrganisationSettingsController@changeLanguage'])->name('settings.change-language');
                Route::resource('settings', 'OrganisationSettingsController', ['only' => ['edit', 'update', 'index', 'change-language']]);



                Route::group(
                    ['prefix' => 'settings'],
                    function () {
                        Route::get('email-settings/sent-test-email', ['uses' => 'EmailNotificationSettingController@sendTestEmail'])->name('email-settings.sendTestEmail');
                        Route::post('email-settings/updateMailConfig', ['uses' => 'EmailNotificationSettingController@updateMailConfig'])->name('email-settings.updateMailConfig');
                        Route::resource('email-settings', 'EmailNotificationSettingController');
                        Route::resource('profile-settings', 'AdminProfileSettingsController');

                        Route::get('currency/exchange-key', ['uses' => 'CurrencySettingController@currencyExchangeKey'])->name('currency.exchange-key');
                        Route::post('currency/exchange-key-store', ['uses' => 'CurrencySettingController@currencyExchangeKeyStore'])->name('currency.exchange-key-store');
                        Route::resource('currency', 'CurrencySettingController');
                        Route::get('currency/exchange-rate/{currency}', ['uses' => 'CurrencySettingController@exchangeRate'])->name('currency.exchange-rate');
                        Route::get('currency/update/exchange-rates', ['uses' => 'CurrencySettingController@updateExchangeRate'])->name('currency.update-exchange-rates');
                        Route::resource('currency', 'CurrencySettingController');


                        Route::post('theme-settings/activeTheme', ['uses' => 'ThemeSettingsController@activeTheme'])->name('theme-settings.activeTheme');
                        Route::post('theme-settings/roundedTheme', ['uses' => 'ThemeSettingsController@roundedTheme'])->name('theme-settings.roundedTheme');
                        Route::resource('theme-settings', 'ThemeSettingsController');
                        Route::resource('project-settings', 'ProjectSettingsController');

                        // Log time
                        Route::resource('log-time-settings', 'LogTimeSettingsController');
                        Route::resource('task-settings', 'TaskSettingsController',  ['only' => ['index', 'store']]);

                        Route::resource('payment-gateway-credential', 'PaymentGatewayCredentialController');
                        Route::resource('invoice-settings', 'InvoiceSettingController');

                        Route::get('slack-settings/sendTestNotification', ['uses' => 'SlackSettingController@sendTestNotification'])->name('slack-settings.sendTestNotification');
                        Route::post('slack-settings/updateSlackNotification/{id}', ['uses' => 'SlackSettingController@updateSlackNotification'])->name('slack-settings.updateSlackNotification');
                        Route::resource('slack-settings', 'SlackSettingController');

                        Route::get('push-notification-settings/sendTestNotification', ['uses' => 'PushNotificationController@sendTestNotification'])->name('push-notification-settings.sendTestNotification');
                        Route::post('push-notification-settings/updatePushNotification/{id}', ['uses' => 'PushNotificationController@updatePushNotification'])->name('push-notification-settings.updatePushNotification');
                        Route::resource('push-notification-settings', 'PushNotificationController');

                        Route::post('ticket-agents/update-group/{id}', ['uses' => 'TicketAgentsController@updateGroup'])->name('ticket-agents.update-group');
                        Route::resource('ticket-agents', 'TicketAgentsController');
                        Route::resource('ticket-groups', 'TicketGroupsController');

                        Route::get('ticketTypes/createModal', ['uses' => 'TicketTypesController@createModal'])->name('ticketTypes.createModal');
                        Route::resource('ticketTypes', 'TicketTypesController');

                        Route::get('lead-source-settings/createModal', ['uses' => 'LeadSourceSettingController@createModal'])->name('lead-source-settings.createModal');
                        Route::resource('lead-source-settings', 'LeadSourceSettingController');

                        Route::get('lead-status-settings/createModal', ['uses' => 'LeadStatusSettingController@createModal'])->name('leadSetting.createModal');
                        Route::get('lead-status-update/{statusId}', ['uses' => 'LeadStatusSettingController@statusUpdate'])->name('leadSetting.statusUpdate');
                        Route::resource('lead-status-settings', 'LeadStatusSettingController');

                        Route::post('lead-agent-settings/create-agent', ['uses' => 'LeadAgentSettingController@storeAgent'])->name('lead-agent-settings.create-agent');
                        Route::resource('lead-agent-settings', 'LeadAgentSettingController');

                        Route::get('offline-payment-setting/createModal', ['uses' => 'OfflinePaymentSettingController@createModal'])->name('offline-payment-setting.createModal');
                        Route::resource('offline-payment-setting', 'OfflinePaymentSettingController');

                        Route::get('ticketChannels/createModal', ['uses' => 'TicketChannelsController@createModal'])->name('ticketChannels.createModal');
                        Route::resource('ticketChannels', 'TicketChannelsController');

                        Route::post('replyTemplates/fetch-template', ['uses' => 'TicketReplyTemplatesController@fetchTemplate'])->name('replyTemplates.fetchTemplate');
                        Route::resource('replyTemplates', 'TicketReplyTemplatesController');

                        Route::resource('attendance-settings', 'AttendanceSettingController');

                        Route::resource('leaves-settings', 'LeavesSettingController');

                        Route::get('data', ['uses' => 'AdminCustomFieldsController@getFields'])->name('custom-fields.data');
                        Route::resource('custom-fields', 'AdminCustomFieldsController');

                        // Message settings
                        Route::resource('message-settings', 'MessageSettingsController');

                        // Module settings
                        Route::resource('module-settings', 'ModuleSettingsController');

                        Route::get('gdpr/lead/approve-reject/{id}/{type}', ['uses' => 'GdprSettingsController@approveRejectLead'])->name('gdpr.lead.approve-reject');
                        Route::get('gdpr/approve-reject/{id}/{type}', ['uses' => 'GdprSettingsController@approveReject'])->name('gdpr.approve-reject');

                        Route::get('gdpr/lead/removal-data', ['uses' => 'GdprSettingsController@removalLeadData'])->name('gdpr.lead.removal-data');
                        Route::get('gdpr/removal-data', ['uses' => 'GdprSettingsController@removalData'])->name('gdpr.removal-data');
                        Route::put('gdpr/update-consent/{id}', ['uses' => 'GdprSettingsController@updateConsent'])->name('gdpr.update-consent');
                        Route::get('gdpr/edit-consent/{id}', ['uses' => 'GdprSettingsController@editConsent'])->name('gdpr.edit-consent');
                        Route::delete('gdpr/purpose-delete/{id}', ['uses' => 'GdprSettingsController@purposeDelete'])->name('gdpr.purpose-delete');
                        Route::get('gdpr/consent-data', ['uses' => 'GdprSettingsController@data'])->name('gdpr.purpose-data');
                        Route::post('gdpr/store-consent', ['uses' => 'GdprSettingsController@storeConsent'])->name('gdpr.store-consent');
                        Route::get('gdpr/add-consent', ['uses' => 'GdprSettingsController@AddConsent'])->name('gdpr.add-consent');
                        Route::get('gdpr/consent', ['uses' => 'GdprSettingsController@consent'])->name('gdpr.consent');
                        Route::get('gdpr/right-of-access', ['uses' => 'GdprSettingsController@rightOfAccess'])->name('gdpr.right-of-access');
                        Route::get('gdpr/right-to-informed', ['uses' => 'GdprSettingsController@rightToInformed'])->name('gdpr.right-to-informed');
                        Route::get('gdpr/right-to-data-portability', ['uses' => 'GdprSettingsController@rightToDataPortability'])->name('gdpr.right-to-data-portability');
                        Route::get('gdpr/right-to-erasure', ['uses' => 'GdprSettingsController@rightToErasure'])->name('gdpr.right-to-erasure');
                        Route::resource('gdpr', 'GdprSettingsController', ['only' => ['index', 'store']]);
                    }
                );

                Route::group(
                    ['prefix' => 'projects'],
                    function () {
                        Route::post('project-members/save-group', ['uses' => 'ManageProjectMembersController@storeGroup'])->name('project-members.storeGroup');
                        Route::resource('project-members', 'ManageProjectMembersController');

                        Route::post('tasks/sort', ['uses' => 'ManageTasksController@sort'])->name('tasks.sort');
                        Route::post('tasks/change-status', ['uses' => 'ManageTasksController@changeStatus'])->name('tasks.changeStatus');
                        Route::get('tasks/check-task/{taskID}', ['uses' => 'ManageTasksController@checkTask'])->name('tasks.checkTask');
                        Route::post('tasks/data/{projectId?}', 'ManageTasksController@data')->name('tasks.data');
                        Route::get('tasks/kanban-board/{id}', ['uses' => 'ManageTasksController@kanbanboard'])->name('tasks.kanbanboard');
                        Route::get('tasks/export/{projectId?}', 'ManageTasksController@export')->name('tasks.export');

                        Route::resource('tasks', 'ManageTasksController');

                        Route::post('files/store-link', ['uses' => 'ManageProjectFilesController@storeLink'])->name('files.storeLink');
                        Route::get('files/download/{id}', ['uses' => 'ManageProjectFilesController@download'])->name('files.download');
                        Route::get('files/thumbnail', ['uses' => 'ManageProjectFilesController@thumbnailShow'])->name('files.thumbnail');
                        Route::post('files/multiple-upload', ['uses' => 'ManageProjectFilesController@storeMultiple'])->name('files.multiple-upload');
                        Route::resource('files', 'ManageProjectFilesController');

                        Route::get('invoices/download/{id}', ['uses' => 'ManageInvoicesController@download'])->name('invoices.download');
                        Route::get('invoices/create-invoice/{id}', ['uses' => 'ManageInvoicesController@createInvoice'])->name('invoices.createInvoice');
                        Route::resource('invoices', 'ManageInvoicesController');

                        Route::resource('issues', 'ManageIssuesController');

                        Route::post('time-logs/stop-timer/{id}', ['uses' => 'ManageTimeLogsController@stopTimer'])->name('time-logs.stopTimer');
                        Route::get('time-logs/data/{id}', ['uses' => 'ManageTimeLogsController@data'])->name('time-logs.data');
                        Route::resource('time-logs', 'ManageTimeLogsController');


                        Route::get('milestones/detail/{id}', ['uses' => 'ManageProjectMilestonesController@detail'])->name('milestones.detail');
                        Route::get('milestones/data/{id}', ['uses' => 'ManageProjectMilestonesController@data'])->name('milestones.data');
                        Route::resource('milestones', 'ManageProjectMilestonesController');

                        Route::resource('project-expenses', 'ManageProjectExpensesController');
                        Route::resource('project-payments', 'ManageProjectPaymentsController');

                        Route::resource('project-notes', 'AdminProjectNotesController');
                        Route::get('project-notes/data/{id}', ['uses' => 'AdminProjectNotesController@data'])->name('project-notes.data');
                        Route::get('project-notes/view/{id}', ['uses' => 'AdminProjectNotesController@view'])->name('project-notes.view');

                        Route::resource('project-ratings', 'ManageProjectRatingController');
                    }
                );

                Route::group(
                    ['prefix' => 'clients'],
                    function () {
                        Route::post('save-consent-purpose-data/{client}', ['uses' => 'ManageClientsController@saveConsentLeadData'])->name('clients.save-consent-purpose-data');
                        Route::get('consent-purpose-data/{client}', ['uses' => 'ManageClientsController@consentPurposeData'])->name('clients.consent-purpose-data');
                        Route::get('gdpr/{id}', ['uses' => 'ManageClientsController@gdpr'])->name('clients.gdpr');
                        Route::get('projects/{id}', ['uses' => 'ManageClientsController@showProjects'])->name('clients.projects');
                        Route::get('invoices/{id}', ['uses' => 'ManageClientsController@showInvoices'])->name('clients.invoices');
                        Route::get('payments/{id}', ['uses' => 'ManageClientsController@showPayments'])->name('clients.payments');

                        //  Route::get('notes/{id}', ['uses' => 'ManageClientsController@showNotes'])->name('clients.notes');

                        Route::get('contacts/data/{id}', ['uses' => 'ClientContactController@data'])->name('contacts.data');
                        Route::resource('contacts', 'ClientContactController');

                        Route::get('notes/data/{id}', ['uses' => 'ClientNotesController@data'])->name('notes.data');
                        Route::get('notes/view/{id}', ['uses' => 'ClientNotesController@view'])->name('notes.view');

                        Route::resource('notes', 'ClientNotesController');

                        Route::get('client-docs/download/{id}', ['uses' => 'ClientDocsController@download'])->name('client-docs.download');
                        Route::get('client-docs/quick-create/{id}', ['uses' => 'ClientDocsController@quickCreate'])->name('client-docs.quick-create');
                        Route::resource('client-docs', 'ClientDocsController');
                    }
                );

                Route::get('all-issues/data', ['uses' => 'ManageAllIssuesController@data'])->name('all-issues.data');
                Route::resource('all-issues', 'ManageAllIssuesController');

                Route::get('all-time-logs/members/{projectId}', ['uses' => 'ManageAllTimeLogController@membersList'])->name('all-time-logs.members');
                Route::get('all-time-logs/task-members/{taskId}', ['uses' => 'ManageAllTimeLogController@taskMembersList'])->name('all-time-logs.task-members');
                Route::get('all-time-logs/show-active-timer', ['uses' => 'ManageAllTimeLogController@showActiveTimer'])->name('all-time-logs.show-active-timer');
                Route::get('all-time-logs/export/{startDate?}/{endDate?}/{projectId?}/{employee?}', ['uses' => 'ManageAllTimeLogController@export'])->name('all-time-logs.export');
                Route::post('all-time-logs/stop-timer/{id}', ['uses' => 'ManageAllTimeLogController@stopTimer'])->name('all-time-logs.stopTimer');
                Route::post('all-time-logs/data', ['uses' => 'ManageAllTimeLogController@data'])->name('all-time-logs.data');
                Route::get('all-time-logs/by-employee', ['uses' => 'ManageAllTimeLogController@byEmployee'])->name('all-time-logs.by-employee');
                Route::post('all-time-logs/userTimelogs', ['uses' => 'ManageAllTimeLogController@userTimelogs'])->name('all-time-logs.userTimelogs');
                Route::post('all-time-logs/approve-timelog', ['uses' => 'ManageAllTimeLogController@approveTimelog'])->name('all-time-logs.approve-timelog');
                Route::get('all-time-logs/active-timelogs', ['uses' => 'ManageAllTimeLogController@activeTimelogs'])->name('all-time-logs.active-timelogs');
                Route::get('all-time-logs/calendar', ['uses' => 'ManageAllTimeLogController@calendar'])->name('all-time-logs.calendar');
                Route::resource('all-time-logs', 'ManageAllTimeLogController');

                // task routes
                Route::resource('task', 'ManageAllTasksController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
                Route::group(
                    ['prefix' => 'task'],
                    function () {

                        Route::get('all-tasks/export/{startDate?}/{endDate?}/{projectId?}/{hideCompleted?}', ['uses' => 'ManageAllTasksController@export'])->name('all-tasks.export');
                        Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'ManageAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
                        Route::get('all-tasks/members/{projectId}', ['uses' => 'ManageAllTasksController@membersList'])->name('all-tasks.members');
                        Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'ManageAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
                        Route::get('all-tasks/reminder/{taskid}', ['uses' => 'ManageAllTasksController@remindForTask'])->name('all-tasks.reminder');
                        Route::get('all-tasks/files/{taskid}', ['uses' => 'ManageAllTasksController@showFiles'])->name('all-tasks.show-files');
                        Route::get('all-tasks/history/{taskid}', ['uses' => 'ManageAllTasksController@history'])->name('all-tasks.history');
                        Route::get('all-tasks/pinned-task', ['uses' => 'ManageAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');
                        Route::resource('all-tasks', 'ManageAllTasksController');

                        // taskboard resource
                        Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'AdminTaskboardController@updateIndex']);
                        Route::resource('taskboard', 'AdminTaskboardController');

                        // task calendar routes
                        Route::resource('task-calendar', 'AdminCalendarController');
                        Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
                        Route::resource('task-files', 'TaskFilesController');
                    }
                );

                Route::resource('sticky-note', 'ManageStickyNotesController');


                Route::resource('reports', 'TaskReportController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
                Route::group(
                    ['prefix' => 'reports'],
                    function () {
                        Route::post('task-report/data', ['uses' => 'TaskReportController@data'])->name('task-report.data');
                        Route::post('task-report/export', ['uses' => 'TaskReportController@export'])->name('task-report.export');
                        Route::resource('task-report', 'TaskReportController');
                        Route::resource('time-log-report', 'TimeLogReportController');
                        Route::resource('finance-report', 'FinanceReportController');
                        Route::resource('income-expense-report', 'IncomeVsExpenseReportController');
                        //region Leave Report routes
                        Route::post('leave-report/data', ['uses' => 'LeaveReportController@data'])->name('leave-report.data');
                        Route::post('leave-report/export', 'LeaveReportController@export')->name('leave-report.export');
                        Route::get('leave-report/pending-leaves/{id?}', 'LeaveReportController@pendingLeaves')->name('leave-report.pending-leaves');
                        Route::get('leave-report/upcoming-leaves/{id?}', 'LeaveReportController@upcomingLeaves')->name('leave-report.upcoming-leaves');
                        Route::resource('leave-report', 'LeaveReportController');

                        Route::post('attendance-report/report', ['uses' => 'AttendanceReportController@report'])->name('attendance-report.report');
                        Route::get('attendance-report/export/{startDate}/{endDate}/{employee}', ['uses' => 'AttendanceReportController@reportExport'])->name('attendance-report.reportExport');
                        Route::resource('attendance-report', 'AttendanceReportController');
                        //endregion
                    }
                );

                Route::resource('search', 'AdminSearchController');



                Route::resource('finance', 'ManageEstimatesController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active

                Route::group(
                    ['prefix' => 'finance'],
                    function () {

                        // Estimate routes
                        Route::get('estimates/download/{id}', ['uses' => 'ManageEstimatesController@download'])->name('estimates.download');
                        Route::get('estimates/export/{startDate}/{endDate}/{status}', ['uses' => 'ManageEstimatesController@export'])->name('estimates.export');
                        Route::get('estimates/duplicate-estimate/{id}', ['uses' => 'ManageEstimatesController@duplicateEstimate'])->name('estimates.duplicate-estimate');
                        Route::get('estimates/change-status/{id}', ['uses' => 'ManageEstimatesController@changeStatus'])->name('estimates.change-status');
                        Route::resource('estimates', 'ManageEstimatesController');

                        //Expenses routes
                        Route::post('expenses/change-status', ['uses' => 'ManageExpensesController@changeStatus'])->name('expenses.changeStatus');
                        Route::get('expenses/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageExpensesController@export'])->name('expenses.export');
                        Route::post('estimates/send-estimate/{id}', ['uses' => 'ManageEstimatesController@sendEstimate'])->name('estimates.send-estimate');
                        Route::resource('expenses', 'ManageExpensesController');

                        //Expenses recurring
                        Route::post('expenses-recurring/change-status', ['uses' => 'ManageExpensesRecurringController@changeStatus'])->name('expenses-recurring.changeStatus');
                        Route::get('expenses-recurring/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageExpensesRecurringController@export'])->name('expenses-recurring.export');
                        Route::get('expenses-recurring/recurring-expenses/{id}', ['uses' => 'ManageExpensesRecurringController@recurringExpenses'])->name('expenses-recurring.recurring-expenses');
                        Route::get('expenses-recurring/download/{id}', ['uses' => 'ManageExpensesRecurringController@download'])->name('expenses-recurring.download');
                        Route::resource('expenses-recurring', 'ManageExpensesRecurringController');

                        // All invoices list routes
                        Route::post('file/store', ['uses' => 'ManageAllInvoicesController@storeFile'])->name('invoiceFile.store');
                        Route::delete('file/destroy', ['uses' => 'ManageAllInvoicesController@destroyFile'])->name('invoiceFile.destroy');
                        Route::get('all-invoices/applied-credits/{id}', ['uses' => 'ManageAllInvoicesController@appliedCredits'])->name('all-invoices.applied-credits');
                        Route::post('all-invoices/delete-applied-credit/{id}', ['uses' => 'ManageAllInvoicesController@deleteAppliedCredit'])->name('all-invoices.delete-applied-credit');
                        Route::get('all-invoices/download/{id}', ['uses' => 'ManageAllInvoicesController@download'])->name('all-invoices.download');
                        Route::get('all-invoices/export/{startDate}/{endDate}/{status}/{projectID}', ['uses' => 'ManageAllInvoicesController@export'])->name('all-invoices.export');
                        Route::get('all-invoices/convert-estimate/{id}', ['uses' => 'ManageAllInvoicesController@convertEstimate'])->name('all-invoices.convert-estimate');
                        Route::get('all-invoices/convert-milestone/{id}', ['uses' => 'ManageAllInvoicesController@convertMilestone'])->name('all-invoices.convert-milestone');
                        Route::get('all-invoices/convert-proposal/{id}', ['uses' => 'ManageAllInvoicesController@convertProposal'])->name('all-invoices.convert-proposal');
                        Route::get('all-invoices/update-item', ['uses' => 'ManageAllInvoicesController@addItems'])->name('all-invoices.update-item');
                        Route::get('all-invoices/payment-detail/{invoiceID}', ['uses' => 'ManageAllInvoicesController@paymentDetail'])->name('all-invoices.payment-detail');
                        Route::get('all-invoices/get-client-company/{projectID?}', ['uses' => 'ManageAllInvoicesController@getClientOrCompanyName'])->name('all-invoices.get-client-company');
                        Route::get('all-invoices/get-client/{projectID}', ['uses' => 'ManageAllInvoicesController@getClient'])->name('all-invoices.get-client');
                        Route::get('all-invoices/check-shipping-address', ['uses' => 'ManageAllInvoicesController@checkShippingAddress'])->name('all-invoices.checkShippingAddress');
                        Route::get('all-invoices/toggle-shipping-address/{invoice}', ['uses' => 'ManageAllInvoicesController@toggleShippingAddress'])->name('all-invoices.toggleShippingAddress');
                        Route::get('all-invoices/shipping-address-modal/{invoice}', ['uses' => 'ManageAllInvoicesController@shippingAddressModal'])->name('all-invoices.shippingAddressModal');
                        Route::post('all-invoices/add-shipping-address/{user}', ['uses' => 'ManageAllInvoicesController@addShippingAddress'])->name('all-invoices.addShippingAddress');
                        Route::get('all-invoices/payment-reminder/{invoiceID}', ['uses' => 'ManageAllInvoicesController@remindForPayment'])->name('all-invoices.payment-reminder');
                        Route::get('all-invoices/payment-verify/{invoiceID}', ['uses' => 'ManageAllInvoicesController@verifyOfflinePayment'])->name('all-invoices.payment-verify');
                        Route::post('all-invoices/payment-verify-submit/{offlinePaymentId}', ['uses' => 'ManageAllInvoicesController@verifyPayment'])->name('offline-invoice-payment.verify');
                        Route::post('all-invoices/payment-reject-submit/{offlinePaymentId}', ['uses' => 'ManageAllInvoicesController@rejectPayment'])->name('offline-invoice-payment.reject');
                        Route::get('all-invoices/update-status/{invoiceID}', ['uses' => 'ManageAllInvoicesController@cancelStatus'])->name('all-invoices.update-status');
                        Route::post('all-invoices/fetchTimelogs', ['uses' => 'ManageAllInvoicesController@fetchTimelogs'])->name('all-invoices.fetchTimelogs');
                        Route::post('all-invoices/send-invoice/{invoiceID}', ['uses' => 'ManageAllInvoicesController@sendInvoice'])->name('all-invoices.send-invoice');

                        Route::resource('all-invoices', 'ManageAllInvoicesController');

                        //Invoice recurring
                        Route::post('invoice-recurring/change-status', ['uses' => 'ManageInvoicesRecurringController@changeStatus'])->name('invoice-recurring.changeStatus');
                        Route::get('invoice-recurring/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ManageInvoicesRecurringController@export'])->name('invoice-recurring.export');
                        Route::get('invoice-recurring/recurring-invoice/{id}', ['uses' => 'ManageInvoicesRecurringController@recurringInvoices'])->name('invoice-recurring.recurring-invoice');
                        Route::resource('invoice-recurring', 'ManageInvoicesRecurringController');

                        // All Credit Note routes
                        Route::post('credit-file/store', ['uses' => 'ManageAllCreditNotesController@storeFile'])->name('creditNoteFile.store');
                        Route::delete('credit-file/destroy', ['uses' => 'ManageAllCreditNotesController@destroyFile'])->name('creditNoteFile.destroy');
                        Route::get('all-credit-notes/apply-to-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@applyToInvoiceModal'])->name('all-credit-notes.apply-to-invoice-modal');
                        Route::post('all-credit-notes/apply-to-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@applyToInvoice'])->name('all-credit-notes.apply-to-invoice');
                        Route::get('all-credit-notes/credited-invoices/{id}', ['uses' => 'ManageAllCreditNotesController@creditedInvoices'])->name('all-credit-notes.credited-invoices');
                        Route::post('all-credit-notes/delete-credited-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@deleteCreditedInvoice'])->name('all-credit-notes.delete-credited-invoice');
                        Route::get('all-credit-notes/download/{id}', ['uses' => 'ManageAllCreditNotesController@download'])->name('all-credit-notes.download');
                        Route::get('all-credit-notes/export/{startDate}/{endDate}/{projectID}', ['uses' => 'ManageAllCreditNotesController@export'])->name('all-credit-notes.export');
                        Route::get('all-credit-notes/convert-invoice/{id}', ['uses' => 'ManageAllCreditNotesController@convertInvoice'])->name('all-credit-notes.convert-invoice');
                        // Route::get('all-credit-notes/convert-proposal/{id}', ['uses' => 'ManageAllCreditNotesController@convertProposal'])->name('all-credit-notes.convert-proposal');
                        Route::get('all-credit-notes/update-item', ['uses' => 'ManageAllCreditNotesController@addItems'])->name('all-credit-notes.update-item');
                        Route::get('all-credit-notes/payment-detail/{creditNoteID}', ['uses' => 'ManageAllCreditNotesController@paymentDetail'])->name('all-credit-notes.payment-detail');
                        Route::resource('all-credit-notes', 'ManageAllCreditNotesController');

                        //Payments routes
                        Route::get('payments/export/{startDate}/{endDate}/{status}/{payment}', ['uses' => 'ManagePaymentsController@export'])->name('payments.export');
                        Route::get('payments/pay-invoice/{invoiceId}', ['uses' => 'ManagePaymentsController@payInvoice'])->name('payments.payInvoice');
                        Route::get('payments/download', ['uses' => 'ManagePaymentsController@downloadSample'])->name('payments.downloadSample');
                        Route::post('payments/import', ['uses' => 'ManagePaymentsController@importExcel'])->name('payments.importExcel');
                        Route::resource('payments', 'ManagePaymentsController');
                    }
                );

                //Ticket routes
                Route::get('tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'ManageTicketsController@export'])->name('tickets.export');
                Route::post('tickets/refresh-count', ['uses' => 'ManageTicketsController@refreshCount'])->name('tickets.refreshCount');
                Route::get('tickets/reply-delete/{id?}', ['uses' => 'ManageTicketsController@destroyReply'])->name('tickets.reply-delete');
                Route::post('tickets/updateOtherData/{id}', ['uses' => 'ManageTicketsController@updateOtherData'])->name('tickets.updateOtherData');

                Route::resource('tickets', 'ManageTicketsController');

                Route::post('ticket-form/sortFields', ['as' => 'ticket-form.sortFields', 'uses' => 'TicketCustomFormController@sortFields']);
                Route::resource('ticket-form', 'TicketCustomFormController');

                Route::get('ticket-files/download/{id}', ['uses' => 'TicketFilesController@download'])->name('ticket-files.download');
                Route::resource('ticket-files', 'TicketFilesController');

                //Support Ticket routes
                Route::get('support-tickets/export/{startDate?}/{endDate?}/{agentId?}/{status?}/{priority?}/{channelId?}/{typeId?}', ['uses' => 'SupportTicketsController@export'])->name('support-tickets.export');
                Route::get('support-tickets/reply-delete/{id?}', ['uses' => 'SupportTicketsController@destroyReply'])->name('support-tickets.reply-delete');
                Route::post('support-tickets/updateOtherData/{id}', ['uses' => 'SupportTicketsController@updateOtherData'])->name('support-tickets.updateOtherData');
                Route::resource('support-tickets', 'SupportTicketsController');

                // Support ticket file routes
                Route::get('support-ticket-files/download/{id}', ['uses' => 'SupportTicketFilesController@download'])->name('support-ticket-files.download');
                Route::resource('support-ticket-files', 'SupportTicketFilesController');

                // User message
                Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'AdminChatController@postChatMessage']);
                Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'AdminChatController@getUserSearch']);
                Route::resource('user-chat', 'AdminChatController');

                // attendance
                Route::get('attendances/export/{startDate?}/{endDate?}/{employee?}', ['uses' => 'ManageAttendanceController@export'])->name('attendances.export');

                Route::get('attendances/detail', ['uses' => 'ManageAttendanceController@attendanceDetail'])->name('attendances.detail');
                Route::get('attendances/data', ['uses' => 'ManageAttendanceController@data'])->name('attendances.data');
                Route::get('attendances/check-holiday', ['uses' => 'ManageAttendanceController@checkHoliday'])->name('attendances.check-holiday');
                Route::get('attendances/employeeData/{startDate?}/{endDate?}/{userId?}', ['uses' => 'ManageAttendanceController@employeeData'])->name('attendances.employeeData');
                Route::get('attendances/refresh-count/{startDate?}/{endDate?}/{userId?}', ['uses' => 'ManageAttendanceController@refreshCount'])->name('attendances.refreshCount');
                Route::get('attendances/attendance-by-date', ['uses' => 'ManageAttendanceController@attendanceByDate'])->name('attendances.attendanceByDate');
                Route::get('attendances/byDateData', ['uses' => 'ManageAttendanceController@byDateData'])->name('attendances.byDateData');
                Route::post('attendances/dateAttendanceCount', ['uses' => 'ManageAttendanceController@dateAttendanceCount'])->name('attendances.dateAttendanceCount');
                Route::get('attendances/info/{id}', ['uses' => 'ManageAttendanceController@detail'])->name('attendances.info');
                Route::get('attendances/summary', ['uses' => 'ManageAttendanceController@summary'])->name('attendances.summary');
                Route::post('attendances/summaryData', ['uses' => 'ManageAttendanceController@summaryData'])->name('attendances.summaryData');
                Route::post('attendances/storeMark', ['uses' => 'ManageAttendanceController@storeMark'])->name('attendances.storeMark');
                Route::get('attendances/mark/{id}/{day}/{month}/{year}', ['uses' => 'ManageAttendanceController@mark'])->name('attendances.mark');

                Route::resource('attendances', 'ManageAttendanceController');

                //Event Calendar
                Route::post('events/removeAttendee', ['as' => 'events.removeAttendee', 'uses' => 'AdminEventCalendarController@removeAttendee']);
                Route::get('events/get-filter', 'AdminEventCalendarController@filterEvent')->name('events.get-filter');
                Route::resource('events', 'AdminEventCalendarController');

                // Role permission routes
                Route::post('role-permission/assignAllPermission', ['as' => 'role-permission.assignAllPermission', 'uses' => 'ManageRolePermissionController@assignAllPermission']);
                Route::post('role-permission/removeAllPermission', ['as' => 'role-permission.removeAllPermission', 'uses' => 'ManageRolePermissionController@removeAllPermission']);
                Route::post('role-permission/assignRole', ['as' => 'role-permission.assignRole', 'uses' => 'ManageRolePermissionController@assignRole']);
                Route::post('role-permission/detachRole', ['as' => 'role-permission.detachRole', 'uses' => 'ManageRolePermissionController@detachRole']);
                Route::post('role-permission/storeRole', ['as' => 'role-permission.storeRole', 'uses' => 'ManageRolePermissionController@storeRole']);
                Route::post('role-permission/deleteRole', ['as' => 'role-permission.deleteRole', 'uses' => 'ManageRolePermissionController@deleteRole']);
                Route::get('role-permission/showMembers/{id}', ['as' => 'role-permission.showMembers', 'uses' => 'ManageRolePermissionController@showMembers']);
                Route::resource('role-permission', 'ManageRolePermissionController');

                //Leaves
                Route::post('leaves/leaveAction', ['as' => 'leaves.leaveAction', 'uses' => 'ManageLeavesController@leaveAction']);
                Route::get('leaves/show-reject-modal', ['as' => 'leaves.show-reject-modal', 'uses' => 'ManageLeavesController@rejectModal']);
                Route::post('leave/data/{employeeId?}', ['uses' => 'ManageLeavesController@data'])->name('leave.data');
                Route::get('leave/all-leaves', ['uses' => 'ManageLeavesController@allLeave'])->name('leave.all-leaves');
                Route::get('leaves/pending', ['as' => 'leaves.pending', 'uses' => 'ManageLeavesController@pendingLeaves']);

                Route::resource('leaves', 'ManageLeavesController');

                Route::resource('leaveType', 'ManageLeaveTypesController');

                //sub task routes
                Route::post('sub-task/changeStatus', ['as' => 'sub-task.changeStatus', 'uses' => 'ManageSubTaskController@changeStatus']);
                Route::resource('sub-task', 'ManageSubTaskController');

                //task comments
                Route::resource('task-comment', 'AdminTaskCommentController');

                //task Note
                Route::resource('task-note', 'AdminNoteController');

                //taxes
                Route::resource('taxes', 'TaxSettingsController');

                //region Products Routes
                Route::get('products/export', ['uses' => 'AdminProductController@export'])->name('products.export');
                Route::resource('products', 'AdminProductController');
                //endregion

                //region contracts routes
                Route::get('contracts/download/{id}', ['as' => 'contracts.download', 'uses' => 'AdminContractController@download']);
                Route::get('contracts/sign/{id}', ['as' => 'contracts.sign-modal', 'uses' => 'AdminContractController@contractSignModal']);
                Route::post('contracts/sign/{id}', ['as' => 'contracts.sign', 'uses' => 'AdminContractController@contractSign']);
                Route::get('contracts/copy/{id}', ['as' => 'contracts.copy', 'uses' => 'AdminContractController@copy']);
                Route::post('contracts/copy-submit', ['as' => 'contracts.copy-submit', 'uses' => 'AdminContractController@copySubmit']);
                Route::post('contracts/send/{id}', ['as' => 'contracts.send', 'uses' => 'AdminContractController@send']);
                // Route::post('contracts/send/{id}', ['uses' => 'AdminContractController@send'])->name('contracts.send');

                Route::post('contracts/add-discussion/{id}', ['as' => 'contracts.add-discussion', 'uses' => 'AdminContractController@addDiscussion']);
                Route::get('contracts/edit-discussion/{id}', ['as' => 'contracts.edit-discussion', 'uses' => 'AdminContractController@editDiscussion']);
                Route::post('contracts/update-discussion/{id}', ['as' => 'contracts.update-discussion', 'uses' => 'AdminContractController@updateDiscussion']);
                Route::post('contracts/remove-discussion/{id}', ['as' => 'contracts.remove-discussion', 'uses' => 'AdminContractController@removeDiscussion']);
                Route::resource('contracts', 'AdminContractController');
                //endregion

                //region contract files routes
                Route::post('contract-files/store-link', ['uses' => 'ContractFilesController@storeLink'])->name('contract-files.storeLink');
                Route::get('contract-files/download/{id}', ['uses' => 'ContractFilesController@download'])->name('contract-files.download');
                Route::get('contract-files/thumbnail', ['uses' => 'ContractFilesController@thumbnailShow'])->name('contract-files.thumbnail');
                Route::post('contract-files/multiple-upload', ['uses' => 'ContractFilesController@storeMultiple'])->name('contract-files.multiple-upload');
                Route::resource('contract-files', 'ContractFilesController');
                //endregion

                //region contracts type routes
                Route::get('contract-type/data', ['as' => 'contract-type.data', 'uses' => 'AdminContractTypeController@data']);
                Route::post('contract-type/type-store', ['as' => 'contract-type.store-contract-type', 'uses' => 'AdminContractTypeController@storeContractType']);
                Route::get('contract-type/type-create', ['as' => 'contract-type.create-contract-type', 'uses' => 'AdminContractTypeController@createContractType']);

                Route::resource('contract-type', 'AdminContractTypeController')->parameters([
                    'contract-type' => 'type'
                ]);
                //endregion

                //region contract renew routes
                Route::get('contract-renew/{id}', ['as' => 'contracts.renew', 'uses' => 'AdminContractRenewController@index']);
                Route::post('contract-renew-submit/{id}', ['as' => 'contracts.renew-submit', 'uses' => 'AdminContractRenewController@renew']);
                Route::post('contract-renew-remove/{id}', ['as' => 'contracts.renew-remove', 'uses' => 'AdminContractRenewController@destroy']);
                //endregion

                //region discussion category routes
                Route::resource('discussion-category', 'DiscussionCategoryController');
                //endregion

                //region discussion routes
                Route::post('discussion/setBestAnswer', ['as' => 'discussion.setBestAnswer', 'uses' => 'DiscussionController@setBestAnswer']);
                Route::resource('discussion', 'DiscussionController');
                //endregion

                //region discussion routes
                Route::resource('discussion-reply', 'DiscussionReplyController');
                //endregion

            });
            Route::group(['middleware' => ['account-setup']], function () {
                Route::post('billing/unsubscribe',  'AdminBillingController@cancelSubscription')->name('billing.unsubscribe');
                Route::post('billing/razorpay-payment',  'AdminBillingController@razorpayPayment')->name('billing.razorpay-payment');
                Route::post('billing/razorpay-subscription',  'AdminBillingController@razorpaySubscription')->name('billing.razorpay-subscription');
                Route::get('billing/data',  'AdminBillingController@data')->name('billing.data');
                Route::get('billing/select-package/{packageID}',  'AdminBillingController@selectPackage')->name('billing.select-package');
                Route::get('billing', 'AdminBillingController@index')->name('billing');
                Route::get('billing/packages', 'AdminBillingController@packages')->name('billing.packages');
                Route::post('billing/payment-stripe', 'AdminBillingController@payment')->name('payments.stripe');
                Route::post('billing/payment-authorize', 'AuthorizeController@createSubscription')->name('payments.authorize');
                Route::post('billing/check-authorize-subscription', 'AuthorizeController@checkSubscription')->name('check-authorize-subscription');
                Route::get('billing/invoice-download/{invoice}', 'AdminBillingController@download')->name('stripe.invoice-download');
                Route::get('billing/razorpay-invoice-download/{id}', 'AdminBillingController@razorpayInvoiceDownload')->name('billing.razorpay-invoice-download');
                Route::get('billing/offline-invoice-download/{id}', 'AdminBillingController@offlineInvoiceDownload')->name('billing.offline-invoice-download');
                Route::get('billing/paystack-invoice-download/{id}', 'AdminBillingController@paystackInvoiceDownload')->name('billing.paystack-invoice-download');
                Route::get('billing/mollie-invoice-download/{id}', 'AdminBillingController@mollieInvoiceDownload')->name('billing.mollie-invoice-download');
                Route::get('billing/authorize-invoice-download/{id}', 'AdminBillingController@authorizeInvoiceDownload')->name('billing.authorize-invoice-download');

                //Pay stack payment
                Route::post('/pay', 'PaystackController@redirectToGateway')->name('payments.paystack');
                Route::get('/payment/callback', 'PaystackController@handleGatewayCallback')->name('payments.paystack.callback');

                //Pay stack payment
                Route::post('/mollie', 'MollieController@redirectToGateway')->name('payments.mollie');
                Route::get('/mollie/payment/callback', 'MollieController@handleGatewayCallback')->name('payments.mollie.callback');

                Route::get('billing/offline-payment', 'AdminBillingController@offlinePayment')->name('billing.offline-payment');
                Route::post('billing/free-plan', 'AdminBillingController@freePlan')->name('billing.free-plan');
                Route::post('billing/offline-payment-submit', 'AdminBillingController@offlinePaymentSubmit')->name('billing.offline-payment-submit');

                Route::get('paypal-recurring', array('as' => 'paypal-recurring', 'uses' => 'AdminPaypalController@payWithPaypalRecurrring',));
                Route::get('paypal-invoice-download/{id}', array('as' => 'paypal.invoice-download', 'uses' => 'AdminPaypalController@paypalInvoiceDownload',));
                Route::get('paypal-invoice', array('as' => 'paypal-invoice', 'uses' => 'AdminPaypalController@createInvoice'));

                // route for view/blade file
                Route::get('paywithpaypal', array('as' => 'paywithpaypal', 'uses' => 'AdminPaypalController@payWithPaypal'));
                // route for post request
                Route::get('paypal/{packageId}/{type}', array('as' => 'paypal', 'uses' => 'AdminPaypalController@paymentWithpaypal'));
                Route::get('paypal/cancel-agreement', array('as' => 'paypal.cancel-agreement', 'uses' => 'AdminPaypalController@cancelAgreement'));
                // route for check status responce
                Route::get('paypal', array('as' => 'status', 'uses' => 'AdminPaypalController@getPaymentStatus'));
            });
            Route::resource('account-setup', 'ManageAccountSetupController');
            Route::put('account-setup/update-invoice/{id}', ['uses' => 'ManageAccountSetupController@updateInvoice'])->name('account-setup.update-invoice');
        }
    );

    // Employee routes
    Route::group(
        ['namespace' => 'Member', 'prefix' => 'member', 'as' => 'member.', 'middleware' => ['role:employee']],
        function () {

            Route::get('dashboard', ['uses' => 'MemberDashboardController@index'])->name('dashboard');

            Route::post('profile/updateOneSignalId', ['uses' => 'MemberProfileController@updateOneSignalId'])->name('profile.updateOneSignalId');
            Route::get('language/change-language', ['uses' => 'MemberProfileController@changeLanguage'])->name('language.change-language');
            Route::resource('profile', 'MemberProfileController');

            Route::get('notes/data', ['uses' => 'MemberNotesController@data'])->name('notes.data');
            Route::get('notes/view/{id}', ['uses' => 'MemberNotesController@view'])->name('notes.view');
            Route::get('notes/verify-password/{id}', ['uses' => 'MemberNotesController@askForPassword'])->name('notes.verify-password');
            Route::post('notes/check-password/{id}', ['uses' => 'MemberNotesController@checkPassword'])->name('notes.check-password');
             Route::resource('notes', 'MemberNotesController');
             
            Route::post('projects/gantt-task-update/{id}', ['uses' => 'MemberProjectsController@updateTaskDuration'])->name('projects.gantt-task-update');
            Route::get('projects/ajaxCreate/{columnId?}', ['uses' => 'MemberProjectsController@ajaxCreate'])->name('projects.ajaxCreate');
            Route::get('projects/ganttData/{projectId?}', ['uses' => 'MemberProjectsController@ganttData'])->name('projects.ganttData');
            Route::get('projects/gantt/{projectId?}', ['uses' => 'MemberProjectsController@gantt'])->name('projects.gantt');
            Route::get('projects/data', ['uses' => 'MemberProjectsController@data'])->name('projects.data');
            Route::get('projects/discussion-replies/{projectId}/{discussionId}', ['uses' => 'MemberProjectsController@discussionReplies'])->name('projects.discussionReplies');
            Route::get('projects/discussion/{projectId}', ['uses' => 'MemberProjectsController@discussion'])->name('projects.discussion');
            Route::get('projects/template-data/{templateId}', ['uses' => 'MemberProjectsController@templateData'])->name('projects.template-data');
            Route::get('projects/pinned-project', ['uses' => 'MemberProjectsController@pinnedItem'])->name('projects.pinned-project');
            Route::resource('projects', 'MemberProjectsController');

            Route::resource('project-ratings', 'MemberProjectRatingController');

            Route::get('project-template/data', ['uses' => 'ProjectTemplateController@data'])->name('project-template.data');
            Route::resource('project-template', 'ProjectTemplateController');

            Route::post('project-template-members/save-group', ['uses' => 'ProjectMemberTemplateController@storeGroup'])->name('project-template-members.storeGroup');
            Route::resource('project-template-member', 'ProjectMemberTemplateController');

            Route::resource('project-template-task', 'ProjectTemplateTaskController');

            Route::get('leads/data', ['uses' => 'MemberLeadController@data'])->name('leads.data');
            Route::post('leads/change-status', ['uses' => 'MemberLeadController@changeStatus'])->name('leads.change-status');
            Route::get('leads/follow-up/{leadID}', ['uses' => 'MemberLeadController@followUpCreate'])->name('leads.follow-up');
            Route::get('leads/followup/{leadID}', ['uses' => 'MemberLeadController@followUpShow'])->name('leads.followup');
            Route::post('leads/follow-up-store', ['uses' => 'MemberLeadController@followUpStore'])->name('leads.follow-up-store');
            Route::get('leads/follow-up-edit/{id?}', ['uses' => 'MemberLeadController@editFollow'])->name('leads.follow-up-edit');
            Route::post('leads/follow-up-update', ['uses' => 'MemberLeadController@UpdateFollow'])->name('leads.follow-up-update');
            Route::post('leads/follow-up-delete/{id}', ['uses' => 'MemberLeadController@deleteFollow'])->name('leads.follow-up-delete');
            Route::get('leads/follow-up-sort', ['uses' => 'MemberLeadController@followUpSort'])->name('leads.follow-up-sort');
            Route::resource('leads', 'MemberLeadController');

            // Lead Files
            Route::get('lead-files/download/{id}', ['uses' => 'LeadFilesController@download'])->name('lead-files.download');
            Route::get('lead-files/thumbnail', ['uses' => 'LeadFilesController@thumbnailShow'])->name('lead-files.thumbnail');
            Route::resource('lead-files', 'LeadFilesController');

            Route::resource('task-label', 'TaskLabelController');

            // FAQ
            Route::get('faqs/{id}', ['uses' => 'FaqController@details'])->name('faqs.details');
            Route::get('faqs', ['uses' => 'FaqController@index'])->name('faqs.index');

            //Pinned route
            Route::resource('pinned', 'MemberPinnedController', ['only' => ['store', 'destroy']]);

            // Proposal routes
            Route::get('proposals/data/{id?}', ['uses' => 'MemberProposalController@data'])->name('proposals.data');
            Route::get('proposals/download/{id}', ['uses' => 'MemberProposalController@download'])->name('proposals.download');
            Route::get('proposals/create/{leadID?}', ['uses' => 'MemberProposalController@create'])->name('proposals.create');
            Route::get('proposals/convert-proposal/{id?}', ['uses' => 'MemberProposalController@convertProposal'])->name('proposals.convert-proposal');
            Route::resource('proposals', 'MemberProposalController', ['except' => ['create']]);

            Route::group(
                ['prefix' => 'projects'],
                function () {
                    Route::resource('project-members', 'MemberProjectsMemberController');

                    Route::get('project-notes/verify-password/{id}', ['uses' => 'MemberProjectsNotesController@askForPassword'])->name('project-notes.verify-password');
                     Route::post('project-notes/check-password/{id}', ['uses' => 'MemberProjectsNotesController@checkPassword'])->name('project-notes.check-password');
                    Route::get('project-notes/data/{id}', ['uses' => 'MemberProjectsNotesController@data'])->name('project-notes.data');
                    Route::get('project-notes/view/{id}', ['uses' => 'MemberProjectsNotesController@view'])->name('project-notes.view');
                    Route::resource('project-notes', 'MemberProjectsNotesController');
                   

                    Route::post('tasks/sort', ['uses' => 'MemberTasksController@sort'])->name('tasks.sort');
                    Route::post('tasks/change-status', ['uses' => 'MemberTasksController@changeStatus'])->name('tasks.changeStatus');
                    Route::get('tasks/check-task/{taskID}', ['uses' => 'MemberTasksController@checkTask'])->name('tasks.checkTask');
                    Route::post('tasks/data/{startDate?}/{endDate?}/{hideCompleted?}/{projectId?}', ['uses' => 'MemberTasksController@data'])->name('tasks.data');
                    Route::resource('tasks', 'MemberTasksController');

                    Route::get('files/download/{id}', ['uses' => 'MemberProjectFilesController@download'])->name('files.download');
                    Route::get('files/thumbnail', ['uses' => 'MemberProjectFilesController@thumbnailShow'])->name('files.thumbnail');
                    Route::post('files/multiple-upload', ['uses' => 'MemberProjectFilesController@storeMultiple'])->name('files.multiple-upload');

                    Route::resource('files', 'MemberProjectFilesController');

                    Route::get('time-log/show-log/{id}', ['uses' => 'MemberTimeLogController@showTomeLog'])->name('time-log.show-log');
                    Route::get('time-log/data/{id}', ['uses' => 'MemberTimeLogController@data'])->name('time-log.data');
                    Route::post('time-log/store-time-log', ['uses' => 'MemberTimeLogController@storeTimeLog'])->name('time-log.store-time-log');
                    Route::post('time-log/update-time-log/{id}', ['uses' => 'MemberTimeLogController@updateTimeLog'])->name('time-log.update-time-log');
                    Route::resource('time-log', 'MemberTimeLogController');

                    Route::get('milestones/detail/{id}', ['uses' => 'MemberProjectMilestonesController@detail'])->name('milestones.detail');
                    Route::get('milestones/data/{id}', ['uses' => 'MemberProjectMilestonesController@data'])->name('milestones.data');
                    Route::resource('milestones', 'MemberProjectMilestonesController');
                }
            );

            //sticky note
            Route::resource('sticky-note', 'MemberStickyNoteController');

            // User message
            Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'MemberChatController@postChatMessage']);
            Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'MemberChatController@getUserSearch']);
            Route::resource('user-chat', 'MemberChatController');

            //Notice
            Route::get('notices/data', ['uses' => 'MemberNoticesController@data'])->name('notices.data');
            Route::resource('notices', 'MemberNoticesController');

            // task routes
            Route::resource('task', 'MemberAllTasksController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
            Route::group(
                ['prefix' => 'task'],
                function () {

                    Route::get('all-tasks/dependent-tasks/{projectId}/{taskId?}', ['uses' => 'MemberAllTasksController@dependentTaskLists'])->name('all-tasks.dependent-tasks');
                    Route::post('all-tasks/data/{hideCompleted?}/{projectId?}', ['uses' => 'MemberAllTasksController@data'])->name('all-tasks.data');
                    Route::get('all-tasks/members/{projectId}', ['uses' => 'MemberAllTasksController@membersList'])->name('all-tasks.members');
                    Route::get('all-tasks/ajaxCreate/{columnId?}', ['uses' => 'MemberAllTasksController@ajaxCreate'])->name('all-tasks.ajaxCreate');
                    Route::get('all-tasks/reminder/{taskid}', ['uses' => 'MemberAllTasksController@remindForTask'])->name('all-tasks.reminder');
                    Route::get('all-tasks/history/{taskid}', ['uses' => 'MemberAllTasksController@history'])->name('all-tasks.history');
                    Route::get('all-tasks/files/{taskid}', ['uses' => 'MemberAllTasksController@showFiles'])->name('all-tasks.show-files');
                    Route::get('all-tasks/pinned-task', ['uses' => 'MemberAllTasksController@pinnedItem'])->name('all-tasks.pinned-task');
                    Route::resource('all-tasks', 'MemberAllTasksController');

                    // taskboard resource
                    Route::post('taskboard/updateIndex', ['as' => 'taskboard.updateIndex', 'uses' => 'MemberTaskboardController@updateIndex']);
                    Route::resource('taskboard', 'MemberTaskboardController');

                    // task calendar routes
                    Route::resource('task-calendar', 'MemberCalendarController');

                    Route::get('task-files/download/{id}', ['uses' => 'TaskFilesController@download'])->name('task-files.download');
                    Route::resource('task-files', 'TaskFilesController');
                }
            );

            Route::resource('finance', 'MemberExpensesController', ['only' => ['edit', 'update', 'index']]); // hack to make left admin menu item active
            Route::group(
                ['prefix' => 'finance'],
                function () {

                    // Estimate routes
                    Route::get('estimates/data', ['uses' => 'MemberEstimatesController@data'])->name('estimates.data');
                    Route::get('estimates/download/{id}', ['uses' => 'MemberEstimatesController@download'])->name('estimates.download');
                    Route::post('estimates/send-estimate/{id}', ['uses' => 'MemberEstimatesController@sendEstimate'])->name('estimates.send-estimate');
                    Route::resource('estimates', 'MemberEstimatesController');

                    //Expenses routes
                    Route::get('expenses/data', ['uses' => 'MemberExpensesController@data'])->name('expenses.data');
                    Route::resource('expenses', 'MemberExpensesController');

                    // All invoices list routes
                    Route::post('file/store', ['uses' => 'MemberAllInvoicesController@storeFile'])->name('invoiceFile.store');
                    Route::delete('file/destroy', ['uses' => 'MemberAllInvoicesController@destroyFile'])->name('invoiceFile.destroy');
                    Route::get('all-invoices/data', ['uses' => 'MemberAllInvoicesController@data'])->name('all-invoices.data');
                    Route::get('all-invoices/download/{id}', ['uses' => 'MemberAllInvoicesController@download'])->name('all-invoices.download');
                    Route::get('all-invoices/convert-estimate/{id}', ['uses' => 'MemberAllInvoicesController@convertEstimate'])->name('all-invoices.convert-estimate');
                    Route::get('all-invoices/update-item', ['uses' => 'MemberAllInvoicesController@addItems'])->name('all-invoices.update-item');
                    Route::get('all-invoices/payment-detail/{invoiceID}', ['uses' => 'MemberAllInvoicesController@paymentDetail'])->name('all-invoices.payment-detail');
                    Route::get('all-invoices/get-client-company/{projectID?}', ['uses' => 'MemberAllInvoicesController@getClientOrCompanyName'])->name('all-invoices.get-client-company');
                    Route::get('all-invoices/check-shipping-address', ['uses' => 'MemberAllInvoicesController@checkShippingAddress'])->name('all-invoices.checkShippingAddress');
                    Route::get('all-invoices/toggle-shipping-address/{invoice}', ['uses' => 'MemberAllInvoicesController@toggleShippingAddress'])->name('all-invoices.toggleShippingAddress');
                    Route::get('all-invoices/shipping-address-modal/{invoice}', ['uses' => 'MemberAllInvoicesController@shippingAddressModal'])->name('all-invoices.shippingAddressModal');
                    Route::post('all-invoices/add-shipping-address/{user}', ['uses' => 'MemberAllInvoicesController@addShippingAddress'])->name('all-invoices.addShippingAddress');
                    Route::get('all-invoices/update-status/{invoiceID}', ['uses' => 'MemberAllInvoicesController@cancelStatus'])->name('all-invoices.update-status');
                    Route::post('all-invoices/send-invoice/{invoiceID}', ['uses' => 'MemberAllInvoicesController@sendInvoice'])->name('all-invoices.send-invoice');

                    Route::resource('all-invoices', 'MemberAllInvoicesController');

                    // All Credit Note routes
                    Route::post('credit-file/store', ['uses' => 'MemberAllCreditNotesController@storeFile'])->name('creditNoteFile.store');
                    Route::delete('credit-file/destroy', ['uses' => 'MemberAllCreditNotesController@destroyFile'])->name('creditNoteFile.destroy');
                    Route::get('all-credit-notes/data', ['uses' => 'MemberAllCreditNotesController@data'])->name('all-credit-notes.data');
                    Route::get('all-credit-notes/download/{id}', ['uses' => 'MemberAllCreditNotesController@download'])->name('all-credit-notes.download');
                    Route::get('all-credit-notes/convert-invoice/{id}', ['uses' => 'MemberAllCreditNotesController@convertInvoice'])->name('all-credit-notes.convert-invoice');
                    Route::get('all-credit-notes/update-item', ['uses' => 'MemberAllCreditNotesController@addItems'])->name('all-credit-notes.update-item');
                    Route::get('all-credit-notes/payment-detail/{creditNoteID}', ['uses' => 'MemberAllCreditNotesController@paymentDetail'])->name('all-credit-notes.payment-detail');
                    Route::resource('all-credit-notes', 'MemberAllCreditNotesController');

                    //Payments routes
                    Route::get('payments/data', ['uses' => 'MemberPaymentsController@data'])->name('payments.data');
                    Route::get('payments/pay-invoice/{invoiceId}', ['uses' => 'MemberPaymentsController@payInvoice'])->name('payments.payInvoice');
                    Route::resource('payments', 'MemberPaymentsController');
                }
            );

            // Ticket reply template routes
            Route::post('replyTemplates/fetch-template', ['uses' => 'MemberTicketReplyTemplatesController@fetchTemplate'])->name('replyTemplates.fetchTemplate');

            //Tickets routes
            Route::post('tickets/data', ['uses' => 'MemberTicketsController@data'])->name('tickets.data');
            Route::post('tickets/storeAdmin', ['uses' => 'MemberTicketsController@storeAdmin'])->name('tickets.storeAdmin');
            Route::post('tickets/updateAdmin/{id}', ['uses' => 'MemberTicketsController@updateAdmin'])->name('tickets.updateAdmin');
            Route::post('tickets/close-ticket/{id}', ['uses' => 'MemberTicketsController@closeTicket'])->name('tickets.closeTicket');
            Route::post('tickets/open-ticket/{id}', ['uses' => 'MemberTicketsController@reopenTicket'])->name('tickets.reopenTicket');
            Route::post('tickets/admin-data', ['uses' => 'MemberTicketsController@adminData'])->name('tickets.adminData');
            Route::post('tickets/refresh-count', ['uses' => 'MemberTicketsController@refreshCount'])->name('tickets.refreshCount');
            Route::get('tickets/reply-delete/{id?}', ['uses' => 'MemberTicketsController@destroyReply'])->name('tickets.reply-delete');
            Route::post('tickets/updateAdminOtherData/{id}', ['uses' => 'MemberTicketsController@updateAdminOtherData'])->name('tickets.updateAdminOtherData');
            Route::resource('tickets', 'MemberTicketsController');

            //Ticket agent routes
            Route::post('ticket-agent/data', ['uses' => 'MemberTicketsAgentController@data'])->name('ticket-agent.data');
            Route::post('ticket-agent/refresh-count', ['uses' => 'MemberTicketsAgentController@refreshCount'])->name('ticket-agent.refreshCount');
            Route::post('ticket-agent/fetch-template', ['uses' => 'MemberTicketsAgentController@fetchTemplate'])->name('ticket-agent.fetchTemplate');
            Route::resource('ticket-agent', 'MemberTicketsAgentController');

            Route::get('ticket-files/download/{id}', ['uses' => 'TicketFilesController@download'])->name('ticket-files.download');
            Route::resource('ticket-files', 'TicketFilesController');

            // attendance
            Route::get('attendances/detail', ['uses' => 'MemberAttendanceController@attendanceDetail'])->name('attendances.detail');
            Route::get('attendances/data', ['uses' => 'MemberAttendanceController@data'])->name('attendances.data');
            Route::get('attendances/check-holiday', ['uses' => 'MemberAttendanceController@checkHoliday'])->name('attendances.check-holiday');
            Route::post('attendances/storeAttendance', ['uses' => 'MemberAttendanceController@storeAttendance'])->name('attendances.storeAttendance');
            Route::get('attendances/employeeData/{startDate?}/{endDate?}/{userId?}', ['uses' => 'MemberAttendanceController@employeeData'])->name('attendances.employeeData');
            Route::get('attendances/refresh-count/{startDate?}/{endDate?}/{userId?}', ['uses' => 'MemberAttendanceController@refreshCount'])->name('attendances.refreshCount');
            Route::post('attendances/storeMark', ['uses' => 'MemberAttendanceController@storeMark'])->name('attendances.storeMark');
            Route::get('attendances/mark/{id}/{day}/{month}/{year}', ['uses' => 'MemberAttendanceController@mark'])->name('attendances.mark');
            Route::get('attendances/summary', ['uses' => 'MemberAttendanceController@summary'])->name('attendances.summary');
            Route::post('attendances/summaryData', ['uses' => 'MemberAttendanceController@summaryData'])->name('attendances.summaryData');
            Route::get('attendances/info/{id}', ['uses' => 'MemberAttendanceController@detail'])->name('attendances.info');
            Route::post('attendances/updateDetails/{id}', ['uses' => 'MemberAttendanceController@updateDetails'])->name('attendances.updateDetails');
            Route::resource('attendances', 'MemberAttendanceController');

            // Holidays
            Route::get('holidays/view-holiday/{year?}', 'MemberHolidaysController@viewHoliday')->name('holidays.view-holiday');
            Route::get('holidays/calendar-month', 'MemberHolidaysController@getCalendarMonth')->name('holidays.calendar-month');
            Route::get('holidays/mark_sunday', 'MemberHolidaysController@Sunday')->name('holidays.mark-sunday');
            Route::get('holidays/calendar/{year?}', 'MemberHolidaysController@holidayCalendar')->name('holidays.calendar');
            Route::get('holidays/mark-holiday', 'MemberHolidaysController@markHoliday')->name('holidays.mark-holiday');
            Route::post('holidays/mark-holiday-store', 'MemberHolidaysController@markDayHoliday')->name('holidays.mark-holiday-store');
            Route::resource('holidays', 'MemberHolidaysController');

            // events
            Route::post('events/removeAttendee', ['as' => 'events.removeAttendee', 'uses' => 'MemberEventController@removeAttendee']);
            Route::resource('events', 'MemberEventController');


            // clients
            Route::group(
                ['prefix' => 'clients'],
                function () {
                    Route::get('projects/{id}', ['uses' => 'MemberClientsController@showProjects'])->name('clients.projects');
                    Route::get('invoices/{id}', ['uses' => 'MemberClientsController@showInvoices'])->name('clients.invoices');

                    Route::get('contacts/data/{id}', ['uses' => 'MemberClientContactController@data'])->name('contacts.data');
                    Route::resource('contacts', 'MemberClientContactController');
                }
            );

            Route::get('clients/data', ['uses' => 'MemberClientsController@data'])->name('clients.data');
            Route::get('clients/create/{clientID?}', ['uses' => 'MemberClientsController@create'])->name('clients.create');
            Route::resource('clients', 'MemberClientsController', ['except' => ['create']]);


            Route::get('employees/docs-create/{id}', ['uses' => 'MemberEmployeesController@docsCreate'])->name('employees.docs-create');
            Route::get('employees/tasks/{userId}/{hideCompleted}', ['uses' => 'MemberEmployeesController@tasks'])->name('employees.tasks');
            Route::get('employees/time-logs/{userId}', ['uses' => 'MemberEmployeesController@timeLogs'])->name('employees.time-logs');
            Route::get('employees/data', ['uses' => 'MemberEmployeesController@data'])->name('employees.data');
            Route::get('employees/export', ['uses' => 'MemberEmployeesController@export'])->name('employees.export');
            Route::post('employees/assignRole', ['uses' => 'MemberEmployeesController@assignRole'])->name('employees.assignRole');
            Route::post('employees/assignProjectAdmin', ['uses' => 'MemberEmployeesController@assignProjectAdmin'])->name('employees.assignProjectAdmin');
            Route::resource('employees', 'MemberEmployeesController');

            Route::get('employee-docs/download/{id}', ['uses' => 'MemberEmployeeDocsController@download'])->name('employee-docs.download');
            Route::resource('employee-docs', 'MemberEmployeeDocsController');

            Route::get('all-time-logs/show-active-timer', ['uses' => 'MemberAllTimeLogController@showActiveTimer'])->name('all-time-logs.show-active-timer');
            Route::post('all-time-logs/stop-timer/{id}', ['uses' => 'MemberAllTimeLogController@stopTimer'])->name('all-time-logs.stopTimer');
            Route::post('all-time-logs/data/{projectId?}/{employee?}', ['uses' => 'MemberAllTimeLogController@data'])->name('all-time-logs.data');
            Route::get('all-time-logs/members/{projectId}', ['uses' => 'MemberAllTimeLogController@membersList'])->name('all-time-logs.members');
            Route::get('all-time-logs/task-members/{taskId}', ['uses' => 'MemberAllTimeLogController@taskMembersList'])->name('all-time-logs.task-members');
            Route::post('all-time-logs/approve-timelog', ['uses' => 'MemberAllTimeLogController@approveTimelog'])->name('all-time-logs.approve-timelog');
            Route::resource('all-time-logs', 'MemberAllTimeLogController');

            Route::post('leaves/leaveAction', ['as' => 'leaves.leaveAction', 'uses' => 'MemberLeavesController@leaveAction']);
            Route::get('leaves/data', ['as' => 'leaves.data', 'uses' => 'MemberLeavesController@data']);
            Route::resource('leaves', 'MemberLeavesController');

            Route::post('leaves-dashboard/leaveAction', ['as' => 'leaves-dashboard.leaveAction', 'uses' => 'MemberLeaveDashboardController@leaveAction']);
            Route::resource('leaves-dashboard', 'MemberLeaveDashboardController');

            //sub task routes
            Route::post('sub-task/changeStatus', ['as' => 'sub-task.changeStatus', 'uses' => 'MemberSubTaskController@changeStatus']);
            Route::resource('sub-task', 'MemberSubTaskController');

            //task comments
            Route::resource('task-comment', 'MemberTaskCommentController');

            //task notes
            Route::resource('task-note', 'MemberTaskNoteController');

            //region Products Routes
            Route::get('products/data', ['uses' => 'MemberProductController@data'])->name('products.data');
            Route::resource('products', 'MemberProductController');
            //endregion


            //region discussion routes
            Route::post('discussion/setBestAnswer', ['as' => 'discussion.setBestAnswer', 'uses' => 'MemberDiscussionController@setBestAnswer']);
            Route::resource('discussion', 'MemberDiscussionController');
            //endregion

            //region discussion routes
            Route::resource('discussion-reply', 'MemberDiscussionReplyController');
            //endregion
            //region contracts routes
            Route::get('contracts/sign/{id}', ['as' => 'contracts.sign-modal', 'uses' => 'MemberContractController@signModal']);
            Route::get('contracts/download/{id}', ['as' => 'contracts.download', 'uses' => 'MemberContractController@download']);
            Route::post('contracts/sign/{id}', ['as' => 'contracts.sign', 'uses' => 'MemberContractController@sign']);
            Route::get('contracts/copy/{id}', ['as' => 'contracts.copy', 'uses' => 'MemberContractController@copy']);
            Route::post('contracts/copy-submit', ['as' => 'contracts.copy-submit', 'uses' => 'MemberContractController@copySubmit']);
            Route::post('contracts/add-discussion/{id}', ['as' => 'contracts.add-discussion', 'uses' => 'MemberContractController@addDiscussion']);
            Route::get('contracts/edit-discussion/{id}', ['as' => 'contracts.edit-discussion', 'uses' => 'MemberContractController@editDiscussion']);
            Route::post('contracts/update-discussion/{id}', ['as' => 'contracts.update-discussion', 'uses' => 'MemberContractController@updateDiscussion']);
            Route::post('contracts/remove-discussion/{id}', ['as' => 'contracts.remove-discussion', 'uses' => 'MemberContractController@removeDiscussion']);
            Route::resource('contracts', 'MemberContractController');
            //endregion

            //region contracts type routes
            Route::get('contract-type/data', ['as' => 'contract-type.data', 'uses' => 'MemberContractTypeController@data']);
            Route::post('contract-type/type-store', ['as' => 'contract-type.store-contract-type', 'uses' => 'MemberContractTypeController@storeContractType']);
            Route::get('contract-type/type-create', ['as' => 'contract-type.create-contract-type', 'uses' => 'MemberContractTypeController@createContractType']);

            Route::resource('contract-type', 'MemberContractTypeController')->parameters([
                'contract-type' => 'type'
            ]);
            //endregion

            //region contract renew routes
            Route::get('contract-renew/{id}', ['as' => 'contracts.renew', 'uses' => 'MemberContractRenewController@index']);
            Route::post('contract-renew-submit/{id}', ['as' => 'contracts.renew-submit', 'uses' => 'MemberContractRenewController@renew']);
            Route::post('contract-renew-remove/{id}', ['as' => 'contracts.renew-remove', 'uses' => 'MemberContractRenewController@destroy']);
            //endregion

        }
    );

    // Client routes
    Route::group(
        ['namespace' => 'Client', 'prefix' => 'client', 'as' => 'client.', 'middleware' => []],
        function () {

            Route::resource('dashboard', 'ClientDashboardController');

            Route::resource('profile', 'ClientProfileController');

            // Project section
            Route::get('projects/data', ['uses' => 'ClientProjectsController@data'])->name('projects.data');
            Route::resource('projects', 'ClientProjectsController');

            Route::group(
                ['prefix' => 'projects'],
                function () {

                    Route::resource('project-members', 'ClientProjectMembersController');

                    Route::post('tasks/data/{projectId?}', ['uses' => 'ClientTasksController@data'])->name('tasks.data');
                    Route::get('tasks/ajax-edit/{taskId?}', ['uses' => 'ClientTasksController@ajaxEdit'])->name('tasks.ajax-edit');
                    Route::get('tasks/check-task/{taskID}', ['uses' => 'ClientTasksController@checkTask'])->name('tasks.checkTask');
                    Route::resource('tasks', 'ClientTasksController');

                    Route::get('files/download/{id}', ['uses' => 'ClientFilesController@download'])->name('files.download');
                    Route::get('files/thumbnail', ['uses' => 'ClientFilesController@thumbnailShow'])->name('files.thumbnail');
                    Route::resource('files', 'ClientFilesController');

                    Route::get('time-log/data/{id}', ['uses' => 'ClientTimeLogController@data'])->name('time-log.data');
                    Route::resource('time-log', 'ClientTimeLogController');

                    Route::get('project-invoice/download/{id}', ['uses' => 'ClientProjectInvoicesController@download'])->name('project-invoice.download');
                    Route::resource('project-invoice', 'ClientProjectInvoicesController');

                    Route::resource('project-expenses', 'ClientProjectExpensesController');
                    Route::resource('project-payments', 'ClientProjectPaymentsController');

                    // Project Ratings
                    Route::resource('project-ratings', 'ClientProjectRatingController');

                    Route::get('milestones/data/{id}', ['uses' => 'ClientProjectMilestonesController@data'])->name('milestones.data');
                    Route::resource('milestones', 'ClientProjectMilestonesController');
                }
            );

            //region Products Routes
            Route::get('products/data', ['uses' => 'ClientProductController@data'])->name('products.data');
            Route::get('products/update-item', ['uses' => 'ClientProductController@addItems'])->name('products.update-item');
            Route::get('products/add-cart-item', ['uses' => 'ClientProductController@addCartItem'])->name('products.add-cart-item');
            Route::get('products/remove-cart-item/{productid}', ['uses' => 'ClientProductController@removeCartItem'])->name('products.remove-cart-item');

            Route::resource('products', 'ClientProductController');
            Route::get('notes/view/{id}', ['uses' => 'ClientNotesController@askForPassword'])->name('notes.view');
            Route::post('notes/check-password/{id}', ['uses' => 'ClientNotesController@checkPassword'])->name('notes.check-password');


            Route::get('notes/data', ['uses' => 'ClientNotesController@data'])->name('notes.data');

            Route::resource('notes', 'ClientNotesController');

            Route::get('project-notes/data/{id}', ['uses' => 'ClientProjectNotesController@data'])->name('project-notes.data');
            
            Route::get('project-notes/verify-password/{id}', ['uses' => 'ClientProjectNotesController@askForPassword'])->name('project-notes.verify-password');
            Route::post('project-notes/check-password/{id}', ['uses' => 'ClientProjectNotesController@checkPassword'])->name('project-notes.check-password');

            Route::resource('project-notes', 'ClientProjectNotesController');
            Route::get('project-notes/view/{id}', ['uses' => 'ClientProjectNotesController@view'])->name('project-notes.view');
            //sticky note
            Route::resource('sticky-note', 'ClientStickyNoteController');

            // Invoice Section
            Route::post('invoices/stripe-modal/', ['uses' => 'ClientInvoicesController@stripeModal'])->name('invoices.stripe-modal');
            Route::get('invoices/download/{id}', ['uses' => 'ClientInvoicesController@download'])->name('invoices.download');
            Route::get('invoices/offline-payment', 'ClientInvoicesController@offlinePayment')->name('invoices.offline-payment');
            Route::post('invoices/offline-payment-submit', 'ClientInvoicesController@offlinePaymentSubmit')->name('invoices.offline-payment-submit');

            Route::resource('invoices', 'ClientInvoicesController');

            //Invoice recurring
            Route::post('invoice-recurring/change-status', ['uses' => 'ClientInvoiceRecurringController@changeStatus'])->name('invoice-recurring.changeStatus');
            Route::get('invoice-recurring/export/{startDate}/{endDate}/{status}/{employee}', ['uses' => 'ClientInvoiceRecurringController@export'])->name('invoice-recurring.export');
            Route::get('invoice-recurring/invoice/{id}', ['uses' => 'ClientInvoiceRecurringController@invoice'])->name('invoice-recurring.invoice');
            Route::get('invoice-recurring/recurring-invoice/{id}', ['uses' => 'ClientInvoiceRecurringController@recurringInvoice'])->name('invoice-recurring.recurring-invoice');
            Route::resource('invoice-recurring', 'ClientInvoiceRecurringController');

            // Estimate Section
            Route::get('estimates/download/{id}', ['uses' => 'ClientEstimateController@download'])->name('estimates.download');
            Route::resource('estimates', 'ClientEstimateController');

            //Credit Note
            Route::get('credit-notes/download/{id}', ['uses' => 'ClientCreditNoteController@download'])->name('credit-notes.download');
            Route::resource('credit-notes', 'ClientCreditNoteController');

            //Payments section
            Route::get('payments/data', ['uses' => 'ClientPaymentsController@data'])->name('payments.data');
            Route::resource('payments', 'ClientPaymentsController');

            // Issues section
            Route::get('my-issues/data', ['uses' => 'ClientMyIssuesController@data'])->name('my-issues.data');
            Route::resource('my-issues', 'ClientMyIssuesController');

            // route for view/blade file
            Route::get('paywithpaypal', array('as' => 'paywithpaypal', 'uses' => 'PaypalController@payWithPaypal',));

            // change language
            Route::get('language/change-language', ['uses' => 'ClientProfileController@changeLanguage'])->name('language.change-language');
            // change company
            Route::get('company/change-company', ['uses' => 'ClientProfileController@changeCompany'])->name('company.change-company');
            // login admin
            Route::get('company/login-admin', ['uses' => 'ClientProfileController@loginAdmin'])->name('company.login-admin');

            Route::get('ticket-files/download/{id}', ['uses' => 'TicketFilesController@download'])->name('ticket-files.download');
            Route::resource('ticket-files', 'TicketFilesController');

            //Tickets routes
            Route::get('tickets/data', ['uses' => 'ClientTicketsController@data'])->name('tickets.data');
            Route::post('tickets/close-ticket/{id}', ['uses' => 'ClientTicketsController@closeTicket'])->name('tickets.closeTicket');
            Route::post('tickets/open-ticket/{id}', ['uses' => 'ClientTicketsController@reopenTicket'])->name('tickets.reopenTicket');
            Route::resource('tickets', 'ClientTicketsController');

            Route::resource('events', 'ClientEventController');

            Route::post('gdpr/update-consent', ['uses' => 'ClientGdprController@updateConsent'])->name('gdpr.update-consent');
            Route::get('gdpr/consent', ['uses' => 'ClientGdprController@consent'])->name('gdpr.consent');
            Route::get('gdpr/download', ['uses' => 'ClientGdprController@downloadJSON'])->name('gdpr.download-json');
            Route::post('gdpr/remove-request', ['uses' => 'ClientGdprController@removeRequest'])->name('gdpr.remove-request');
            Route::get('privacy-policy', ['uses' => 'ClientGdprController@privacy'])->name('gdpr.privacy');
            Route::get('terms-and-condition', ['uses' => 'ClientGdprController@terms'])->name('gdpr.terms');
            Route::resource('gdpr', 'ClientGdprController');

            // User message
            Route::post('message-submit', ['as' => 'user-chat.message-submit', 'uses' => 'ClientChatController@postChatMessage']);
            Route::get('user-search', ['as' => 'user-chat.user-search', 'uses' => 'ClientChatController@getUserSearch']);
            Route::resource('user-chat', 'ClientChatController');

            //task comments
            Route::resource('task-comment', 'ClientTaskCommentController');

            Route::post('pay-with-razorpay', array('as' => 'pay-with-razorpay', 'uses' => 'RazorPayController@payWithRazorPay',));

            //region contracts routes
            Route::get('contracts/data', ['as' => 'contracts.data', 'uses' => 'ClientContractController@data']);
            Route::get('contracts/download/{id}', ['as' => 'contracts.download', 'uses' => 'ClientContractController@download']);
            Route::get('contracts/sign/{id}', ['as' => 'contracts.sign-modal', 'uses' => 'ClientContractController@signModal']);
            Route::post('contracts/sign/{id}', ['as' => 'contracts.sign', 'uses' => 'ClientContractController@sign']);
            Route::post('contracts/add-discussion/{id}', ['as' => 'contracts.add-discussion', 'uses' => 'ClientContractController@addDiscussion']);
            Route::get('contracts/edit-discussion/{id}', ['as' => 'contracts.edit-discussion', 'uses' => 'ClientContractController@editDiscussion']);
            Route::post('contracts/update-discussion/{id}', ['as' => 'contracts.update-discussion', 'uses' => 'ClientContractController@updateDiscussion']);
            Route::post('contracts/remove-discussion/{id}', ['as' => 'contracts.remove-discussion', 'uses' => 'ClientContractController@removeDiscussion']);
            Route::resource('contracts', 'ClientContractController');
            //endregion

            //Notice
            Route::get('notices/data', ['uses' => 'ClientNoticesController@data'])->name('notices.data');
            Route::resource('notices', 'ClientNoticesController');
        }
    );


    // Mark all notifications as readu
    Route::post('show-admin-notifications', ['uses' => 'NotificationController@showAdminNotifications'])->name('show-admin-notifications');
    Route::post('show-user-notifications', ['uses' => 'NotificationController@showUserNotifications'])->name('show-user-notifications');
    Route::post('show-client-notifications', ['uses' => 'NotificationController@showClientNotifications'])->name('show-client-notifications');
    Route::post('mark-notification-read', ['uses' => 'NotificationController@markAllRead'])->name('mark-notification-read');
    Route::get('show-all-member-notifications', ['uses' => 'NotificationController@showAllMemberNotifications'])->name('show-all-member-notifications');
    Route::get('show-all-client-notifications', ['uses' => 'NotificationController@showAllClientNotifications'])->name('show-all-client-notifications');
    Route::get('show-all-admin-notifications', ['uses' => 'NotificationController@showAllAdminNotifications'])->name('show-all-admin-notifications');

    Route::post('show-superadmin-user-notifications', ['uses' => 'SuperAdmin\NotificationController@showUserNotifications'])->name('show-superadmin-user-notifications');
    Route::post('mark-superadmin-notification-read', ['uses' => 'SuperAdmin\NotificationController@markAllRead'])->name('mark-superadmin-notification-read');
    Route::get('show-all-super-admin-notifications', ['uses' => 'SuperAdmin\NotificationController@showAllSuperAdminNotifications'])->name('show-all-super-admin-notifications');
});
