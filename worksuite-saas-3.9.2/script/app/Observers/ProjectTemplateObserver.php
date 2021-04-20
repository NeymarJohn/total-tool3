<?php

namespace App\Observers;

use App\ProjectTemplate;

class ProjectTemplateObserver
{

    public function saving(ProjectTemplate $projectTemplate)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $projectTemplate->company_id = company()->id;
        }
    }

}
