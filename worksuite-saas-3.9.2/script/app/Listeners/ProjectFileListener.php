<?php

namespace App\Listeners;


use App\Events\ProjectFileEvent;
use App\Notifications\FileUpload;
use App\Project;
use App\Scopes\CompanyScope;
use App\User;
use Illuminate\Support\Facades\Notification;

class ProjectFileListener
{
    /**
     * NewExpenseRecurringListener constructor.
     */
    public function __construct()
    {
        //
    }


    /**
     * @param ProjectFileEvent $event
     */
    public function handle(ProjectFileEvent $event)
    {
        $project = Project::with('members', 'members.user')->findOrFail($event->file->project_id);
        foreach ($project->members as $member) {
            Notification::send($member->user, new FileUpload($event->file));
        }

        if (($event->file->project->client_id != null)) {
            // Notify client
            $notifyUser = User::withoutGlobalScopes([CompanyScope::class, 'active'])->findOrFail($event->file->project->client_id);

            if ($notifyUser) {
                Notification::send($notifyUser, new FileUpload($event->file));
            }
        }


    }
}
