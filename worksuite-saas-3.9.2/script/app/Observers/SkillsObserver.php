<?php

namespace App\Observers;

use App\Skill;

class SkillsObserver
{

    public function saving(Skill $skill)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $skill->company_id = company()->id;
        }
    }

}
