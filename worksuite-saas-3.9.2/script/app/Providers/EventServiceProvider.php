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
        'App\Events\CompanyRegistered' => ['App\Listeners\CompanyRegisteredListener'],
        'App\Events\TaskEvent' => ['App\Listeners\TaskListener'],
        'App\Events\TaskReminderEvent' => ['App\Listeners\TaskReminderListener'],
        'App\Events\TaskCommentEvent' => ['App\Listeners\TaskCommentListener'],
        'App\Events\AutoTaskReminderEvent' => ['App\Listeners\AutoTaskReminderListener'],
        'App\Events\SubTaskCompletedEvent' => ['App\Listeners\SubTaskCompletedListener'],
        'App\Events\DiscussionReplyEvent' => ['App\Listeners\DiscussionReplyListener'],
        'App\Events\DiscussionEvent' => ['App\Listeners\DiscussionListener'],
        'App\Events\TicketReplyEvent' => ['App\Listeners\TicketReplyListener'],
        'App\Events\TaskNoteEvent' => ['App\Listeners\TaskNoteListener'],
        'App\Events\TicketRequesterEvent' => ['App\Listeners\TicketRequesterListener'],
        'App\Events\NewExpenseRecurringEvent' => ['App\Listeners\NewExpenseRecurringListener'],
        'App\Events\NewInvoiceRecurringEvent' => ['App\Listeners\NewInvoiceRecurringListener'],
        'App\Events\NewCreditNoteEvent' => ['App\Listeners\NewCreditNoteListener'],
        'App\Events\LeadEvent' => ['App\Listeners\LeadListener'],
        'App\Events\NewSupportTicketEvent' => ['App\Listeners\NewSupportTicketListener'],
        'App\Events\SupportTicketAgentEvent' => ['App\Listeners\SupportTicketAgentListener'],
        'App\Events\SupportTicketReplyEvent' => ['App\Listeners\SupportTicketReplyListener'],
        'App\Events\SupportTicketRequesterEvent' => ['App\Listeners\SupportTicketRequesterListener'],
        'App\Events\NewProjectEvent' => ['App\Listeners\NewProjectListener'],
        'App\Events\ProjectFileEvent' => ['App\Listeners\ProjectFileListener'],
        'App\Events\RatingEvent' => ['App\Listeners\RatingListener'],
        'App\Events\LeaveEvent' => ['App\Listeners\LeaveListener'],
        'App\Events\NewExpenseEvent' => ['App\Listeners\NewExpenseListener'],
        'App\Events\NewNoticeEvent' => ['App\Listeners\NewNoticeListener'],
        'App\Events\NewProjectMemberEvent' => ['App\Listeners\NewProjectMemberListener'],
        'App\Events\PaymentReminderEvent' => ['App\Listeners\PaymentReminderListener'],
        'App\Events\NewProposalEvent' => ['App\Listeners\NewProposalListener'],
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
