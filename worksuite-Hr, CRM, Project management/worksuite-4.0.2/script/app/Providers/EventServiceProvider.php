<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'App\Events\SubTaskCompletedEvent' => ['App\Listeners\SubTaskCompletedListener'],
        'App\Events\NewUserEvent' => ['App\Listeners\NewUserListener'],
        'App\Events\NewContractEvent' => ['App\Listeners\NewContractListener'],
        'App\Events\NewEstimateEvent' => ['App\Listeners\NewEstimateListener'],
        'App\Events\NewExpenseEvent' => ['App\Listeners\NewExpenseListener'],
        'App\Events\FileUploadEvent' => ['App\Listeners\FileUploadListener'],
        'App\Events\NewInvoiceEvent' => ['App\Listeners\NewInvoiceListener'],
        'App\Events\InvoicePaymentReceivedEvent' => ['App\Listeners\InvoicePaymentReceivedListener'],
        'App\Events\NewIssueEvent' => ['App\Listeners\NewIssueListener'],
        'App\Events\LeaveEvent' => ['App\Listeners\LeaveListener'],
        'App\Events\NewChatEvent' => ['App\Listeners\NewChatListener'],
        'App\Events\NewNoticeEvent' => ['App\Listeners\NewNoticeListener'],
        'App\Events\NewPaymentEvent' => ['App\Listeners\NewPaymentListener'],
        'App\Events\NewProjectMemberEvent' => ['App\Listeners\NewProjectMemberListener'],
        'App\Events\RemovalRequestAdminLeadEvent' => ['App\Listeners\RemovalRequestAdminLeadListener'],
        'App\Events\RemovalRequestAdminEvent' => ['App\Listeners\RemovalRequestAdminListener'],
        'App\Events\RemovalRequestApprovedRejectLeadEvent' => ['App\Listeners\RemovalRequestApprovedRejectLeadListener'],
        'App\Events\RemovalRequestApprovedRejectUserEvent' => ['App\Listeners\RemovalRequestApprovedRejectUserListener'],
        'App\Events\TaskCommentEvent' => ['App\Listeners\TaskCommentListener'],
        'App\Events\TaskNoteEvent' => ['App\Listeners\TaskNoteListener'],
        'App\Events\TaskEvent' => ['App\Listeners\TaskListener'],
        'App\Events\TicketEvent' => ['App\Listeners\TicketListener'],
        'App\Events\TicketReplyEvent' => ['App\Listeners\TicketReplyListener'],
        'App\Events\EventInviteEvent' => ['App\Listeners\EventInviteListener'],
        'App\Events\ProjectReminderEvent' => ['App\Listeners\ProjectReminderListener'],
        'App\Events\PaymentReminderEvent' => ['App\Listeners\PaymentReminderListener'],
        'App\Events\AutoTaskReminderEvent' => ['App\Listeners\AutoTaskReminderListener'],
        'App\Events\TaskReminderEvent' => ['App\Listeners\TaskReminderListener'],
        'App\Events\EventReminderEvent' => ['App\Listeners\EventReminderListener'],
        'App\Events\LeadEvent' => ['App\Listeners\LeadListener'],
        'App\Events\DiscussionReplyEvent' => ['App\Listeners\DiscussionReplyListener'],
        'App\Events\DiscussionEvent' => ['App\Listeners\DiscussionListener'],
        'App\Events\EstimateDeclinedEvent' => ['App\Listeners\EstimateDeclinedListener'],
        'App\Events\NewProposalEvent' => ['App\Listeners\NewProposalListener'],
        'App\Events\TicketRequesterEvent' => ['App\Listeners\TicketRequesterListener'],
        'App\Events\RemovalRequestApproveRejectEvent' => ['App\Listeners\RemovalRequestApprovedRejectListener'],
        'App\Events\NewExpenseRecurringEvent' => ['App\Listeners\NewExpenseRecurringListener'],
        'App\Events\NewInvoiceRecurringEvent' => ['App\Listeners\NewInvoiceRecurringListener'],
        'App\Events\NewCreditNoteEvent' => ['App\Listeners\NewCreditNoteListener'],
        'App\Events\NewProjectEvent' => ['App\Listeners\NewProjectListener'],
        'App\Events\NewProductPurchaseEvent' => ['App\Listeners\NewProductPurchaseListener'],
        'App\Events\RatingEvent' => ['App\Listeners\RatingListener'],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
