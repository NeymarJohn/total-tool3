<?php

namespace App\Listeners;

use App\Events\FileUploadEvent;
use App\Notifications\FileUpload;
use App\Project;
use App\User;
use Illuminate\Support\Facades\Notification;

class FileUploadListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FileUploadEvent  $event
     * @return void
     */
    public function handle(FileUploadEvent $event)
    {
        $project = Project::findOrFail($event->fileUpload->project_id);
        Notification::send($project->members_many, new FileUpload($event->fileUpload));

        if (($event->fileUpload->project->client_id != null)) {
            // Notify client
            $notifyUser = User::withoutGlobalScopes(['active'])->findOrFail($event->fileUpload->project->client_id);

            if ($notifyUser) {
                Notification::send($notifyUser, new FileUpload($event->fileUpload));
            }
        }

    }
}
