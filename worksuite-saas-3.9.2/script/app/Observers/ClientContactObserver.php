<?php

namespace App\Observers;

use App\ClientContact;

class ClientContactObserver
{
    public function saving(ClientContact $client_contact)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $client_contact->company_id = company()->id;
        }
    }
}
