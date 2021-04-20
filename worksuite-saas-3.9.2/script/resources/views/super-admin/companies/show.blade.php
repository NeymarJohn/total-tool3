<div class="rpanel-title"> @lang('app.company') @lang('app.details')
    <span><i class="ti-close right-side-toggle"></i></span> 
</div>
<div class="r-panel-body p-t-0 h-scroll">

    <div class="row">
        <div class="col-xs-12 col-md-7 p-t-20 b-r ">
            <div class="row">
                <div class="col-xs-6 m-b-20">
                    <img src="{{ $companyDetails->logo_url }}" alt="logo" height="30">
                </div>
                <div class="col-xs-6 m-b-20">
                    <a href="{{ route('super-admin.companies.edit',$companyDetails->id) }}" class="btn btn-outline btn-success btn-sm">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        @lang('app.edit')
                        @lang('app.company') </a>
                    
                </div>

                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-6 b-r"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.companyName')</span> <br>
                            <p class="text-muted">{{ ucwords($companyDetails->company_name) }}</p>
                        </div>
                        <div class="col-xs-6"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.companyEmail')</span> <br>
                            <p class="text-muted">{{ $companyDetails->company_email }}</p>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6 b-r"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.companyPhone')</span> <br>
                            <p class="text-muted">{{ ucwords($companyDetails->company_phone) }}</p>
                        </div>
                        <div class="col-xs-6"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.companyWebsite')</span> <br>
                            <p class="text-muted">{{ $companyDetails->website ?? "--" }}</p>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-xs-12"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.companyAddress')</span> <br>
                            <p class="text-muted">{!! $companyDetails->address !!}</p>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6 b-r"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.defaultCurrency')</span> <br>
                            <p class="text-muted">
                                {{ $companyDetails->currency->currency_symbol . ' (' .$companyDetails->currency->currency_code .')' }}
                            </p>
                        </div>
                        <div class="col-xs-6"> <span
                                class="font-semi-bold">@lang('modules.accountSettings.defaultTimezone')</span> <br>
                            <p class="text-muted">{{ $companyDetails->timezone }}</p>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-xs-6 b-r"> <span class="font-semi-bold">@lang('app.status')</span> <br>
                            <p class="text-muted">
                                @if ($companyDetails->status == 'active')
                                <label class="label label-success">@lang('app.active')</label>
                                @else
                                <label class="label label-success">@lang('app.active')</label>
                                @endif
                            </p>
                        </div>
                        <div class="col-xs-6"> <span class="font-semi-bold">@lang('app.lastActivity')</span> <br>
                            <p class="text-muted">
                                {{ ($companyDetails->last_login) ? $companyDetails->last_login->diffForHumans() : '--' }}
                            </p>
                        </div>
                    </div>
                    <hr>

                </div>
            </div>


        </div>

        <div class="col-xs-12 col-md-5 p-t-20 ">

            <h4>@lang('app.package') @lang('app.details')</h4>
            <table class="table">
                <tr>
                    <td class="font-semi-bold">@lang('app.package') @lang('app.name')</td>
                    <td>{{  $companyDetails->package->name }}</td>
                </tr>
                <tr>
                    <td class="font-semi-bold">@lang('app.menu.employees') @lang('app.quota')</td>
                    <td>{{ $companyDetails->employees->count() }} / {{ $companyDetails->package->max_employees }}</td>
                </tr>
                <tr>
                    <td class="font-semi-bold">@lang('app.menu.storage')</td>
                    <td>
                        @if($companyDetails->package->storage_unit == 'mb')
                        {{ $companyDetails->file_storage->count() > 0 ? round($companyDetails->file_storage->sum('size')/(1000*1024), 4). ' MB' : 'Not used' }}
                        @else
                        {{ $companyDetails->file_storage->count() > 0 ? round($companyDetails->file_storage->sum('size')/(1000*1024*1024), 4). ' MB' : 'Not Used' }}
                        @endif
                        /
                        @if($companyDetails->package->max_storage_size == -1)
                        @lang('app.unlimited')
                        @else
                        {{ $companyDetails->package->max_storage_size }}
                        ({{ strtoupper($companyDetails->package->storage_unit) }})
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="font-semi-bold">@lang('app.price')</td>
                    <td>
                        <p>
                            @lang('app.monthly') @lang('app.price'):
                            {{ $companyDetails->package->currency->currency_symbol . $companyDetails->package->monthly_price }}
                        </p>
                        <p>
                            @lang('app.annual') @lang('app.price'):
                            {{ $companyDetails->package->currency->currency_symbol . $companyDetails->package->annual_price }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="font-semi-bold">@lang('modules.package.licenseExpiresOn')</td>
                    <td>{{ ($companyDetails->licence_expire_on) ? $companyDetails->licence_expire_on->toFormattedDateString() . ' ('. $companyDetails->licence_expire_on->diffForHumans() .')' : '--' }}
                    </td>
                </tr>
            </table>

            <a href="javascript:;" class="btn btn-info btn-outline package-update-button"
                data-company-id="{{ $companyDetails->id }}"><i class="fa fa-pencil"></i> @lang('app.change')
                @lang('app.package')</a>

            <a href="javascript:;" class="btn btn-success btn-outline" data-company-id="{{ $companyDetails->id }}"
                id="login-as-company"><i class="fa fa-sign-in"></i> @lang('modules.superadmin.loginAsCompany')</a>


        </div>



    </div>

</div>