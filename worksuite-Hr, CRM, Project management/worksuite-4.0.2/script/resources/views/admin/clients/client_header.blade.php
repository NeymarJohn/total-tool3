<div class="col-md-12">
    <div class="white-box">

        <div class="row m-t-20">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body b-all border-radius">
                            <div class="row">
                                
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <img src="{{ $client->image_url }}" width="75" height="75" class="img-circle" alt="">
                                        </div>
                                        <div class="col-xs-9">
                                            <p>
                                                <span class="font-medium text-info font-semi-bold">{{ ucwords($client->name) }}</span>
                                                <br>

                                                @if (!empty($client->client_details) && $client->client_details->company_name != '')
                                                   <span class="text-muted">{{ $client->client_details->company_name }}</span>  
                                                @endif
                                            </p>
                                            
                                            <p class="font-12">
                                                @lang('app.lastLogin'): 

                                                @if (!is_null($client->last_login)) 
                                                {{ $client->last_login->timezone($global->timezone)->format($global->date_format.' '.$global->time_format) }}
                                                @else
                                                --
                                                @endif
                                            </p>
            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7 b-l">
                                    <div class="row project-top-stats">

                                        <div class="col-md-6 m-b-20 m-t-10 text-center">
                                            <span class="text-primary">
                                                {{ $clientStats->totalProjects}}
                                            </span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalProjects')</span>
                                        </div>
        
                                        <div class="col-md-4 m-b-20 m-t-10 text-center">
                                            <span class="text-danger">
                                                {{ $clientStats->totalUnpaidInvoices}}
                                            </span> <span class="font-12 text-muted m-l-5"> @lang('modules.dashboard.totalUnpaidInvoices')</span>
                                        </div>
        
                                    </div>
                                    
                                    <div class="row project-top-stats">

                                        <div class="col-md-6 m-b-20 m-t-10 text-center">
                                            <span class="text-success">
                                                {{ $clientStats->projectPayments + $clientStats->invoicePayments }}
                                            </span> <span class="font-12 text-muted m-l-5"> @lang('app.earnings')</span>
                                        </div>
        
                                        <div class="col-md-4 m-b-20 m-t-10 text-center">
                                            <span class="text-primary">
                                                {{ $clientStats->totalContracts}}
                                            </span> <span class="font-12 text-muted m-l-5"> @lang('modules.contracts.totalContracts')</span>
                                        </div>
                        
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>