<?php

namespace App\Observers;


use App\ClientDocs;

class ClientDocsObserver
{

    public function saving(ClientDocs $clientDocs)
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $clientDocs->company_id = company()->id;
        }
    }

}
