@extends('layouts.client-app')

@section('page-title')
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-6 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon }}"></i> @lang($pageTitle) #{{ $project->id }} - <span
                        class="font-bold">{{ ucwords($project->project_name) }}</span></h4>
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-6 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('client.dashboard.index') }}">@lang('app.menu.home')</a></li>
                <li><a href="{{ route('client.projects.index') }}">@lang($pageTitle)</a></li>
                <li class="active">@lang('app.menu.invoices')</li>
            </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <section>
                <div class="sttabs tabs-style-line">

                    @include('client.projects.show_project_menu')

                    <div class="content-wrap">
                        <section id="section-line-3" class="show">
                            <div class="row">
                                <div class="col-md-12" id="invoices-list-panel">
                                    <div class="white-box">

                                        <h4>@lang('app.menu.payments')</h4>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover toggle-circle default footable-loaded footable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>@lang('modules.invoices.amount')</th>
                                                        <th>@lang('modules.payments.paidOn')</th>
                                                        <th>@lang('app.remark')</th>
                                                        <th>@lang('app.status')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($project->payments as $key=>$item)
                                                        <tr>
                                                            <td>{{ $key+1 }}</td>
                                                            <td>
                                                                @php
                                                                    $symbol = (!is_null($item->currency)) ? $item->currency->currency_symbol : '';
                                                                    $code = (!is_null($item->currency)) ? $item->currency->currency_code : '';

                                                                    echo $symbol . number_format((float) $item->amount, 2, '.', '') . ' (' . $code . ')';
                                                                @endphp
                                                            </td>
                                                            <td>
                                                                @if (!is_null($item->paid_on))
                                                                    {{ $item->paid_on->format($global->date_format . ' ' . $global->time_format) }}
                                                                @endif
                                                            </td>
                                                            <td>{{ ucfirst($item->remarks) }}</td>
                                                            <td>
                                                                @if ($item->status == 'pending')
                                                                    <label class="label label-warning">{{ strtoupper($item->status) }}</label>
                                                                @else
                                                                    <label class="label label-success">{{ strtoupper($item->status) }}</label>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7">@lang('messages.noRecordFound')</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </section>

                    </div><!-- /content -->
                </div><!-- /tabs -->
            </section>
        </div>


    </div>
    <!-- .row -->

    {{--Ajax Modal--}}
    <div class="modal fade bs-modal-lg in" id="add-invoice-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}

@endsection
@push('footer-script')
<script>
    $('ul.showProjectTabs .projectPayments').addClass('tab-current');
</script>
@endpush